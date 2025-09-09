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

                        <!-- Reporter info (prefill from authenticated user or query) -->
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
                                    <option value="{{ $school->id }}" {{ (old('school_id', request('school_id', auth()->user()->school_id ?? '')) == $school->id) ? 'selected' : '' }}>
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

                                        <form action="{{ route('complaints.store') }}"
                                              method="POST"
                                              enctype="multipart/form-data"
                                              class="space-y-6"
                                              id="complaintForm"
                                              x-data="complaintForm()">

                                            <!-- Progress Indicator -->
                                            <div class="mb-8">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">Progress Penghantaran</h3>
                                                    <span class="text-sm text-gray-500" x-text="currentStep + '/3'"></span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                                         :style="`width: ${(currentStep / 3) * 100}%`"></div>
                                                </div>
                                                <div class="flex justify-between mt-2 text-xs text-gray-600">
                                                    <span :class="currentStep >= 1 ? 'text-indigo-600 font-medium' : ''">Maklumat Asas</span>
                                                    <span :class="currentStep >= 2 ? 'text-indigo-600 font-medium' : ''">Butiran Kerosakan</span>
                                                    <span :class="currentStep >= 3 ? 'text-indigo-600 font-medium' : ''">Media & Hantar</span>
                                                </div>
                                            </div>

                                            @include('complaints._form_fields')

                                            <!-- Submit Buttons -->
                                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                                <a href="{{ route('complaints.index') }}"
                                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                                    <i class="fas fa-arrow-left mr-2"></i>
                                                    Kembali
                                                </a>
                                                <button type="submit"
                                                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                                                        :disabled="isSubmitting"
                                                        :class="isSubmitting ? 'opacity-75 cursor-not-allowed' : ''">
                                                    <i class="fas fa-spinner fa-spin mr-2" x-show="isSubmitting" x-cloak></i>
                                                    <i class="fas fa-paper-plane mr-2" x-show="!isSubmitting"></i>
                                                    <span x-text="isSubmitting ? 'Menghantar...' : 'Hantar Aduan'"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        function complaintForm() {
            return {
                currentStep: 1,
                isSubmitting: false,

                init() {
                    // Auto-update progress based on form completion
                    this.updateProgress();

                    // Watch for form changes
                    document.querySelectorAll('input, select, textarea').forEach(field => {
                        field.addEventListener('input', () => this.updateProgress());
                        field.addEventListener('change', () => this.updateProgress());
                    });
                },

                updateProgress() {
                    const requiredFields = document.querySelectorAll('[required]');
                    const filledFields = Array.from(requiredFields).filter(field => field.value.trim() !== '');

                    if (filledFields.length >= requiredFields.length * 0.3) {
                        this.currentStep = 1;
                    }
                    if (filledFields.length >= requiredFields.length * 0.6) {
                        this.currentStep = 2;
                    }
                    if (filledFields.length >= requiredFields.length) {
                        this.currentStep = 3;
                    }
                },

                async submitForm() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;

                    try {
                        const form = document.getElementById('complaintForm');
                        const formData = new FormData(form);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Success
                            window.showToast('Aduan berjaya dihantar!', 'success');
                            setTimeout(() => {
                                window.location.href = '{{ route("complaints.index") }}';
                            }, 1500);
                        } else {
                            // Handle validation errors
                            if (result.errors) {
                                let errorMessage = 'Sila betulkan ralat berikut:\n';
                                Object.values(result.errors).forEach(errors => {
                                    errorMessage += '• ' + errors.join('\n• ') + '\n';
                                });
                                window.showToast(errorMessage, 'error');
                            } else {
                                window.showToast(result.message || 'Ralat berlaku', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Submit error:', error);
                        window.showToast('Ralat rangkaian. Sila cuba lagi.', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
@endsection
