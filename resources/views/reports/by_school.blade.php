@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title mb-0">Laporan Aduan Mengikut Sekolah</h2>
                <a href="{{ route('reports.by-school.export.pdf') }}" class="btn btn-danger btn-sm">Export PDF</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sekolah</th>
                            <th>Jumlah Aduan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Tiada data aduan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(function(){ $('#datatable').DataTable(); });
</script>
@endsection
