@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Dashboard Analitik Aduan</h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Aduan</h5>
                    <p class="display-4">{{ $total }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Status Aduan</h5>
                    <ul class="list-group">
                        @foreach($byStatus as $status => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($status) }}
                            <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Prestasi Kontraktor</h5>
                    <ul class="list-group">
                        @foreach($byContractor as $contractor)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $contractor->name }}
                            <span class="badge bg-success rounded-pill">{{ $contractor->complaints_count }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
