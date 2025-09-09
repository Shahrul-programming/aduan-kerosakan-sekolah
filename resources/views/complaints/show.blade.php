@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        @php
            // Determine if current user is the assigned contractor (used for header actions)
            $isAssignedToThisUser = false;
            if (auth()->check()) {
                $user = auth()->user();
                if (method_exists($user, 'contractor') && $user->contractor) {
                    $isAssignedToThisUser = ($complaint->assigned_to == $user->contractor->id);
                } else {
                    $isAssignedToThisUser = ($complaint->assigned_to == $user->id);
                }
            }
        @endphp

        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Maklumat Aduan</h1>
                <div class="text-sm text-gray-500 mt-1">No. Aduan: <span class="font-medium text-gray-800">{{ $complaint->complaint_number }}</span></div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('complaints.edit', $complaint) }}" class="px-3 py-2 bg-yellow-500 text-white rounded">Update progress</a>
                <a href="{{ route('complaints.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded">Kembali</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">No. Aduan</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $complaint->complaint_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Sekolah</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $complaint->school->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Nama Pengadu</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $complaint->user->name ?? ($complaint->reported_by_name ?? '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">No. Telefon Pengadu</dt>
                        <dd class="mt-1 text-gray-700 dark:text-gray-300">{{ $complaint->reporter_phone ?? $complaint->user->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tarikh Lapor</dt>
                        <dd class="mt-1 text-gray-700 dark:text-gray-300">{{ optional($complaint->reported_at)->format('d/m/Y H:i') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Kategori</dt>
                        <dd class="mt-1 text-gray-700 dark:text-gray-300">{{ ucfirst($complaint->category) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Prioriti</dt>
                        <dd class="mt-1 text-gray-700 dark:text-gray-300">{{ ucfirst($complaint->priority) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="mt-1"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800">{{ ucfirst($complaint->status) }}</span></dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-sm text-gray-500">Deskripsi</h3>
                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $complaint->description }}</p>

                @if($complaint->contractor)
                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded flex justify-between items-start gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Kontraktor Ditugaskan</h4>
                        <div class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $complaint->contractor->name }} @if($complaint->contractor->company_name)<span class="text-gray-500">({{ $complaint->contractor->company_name }})</span>@endif</div>
                        <div class="text-sm text-gray-600">{{ $complaint->contractor->phone }} @if($complaint->contractor->email) &middot; {{ $complaint->contractor->email }}@endif</div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        <div>
                            @if($complaint->acknowledged_status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs">Belum Diterima</span>
                            @elseif($complaint->acknowledged_status === 'accepted')
                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-800 text-xs">Diterima</span>
                            @elseif($complaint->acknowledged_status === 'rejected')
                                <span class="inline-flex items-center px-2 py-1 rounded bg-red-100 text-red-800 text-xs">Ditolak</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            @if(auth()->check() && auth()->user()->role === 'kontraktor' && $isAssignedToThisUser && $complaint->acknowledged_status === 'pending')
                                <form action="{{ route('complaints.acknowledge', $complaint) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <button type="submit" name="acknowledge" value="accepted" class="px-3 py-1 bg-green-600 text-white rounded text-sm">Terima</button>
                                    <button type="submit" name="acknowledge" value="rejected" class="px-3 py-1 bg-red-600 text-white rounded text-sm" onclick="return confirm('Tolak tugasan ini?');">Tolak</button>
                                </form>
                            @endif

                            @if(auth()->check() && auth()->user()->role === 'kontraktor' && $isAssignedToThisUser)
                                <a href="{{ route('complaints.work-order', $complaint) }}" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Work Order</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Acknowledgement and download actions are in the contractor card above --}}
            </div>
        </div>

        {{-- Media: image/video --}}
        @php
            // Resolve image/video URLs robustly: absolute URLs, storage disk, or asset fallback
            use Illuminate\Support\Facades\Storage;
            use Illuminate\Support\Str;

            $imageUrl = null;
            $videoUrl = null;

            if ($complaint->image) {
                if (Str::startsWith($complaint->image, ['http://', 'https://'])) {
                    $imageUrl = $complaint->image;
                } elseif (Storage::disk('public')->exists($complaint->image)) {
                    $imageUrl = Storage::disk('public')->url($complaint->image);
                } else {
                    $imageUrl = asset('storage/' . ltrim($complaint->image, '/'));
                }
            }

            if ($complaint->video) {
                if (Str::startsWith($complaint->video, ['http://', 'https://'])) {
                    $videoUrl = $complaint->video;
                } elseif (Storage::disk('public')->exists($complaint->video)) {
                    $videoUrl = Storage::disk('public')->url($complaint->video);
                } else {
                    $videoUrl = asset('storage/' . ltrim($complaint->video, '/'));
                }
            }
        @endphp

        @if($imageUrl || $videoUrl)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($imageUrl)
                <div>
                    <h4 class="text-sm text-gray-500">Gambar Aduan</h4>
                    @php
                        // simple SVG placeholder (data URI)
                        $placeholderSvg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600"><rect width="800" height="600" fill="#f3f4f6"/><g fill="#9ca3af"><text x="50%" y="50%" font-family="Arial, Helvetica, sans-serif" font-size="20" dominant-baseline="middle" text-anchor="middle">Tiada Pratonton Imej</text></g></svg>');
                    @endphp

                    <div class="mt-2 w-full min-h-[12rem] bg-gray-100 rounded overflow-hidden flex items-center justify-center relative">
                        {{-- Thumbnail that opens lightbox on click --}}
                        <button type="button" class="w-full h-full p-0 border-0 bg-transparent text-left" aria-label="Buka imej penuh" onclick="openLightbox('{{ $imageUrl }}')">
                            <img src="{{ $imageUrl }}" alt="Gambar Aduan" class="w-full h-full object-contain" loading="lazy" decoding="async"
                                onerror="this.onerror=null;this.src='{{ $placeholderSvg }}';this.parentNode.classList.add('bg-gray-200');">
                        </button>
                        {{-- small overlay label for accessibility/clarity --}}
                        <span class="absolute left-3 bottom-3 text-xs text-gray-600 bg-white/80 px-2 py-1 rounded">Klik untuk lihat</span>
                    </div>
                </div>
                @endif

                @if($videoUrl)
                <div>
                    <h4 class="text-sm text-gray-500">Video</h4>
                    <div class="mt-2">
                        <video controls class="w-full rounded shadow-sm max-h-64">
                            <source src="{{ $videoUrl }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                @endif
            </div>
        @endif

    {{-- actions moved to header for a cleaner layout --}}
    </div>

    {{-- Progress updates --}}
    {{-- Assign contractor (school admin only) --}}
    @if(auth()->check() && auth()->user()->role === 'school_admin' && auth()->user()->school_id === $complaint->school_id)
    <div class="mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tugaskan Kontraktor</h2>
        @php
            // Include contractors either with direct school_id or linked via the contractor_school pivot
            $schoolContractors = \App\Models\Contractor::where(function($q) use ($complaint) {
                $q->where('school_id', $complaint->school_id)
                  ->orWhereHas('schools', function($q2) use ($complaint) {
                      $q2->where('schools.id', $complaint->school_id);
                  });
            })->orderBy('name')->get();
        @endphp
        @if($schoolContractors->count())
            @if(is_null($complaint->assigned_to))
                <form action="{{ route('complaints.assign', $complaint) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600 mb-2">Pilih Kontraktor</label>
                        <select name="contractor_id" class="border rounded p-2 w-full">
                            <option value="">-- Pilih --</option>
                            @foreach($schoolContractors as $sc)
                                <option value="{{ $sc->id }}" {{ $complaint->assigned_to == $sc->id ? 'selected' : '' }}>{{ $sc->name }} ({{ $sc->company_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Tugaskan</button>
                        <a href="{{ route('complaints.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded">Kembali</a>
                    </div>
                </form>
            @else
                <div class="mb-3">
                    <label class="block text-sm text-gray-600 mb-2">Kontraktor Dipilih</label>
                    <div class="p-3 bg-gray-50 rounded">{{ $complaint->contractor->name }} @if($complaint->contractor->company_name) ({{ $complaint->contractor->company_name }}) @endif</div>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('complaints.unassign', $complaint) }}" method="POST" onsubmit="return confirm('Nyah-tugaskan kontraktor dari aduan ini?');">
                        @csrf
                        <button class="px-4 py-2 bg-red-600 text-white rounded">Unassign</button>
                    </form>
                    <a href="{{ route('complaints.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded">Kembali</a>
                </div>
            @endif
        @else
            <div class="text-sm text-gray-500">Tiada kontraktor berdaftar untuk sekolah ini.</div>
        @endif
    </div>
    @endif
    @if($complaint->progressUpdates->count())
    <div class="mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Progress Kerja</h2>
        <div class="space-y-4">
            @foreach($complaint->progressUpdates as $progress)
            <div class="p-4 border rounded bg-gray-50 dark:bg-gray-700">
                <div class="flex justify-between items-start">
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $progress->contractor->name ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $progress->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $progress->description }}</p>
                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @if($progress->image_before)
                    <div>
                        <div class="text-xs text-gray-500">Gambar Sebelum</div>
                        <img src="{{ asset('storage/' . $progress->image_before) }}" class="mt-1 w-full h-40 object-cover rounded">
                    </div>
                    @endif
                    @if($progress->image_after)
                    <div>
                        <div class="text-xs text-gray-500">Gambar Selepas</div>
                        <img src="{{ asset('storage/' . $progress->image_after) }}" class="mt-1 w-full h-40 object-cover rounded">
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Activity logs --}}
    @php($logs = \App\Models\ActivityLog::where('complaint_id', $complaint->id)->with('user')->latest()->get())
    @if($logs->count())
    <div class="mt-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Log Aktiviti</h2>
        <ul class="space-y-3">
            @foreach($logs as $log)
            <li class="text-sm text-gray-700 dark:text-gray-300"> <strong>{{ $log->created_at->format('d/m/Y H:i') }}</strong> - {{ $log->user->name ?? '-' }}: {{ $log->action }}</li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<style>
    /* very small lightbox styles */
    .__lightbox-backdrop{position:fixed;inset:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:60}
    .__lightbox-img{max-width:95%;max-height:95%;box-shadow:0 10px 30px rgba(0,0,0,0.5);border-radius:6px}
    .__lightbox-close{position:fixed;right:18px;top:18px;z-index:70;color:#fff;background:transparent;border:0;font-size:22px}
</style>
<div id="__lightbox" style="display:none;">
    <div id="__lightbox_backdrop" class="__lightbox-backdrop" onclick="closeLightbox()" role="dialog" aria-hidden="true">
        <img id="__lightbox_img" class="__lightbox-img" src="" alt="Preview">
    </div>
    <button id="__lightbox_close" class="__lightbox-close" onclick="closeLightbox()" aria-label="Tutup">&times;</button>
</div>
<script>
    function openLightbox(src){
        var lb = document.getElementById('__lightbox');
        var img = document.getElementById('__lightbox_img');
        img.src = src;
        lb.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox(){
        var lb = document.getElementById('__lightbox');
        var img = document.getElementById('__lightbox_img');
        img.src = '';
        lb.style.display = 'none';
        document.body.style.overflow = '';
    }
    // close on ESC
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeLightbox(); });
</script>
@endpush
