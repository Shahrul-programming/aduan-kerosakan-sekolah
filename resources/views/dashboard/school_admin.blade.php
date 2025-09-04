<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-blue-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $totalComplaints }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-exclamation-circle text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Total Complaints</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-yellow-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $pendingComplaints }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-clock text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Pending Review</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-purple-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $reviewComplaints }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-search text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Dalam Semakan</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-indigo-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $assignedComplaints }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-user-check text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Assigned</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-orange-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $inProgressComplaints }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-tools text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">In Progress</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-green-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $completedComplaints }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-check-circle text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('complaints.review') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                                <i class="fas fa-search text-purple-500 mr-3"></i>
                                <span class="text-purple-700 font-medium">Semak Aduan</span>
                            </a>
                            <a href="{{ route('complaints.prioritize') }}" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                                <i class="fas fa-star text-yellow-500 mr-3"></i>
                                <span class="text-yellow-700 font-medium">Tetapkan Prioriti</span>
                            </a>
                            <a href="{{ route('complaints.assign') }}" class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                <i class="fas fa-user-check text-indigo-500 mr-3"></i>
                                <span class="text-indigo-700 font-medium">Assign Kontraktor</span>
                            </a>
                            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                <i class="fab fa-whatsapp text-green-500 mr-3"></i>
                                <span class="text-green-700 font-medium">WhatsApp Notification: Aktif</span>
                            </div>
                            <a href="#" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-file-download text-gray-500 mr-3"></i>
                                <span class="text-gray-700 font-medium">Muat Turun Laporan</span>
                            </a>
                            <a href="{{ route('complaints.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <i class="fas fa-plus-circle text-blue-500 mr-3"></i>
                                <span class="text-blue-700 font-medium">Submit New Complaint</span>
                            </a>
                            <a href="{{ route('complaints.index') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-list text-green-500 mr-3"></i>
                                <span class="text-green-700 font-medium">View My Complaints</span>
                            </a>
                            <a href="{{ route('schools.show', auth()->user()->school->id ?? 0) }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                                <i class="fas fa-school text-purple-500 mr-3"></i>
                                <span class="text-purple-700 font-medium">School Profile</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">School Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">School:</span>
                                <span class="font-medium">{{ auth()->user()->school->name ?? 'Not Assigned' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">This Month:</span>
                                <span class="font-medium">{{ $monthlyComplaints }} complaints</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Priority Issues:</span>
                                <span class="font-medium text-red-600">{{ $urgentComplaints }} urgent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Complaints -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Complaints</h3>
                    @if($recentComplaints->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentComplaints as $complaint)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $complaint->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($complaint->category) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($complaint->priority == 'urgent') bg-red-100 text-red-800
                                                @elseif($complaint->priority == 'tinggi') bg-orange-100 text-orange-800
                                                @elseif($complaint->priority == 'sederhana') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($complaint->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($complaint->status == 'completed') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $complaint->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Tiada aduan terkini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
