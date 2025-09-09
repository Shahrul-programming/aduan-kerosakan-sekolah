@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Sekolah Baru</h1>
            <p class="text-gray-600">Hanya perlukan Nama & Kod. Admin sekolah akan dilantik kemudian.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-school mr-3"></i>
                    Maklumat Sekolah (Minimum)
                </h2>
            </div>

            <form action="{{ route('schools.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            Nama Sekolah
                        </label>
                        <input type="text" name="name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Masukkan nama sekolah" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-barcode mr-2 text-green-500"></i>
                            Kod Sekolah
                        </label>
                        <input type="text" name="code"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Contoh: BEA8664" required>
                    </div>
                </div>
                <div class="text-sm text-gray-500">Maklumat lain (alamat, pengetua, HEM) boleh diisi kemudian di halaman Edit Sekolah. Admin sekolah akan dilantik selepas sekolah ini dicipta.</div>

                <!-- Action Buttons -->
                <div class="border-t pt-6 flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('schools.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
