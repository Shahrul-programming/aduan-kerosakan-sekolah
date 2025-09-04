@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Aduan Mengikut Sekolah</h4>
                    <div class="float-end">
                        <a href="{{ route('reports.by-school.export.pdf') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($complaints->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sekolah</th>
                                        <th>Jumlah Aduan</th>
                                        <th>Selesai</th>
                                        <th>Dalam Proses</th>
                                        <th>Pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr>
                                        <td>{{ $complaint->school_name }}</td>
                                        <td>{{ $complaint->total }}</td>
                                        <td>{{ $complaint->completed ?? 0 }}</td>
                                        <td>{{ $complaint->in_progress ?? 0 }}</td>
                                        <td>{{ $complaint->pending ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Chart -->
                        <div class="mt-4">
                            <canvas id="schoolChart" width="400" height="200"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Tiada data aduan untuk dipaparkan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('schoolChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($complaints->pluck('school_name')) !!},
        datasets: [{
            label: 'Jumlah Aduan',
            data: {!! json_encode($complaints->pluck('total')) !!},
            backgroundColor: '#36A2EB'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Statistik Aduan Mengikut Sekolah'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
