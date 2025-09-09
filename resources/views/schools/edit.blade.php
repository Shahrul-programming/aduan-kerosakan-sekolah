@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Sekolah</h1>
            <p class="text-sm text-gray-500">Kemaskini maklumat sekolah di sini.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('schools.update', $school) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Sekolah</label>
                    <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('name', $school->name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kod Sekolah</label>
                    <input type="text" name="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('code', $school->code) }}" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Alamat</label>
                    <input type="text" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('address', $school->address) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Pengetua</label>
                    <input type="text" name="principal_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('principal_name', $school->principal_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">No. Telefon Pengetua</label>
                    <input type="text" name="principal_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('principal_phone', $school->principal_phone) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama PK HEM</label>
                    <input type="text" name="hem_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('hem_name', $school->hem_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">No. Telefon PK HEM</label>
                    <input type="text" name="hem_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('hem_phone', $school->hem_phone) }}" required>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('schools.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Kembali</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Kemaskini</button>
            </div>
        </form>

            <div class="mt-6 p-4 bg-gray-50 rounded">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Pautan Pendaftaran Guru (Self-register)</h3>
                @php
                    $registerUrl = url('/daftar-guru/' . $school->code);
                @endphp
                <div class="flex gap-2 items-center">
                    <input type="text" id="registerLink" class="w-full rounded border p-2 text-sm" value="{{ $registerUrl }}" readonly>
                    <button type="button" onclick="copyRegisterLink()" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm">Salin</button>
                </div>
                <p class="text-xs text-gray-500 mt-2">Sesiapa yang mendaftar melalui pautan ini akan didaftarkan sebagai guru untuk sekolah ini sahaja.</p>
            </div>

            @if(auth()->check() && auth()->user()->role === 'super_admin')
            <div class="mt-6 p-4 bg-gray-50 rounded">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Lantik Admin Sekolah</h3>
                <form action="{{ route('schools.assign-admin', $school) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-600">Nama Penuh</label>
                        <input type="text" name="name" class="mt-1 w-full rounded border p-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Jawatan</label>
                        <input type="text" name="position" class="mt-1 w-full rounded border p-2 text-sm" placeholder="Contoh: Penolong Kanan HEM">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Email</label>
                        <input type="email" name="email" class="mt-1 w-full rounded border p-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">No. WhatsApp</label>
                        <input type="text" name="phone" class="mt-1 w-full rounded border p-2 text-sm" placeholder="6012XXXXXXXX">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Password</label>
                        <input type="password" name="password" class="mt-1 w-full rounded border p-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Sahkan Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full rounded border p-2 text-sm" required>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button class="px-4 py-2 bg-green-600 text-white rounded text-sm">Lantik Admin</button>
                    </div>
                </form>
                <p class="text-xs text-gray-500 mt-2">Melantik admin baharu akan menukar (nyah-lantik) admin sedia ada untuk sekolah ini.</p>
            </div>
            @endif
    </div>
</div>
@endsection
