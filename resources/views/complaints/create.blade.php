@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Tambah Aduan</h1>
    <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="complaint_number" class="form-label">No. Aduan</label>
            <input type="text" name="complaint_number" class="form-control" value="{{ old('complaint_number') }}" required>
        </div>
        <div class="mb-3">
            <label for="school_id" class="form-label">Sekolah</label>
            <select name="school_id" class="form-control" required>
                <option value="">Pilih Sekolah</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Prioriti</label>
            <select name="priority" class="form-control" required>
                <option value="tinggi">Tinggi</option>
                <option value="sederhana">Sederhana</option>
                <option value="rendah">Rendah</option>
            </select>
        </div>
    @include('complaints._media_upload')
    <button type="submit" class="btn btn-primary">Hantar</button>
    <a href="{{ route('complaints.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
