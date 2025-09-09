@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Senarai Aduan</h1>
        @if(auth()->check() && in_array(optional(auth()->user())->role, ['guru','teacher']))
            <a href="{{ route('complaints.create') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded">Tambah Aduan</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @php
        $statusLabels = [
            'baru' => 'Baru', 'semakan' => 'Semakan', 'assigned' => 'Diberi Tugasan',
            'proses' => 'Sedang Diproses', 'in_progress' => 'Dalam Progress', 'pending' => 'Pending', 'selesai' => 'Selesai', 'completed' => 'Selesai',
        ];
        $priorityLabels = ['tinggi' => 'Tinggi', 'sederhana' => 'Sederhana', 'rendah' => 'Rendah', 'urgent' => 'Urgent'];
    @endphp

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full rounded border-gray-300" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach($statusLabels as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Sekolah</label>
            <select name="school_id" class="w-full rounded border-gray-300" onchange="this.form.submit()">
                <option value="">Semua Sekolah</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Prioriti</label>
            <select name="priority" class="w-full rounded border-gray-300" onchange="this.form.submit()">
                <option value="">Semua Prioriti</option>
                @foreach($priorityLabels as $key => $label)
                    <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end space-x-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
            <a href="{{ route('complaints.index') }}" class="px-4 py-2 border rounded">Reset</a>
        </div>
    </form>

    <!-- Mobile Card Layout -->
    <div class="md:hidden space-y-4">
        @forelse($complaints as $complaint)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-sm">{{ $complaint->complaint_number }}</h3>
                    <p class="text-xs text-gray-600 mt-1">{{ $complaint->school->name ?? '-' }}</p>
                </div>
                <div class="flex flex-col items-end space-y-2">
                    @php $p = $complaint->priority; @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                        {{ $p == 'tinggi' ? 'bg-red-100 text-red-800' :
                           ($p == 'sederhana' ? 'bg-yellow-100 text-yellow-800' :
                           ($p == 'urgent' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $priorityLabels[$p] ?? ucfirst($p) }}
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <p class="text-sm text-gray-700 font-medium">{{ $complaint->category }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($complaint->description, 100) }}</p>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    @php $s = $complaint->status; @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                        {{ match($s) {
                            'baru' => 'bg-blue-100 text-blue-800',
                            'semakan' => 'bg-indigo-100 text-indigo-800',
                            'assigned' => 'bg-purple-100 text-purple-800',
                            'proses','in_progress' => 'bg-yellow-100 text-yellow-800',
                            'pending' => 'bg-gray-100 text-gray-800',
                            'selesai','completed' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800'
                        } }}">
                        {{ $statusLabels[$s] ?? ucfirst($s) }}
                    </span>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('complaints.show', $complaint) }}"
                       class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-100 transition-colors">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </a>
                    <a href="{{ route('complaints.edit', $complaint) }}"
                       class="inline-flex items-center px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-medium rounded-md hover:bg-yellow-100 transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <i class="fas fa-inbox text-gray-400 text-3xl mb-3"></i>
            <p class="text-gray-500">Tiada aduan ditemui.</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop Table Layout -->
    <div class="hidden md:block overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Aduan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sekolah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioriti</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tindakan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($complaints as $complaint)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $complaint->complaint_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $complaint->school->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $complaint->category }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php $p = $complaint->priority; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $p == 'tinggi' ? 'red' : ($p=='sederhana' ? 'yellow' : 'gray') }}-100 text-{{ $p == 'tinggi' ? 'red' : ($p=='sederhana' ? 'yellow' : 'gray') }}-800">{{ $priorityLabels[$p] ?? ucfirst($p) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @php $s = $complaint->status; @endphp
                        @php
                            $color = match($s) {
                                'baru' => 'blue', 'semakan' => 'indigo', 'assigned' => 'purple', 'proses','in_progress' => 'yellow', 'pending' => 'gray', 'selesai','completed' => 'green', default => 'gray'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">{{ $statusLabels[$s] ?? ucfirst($s) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-right">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <a href="{{ route('complaints.show', $complaint) }}" class="px-3 py-1 bg-white border text-sm hover:bg-gray-50 transition-colors">Lihat</a>
                            <a href="{{ route('complaints.edit', $complaint) }}" class="px-3 py-1 bg-yellow-50 border text-sm hover:bg-yellow-100 transition-colors">Edit</a>
                            <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" onsubmit="return confirm('Padam aduan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-50 border text-sm hover:bg-red-100 transition-colors">Padam</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-3 block"></i>
                        Tiada aduan ditemui.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
