<?php

namespace App\Services;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send email notification when new complaint is created
     */
    public static function sendNewComplaintNotification(Complaint $complaint)
    {
        try {
            // Send email notification
            self::sendEmailNewComplaint($complaint);
            
            // Send WhatsApp notification
            WhatsappService::sendNewComplaintNotification($complaint);
            
            Log::info("New complaint notifications sent for complaint: {$complaint->complaint_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send new complaint notifications: " . $e->getMessage());
        }
    }

    /**
     * Send email notification when complaint is assigned to contractor
     */
    public static function sendAssignmentNotification(Complaint $complaint)
    {
        try {
            if (!$complaint->contractor) {
                return;
            }

            // Send email notification
            self::sendEmailAssignment($complaint);
            
            // Send WhatsApp notification
            WhatsappService::sendAssignmentNotification($complaint);

            Log::info("Assignment notifications sent for complaint: {$complaint->complaint_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send assignment notifications: " . $e->getMessage());
        }
    }

    /**
     * Send email notification when contractor acknowledges task
     */
    public static function sendAcknowledgeNotification(Complaint $complaint)
    {
        try {
            // Send email notification
            self::sendEmailAcknowledge($complaint);
            
            // Send WhatsApp notification
            WhatsappService::sendAcknowledgeNotification($complaint);

            Log::info("Acknowledge notifications sent for complaint: {$complaint->complaint_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send acknowledge notifications: " . $e->getMessage());
        }
    }

    /**
     * Send email notification when progress is updated
     */
    public static function sendProgressUpdateNotification(Complaint $complaint, $progressDescription)
    {
        try {
            // Send email notification
            self::sendEmailProgressUpdate($complaint, $progressDescription);
            
            // Send WhatsApp notification
            WhatsappService::sendProgressUpdateNotification($complaint, $progressDescription);

            Log::info("Progress update notifications sent for complaint: {$complaint->complaint_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send progress update notifications: " . $e->getMessage());
        }
    }

    /**
     * Send email notification when complaint is completed
     */
    public static function sendCompletionNotification(Complaint $complaint)
    {
        try {
            // Send email notification
            self::sendEmailCompletion($complaint);
            
            // Send WhatsApp notification
            WhatsappService::sendCompletionNotification($complaint);

            Log::info("Completion notifications sent for complaint: {$complaint->complaint_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send completion notifications: " . $e->getMessage());
        }
    }

    // Private methods for email sending
    private static function sendEmailNewComplaint(Complaint $complaint)
    {
        $pengurusanUsers = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->get();

        foreach ($pengurusanUsers as $user) {
            Mail::raw(
                "Aduan baru: {$complaint->complaint_number}\nSekolah: {$complaint->school->name}\nKategori: {$complaint->category}",
                function (\Illuminate\Mail\Message $message) use ($user, $complaint) {
                    $message->to($user->email)->subject("[Aduan Baru] {$complaint->complaint_number}");
                }
            );
        }
    }

    private static function sendEmailAssignment(Complaint $complaint)
    {
        if ($complaint->contractor->email) {
            Mail::raw(
                "Tugasan baru: {$complaint->complaint_number}\nSekolah: {$complaint->school->name}",
                function (\Illuminate\Mail\Message $message) use ($complaint) {
                    $message->to($complaint->contractor->email)->subject("[Tugasan] {$complaint->complaint_number}");
                }
            );
        }
    }

    private static function sendEmailAcknowledge(Complaint $complaint)
    {
        $status = $complaint->acknowledged_status === 'accepted' ? 'Diterima' : 'Ditolak';
        $pengurusanUsers = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->get();

        foreach ($pengurusanUsers as $user) {
            Mail::raw(
                "Tugasan {$status}: {$complaint->complaint_number}",
                function (\Illuminate\Mail\Message $message) use ($user, $complaint, $status) {
                    $message->to($user->email)->subject("[Tugasan {$status}] {$complaint->complaint_number}");
                }
            );
        }
    }

    private static function sendEmailProgressUpdate(Complaint $complaint, $progressDescription)
    {
        $users = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->orWhere('id', $complaint->user_id)
            ->get();

        foreach ($users as $user) {
            Mail::raw(
                "Progress: {$complaint->complaint_number}\n{$progressDescription}",
                function (\Illuminate\Mail\Message $message) use ($user, $complaint) {
                    $message->to($user->email)->subject("[Progress] {$complaint->complaint_number}");
                }
            );
        }
    }

    private static function sendEmailCompletion(Complaint $complaint)
    {
        $users = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->orWhere('id', $complaint->user_id)
            ->get();

        foreach ($users as $user) {
            Mail::raw(
                "Aduan selesai: {$complaint->complaint_number}",
                function (\Illuminate\Mail\Message $message) use ($user, $complaint) {
                    $message->to($user->email)->subject("[Selesai] {$complaint->complaint_number}");
                }
            );
        }
    }
}
