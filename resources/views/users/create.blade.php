@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Tambah Pengguna Baru</h1>
    @php
        // Prefilled query values (must be defined before use)
        $prefRole = request()->query('role') ?? null;
        $prefSchoolId = request()->query('school_id') ?? null;

        // Try to resolve the school for which we're creating a user so we can show the self-register link
        $showRegisterLinkSchool = null;
        if(isset($schools) && $prefSchoolId) {
            // $schools may be an array (from compact or other callers) or a Collection.
            if(is_array($schools)){
                $showRegisterLinkSchool = collect($schools)->firstWhere('id', $prefSchoolId);
            } else {
                $showRegisterLinkSchool = $schools->firstWhere('id', $prefSchoolId);
            }
        }
        if(!$showRegisterLinkSchool && $prefSchoolId) {
            $showRegisterLinkSchool = \App\Models\School::find($prefSchoolId);
        }
        if(!$showRegisterLinkSchool && optional(auth()->user())->school) {
            $showRegisterLinkSchool = auth()->user()->school;
        }
    @endphp

    @if($showRegisterLinkSchool && $showRegisterLinkSchool->code)
    <div class="mb-4 p-4 bg-gray-50 rounded border">
        <label class="block text-sm font-medium mb-1">Pautan Pendaftaran Guru untuk Sekolah: <strong>{{ $showRegisterLinkSchool->name ?? '' }}</strong></label>
        @php $regUrl = url('/daftar-guru/' . $showRegisterLinkSchool->code); @endphp
        <div class="flex gap-2 items-center mt-2">
            <input type="text" id="createRegisterLink" class="w-full rounded border p-2 text-sm" value="{{ $regUrl }}" readonly>
            <button type="button" onclick="copyCreateRegisterLink()" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm">Salin</button>
        </div>
        <div class="text-xs text-gray-500 mt-2">Gunakan pautan ini untuk mengajak guru mendaftar terus ke sekolah ini.</div>
    </div>
    @endif
    {{-- pref values defined earlier above so they are available for the form and register link block --}}
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">No Telefon</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Peranan</label>
            @if($prefRole === 'guru')
                {{-- Lock role to guru: include hidden input for submission and show disabled selector for UI --}}
                <input type="hidden" name="role" value="guru">
                <select class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
                    <option value="guru" selected>Guru/Staff</option>
                </select>
            @else
                <select name="role" class="w-full border rounded px-3 py-2" required>
                    <option value="school_admin" @selected($prefRole === 'school_admin')>Admin Sekolah</option>
                    <option value="guru" @selected($prefRole === 'guru')>Guru/Staff</option>
                    @if(isset($user) && in_array($user->role, ['school_admin','super_admin']))
                        <option value="kontraktor" @selected($prefRole === 'kontraktor')>Kontraktor</option>
                    @endif
                </select>
            @endif
        </div>

        @if(isset($user) && $user->role === 'super_admin')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Sekolah</label>
            <select name="school_id" class="w-full border rounded px-3 py-2" required>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" @selected($prefSchoolId == $school->id)>{{ $school->name }} ({{ $school->code }})</option>
                @endforeach
            </select>
        </div>
        @else
            @if($prefSchoolId)
                {{-- non-super-admin can still set school via query param (school admin creating teacher) --}}
                <input type="hidden" name="school_id" value="{{ $prefSchoolId }}">
            @endif
        @endif
    @php $showPassword = (isset($user) && in_array($user->role, ['super_admin','school_admin'])); @endphp
    @if($showPassword)
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Sahkan Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>
        @else
        <!-- Non-super admin will not set password here; invite/reset flow applies for kontraktor -->
        <input type="hidden" name="password" value="placeholder">
        <input type="hidden" name="password_confirmation" value="placeholder">
        @endif
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('dashboard') }}" class="ml-2 text-gray-600">Kembali</a>
        </form>
    </div>

    @push('scripts')
    <script>
    function copyCreateRegisterLink(){
        const el = document.getElementById('createRegisterLink');
        if(!el) return;
        const val = el.value || el.getAttribute('value');
        if(!val) return;
        if(navigator.clipboard && navigator.clipboard.writeText){
            navigator.clipboard.writeText(val).then(()=>{
                alert('Pautan disalin ke papan klip');
            }).catch(()=>{
                fallbackCopyText(val);
            });
        } else {
            fallbackCopyText(val);
        }
    }

    function fallbackCopyText(text){
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        try{
            document.execCommand('copy');
            alert('Pautan disalin ke papan klip');
        }catch(e){
            alert('Salin gagal — sila salin secara manual');
        }
        document.body.removeChild(textarea);
    }
    </script>
    @endpush

    @endsection
