@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Dashboard Analitik (Grafik)</h2>
    <div class="row mb-4">
        <div class="col-md-6">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusData = @json($statusData);
    const categoryData = @json($categoryData);
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#007bff','#ffc107','#28a745','#dc3545','#6c757d']
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(categoryData),
            datasets: [{
                label: 'Jumlah Aduan',
                data: Object.values(categoryData),
                backgroundColor: '#007bff'
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
</script>
@endsection
