<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin Sekolah') }} - {{ $stats['school']->name ?? 'N/A' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Complaints -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Aduan</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_complaints'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Complaints -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu</dt>
                                <dd class="text-2xl font-bold text-yellow-600">{{ $stats['pending_complaints'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dalam Proses</dt>
                                <dd class="text-2xl font-bold text-blue-600">{{ $stats['in_progress_complaints'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</dt>
                                <dd class="text-2xl font-bold text-green-600">{{ $stats['completed_complaints'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tindakan Pantas</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @php $me = auth()->user(); @endphp
                            <!-- Quick action 'Aduan Baru' removed for school admin; teachers should use their dashboard to submit complaints -->
                            <a href="{{ route('complaints.index') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Lihat Aduan</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition">
                                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Laporan</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Profil</span>
                            </a>
                            {{-- Daftar Kontraktor (school admin only) --}}
                            @if(auth()->check() && auth()->user()->role === 'school_admin')
                            <a href="{{ route('contractors.create') }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900/10 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/20 transition">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Daftar Kontraktor</span>
                            </a>
                            @endif
                            {{-- Daftar Guru (school admin) --}}
                            @if(auth()->check() && auth()->user()->role === 'school_admin' && isset($stats['school']))
                            <a href="{{ route('users.create') }}?role=guru&school_id={{ $stats['school']->id }}" class="flex items-center p-3 bg-indigo-50 dark:bg-indigo-900/10 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/20 transition">
                                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Daftar Guru</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Maklumat Sekolah</h3>
                        @if($stats['school'])
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Nama Sekolah:</span>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $stats['school']->name }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Kod Sekolah:</span>
                                <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->code ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Alamat:</span>
                                <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->address }}</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Pengetua / PPD:</span>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->principal_name ?? '—' }} @if($stats['school']->ppd) ({{ $stats['school']->ppd }}) @endif</div>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Telefon Pengetua:</span>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->principal_phone ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">HEM:</span>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->hem_name ?? '—' }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Telefon HEM:</span>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->hem_phone ?? '—' }}</div>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">E-mel Sekolah:</span>
                                <div class="text-gray-900 dark:text-gray-100">{{ $stats['school']->email ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Kadar Selesai:</span>
                                <div class="font-semibold text-green-600">
                                    {{ $stats['total_complaints'] > 0 ? round(($stats['completed_complaints'] / $stats['total_complaints']) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.516 9.8A1.75 1.75 0 0116.516 16H3.484a1.75 1.75 0 01-1.743-2.101l5.516-9.8zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L8.01 7h3.98l-.0.883A1 1 0 0010 5z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">Akaun anda belum dikaitkan dengan mana-mana sekolah</h4>
                                    <div class="mt-1 text-sm text-red-700 dark:text-red-200">Sila kemaskini profil anda atau hubungi pentadbir untuk pautan ke sekolah. Tanpa pautan sekolah, anda tidak akan menerima maklumat aduan untuk sekolah.</div>
                                    <div class="mt-3">
                                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md text-sm">Kemas kini Profil</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Complaints -->
            @if($stats['recent_complaints']->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aduan Terkini</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Masalah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tarikh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($stats['recent_complaints'] as $complaint)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $complaint->id }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ Str::limit($complaint->description ?? $complaint->issue_description ?? '—', 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($complaint->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($complaint->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($complaint->status === 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $complaint->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('complaints.show', $complaint) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('complaints.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat semua aduan →
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tiada aduan lagi</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Belum ada aduan yang dibuat untuk sekolah ini.</p>
                    <p class="text-sm text-gray-600 mb-3">Guru perlu menggunakan dashboard masing-masing untuk membuat aduan. Anda boleh melihat semua aduan untuk sekolah ini di halaman aduan.</p>
                    <a href="{{ route('complaints.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Lihat Aduan Sekolah
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
