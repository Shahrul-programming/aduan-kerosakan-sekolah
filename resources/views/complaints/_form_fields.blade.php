<!-- Complaint form fields partial (used in create page and teacher dashboard) -->
@php
use App\Models\School;

// Ensure $schools is defined so this partial can be used standalone (e.g. teacher dashboard)
if (!isset($schools)) {
    if (auth()->check() && auth()->user()->school_id) {
        // Limit to the authenticated user's school
        $schools = School::where('id', auth()->user()->school_id)->get();
    } else {
        // Fallback: load all schools for super-admins or when no user school is set
        $schools = School::all();
    }
}
@endphp

@csrf

<!-- Auto-generated Info -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
        <span class="text-sm text-blue-700">
            <strong>Nombor Aduan:</strong> Akan dijana secara automatik selepas submit
        </span>
    </div>
</div>

<!-- Reporter info (prefill logic handled by parent view) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="reporter_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengadu</label>
        <input type="text" name="reporter_name" id="reporter_name" class="w-full rounded-md border-gray-300 shadow-sm" value="{{ old('reporter_name', request('reporter_name', auth()->user()->name ?? '')) }}">
    </div>
    <div>
        <label for="reporter_phone" class="block text-sm font-medium text-gray-700 mb-2">No Telefon Pengadu</label>
        <input type="text" name="reporter_phone" id="reporter_phone" class="w-full rounded-md border-gray-300 shadow-sm" value="{{ old('reporter_phone', request('reporter_phone', auth()->user()->phone ?? '')) }}">
    </div>
</div>

<!-- Title -->
<div>
    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tajuk Aduan <span class="text-red-500">*</span></label>
    <input type="text" name="title" id="title" class="w-full rounded-md border-gray-300 shadow-sm" value="{{ old('title') }}" placeholder="Contoh: Kipas siling rosak" required>
</div>

<!-- School Selection -->
<div>
    <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">Sekolah <span class="text-red-500">*</span></label>

    @if(auth()->check() && in_array(optional(auth()->user())->role, ['guru', 'teacher']))
        {{-- Untuk guru: tunjukkan select tetapi disable dan juga sertakan hidden input supaya nilai dihantar --}}
        @php $userSchool = $schools->first(); @endphp
        <select id="school_id" class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50" disabled>
            <option value="{{ $userSchool->id ?? '' }}">{{ $userSchool->name ?? '—' }}</option>
        </select>
        <input type="hidden" name="school_id" value="{{ old('school_id', request('school_id', optional($userSchool)->id ?? auth()->user()->school_id ?? '')) }}">
    @else
        <select name="school_id" id="school_id" class="w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="">-- Pilih Sekolah --</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ (old('school_id', request('school_id', auth()->user()->school_id ?? '')) == $school->id) ? 'selected' : '' }}>{{ $school->name }}</option>
            @endforeach
        </select>
    @endif
</div>

<!-- Category -->
<div>
    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori Kerosakan <span class="text-red-500">*</span></label>
    <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="elektrik" {{ old('category') == 'elektrik' ? 'selected' : '' }}>Elektrik</option>
        <option value="paip" {{ old('category') == 'paip' ? 'selected' : '' }}>Paip & Plumbing</option>
        <option value="bumbung" {{ old('category') == 'bumbung' ? 'selected' : '' }}>Bumbung & Atap</option>
        <option value="pintu_tingkap" {{ old('category') == 'pintu_tingkap' ? 'selected' : '' }}>Pintu & Tingkap</option>
        <option value="lantai" {{ old('category') == 'lantai' ? 'selected' : '' }}>Lantai & Jubin</option>
        <option value="dinding" {{ old('category') == 'dinding' ? 'selected' : '' }}>Dinding & Cat</option>
        <option value="peralatan" {{ old('category') == 'peralatan' ? 'selected' : '' }}>Peralatan & Perabot</option>
        <option value="tandas" {{ old('category') == 'tandas' ? 'selected' : '' }}>Tandas & Bilik Air</option>
        <option value="lain-lain" {{ old('category') == 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
    </select>
</div>

<!-- Priority -->
<div>
    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Tahap Keutamaan <span class="text-red-500">*</span></label>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <label class="relative">
            <input type="radio" name="priority" value="urgent" class="peer sr-only" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
            <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 text-center">
                <div class="text-sm font-medium text-red-700">Kecemasan</div>
            </div>
        </label>
        <label class="relative">
            <input type="radio" name="priority" value="tinggi" class="peer sr-only" {{ old('priority') == 'tinggi' ? 'checked' : '' }}>
            <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 text-center">
                <div class="text-sm font-medium text-orange-700">Tinggi</div>
            </div>
        </label>
        <label class="relative">
            <input type="radio" name="priority" value="sederhana" class="peer sr-only" {{ old('priority', 'sederhana') == 'sederhana' ? 'checked' : '' }}>
            <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-yellow-500 peer-checked:bg-yellow-50 text-center">
                <div class="text-sm font-medium text-yellow-700">Sederhana</div>
            </div>
        </label>
        <label class="relative">
            <input type="radio" name="priority" value="rendah" class="peer sr-only" {{ old('priority') == 'rendah' ? 'checked' : '' }}>
            <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 text-center">
                <div class="text-sm font-medium text-green-700">Rendah</div>
            </div>
        </label>
    </div>
</div>

<!-- Description -->
<div>
    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Kerosakan <span class="text-red-500">*</span></label>
    <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Terangkan kerosakan dengan terperinci." required>{{ old('description') }}</textarea>
</div>

<!-- Media Upload (images/videos) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Kerosakan</label>
        <input id="image" name="image" type="file" class="w-full">
    </div>
    <div>
        <label for="video" class="block text-sm font-medium text-gray-700 mb-2">Video (Pilihan)</label>
        <input id="video" name="video" type="file" class="w-full">
    </div>
</div>
