@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Aduan Mengikut Kategori</h4>
                    <div class="float-end">
                        <a href="{{ route('reports.by-category.export.excel') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($complaints->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah Aduan</th>
                                        <th>Peratus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr>
                                        <td>{{ $complaint->category }}</td>
                                        <td>{{ $complaint->total }}</td>
                                        <td>{{ number_format(($complaint->total / $totalComplaints) * 100, 2) }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Chart -->
                        <div class="mt-4">
                            <canvas id="categoryChart" width="400" height="200"></canvas>
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
const ctx = document.getElementById('categoryChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($complaints->pluck('category')) !!},
        datasets: [{
            data: {!! json_encode($complaints->pluck('total')) !!},
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Statistik Aduan Mengikut Kategori'
            }
        }
    }
});
</script>
@endsection
