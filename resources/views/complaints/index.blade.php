@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Senarai Aduan</h1>
    <a href="{{ route('complaints.create') }}" class="btn btn-primary mb-3">Tambah Aduan</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="baru" {{ request('status')=='baru' ? 'selected' : '' }}>Baru</option>
                <option value="semakan" {{ request('status')=='semakan' ? 'selected' : '' }}>Semakan</option>
                <option value="assigned" {{ request('status')=='assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="proses" {{ request('status')=='proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="school_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Sekolah</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id')==$school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="priority" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Prioriti</option>
                <option value="tinggi" {{ request('priority')=='tinggi' ? 'selected' : '' }}>Tinggi</option>
                <option value="sederhana" {{ request('priority')=='sederhana' ? 'selected' : '' }}>Sederhana</option>
                <option value="rendah" {{ request('priority')=='rendah' ? 'selected' : '' }}>Rendah</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('complaints.index') }}" class="btn btn-light">Reset</a>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>No. Aduan</th>
                <th>Sekolah</th>
                <th>Kategori</th>
                <th>Prioriti</th>
                <th>Status</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
            <tr>
                <td>{{ $complaint->complaint_number }}</td>
                <td>{{ $complaint->school->name ?? '-' }}</td>
                <td>{{ $complaint->category }}</td>
                <td>{{ ucfirst($complaint->priority) }}</td>
                <td>{{ ucfirst($complaint->status) }}</td>
                <td>
                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-info">Lihat</a>
                    <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Padam aduan ini?')">Padam</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $complaints->links() }}
</div>
@endsection
