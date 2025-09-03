@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Maklumat Aduan</h1>
    <div class="mb-3">
        <strong>No. Aduan:</strong> {{ $complaint->complaint_number }}
    </div>
    <div class="mb-3">
        <strong>Sekolah:</strong> {{ $complaint->school->name ?? '-' }}
    </div>
    <div class="mb-3">
        <strong>Kategori:</strong> {{ $complaint->category }}
    </div>
    <div class="mb-3">
        <strong>Deskripsi:</strong> {{ $complaint->description }}
    </div>
    <div class="mb-3">
        <strong>Prioriti:</strong> {{ ucfirst($complaint->priority) }}
    </div>
    <div class="mb-3">
        <strong>Status:</strong> {{ ucfirst($complaint->status) }}
    </div>
    @if($complaint->image)
        <div class="mb-3">
            <strong>Gambar:</strong><br>
            <img src="{{ asset('storage/' . $complaint->image) }}" alt="Gambar Aduan" style="max-width:300px;">
        </div>
    @endif
    @if($complaint->video)
        <div class="mb-3">
            <strong>Video:</strong><br>
            <video controls style="max-width:400px;">
                <source src="{{ asset('storage/' . $complaint->video) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    @endif
    <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('complaints.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
