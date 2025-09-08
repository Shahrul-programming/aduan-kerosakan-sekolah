@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Sekolah Baru</h1>
            <p class="text-gray-600">Isikan maklumat sekolah dengan lengkap</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-school mr-3"></i>
                    Maklumat Sekolah
                </h2>
            </div>

            <form action="{{ route('schools.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Sekolah -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            Nama Sekolah
                        </label>
                        <input type="text" name="name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               placeholder="Masukkan nama sekolah" required>
                    </div>

                    <!-- Kod Sekolah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-barcode mr-2 text-green-500"></i>
                            Kod Sekolah
                        </label>
                        <input type="text" name="code" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               placeholder="Contoh: BEA8664" required>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                            Alamat
                        </label>
                        <textarea name="address" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                  placeholder="Alamat penuh sekolah" required></textarea>
                    </div>
                </div>

                <!-- Maklumat Pengetua -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                        Maklumat Pengetua
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengetua</label>
                            <input type="text" name="principal_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   placeholder="Nama penuh pengetua" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telefon Pengetua</label>
                            <input type="tel" name="principal_phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   placeholder="Contoh: 013-1234567" required>
                        </div>
                    </div>
                </div>

                <!-- Maklumat PK HEM -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-cog mr-2 text-orange-500"></i>
                        Maklumat Penolong Kanan Hal Ehwal Murid (PK HEM)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama PK HEM</label>
                            <input type="text" name="hem_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   placeholder="Nama penuh PK HEM" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telefon PK HEM</label>
                            <input type="tel" name="hem_phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   placeholder="Contoh: 013-1234567" required>
                        </div>
                    </div>
                </div>

                <!-- Maklumat Admin Sekolah (Pilihan) -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-shield mr-2 text-teal-500"></i>
                        Maklumat Admin Sekolah (Pilihan — jika kosong, akaun akan dijana automatik)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Admin</label>
                            <input type="text" name="admin_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Nama penuh admin (contoh: Admin SMK)" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Admin</label>
                            <input type="email" name="admin_email" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="contoh: admin@school.edu" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Admin</label>
                            <input type="password" name="admin_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Password (min 6)" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sahkan Password</label>
                            <input type="password" name="admin_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Sahkan password" />
                        </div>
                    </div>
                </div>

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
