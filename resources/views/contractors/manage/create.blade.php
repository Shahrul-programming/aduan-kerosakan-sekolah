@extends('layouts.app')
@section('content')
@unless(auth()->user() && auth()->user()->role === 'super_admin')
    <?php abort(403); ?>
@endunless
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-xl font-semibold mb-4">Tambah Kontraktor (Super Admin)</h1>
    <div class="mb-4">
        <a href="{{ url('/admin') }}" class="text-sm text-blue-600 hover:underline">⬅ Kembali ke Dashboard Super Admin</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contractors.manage.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            <label class="block">
                <span class="text-sm text-gray-700">Nama</span>
                <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded border-gray-300" required>
            </label>

            <label class="block">
                <span class="text-sm text-gray-700">Syarikat</span>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="mt-1 block w-full rounded border-gray-300">
            </label>

            <label class="block">
                <span class="text-sm text-gray-700">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded border-gray-300">
            </label>

            <label class="block">
                <span class="text-sm text-gray-700">Telefon</span>
                <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded border-gray-300">
            </label>

            <label class="block">
                <span class="text-sm text-gray-700">Alamat</span>
                <textarea name="address" class="mt-1 block w-full rounded border-gray-300">{{ old('address') }}</textarea>
            </label>

            <label class="block">
                <span class="text-sm text-gray-700">Pilih Sekolah (boleh pilih lebih dari satu)</span>
                <input id="schoolFilter" type="text" placeholder="Cari sekolah..." class="mt-1 mb-2 block w-full rounded border-gray-200 px-2 py-1">
                <select id="schoolsSelect" name="schools[]" multiple size="6" class="mt-1 block w-full rounded border-gray-300">
                    @foreach($schools as $s)
                        <option value="{{ $s->id }}" {{ (collect(old('schools'))->contains($s->id)) ? 'selected' : '' }}>{{ $s->name }} ({{ $s->state ?? '' }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Gunakan Ctrl/Cmd + klik untuk pilih lebih dari satu.</p>
            </label>

            <label class="block">
                <span class="text-sm text-gray-700">Password (tetapkan sekarang jika anda mahu)</span>
                <input type="password" name="password" class="mt-1 block w-full rounded border-gray-300">
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk hantar pautan reset kepada kontraktor.</p>
            </label>

            <div class="flex items-center justify-end space-x-2">
                <a href="{{ route('contractors.manage.index') }}" class="px-3 py-2 rounded border">Batal</a>
                <button class="px-3 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var filter = document.getElementById('schoolFilter');
    var select = document.getElementById('schoolsSelect');
    if (!filter || !select) return;

    filter.addEventListener('input', function () {
        var q = this.value.toLowerCase();
        for (var i = 0; i < select.options.length; i++) {
            var opt = select.options[i];
            opt.style.display = opt.text.toLowerCase().includes(q) ? 'block' : 'none';
        }
    });
});
</script>
@endsection
