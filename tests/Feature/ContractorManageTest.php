<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\School;
use App\Models\Contractor;

class ContractorManageTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_contractor_with_multiple_schools_and_user_linked()
    {
        // create schools
        $s1 = School::factory()->create();
        $s2 = School::factory()->create();

        // super admin
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)
            ->post(route('contractors.manage.store'), [
                'name' => 'Kontraktor Uji',
                'company_name' => 'Syarikat Uji',
                'email' => 'kontraktor.test@example.test',
                'phone' => '0123456789',
                'address' => 'Alamat',
                'schools' => [$s1->id, $s2->id],
            ])
            ->assertRedirect(route('contractors.manage.index'));

        $this->assertDatabaseHas('contractors', ['email' => 'kontraktor.test@example.test', 'company_name' => 'Syarikat Uji']);

        $contractor = Contractor::where('email', 'kontraktor.test@example.test')->first();
        $this->assertNotNull($contractor);

        // pivot entries
        $this->assertDatabaseHas('contractor_school', ['contractor_id' => $contractor->id, 'school_id' => $s1->id]);
        $this->assertDatabaseHas('contractor_school', ['contractor_id' => $contractor->id, 'school_id' => $s2->id]);

        // user must exist and be linked
        $this->assertDatabaseHas('users', ['email' => 'kontraktor.test@example.test', 'role' => 'kontraktor']);
        $this->assertDatabaseHas('contractors', ['id' => $contractor->id, 'user_id' => \DB::table('users')->where('email', 'kontraktor.test@example.test')->value('id')]);
    }
}
