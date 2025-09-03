<?php

namespace App\Services;

use App\Models\WhatsappNumber;
use App\Models\Complaint;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send WhatsApp message to a number
     * (This is a placeholder implementation - requires actual WhatsApp API integration)
     */
    public static function sendMessage($to, $message)
    {
        try {
            // TODO: Implement actual WhatsApp API integration
            // For now, we'll just log the message
            Log::info("WhatsApp Message Sent", [
                'to' => $to,
                'message' => $message,
                'timestamp' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send new complaint notification via WhatsApp
     */
    public static function sendNewComplaintNotification(Complaint $complaint)
    {
        $message = "🚨 *ADUAN BARU* 🚨\n\n" .
                   "📋 No: {$complaint->complaint_number}\n" .
                   "🏫 Sekolah: {$complaint->school->name}\n" .
                   "📝 Kategori: {$complaint->category}\n" .
                   "⚡ Prioriti: " . strtoupper($complaint->priority) . "\n" .
                   "📄 Deskripsi: {$complaint->description}\n\n" .
                   "Sila semak sistem untuk tindakan lanjut.";

        // Send to pengurusan phone numbers
        $pengurusanUsers = \App\Models\User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->whereNotNull('phone')
            ->get();

        foreach ($pengurusanUsers as $user) {
            self::sendMessage($user->phone, $message);
        }
    }

    /**
     * Send assignment notification via WhatsApp
     */
    public static function sendAssignmentNotification(Complaint $complaint)
    {
        if (!$complaint->contractor || !$complaint->contractor->phone) {
            return;
        }

        $message = "🔨 *TUGASAN BARU* 🔨\n\n" .
                   "📋 No: {$complaint->complaint_number}\n" .
                   "🏫 Sekolah: {$complaint->school->name}\n" .
                   "📝 Kategori: {$complaint->category}\n" .
                   "⚡ Prioriti: " . strtoupper($complaint->priority) . "\n" .
                   "📄 Deskripsi: {$complaint->description}\n\n" .
                   "Sila login ke sistem untuk menerima atau menolak tugasan ini.";

        self::sendMessage($complaint->contractor->phone, $message);
    }

    /**
     * Send acknowledge notification via WhatsApp
     */
    public static function sendAcknowledgeNotification(Complaint $complaint)
    {
        $status = $complaint->acknowledged_status === 'accepted' ? 'MENERIMA' : 'MENOLAK';
        $emoji = $complaint->acknowledged_status === 'accepted' ? '✅' : '❌';
        
        $message = "{$emoji} *TUGASAN {$status}* {$emoji}\n\n" .
                   "📋 No: {$complaint->complaint_number}\n" .
                   "👷 Kontraktor: {$complaint->contractor->name}\n" .
                   "📊 Status: " . ($complaint->acknowledged_status === 'accepted' ? 'Diterima' : 'Ditolak') . "\n\n" .
                   "Sila semak sistem untuk maklumat lanjut.";

        // Send to pengurusan phone numbers
        $pengurusanUsers = \App\Models\User::where('role', 'pengurusan')
            ->where('school_id', $complaint->school_id)
            ->whereNotNull('phone')
            ->get();

        foreach ($pengurusanUsers as $user) {
            self::sendMessage($user->phone, $message);
        }
    }

    /**
     * Send progress update notification via WhatsApp
     */
    public static function sendProgressUpdateNotification(Complaint $complaint, $progressDescription)
    {
        $message = "🔄 *KEMASKINI PROGRESS* 🔄\n\n" .
                   "📋 No: {$complaint->complaint_number}\n" .
                   "👷 Kontraktor: {$complaint->contractor->name}\n" .
                   "📝 Progress: {$progressDescription}\n\n" .
                   "Sila semak sistem untuk gambar dan maklumat lengkap.";

        // Send to pengurusan and complaint creator
        $users = \App\Models\User::where(function($query) use ($complaint) {
            $query->where('role', 'pengurusan')
                  ->where('school_id', $complaint->school_id);
        })->orWhere('id', $complaint->user_id)
        ->whereNotNull('phone')
        ->get();

        foreach ($users as $user) {
            self::sendMessage($user->phone, $message);
        }
    }

    /**
     * Send completion notification via WhatsApp
     */
    public static function sendCompletionNotification(Complaint $complaint)
    {
        $message = "🎉 *ADUAN SELESAI* 🎉\n\n" .
                   "📋 No: {$complaint->complaint_number}\n" .
                   "🏫 Sekolah: {$complaint->school->name}\n" .
                   "📝 Kategori: {$complaint->category}\n" .
                   "👷 Kontraktor: {$complaint->contractor->name}\n\n" .
                   "Terima kasih atas kerjasama anda! 🙏";

        // Send to pengurusan and complaint creator
        $users = \App\Models\User::where(function($query) use ($complaint) {
            $query->where('role', 'pengurusan')
                  ->where('school_id', $complaint->school_id);
        })->orWhere('id', $complaint->user_id)
        ->whereNotNull('phone')
        ->get();

        foreach ($users as $user) {
            self::sendMessage($user->phone, $message);
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
        // TODO: Implement actual QR generation logic
        return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQAAAABYmaj5AAAAiklEQVR4Ae3PAQ0AAAwCwdm/9HI83BLIOdw5c5QIEc...";
    }
}
