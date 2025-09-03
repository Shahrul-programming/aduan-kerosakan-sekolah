<?php
namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_school_list()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($user);
        $response = $this->get('/schools');
        $response->assertStatus(200);
    }

    public function test_super_admin_can_create_school()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($user);
        $data = [
            'name' => 'Sekolah Test',
            'code' => 'ST001',
            'address' => 'Alamat Test',
            'principal_name' => 'Pengetua Test',
            'principal_phone' => '0123456789',
            'hem_name' => 'PK HEM Test',
            'hem_phone' => '0198765432',
        ];
        $response = $this->post('/schools', $data);
        $response->assertRedirect('/schools');
        $this->assertDatabaseHas('schools', ['name' => 'Sekolah Test']);
    }

    public function test_super_admin_can_edit_school()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $school = School::factory()->create();
        $this->actingAs($user);
        $data = [
            'name' => 'Sekolah Edit',
            'code' => $school->code,
            'address' => 'Alamat Edit',
            'principal_name' => 'Pengetua Edit',
            'principal_phone' => '0111111111',
            'hem_name' => 'PK HEM Edit',
            'hem_phone' => '0199999999',
        ];
        $response = $this->put('/schools/' . $school->id, $data);
        $response->assertRedirect('/schools');
        $this->assertDatabaseHas('schools', ['name' => 'Sekolah Edit']);
    }

    public function test_super_admin_can_delete_school()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $school = School::factory()->create();
        $this->actingAs($user);
        $response = $this->delete('/schools/' . $school->id);
        $response->assertRedirect('/schools');
        $this->assertDatabaseMissing('schools', ['id' => $school->id]);
    }
}
