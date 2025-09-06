@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-8 text-center">
    <h1 class="text-2xl font-bold mb-4">QR Code Pendaftaran Guru</h1>
    <div class="mb-4">
        <span class="block text-lg font-semibold">{{ $schoolObj->name }} ({{ $schoolObj->code }})</span>
    </div>
    <div class="mb-6">
        <!-- QR code placeholder -->
        <div class="inline-block p-6 bg-gray-100 rounded-lg border">
            <i class="fas fa-qrcode text-6xl text-gray-400"></i>
            <div class="mt-2 text-gray-500">QR code akan dipaparkan di sini</div>
        </div>
    </div>
    <div class="flex justify-center gap-4">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Download PNG</button>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Download PDF</button>
    </div>
    <div class="mt-6 text-gray-500 text-sm">Guru boleh scan QR ini untuk daftar akaun sendiri.</div>
</div>
@endsection
