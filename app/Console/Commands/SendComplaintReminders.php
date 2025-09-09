<?php

namespace App\Console\Commands;

use App\Models\Complaint;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendComplaintReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complaint:send-reminders {--days=3 : Days since last activity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for pending complaints that have not been updated for specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Checking for complaints older than {$days} days...");

        // Find complaints that need reminders
        $pendingComplaints = $this->getPendingComplaints($cutoffDate);
        $overdueTasks = $this->getOverdueTasks($cutoffDate);

        $totalSent = 0;

        // Send reminders for pending complaints (not assigned)
        if ($pendingComplaints->count() > 0) {
            $this->info("Found {$pendingComplaints->count()} pending complaints to remind...");
            foreach ($pendingComplaints as $complaint) {
                $this->sendPendingComplaintReminder($complaint);
                $totalSent++;
            }
        }

        // Send reminders for overdue tasks (assigned but not progressing)
        if ($overdueTasks->count() > 0) {
            $this->info("Found {$overdueTasks->count()} overdue tasks to remind...");
            foreach ($overdueTasks as $complaint) {
                $this->sendOverdueTaskReminder($complaint);
                $totalSent++;
            }
        }

        if ($totalSent === 0) {
            $this->info('No reminders needed at this time.');
        } else {
            $this->info("Successfully sent {$totalSent} reminders.");
        }

        return 0;
    }

    /**
     * Get complaints that are pending (not assigned) and old
     */
    private function getPendingComplaints($cutoffDate)
    {
        return Complaint::where('status', 'baru')
            ->orWhere('status', 'dalam_semakan')
            ->whereNull('assigned_to')
            ->where('created_at', '<=', $cutoffDate)
            ->with(['school', 'user'])
            ->get();
    }

    /**
     * Get complaints that are assigned but have no recent progress
     */
    private function getOverdueTasks($cutoffDate)
    {
        return Complaint::whereIn('status', ['assigned', 'dalam_proses'])
            ->whereNotNull('assigned_to')
            ->where(function ($query) use ($cutoffDate) {
                $query->where('updated_at', '<=', $cutoffDate)
                    ->whereDoesntHave('progressUpdates', function ($q) use ($cutoffDate) {
                        $q->where('created_at', '>', $cutoffDate);
                    });
            })
            ->with(['school', 'user', 'contractor'])
            ->get();
    }

    /**
     * Send reminder for pending complaint (not assigned)
     */
    private function sendPendingComplaintReminder($complaint)
    {
        try {
            $daysSince = Carbon::parse($complaint->created_at)->diffInDays(Carbon::now());

            // Email reminder to pengurusan
            $pengurusanUsers = \App\Models\User::where('role', 'pengurusan')
                ->where('school_id', $complaint->school_id)
                ->get();

            foreach ($pengurusanUsers as $user) {
                \Illuminate\Support\Facades\Mail::raw(
                    "🔔 PERINGATAN: Aduan belum diproses\n\n".
                    "No. Aduan: {$complaint->complaint_number}\n".
                    "Sekolah: {$complaint->school->name}\n".
                    "Kategori: {$complaint->category}\n".
                    'Prioriti: '.strtoupper($complaint->priority)."\n".
                    "Dihantar: {$daysSince} hari yang lalu\n".
                    'Status: '.ucfirst($complaint->status)."\n\n".
                    'Sila segera proses aduan ini dan tugaskan kepada kontraktor yang sesuai.',
                    function ($message) use ($user, $complaint) {
                        $message->to($user->email)
                            ->subject("[PERINGATAN] Aduan Tertunggak - {$complaint->complaint_number}");
                    }
                );
            }

            // WhatsApp reminder
            WhatsappService::sendMessage($pengurusanUsers->first()->phone ?? '',
                "🔔 *PERINGATAN ADUAN TERTUNGGAK*\n\n".
                "📋 No: {$complaint->complaint_number}\n".
                "🏫 Sekolah: {$complaint->school->name}\n".
                "⏰ Tertunggak: {$daysSince} hari\n".
                '📊 Status: '.strtoupper($complaint->status)."\n\n".
                'Sila segera proses aduan ini! ⚠️'
            );

            $this->line("✓ Sent pending reminder for {$complaint->complaint_number}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to send reminder for {$complaint->complaint_number}: ".$e->getMessage());
        }
    }

    /**
     * Send reminder for overdue task (assigned but no progress)
     */
    private function sendOverdueTaskReminder($complaint)
    {
        try {
            $daysSince = Carbon::parse($complaint->updated_at)->diffInDays(Carbon::now());

            // Email reminder to contractor
            if ($complaint->contractor && $complaint->contractor->email) {
                \Illuminate\Support\Facades\Mail::raw(
                    "🔔 PERINGATAN: Tugasan tertunggak\n\n".
                    "No. Aduan: {$complaint->complaint_number}\n".
                    "Sekolah: {$complaint->school->name}\n".
                    "Kategori: {$complaint->category}\n".
                    "Tiada kemaskini: {$daysSince} hari\n\n".
                    'Sila segera kemaskini progress kerja atau hubungi pihak pengurusan jika ada masalah.',
                    function ($message) use ($complaint) {
                        $message->to($complaint->contractor->email)
                            ->subject("[PERINGATAN] Tugasan Tertunggak - {$complaint->complaint_number}");
                    }
                );
            }

            // Email to pengurusan for awareness
            $pengurusanUsers = \App\Models\User::where('role', 'pengurusan')
                ->where('school_id', $complaint->school_id)
                ->get();

            foreach ($pengurusanUsers as $user) {
                \Illuminate\Support\Facades\Mail::raw(
                    "🔔 MAKLUMAN: Tugasan kontraktor tertunggak\n\n".
                    "No. Aduan: {$complaint->complaint_number}\n".
                    "Kontraktor: {$complaint->contractor->name}\n".
                    "Tiada kemaskini: {$daysSince} hari\n\n".
                    'Sila follow up dengan kontraktor atau pertimbangkan tugasan semula.',
                    function ($message) use ($user, $complaint) {
                        $message->to($user->email)
                            ->subject("[MAKLUMAN] Tugasan Tertunggak - {$complaint->complaint_number}");
                    }
                );
            }

            // WhatsApp reminder to contractor
            if ($complaint->contractor && $complaint->contractor->phone) {
                WhatsappService::sendMessage($complaint->contractor->phone,
                    "🔔 *PERINGATAN TUGASAN TERTUNGGAK*\n\n".
                    "📋 No: {$complaint->complaint_number}\n".
                    "🏫 Sekolah: {$complaint->school->name}\n".
                    "⏰ Tiada kemaskini: {$daysSince} hari\n\n".
                    'Sila segera kemaskini progress! ⚠️'
                );
            }

            $this->line("✓ Sent overdue reminder for {$complaint->complaint_number}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to send overdue reminder for {$complaint->complaint_number}: ".$e->getMessage());
        }
    }
}
