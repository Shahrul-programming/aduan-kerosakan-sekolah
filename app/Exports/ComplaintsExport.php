<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintsExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        return Complaint::with(['school', 'user', 'contractor'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->get()
            ->map(function ($c) {
                return [
                    $c->complaint_number,
                    $c->school->name ?? '',
                    $c->user->name ?? '',
                    $c->category,
                    $c->status,
                    $c->contractor->name ?? '',
                    $c->created_at,
                ];
            });
    }

    public function headings(): array
    {
        return ['No Aduan', 'Sekolah', 'Pelapor', 'Kategori', 'Status', 'Kontraktor', 'Tarikh Aduan'];
    }
}
