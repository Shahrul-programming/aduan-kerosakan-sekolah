@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Senarai Sekolah</h1>
    <a href="{{ route('schools.create') }}" class="btn btn-primary mb-3">Tambah Sekolah</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kod</th>
                <th>Alamat</th>
                <th>Pengetua</th>
                <th>PK HEM</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schools as $school)
            <tr>
                <td>{{ $school->name }}</td>
                <td>{{ $school->code }}</td>
                <td>{{ $school->address }}</td>
                <td>{{ $school->principal_name }} ({{ $school->principal_phone }})</td>
                <td>{{ $school->hem_name }} ({{ $school->hem_phone }})</td>
                <td>
                    <a href="{{ route('schools.edit', $school) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('schools.destroy', $school) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Padam sekolah?')">Padam</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
