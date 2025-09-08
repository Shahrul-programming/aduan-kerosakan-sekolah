@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Edit Aduan</h1>

    <form action="{{ route('complaints.update', $complaint) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Aduan</label>
                    <input type="text" name="complaint_number" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('complaint_number', $complaint->complaint_number) }}" required disabled>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sekolah</label>
                    <select name="school_id" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required disabled>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ $complaint->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <input type="text" name="category" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ old('category', $complaint->category) }}" required disabled>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioriti</label>
                    <select name="priority" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required disabled>
                        @if(isset($priorities) && is_array($priorities))
                            @foreach($priorities as $key => $label)
                                <option value="{{ $key }}" {{ $complaint->priority == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        @else
                            <option value="tinggi" {{ $complaint->priority == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="sederhana" {{ $complaint->priority == 'sederhana' ? 'selected' : '' }}>Sederhana</option>
                            <option value="rendah" {{ $complaint->priority == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        @endif
                    </select>
                </div>
            </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                    <textarea name="description" rows="4" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100" disabled>{{ old('description', $complaint->description) }}</textarea>
                </div>

            {{-- Main status removed to avoid duplicate 'Status Progress' with contractor update section. --}}

            <div class="flex items-center gap-3">
                {{-- Main complaint details are readonly; only status/progress updates are handled separately --}}
                <a href="{{ route('complaints.index') }}" class="inline-flex items-center px-4 py-2 border rounded bg-white dark:bg-gray-900">Kembali</a>
            </div>
        </form>

        {{-- Progress update section for assigned contractors --}}
        @if(auth()->check() && optional(auth()->user())->role === 'kontraktor')
            @php
                $user = auth()->user();
                $userContractor = optional($user)->contractor;
                $userContractorId = $userContractor->id ?? null;
                // assigned_to historically may store contractor.id or user.id; check both
                $assignedTo = $complaint->assigned_to;
                $isAssigned = false;
                if ($assignedTo) {
                    if ($userContractorId && $assignedTo == $userContractorId) {
                        $isAssigned = true;
                    }
                    // if assigned_to is user id (legacy), allow match
                    if ($user && $assignedTo == $user->id) {
                        $isAssigned = true;
                    }
                    // also check contractor.user_id match
                    if ($userContractor && $userContractor->user_id && $assignedTo == $userContractor->user_id) {
                        $isAssigned = true;
                    }
                }

                // whether the complaint was acknowledged by the contractor
                $hasAcknowledged = $complaint->acknowledged_status === 'accepted';

                // Fallback: some legacy/edge cases have assigned_to NULL but the contractor
                // has already acknowledged the job (acknowledged_status == 'accepted').
                // Treat that contractor as assigned when their linked user matches the current user.
                if (! $isAssigned && ! $assignedTo && $hasAcknowledged) {
                    if ($userContractor && $userContractor->user_id && $userContractor->user_id == ($user->id ?? null)) {
                        $isAssigned = true;
                    }
                }

                $canSubmit = $isAssigned && $hasAcknowledged;
            @endphp
            <div class="mt-8 border-t pt-6">
                <h2 class="text-xl font-medium mb-3">Kemaskini Progress</h2>
                {{-- debug panel removed --}}

                @if(! $isAssigned)
                    <div class="mb-3 p-3 bg-yellow-50 text-yellow-800 rounded">Anda belum ditugaskan untuk aduan ini. Hanya kontraktor yang ditugaskan boleh mengemaskini progress.</div>
                @elseif(! $hasAcknowledged)
                    <div class="mb-3 p-3 bg-yellow-50 text-yellow-800 rounded">Sila terima tugasan terlebih dahulu (Acknowledge) sebelum menghantar progress.</div>
                @endif

                <form action="{{ route('complaints.progress.store', $complaint) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Progress</label>
                        <select name="status" class="mt-1 block w-64 rounded border-gray-300">
                            <option value="in_progress">Dalam Progress</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Komen / Catatan</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded border-gray-300" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar (sebelum)</label>
                            <input type="file" name="image_before" class="mt-1 block w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar (selepas)</label>
                            <input type="file" name="image_after" class="mt-1 block w-full">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded" {{ $canSubmit ? '' : 'disabled' }}>Hantar Progress</button>
                        <a href="{{ route('complaints.show', $complaint) }}" class="px-4 py-2 border rounded">Lihat Aduan</a>
                    </div>
                    @if(! $canSubmit)
                        <p class="text-sm text-gray-500 mt-2">Jika butang Hantar tidak aktif, pastikan anda telah ditugaskan dan telah menerima tugasan.</p>
                    @endif
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
