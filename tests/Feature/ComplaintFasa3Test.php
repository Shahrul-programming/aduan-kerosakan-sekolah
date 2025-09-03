<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Contractor;
use App\Models\User;
use App\Models\ProgressUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintFasa3Test extends TestCase
{
    use RefreshDatabase;

    public function test_pengurusan_boleh_assign_kontraktor_ke_aduan()
    {
        $pengurusan = User::factory()->create(['role' => 'pengurusan']);
        $kontraktor = Contractor::factory()->create();
        $aduan = Complaint::factory()->create(['assigned_to' => null]);

        $response = $this->actingAs($pengurusan)->put(route('complaints.update', $aduan), [
            'complaint_number' => $aduan->complaint_number,
            'school_id' => $aduan->school_id,
            'category' => $aduan->category,
            'description' => $aduan->description,
            'priority' => $aduan->priority,
            'status' => 'assigned',
            'assigned_to' => $kontraktor->id,
        ]);
        $response->assertRedirect(route('complaints.index'));
        $this->assertDatabaseHas('complaints', [
            'id' => $aduan->id,
            'assigned_to' => $kontraktor->id,
            'status' => 'assigned',
        ]);
    }

    public function test_kontraktor_boleh_kemaskini_progress_aduan()
    {
        $kontraktor = User::factory()->create(['role' => 'kontraktor']);
        $contractor = Contractor::factory()->create(['id' => $kontraktor->id]);
        $aduan = Complaint::factory()->create([
            'assigned_to' => $contractor->id,
            'acknowledged_status' => 'accepted' // Aduan sudah di-acknowledge
        ]);

        $response = $this->actingAs($kontraktor)->post(route('complaints.progress.store', $aduan), [
            'description' => 'Kerja dalam proses',
        ]);
        $response->assertRedirect(route('complaints.show', $aduan));
        $this->assertDatabaseHas('progress_updates', [
            'complaint_id' => $aduan->id,
            'contractor_id' => $kontraktor->id,
            'description' => 'Kerja dalam proses',
        ]);
    }

    public function test_log_aktiviti_akan_direkod()
    {
        $pengurusan = User::factory()->create(['role' => 'pengurusan']);
        $kontraktor = User::factory()->create(['role' => 'kontraktor']);
        $contractor = Contractor::factory()->create(['id' => $kontraktor->id]);
        $aduan = Complaint::factory()->create(['assigned_to' => null]);

        // Assign kontraktor
        $this->actingAs($pengurusan)->put(route('complaints.update', $aduan), [
            'complaint_number' => $aduan->complaint_number,
            'school_id' => $aduan->school_id,
            'category' => $aduan->category,
            'description' => $aduan->description,
            'priority' => $aduan->priority,
            'status' => 'assigned',
            'assigned_to' => $contractor->id,
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'assign kontraktor',
            'complaint_id' => $aduan->id,
        ]);

        // Kontraktor acknowledge tugasan
        $this->actingAs($kontraktor)->post(route('complaints.acknowledge', $aduan->fresh()), [
            'acknowledge' => 'accepted',
        ]);

        // Kontraktor update progress  
        $this->actingAs($kontraktor)->post(route('complaints.progress.store', $aduan->fresh()), [
            'description' => 'Kerja dalam proses',
        ]);
        
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'kemaskini progress',
            'complaint_id' => $aduan->id,
        ]);
    }
    public function test_kontraktor_mesti_acknowledge_sebelum_update_progress()
    {
        $kontraktor = User::factory()->create(['role' => 'kontraktor']);
        $contractor = Contractor::factory()->create(['id' => $kontraktor->id]);
        $aduan = Complaint::factory()->create([
            'assigned_to' => $contractor->id,
            'acknowledged_status' => 'pending',
        ]);

        // Cuba update progress sebelum acknowledge
        $response = $this->actingAs($kontraktor)->post(route('complaints.progress.store', $aduan), [
            'description' => 'Kerja dalam proses',
        ]);
        // Sepatutnya tidak dibenarkan (redirect atau error)
        $this->assertDatabaseMissing('progress_updates', [
            'complaint_id' => $aduan->id,
            'contractor_id' => $kontraktor->id,
        ]);

        // Kontraktor acknowledge (terima tugasan)
        $response = $this->actingAs($kontraktor)->post(route('complaints.acknowledge', $aduan), [
            'acknowledge' => 'accepted',
        ]);
        $aduan->refresh();
        $this->assertEquals('accepted', $aduan->acknowledged_status);

        // Selepas acknowledge, boleh update progress
        $response = $this->actingAs($kontraktor)->post(route('complaints.progress.store', $aduan), [
            'description' => 'Kerja dalam proses',
        ]);
        $this->assertDatabaseHas('progress_updates', [
            'complaint_id' => $aduan->id,
            'contractor_id' => $kontraktor->id,
            'description' => 'Kerja dalam proses',
        ]);

        // Log acknowledge direkod
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'Terima tugasan',
            'complaint_id' => $aduan->id,
        ]);
    }
}
