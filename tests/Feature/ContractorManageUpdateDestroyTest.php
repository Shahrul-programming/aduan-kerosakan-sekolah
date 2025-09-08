<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\School;
use App\Models\Contractor;

class ContractorManageUpdateDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_update_contractor_and_change_schools()
    {
        $s1 = School::factory()->create();
        $s2 = School::factory()->create();
        $s3 = School::factory()->create();

        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)
            ->post(route('contractors.manage.store'), [
                'name' => 'Kontraktor Uji2',
                'company_name' => 'Syarikat Uji2',
                'email' => 'kontraktor2.test@example.test',
                'schools' => [$s1->id, $s2->id],
            ]);

        $contractor = Contractor::where('email', 'kontraktor2.test@example.test')->first();
        $this->assertNotNull($contractor);

        // Update to only s3
        $this->actingAs($admin)
            ->put(route('contractors.manage.update', $contractor), [
                'name' => 'Kontraktor Uji2 Updated',
                'company_name' => 'Syarikat Updated',
                'email' => 'kontraktor2.test@example.test',
                'schools' => [$s3->id],
            ])
            ->assertRedirect(route('contractors.manage.index'));

        $this->assertDatabaseHas('contractor_school', ['contractor_id' => $contractor->id, 'school_id' => $s3->id]);
        $this->assertDatabaseMissing('contractor_school', ['contractor_id' => $contractor->id, 'school_id' => $s1->id]);
    }

    public function test_super_admin_can_delete_contractor_and_detach_schools()
    {
        $s1 = School::factory()->create();
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)
            ->post(route('contractors.manage.store'), [
                'name' => 'Kontraktor Padam',
                'company_name' => 'Syarikat Padam',
                'email' => 'kontraktor.delete@example.test',
                'schools' => [$s1->id],
            ]);

        $contractor = Contractor::where('email', 'kontraktor.delete@example.test')->first();
        $this->assertNotNull($contractor);

        $this->actingAs($admin)
            ->delete(route('contractors.manage.destroy', $contractor))
            ->assertRedirect(route('contractors.manage.index'));

        $this->assertDatabaseMissing('contractors', ['id' => $contractor->id]);
        $this->assertDatabaseMissing('contractor_school', ['contractor_id' => $contractor->id]);
    }
}
