<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Complaint $complaint,
        public string $type,
        public string $message
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->getSubject(),
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.complaint-notification',
        );
    }

    private function getSubject(): string
    {
        return match($this->type) {
            'new' => "[Aduan Baru] {$this->complaint->complaint_number}",
            'assignment' => "[Tugasan Baru] {$this->complaint->complaint_number}",
            'acknowledge' => "[Tugasan Diterima] {$this->complaint->complaint_number}",
            'progress' => "[Kemaskini Progress] {$this->complaint->complaint_number}",
            'completion' => "[Aduan Selesai] {$this->complaint->complaint_number}",
            default => "Notification: {$this->complaint->complaint_number}"
        };
    }
}
