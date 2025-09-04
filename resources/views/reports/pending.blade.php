@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title mb-0">Laporan Aduan Belum Selesai</h2>
                <a href="{{ route('reports.pending.export.pdf', request()->all()) }}" class="btn btn-danger btn-sm">Export PDF</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Aduan</th>
                            <th>Sekolah</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Kontraktor</th>
                            <th>Tarikh Aduan</th>
                            <th>Tempoh (hari)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $c)
                        <tr>
                            <td>{{ $c->complaint_number }}</td>
                            <td>{{ $c->school->name ?? '-' }}</td>
                            <td>{{ $c->user->name ?? '-' }}</td>
                            <td>{{ $c->category }}</td>
                            <td>{{ $c->status }}</td>
                            <td>{{ $c->contractor->name ?? '-' }}</td>
                            <td>{{ $c->created_at }}</td>
                            <td>{{ $c->created_at->diffInDays(now()) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tiada data aduan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
