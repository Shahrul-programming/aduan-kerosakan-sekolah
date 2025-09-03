@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Sekolah</h1>
    <form action="{{ route('schools.update', $school) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Sekolah</label>
            <input type="text" name="name" class="form-control" value="{{ $school->name }}" required>
        </div>
        <div class="mb-3">
            <label>Kod Sekolah</label>
            <input type="text" name="code" class="form-control" value="{{ $school->code }}" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <input type="text" name="address" class="form-control" value="{{ $school->address }}" required>
        </div>
        <div class="mb-3">
            <label>Nama Pengetua</label>
            <input type="text" name="principal_name" class="form-control" value="{{ $school->principal_name }}" required>
        </div>
        <div class="mb-3">
            <label>No. Telefon Pengetua</label>
            <input type="text" name="principal_phone" class="form-control" value="{{ $school->principal_phone }}" required>
        </div>
        <div class="mb-3">
            <label>Nama PK HEM</label>
            <input type="text" name="hem_name" class="form-control" value="{{ $school->hem_name }}" required>
        </div>
        <div class="mb-3">
            <label>No. Telefon PK HEM</label>
            <input type="text" name="hem_phone" class="form-control" value="{{ $school->hem_phone }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Kemaskini</button>
        <a href="{{ route('schools.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
