@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Laporan Prestasi Kontraktor</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kontraktor</th>
                <th>Jumlah Aduan</th>
                <th>Aduan Selesai</th>
                <th>Aduan Belum Selesai</th>
                <th>Kadar Selesai (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contractors as $c)
            <tr>
                <td>{{ $c->name }}</td>
                <td>{{ $c->complaints_count }}</td>
                <td>{{ $c->complaints_selesai }}</td>
                <td>{{ $c->complaints_belum }}</td>
                <td>{{ $c->complaints_count > 0 ? number_format(100 * $c->complaints_selesai / $c->complaints_count, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
