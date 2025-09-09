<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_submit_complaint_and_school_id_is_enforced()
    {
        // Create a school for the teacher
        $school = School::create([
            'name' => 'Sekolah Contoh',
            'code' => 'SC01',
            'address' => 'Alamat Contoh',
        ]);

        // Create another school to attempt override
        $otherSchool = School::create([
            'name' => 'Sekolah Lain',
            'code' => 'SL02',
            'address' => 'Alamat Lain',
        ]);

        // Create a teacher user linked to $school
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'school_id' => $school->id,
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($teacher);

        $payload = [
            'title' => 'Kipas rosak',
            'school_id' => $otherSchool->id, // attempt to override
            'category' => 'elektrik',
            'description' => 'Kipas siling berhenti berfungsi',
            'priority' => 'sederhana',
        ];

        $response = $this->post(route('complaints.store'), $payload);

        // Guru dialihkan ke dashboard selepas hantar aduan
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('complaints', [
            'title' => 'Kipas rosak',
            'reported_by' => $teacher->id,
            'school_id' => $school->id, // should be teacher's school, not otherSchool
        ]);
    }
}
