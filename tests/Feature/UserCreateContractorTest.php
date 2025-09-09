<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateContractorTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_admin_can_create_contractor_and_inherits_school_id()
    {
        $school = School::factory()->create();
        $admin = User::factory()->create(['role' => 'school_admin', 'school_id' => $school->id]);

        $this->actingAs($admin);

        $response = $this->post(route('users.store'), [
            'name' => 'Kontraktor A',
            'email' => 'kontraktorA@example.test',
            'phone' => '0123456789',
            'role' => 'kontraktor',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'kontraktorA@example.test',
            'role' => 'kontraktor',
            'school_id' => $school->id,
        ]);
    }

    public function test_super_admin_can_create_contractor_and_choose_school()
    {
        $school = School::factory()->create();
        $super = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($super);

        $response = $this->post(route('users.store'), [
            'name' => 'Kontraktor B',
            'email' => 'kontraktorB@example.test',
            'phone' => '0123450000',
            'role' => 'kontraktor',
            'school_id' => $school->id,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'kontraktorB@example.test',
            'role' => 'kontraktor',
            'school_id' => $school->id,
        ]);
    }
}
