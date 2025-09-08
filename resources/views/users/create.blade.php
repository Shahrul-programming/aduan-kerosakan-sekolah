@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Tambah Pengguna Baru</h1>
    @php
        $prefRole = request()->query('role') ?? null;
        $prefSchoolId = request()->query('school_id') ?? null;
    @endphp
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
        @php $showPassword = (isset($user) && $user->role === 'super_admin'); @endphp
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
@endsection
