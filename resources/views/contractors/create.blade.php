@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <h1 class="text-xl font-semibold mb-4">Daftar Kontraktor Baru</h1>
        <form action="{{ route('contractors.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm text-gray-600">Nama</label>
                <input type="text" name="name" class="border rounded p-2 w-full" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label class="block text-sm text-gray-600">Syarikat</label>
                <input type="text" name="company_name" class="border rounded p-2 w-full" required value="{{ old('company_name') }}">
            </div>
            <div class="mb-3">
                <label class="block text-sm text-gray-600">Telefon</label>
                <input type="text" name="phone" class="border rounded p-2 w-full" value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label class="block text-sm text-gray-600">Emel</label>
                <input type="email" name="email" class="border rounded p-2 w-full" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="block text-sm text-gray-600">Alamat</label>
                <textarea name="address" class="border rounded p-2 w-full">{{ old('address') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Daftar</button>
                <a href="{{ route('complaints.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
