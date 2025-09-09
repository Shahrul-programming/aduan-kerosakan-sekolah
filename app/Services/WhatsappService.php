<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\WhatsappNumber;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send WhatsApp message to a number
     * (This is a placeholder implementation - requires actual WhatsApp API integration)
     */
    public static function sendMessage($to, $message)
    {
        // Backward-compatible synchronous send
        return self::sendMessageSync($to, $message);
    }

    /**
     * Synchronous send (used by health/test and job handler)
     */
    public static function sendMessageSync($to, $message)
    {
        // Normalize phone number to international format expected by gateway
        $to = preg_replace('/[^0-9]/', '', (string) $to);
        if (! $to) {
            Log::warning('WhatsApp sendMessage skipped: empty number');

            return false;
        }

        $gatewayUrl = config('whatsapp.gateway_url');
        $gatewayToken = config('whatsapp.gateway_token');
        $timeout = (int) config('whatsapp.timeout', 10);

        // If gateway not configured, fall back to logging (no-op integration)
        if (empty($gatewayUrl) || empty($gatewayToken)) {
            Log::info('WhatsApp (dry-run) Message Sent', [
                'to' => $to,
                'message' => $message,
                'timestamp' => now(),
            ]);

            return true;
        }

        try {
            // Use Laravel's HTTP client
            $response = \Illuminate\Support\Facades\Http::withToken($gatewayToken)
                ->timeout($timeout)
                ->asJson()
                ->post(rtrim($gatewayUrl, '/').'/send', [
                    'to' => $to,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp gateway sent', [
                    'to' => $to,
                    'status' => $response->status(),
                ]);

                return true;
            }

            Log::error('WhatsApp gateway error', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsApp gateway exception: '.$e->getMessage(), [
                'to' => $to,
            ]);

            return false;
        }
    }

    /**
     * Queue send with retries (used for normal notifications)
     */
    public static function sendMessageAsync($to, $message)
    {
        try {
            \App\Jobs\SendWhatsappMessage::dispatch((string) $to, (string) $message)
                ->onQueue('notifications');

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch WhatsApp job: '.$e->getMessage());

            // Fallback: try sync to not lose message
            return self::sendMessageSync($to, $message);
        }
    }

    /**
     * Send new complaint notification via WhatsApp
     */
    public static function sendNewComplaintNotification(Complaint $complaint)
    {
        $message = "🚨 *ADUAN BARU* 🚨\n\n".
                   "📋 No: {$complaint->complaint_number}\n".
                   "🏫 Sekolah: {$complaint->school->name}\n".
                   "📝 Kategori: {$complaint->category}\n".
                   '⚡ Prioriti: '.strtoupper($complaint->priority)."\n".
                   "📄 Deskripsi: {$complaint->description}\n\n".
                   'Sila semak sistem untuk tindakan lanjut.';

        // Send to pengurusan phone numbers
        $pengurusanUsers = \App\Models\User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->whereNotNull('phone')
            ->get();

        foreach ($pengurusanUsers as $user) {
            self::sendMessageAsync($user->phone, $message);
        }
    }

    /**
     * Send assignment notification via WhatsApp
     */
    public static function sendAssignmentNotification(Complaint $complaint)
    {
        if (! $complaint->contractor || ! $complaint->contractor->phone) {
            return;
        }

        $message = "🔨 *TUGASAN BARU* 🔨\n\n".
                   "📋 No: {$complaint->complaint_number}\n".
                   "🏫 Sekolah: {$complaint->school->name}\n".
                   "📝 Kategori: {$complaint->category}\n".
                   '⚡ Prioriti: '.strtoupper($complaint->priority)."\n".
                   "📄 Deskripsi: {$complaint->description}\n\n".
                   'Sila login ke sistem untuk menerima atau menolak tugasan ini.';

        self::sendMessageAsync($complaint->contractor->phone, $message);
    }

    /**
     * Send acknowledge notification via WhatsApp
     */
    public static function sendAcknowledgeNotification(Complaint $complaint)
    {
        $status = $complaint->acknowledged_status === 'accepted' ? 'MENERIMA' : 'MENOLAK';
        $emoji = $complaint->acknowledged_status === 'accepted' ? '✅' : '❌';

        $message = "{$emoji} *TUGASAN {$status}* {$emoji}\n\n".
                   "📋 No: {$complaint->complaint_number}\n".
                   "👷 Kontraktor: {$complaint->contractor->name}\n".
                   '📊 Status: '.($complaint->acknowledged_status === 'accepted' ? 'Diterima' : 'Ditolak')."\n\n".
                   'Sila semak sistem untuk maklumat lanjut.';

        // Send to pengurusan phone numbers
        $pengurusanUsers = \App\Models\User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->whereNotNull('phone')
            ->get();

        foreach ($pengurusanUsers as $user) {
            self::sendMessageAsync($user->phone, $message);
        }
    }

    /**
     * Send progress update notification via WhatsApp
     */
    public static function sendProgressUpdateNotification(Complaint $complaint, $progressDescription)
    {
        $message = "🔄 *KEMASKINI PROGRESS* 🔄\n\n".
                   "📋 No: {$complaint->complaint_number}\n".
                   "👷 Kontraktor: {$complaint->contractor->name}\n".
                   "📝 Progress: {$progressDescription}\n\n".
                   'Sila semak sistem untuk gambar dan maklumat lengkap.';

        // Send to pengurusan and complaint creator
        $users = \App\Models\User::where(function ($query) use ($complaint) {
            $query->where('role', 'pengurusan')
                ->where('school_id', $complaint->school_id);
        })->orWhere('id', $complaint->user_id)
            ->whereNotNull('phone')
            ->get();

        foreach ($users as $user) {
            self::sendMessageAsync($user->phone, $message);
        }
    }

    /**
     * Send completion notification via WhatsApp
     */
    public static function sendCompletionNotification(Complaint $complaint)
    {
        $message = "🎉 *ADUAN SELESAI* 🎉\n\n".
                   "📋 No: {$complaint->complaint_number}\n".
                   "🏫 Sekolah: {$complaint->school->name}\n".
                   "📝 Kategori: {$complaint->category}\n".
                   "👷 Kontraktor: {$complaint->contractor->name}\n\n".
                   'Terima kasih atas kerjasama anda! 🙏';

        // Send to pengurusan and complaint creator
        $users = \App\Models\User::where(function ($query) use ($complaint) {
            $query->where('role', 'pengurusan')
                ->where('school_id', $complaint->school_id);
        })->orWhere('id', $complaint->user_id)
            ->whereNotNull('phone')
            ->get();

        foreach ($users as $user) {
            self::sendMessageAsync($user->phone, $message);
        }
    }

    /**
     * Get active WhatsApp numbers
     */
    public static function getActiveNumbers()
    {
        return WhatsappNumber::where('status', 'active')->get();
    }

    /**
     * Generate QR code for WhatsApp Web connection
     * (Placeholder - requires actual WhatsApp Web API integration)
     */
    public static function generateQRCode($phoneNumber)
    {
        $gatewayUrl = config('whatsapp.gateway_url');
        $gatewayToken = config('whatsapp.gateway_token');
        $timeout = (int) config('whatsapp.timeout', 10);

        // If gateway not configured, return an empty string (UI will show generate option)
        if (empty($gatewayUrl) || empty($gatewayToken)) {
            \Log::warning('generateQRCode: gateway not configured');

            return '';
        }

        try {
            $resp = \Illuminate\Support\Facades\Http::withToken($gatewayToken)
                ->timeout($timeout)
                ->acceptJson()
                ->get(rtrim($gatewayUrl, '/').'/qr', [
                    'number' => preg_replace('/[^0-9]/', '', (string) $phoneNumber),
                ]);

            if (! $resp->successful()) {
                \Log::error('generateQRCode: gateway error', ['status' => $resp->status(), 'body' => $resp->body()]);

                return '';
            }

            // Try decode JSON { qr: '...', type: 'data-url|base64' }
            $body = $resp->body();
            $data = null;
            try {
                $data = $resp->json();
            } catch (\Throwable $e) { /* may not be JSON */
            }

            $qr = '';
            if (is_array($data)) {
                if (! empty($data['qr'])) {
                    $qr = (string) $data['qr'];
                } elseif (! empty($data['image'])) {
                    $qr = (string) $data['image'];
                } elseif (! empty($data['data'])) {
                    $qr = (string) $data['data'];
                }
            }

            if (! $qr) {
                // Fallback: body might already be a data URL or base64
                $qr = trim($body);
            }

            // Ensure data URL prefix for <img src>
            if ($qr && ! str_starts_with($qr, 'data:image')) {
                // If it's raw base64, prefix
                if (preg_match('/^[A-Za-z0-9+\/]+=*$/', str_replace(["\r", "\n"], '', $qr))) {
                    $qr = 'data:image/png;base64,'.$qr;
                }
            }

            return $qr;
        } catch (\Throwable $e) {
            \Log::error('generateQRCode: exception '.$e->getMessage());

            return '';
        }
    }
}
