    @if($complaint->progressUpdates->count())
    <hr>
    <h3>Progress Kerja</h3>
    <ul class="list-group mb-3">
        @foreach($complaint->progressUpdates as $progress)
        <li class="list-group-item">
            <strong>{{ $progress->created_at->format('d/m/Y H:i') }}</strong> oleh {{ $progress->contractor->name ?? '-' }}<br>
            <div>{{ $progress->description }}</div>
            @if($progress->image_before)
                <div class="mt-2"><strong>Gambar Sebelum:</strong><br><img src="{{ asset('storage/' . $progress->image_before) }}" style="max-width:200px;"></div>
            @endif
            @if($progress->image_after)
                <div class="mt-2"><strong>Gambar Selepas:</strong><br><img src="{{ asset('storage/' . $progress->image_after) }}" style="max-width:200px;"></div>
            @endif
        </li>
        @endforeach
    </ul>
    @endif

    @php($logs = \App\Models\ActivityLog::where('complaint_id', $complaint->id)->with('user')->latest()->get())
    @if($logs->count())
    <hr>
    <h3>Log Aktiviti</h3>
    <ul class="list-group mb-3">
        @foreach($logs as $log)
        <li class="list-group-item">
            <strong>{{ $log->created_at->format('d/m/Y H:i') }}</strong> - {{ $log->user->name ?? '-' }}: {{ $log->action }}
        </li>
        @endforeach
    </ul>
    @endif
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
    @if($complaint->contractor)
    <div class="mb-3">
        <strong>Kontraktor Ditugaskan:</strong> {{ $complaint->contractor->name }} ({{ $complaint->contractor->company_name }})<br>
        <strong>No. Telefon:</strong> {{ $complaint->contractor->phone }}<br>
        <strong>Email:</strong> {{ $complaint->contractor->email }}
    </div>
    <div class="mb-3">
        <strong>Status Acknowledge:</strong>
        @if($complaint->acknowledged_status === 'pending')
            <span class="badge bg-warning">Belum Diterima</span>
        @elseif($complaint->acknowledged_status === 'accepted')
            <span class="badge bg-success">Diterima</span>
        @elseif($complaint->acknowledged_status === 'rejected')
            <span class="badge bg-danger">Ditolak</span>
        @endif
    </div>
    @if(auth()->check() && auth()->user()->role === 'kontraktor' && $complaint->assigned_to == auth()->id() && $complaint->acknowledged_status === 'pending')
        <form action="{{ route('complaints.acknowledge', $complaint) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" name="acknowledge" value="accepted" class="btn btn-success">Terima Tugasan</button>
            <button type="submit" name="acknowledge" value="rejected" class="btn btn-danger" onclick="return confirm('Tolak tugasan ini?');">Tolak Tugasan</button>
        </form>
    @endif
    @endif
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

    @if($complaint->progressUpdates->count())
    <hr>
    <h3>Progress Kerja</h3>
    <ul class="list-group mb-3">
        @foreach($complaint->progressUpdates as $progress)
        <li class="list-group-item">
            <strong>{{ $progress->created_at->format('d/m/Y H:i') }}</strong> oleh {{ $progress->contractor->name ?? '-' }}<br>
            <div>{{ $progress->description }}</div>
            @if($progress->image_before)
                <div class="mt-2"><strong>Gambar Sebelum:</strong><br><img src="{{ asset('storage/' . $progress->image_before) }}" style="max-width:200px;"></div>
            @endif
            @if($progress->image_after)
                <div class="mt-2"><strong>Gambar Selepas:</strong><br><img src="{{ asset('storage/' . $progress->image_after) }}" style="max-width:200px;"></div>
            @endif
        </li>
        @endforeach
    </ul>
    @endif

    @if(auth()->check() && auth()->user()->role === 'kontraktor' && $complaint->assigned_to == auth()->id() && $complaint->acknowledged_status === 'accepted')
        <hr>
        <h3>Kemaskini Progress Kerja</h3>
        <form action="{{ route('complaints.progress.store', $complaint) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Progress</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image_before" class="form-label">Gambar Sebelum</label>
                <input type="file" name="image_before" class="form-control">
            </div>
            <div class="mb-3">
                <label for="image_after" class="form-label">Gambar Selepas</label>
                <input type="file" name="image_after" class="form-control">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="mark_complete" value="1" class="form-check-input" id="mark_complete">
                <label class="form-check-label" for="mark_complete">Tandakan sebagai Selesai</label>
            </div>
            <button type="submit" class="btn btn-success">Hantar Progress</button>
        </form>
    @endif
</div>
@endsection
