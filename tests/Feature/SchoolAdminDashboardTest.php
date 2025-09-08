<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchoolAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_admin_dashboard_shows_correct_school_info()
    {
        $school = School::factory()->create(['name' => 'Sekolah Ujian XYZ']);
        $admin = User::factory()->create(['role' => 'school_admin', 'school_id' => $school->id]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText('Sekolah Ujian XYZ');
    }
}
