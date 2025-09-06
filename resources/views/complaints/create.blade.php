<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit New Complaint') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Borang Aduan Kerosakan</h3>
                        <p class="text-sm text-gray-600">Sila isi maklumat aduan dengan lengkap. Nombor aduan akan dijana secara automatik.</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Tajuk Aduan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('title') }}" 
                                   placeholder="Contoh: Kipas siling rosak di Bilik Darjah 3A"
                                   required>
                        </div>

                        <!-- School Selection -->
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sekolah <span class="text-red-500">*</span>
                            </label>
                            <select name="school_id" 
                                    id="school_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Dropdown -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori Kerosakan <span class="text-red-500">*</span>
                            </label>
                            <select name="category" 
                                    id="category"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="elektrik" {{ old('category') == 'elektrik' ? 'selected' : '' }}>
                                    <i class="fas fa-bolt"></i> Elektrik
                                </option>
                                <option value="paip" {{ old('category') == 'paip' ? 'selected' : '' }}>
                                    <i class="fas fa-tint"></i> Paip & Plumbing
                                </option>
                                <option value="bumbung" {{ old('category') == 'bumbung' ? 'selected' : '' }}>
                                    <i class="fas fa-home"></i> Bumbung & Atap
                                </option>
                                <option value="pintu_tingkap" {{ old('category') == 'pintu_tingkap' ? 'selected' : '' }}>
                                    <i class="fas fa-door-open"></i> Pintu & Tingkap
                                </option>
                                <option value="lantai" {{ old('category') == 'lantai' ? 'selected' : '' }}>
                                    <i class="fas fa-th-large"></i> Lantai & Jubin
                                </option>
                                <option value="dinding" {{ old('category') == 'dinding' ? 'selected' : '' }}>
                                    <i class="fas fa-th"></i> Dinding & Cat
                                </option>
                                <option value="peralatan" {{ old('category') == 'peralatan' ? 'selected' : '' }}>
                                    <i class="fas fa-tools"></i> Peralatan & Perabot
                                </option>
                                <option value="tandas" {{ old('category') == 'tandas' ? 'selected' : '' }}>
                                    <i class="fas fa-restroom"></i> Tandas & Bilik Air
                                </option>
                                <option value="lain-lain" {{ old('category') == 'lain-lain' ? 'selected' : '' }}>
                                    <i class="fas fa-ellipsis-h"></i> Lain-lain
                                </option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Tahap Keutamaan <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="relative">
                                    <input type="radio" name="priority" value="urgent" 
                                           class="peer sr-only" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
                                    <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50">
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle text-red-500 text-xl mb-1"></i>
                                            <div class="text-sm font-medium text-red-700">Kecemasan</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="priority" value="tinggi" 
                                           class="peer sr-only" {{ old('priority') == 'tinggi' ? 'checked' : '' }}>
                                    <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50">
                                        <div class="text-center">
                                            <i class="fas fa-arrow-up text-orange-500 text-xl mb-1"></i>
                                            <div class="text-sm font-medium text-orange-700">Tinggi</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="priority" value="sederhana" 
                                           class="peer sr-only" {{ old('priority', 'sederhana') == 'sederhana' ? 'checked' : '' }}>
                                    <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-yellow-500 peer-checked:bg-yellow-50">
                                        <div class="text-center">
                                            <i class="fas fa-minus text-yellow-500 text-xl mb-1"></i>
                                            <div class="text-sm font-medium text-yellow-700">Sederhana</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="priority" value="rendah" 
                                           class="peer sr-only" {{ old('priority') == 'rendah' ? 'checked' : '' }}>
                                    <div class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50">
                                        <div class="text-center">
                                            <i class="fas fa-arrow-down text-green-500 text-xl mb-1"></i>
                                            <div class="text-sm font-medium text-green-700">Rendah</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan Kerosakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      rows="4"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Terangkan kerosakan dengan terperinci. Contoh: Kipas siling di Bilik Darjah 3A tidak berfungsi sejak 2 hari lepas. Bunyi bising dan bilah kipas bergoyang."
                                      required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Media Upload -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Kerosakan
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-camera text-gray-400 text-3xl"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload gambar</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF sehingga 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Video Upload -->
                            <div>
                                <label for="video" class="block text-sm font-medium text-gray-700 mb-2">
                                    Video Kerosakan (Pilihan)
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-video text-gray-400 text-3xl"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="video" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload video</span>
                                                <input id="video" name="video" type="file" class="sr-only" accept="video/*">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500">MP4, AVI sehingga 10MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('complaints.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Hantar Aduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
