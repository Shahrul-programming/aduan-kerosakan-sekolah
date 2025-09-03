@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Aduan</h1>
    <form action="{{ route('complaints.update', $complaint) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="complaint_number" class="form-label">No. Aduan</label>
            <input type="text" name="complaint_number" class="form-control" value="{{ old('complaint_number', $complaint->complaint_number) }}" required>
        </div>
        <div class="mb-3">
            <label for="school_id" class="form-label">Sekolah</label>
            <select name="school_id" class="form-control" required>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ $complaint->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $complaint->category) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" required>{{ old('description', $complaint->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Prioriti</label>
            <select name="priority" class="form-control" required>
                <option value="tinggi" {{ $complaint->priority == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                <option value="sederhana" {{ $complaint->priority == 'sederhana' ? 'selected' : '' }}>Sederhana</option>
                <option value="rendah" {{ $complaint->priority == 'rendah' ? 'selected' : '' }}>Rendah</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="baru" {{ $complaint->status == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="semakan" {{ $complaint->status == 'semakan' ? 'selected' : '' }}>Semakan</option>
                <option value="assigned" {{ $complaint->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="proses" {{ $complaint->status == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ $complaint->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        @include('complaints._media_upload')
        <button type="submit" class="btn btn-primary">Kemaskini</button>
        <a href="{{ route('complaints.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
