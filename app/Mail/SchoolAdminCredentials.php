<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolAdminCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $password;

    public $school;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $password, $school)
    {
        $this->user = $user;
        $this->password = $password;
        $this->school = $school;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Maklumat Login Admin Sekolah - '.($this->school->name ?? 'Sekolah'))
            ->view('emails.school_admin_credentials')
            ->with([
                'user' => $this->user,
                'password' => $this->password,
                'school' => $this->school,
            ]);
    }
}
