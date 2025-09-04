<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\School;
use App\Models\Complaint;
use App\Models\Contractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportFasa5Test extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_view_report_by_category()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($admin)->get(route('reports.by-category'));
        $response->assertStatus(200)->assertSee('Laporan Aduan Mengikut Kategori');
    }


    public function test_can_view_report_by_school()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($admin)->get(route('reports.by-school'));
        $response->assertStatus(200)->assertSee('Laporan Aduan Mengikut Sekolah');
    }

    public function test_can_export_report_by_school_pdf()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($admin)->get(route('reports.by-school.export.pdf'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=aduan-mengikut-sekolah.pdf');
    }

    public function test_pending_report_lists_unfinished_complaints()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $complaint = Complaint::factory()->create(['status' => 'proses']);
        $response = $this->actingAs($admin)->get(route('reports.pending'));
        $response->assertStatus(200)->assertSee($complaint->complaint_number);
    }
}
