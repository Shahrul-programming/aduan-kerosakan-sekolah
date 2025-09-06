@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Super Admin</h1>
        @if(auth()->user() && auth()->user()->role === 'super_admin')
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow transition duration-150">
                <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna
            </a>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Complaints -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Aduan</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalComplaints }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Complaints -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-yellow-600 uppercase tracking-wider">Menunggu</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingComplaints }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-hourglass-half text-4xl text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-indigo-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Dalam Proses</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $inProgressComplaints }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-cogs text-4xl text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Selesai</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $completedComplaints }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-4xl text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Complaints -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 bg-blue-600 text-white">
                <h3 class="text-lg font-semibold">Tindakan Pantas</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('complaints.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150">
                        <i class="fas fa-list mr-2"></i> Urus Aduan
                    </a>
                    <a href="{{ route('schools.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-150">
                        <i class="fas fa-school mr-2"></i> Urus Sekolah
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-150">
                        <i class="fas fa-chart-bar mr-2"></i> Laporan
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition duration-150">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>        <!-- Recent Complaints -->
        <!-- Recent Complaints -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
            <div class="px-6 py-4 bg-indigo-600 text-white">
                <h3 class="text-lg font-semibold">Aduan Terkini</h3>
            </div>
            <div class="p-6">
                @if($recentComplaints->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentComplaints as $complaint)
                            <div class="flex justify-between items-start p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $complaint->title }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $complaint->school->name ?? 'N/A' }}</p>
                                </div>
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $complaint->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($complaint->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('complaints.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-150">
                            Lihat Semua
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">Tiada aduan terkini.</p>
                @endif
            </div>
        </div>
    </div>    <!-- Schools Summary -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
        <div class="px-6 py-4 bg-green-600 text-white">
            <h3 class="text-lg font-semibold">Ringkasan Sekolah</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $totalSchools }}</div>
                    <p class="text-gray-500 dark:text-gray-400">Jumlah Sekolah</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalComplaints }}</div>
                    <p class="text-gray-500 dark:text-gray-400">Jumlah Aduan</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ number_format($completionRate, 1) }}%</div>
                    <p class="text-gray-500 dark:text-gray-400">Kadar Selesai</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
