<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Contractor;
use App\Models\User;
use App\Models\School;
use App\Services\NotificationService;
use App\Services\WhatsappService;
use App\Mail\ComplaintNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationFasa4Test extends TestCase
{
    use RefreshDatabase;

    public function test_email_notification_sent_for_new_complaint()
    {
        Mail::fake();
        
        $school = School::factory()->create();
        $pengurusan = User::factory()->create([
            'role' => 'pengurusan',
            'school_id' => $school->id,
            'email' => 'pengurusan@test.com'
        ]);
        $guru = User::factory()->create(['role' => 'guru']);
        
        $complaint = Complaint::factory()->create([
            'school_id' => $school->id,
            'user_id' => $guru->id
        ]);

        // Ensure the complaint has the school relationship loaded
        $complaint = $complaint->fresh('school');
        
        NotificationService::sendNewComplaintNotification($complaint);

        // Check that ComplaintNotification was sent
        Mail::assertSent(ComplaintNotification::class, function ($mail) use ($pengurusan) {
            return $mail->hasTo($pengurusan->email);
        });
    }

    public function test_email_notification_sent_for_assignment()
    {
        Mail::fake();
        
        $contractor = Contractor::factory()->create(['email' => 'contractor@test.com']);
        $complaint = Complaint::factory()->create(['assigned_to' => $contractor->id]);
        
        // Ensure relationships are loaded
        $complaint = $complaint->fresh('contractor', 'school');

        NotificationService::sendAssignmentNotification($complaint);

        Mail::assertSent(ComplaintNotification::class, function ($mail) use ($contractor) {
            return $mail->hasTo($contractor->email);
        });
    }

    public function test_email_notification_sent_for_acknowledge()
    {
        Mail::fake();
        
        $school = School::factory()->create();
        $pengurusan = User::factory()->create([
            'role' => 'pengurusan',
            'school_id' => $school->id,
            'email' => 'pengurusan@test.com'
        ]);
        $contractor = Contractor::factory()->create();
        $complaint = Complaint::factory()->create([
            'school_id' => $school->id,
            'assigned_to' => $contractor->id,
            'acknowledged_status' => 'accepted'
        ]);
        
        // Load relationships
        $complaint = $complaint->fresh('school');

        NotificationService::sendAcknowledgeNotification($complaint);

        Mail::assertSent(ComplaintNotification::class, function ($mail) use ($pengurusan) {
            return $mail->hasTo($pengurusan->email);
        });
    }

    public function test_email_notification_sent_for_progress_update()
    {
        Mail::fake();
        
        $school = School::factory()->create();
        $pengurusan = User::factory()->create([
            'role' => 'pengurusan',
            'school_id' => $school->id,
            'email' => 'pengurusan@test.com'
        ]);
        $guru = User::factory()->create(['email' => 'guru@test.com']);
        $contractor = Contractor::factory()->create();
        $complaint = Complaint::factory()->create([
            'school_id' => $school->id,
            'user_id' => $guru->id,
            'assigned_to' => $contractor->id
        ]);
        
        // Load relationships
        $complaint = $complaint->fresh('school');

        NotificationService::sendProgressUpdateNotification($complaint, 'Kerja sedang berjalan');

        Mail::assertSent(ComplaintNotification::class, 2);
    }

    public function test_email_notification_sent_for_completion()
    {
        Mail::fake();
        
        $school = School::factory()->create();
        $pengurusan = User::factory()->create([
            'role' => 'pengurusan',
            'school_id' => $school->id,
            'email' => 'pengurusan@test.com'
        ]);
        $guru = User::factory()->create(['email' => 'guru@test.com']);
        $contractor = Contractor::factory()->create();
        $complaint = Complaint::factory()->create([
            'school_id' => $school->id,
            'user_id' => $guru->id,
            'assigned_to' => $contractor->id,
            'status' => 'selesai'
        ]);
        
        // Load relationships
        $complaint = $complaint->fresh('school');

        NotificationService::sendCompletionNotification($complaint);

        Mail::assertSent(ComplaintNotification::class, 2);
    }

    public function test_whatsapp_service_logs_messages()
    {
        $complaint = Complaint::factory()->create();
        
        // Test WhatsApp message logging (since we don't have real API)
        $result = WhatsappService::sendMessage('60123456789', 'Test message');
        
        // Should return true (successful log)
        $this->assertTrue($result);
    }

    public function test_reminder_command_identifies_pending_complaints()
    {
        // Create old pending complaint
        $oldComplaint = Complaint::factory()->create([
            'status' => 'baru',
            'assigned_to' => null,
            'created_at' => now()->subDays(5)
        ]);

        // Create recent complaint
        $recentComplaint = Complaint::factory()->create([
            'status' => 'baru',
            'assigned_to' => null,
            'created_at' => now()->subDay()
        ]);

        // Run command to check logic
        $this->artisan('complaint:send-reminders --days=3')
             ->expectsOutput('Checking for complaints older than 3 days...')
             ->assertExitCode(0);
    }

    public function test_whatsapp_controller_can_add_number()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        
        $response = $this->actingAs($admin)->post(route('whatsapp.store'), [
            'number' => '60123456789'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('whatsapp_numbers', [
            'number' => '60123456789',
            'status' => 'scanning'
        ]);
    }

    public function test_whatsapp_controller_can_update_status()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $whatsappNumber = \App\Models\WhatsappNumber::create([
            'number' => '60123456789',
            'status' => 'scanning'
        ]);
        
        $response = $this->actingAs($admin)->patch(route('whatsapp.update-status', $whatsappNumber), [
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('whatsapp_numbers', [
            'id' => $whatsappNumber->id,
            'status' => 'active'
        ]);
    }
}
