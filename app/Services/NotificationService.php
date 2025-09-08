<?php

namespace App\Services;

use App\Models\User;
use App\Models\Complaint;
use App\Mail\ComplaintNotification;
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

        $message = "Aduan baru telah diterima dari sekolah {$complaint->school->name}.\nKategori: {$complaint->category}";

        foreach ($pengurusanUsers as $user) {
            Mail::to($user->email)->send(new ComplaintNotification($complaint, 'new', $message));
        }
    }

    private static function sendEmailAssignment(Complaint $complaint)
    {
        if ($complaint->contractor && $complaint->contractor->email) {
            $message = "Anda telah ditugaskan untuk mengendalikan aduan ini dari sekolah {$complaint->school->name}.";
            Mail::to($complaint->contractor->email)->send(new ComplaintNotification($complaint, 'assignment', $message));
        }
    }

    private static function sendEmailAcknowledge(Complaint $complaint)
    {
        $status = $complaint->acknowledged_status === 'accepted' ? 'diterima' : 'ditolak';
    $message = "Kontraktor telah {$status} tugasan untuk aduan ini.";
        
        $pengurusanUsers = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->get();

        foreach ($pengurusanUsers as $user) {
            Mail::to($user->email)->send(new ComplaintNotification($complaint, 'acknowledge', $message));
        }
    }

    private static function sendEmailProgressUpdate(Complaint $complaint, $progressDescription)
    {
        $users = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->orWhere('id', $complaint->user_id)
            ->get();

    $message = "Kemaskini progress untuk aduan ini: {$progressDescription}";

        foreach ($users as $user) {
            Mail::to($user->email)->send(new ComplaintNotification($complaint, 'progress', $message));
        }
    }

    private static function sendEmailCompletion(Complaint $complaint)
    {
        $users = User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->orWhere('id', $complaint->user_id)
            ->get();

        $message = "Aduan ini telah selesai diselesaikan.";

        foreach ($users as $user) {
            Mail::to($user->email)->send(new ComplaintNotification($complaint, 'completion', $message));
        }
    }
}
