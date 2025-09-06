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
                            <a href="{{ route('schools.qr', auth()->user()->school->id ?? 0) }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-qrcode text-green-500 mr-3"></i>
                                <span class="text-green-700 font-medium">Generate QR Code Guru</span>
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
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Complaints</h3>
                        <div class="flex space-x-2">
                            <button onclick="refreshComplaints()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh
                            </button>
                            <a href="{{ route('complaints.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-list mr-1"></i> View All
                            </a>
                        </div>
                    </div>
                    @if($recentComplaints->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentComplaints as $complaint)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $complaint->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($complaint->title, 40) }}</div>
                                            <div class="text-sm text-gray-500">{{ $complaint->user ? $complaint->user->name : 'Unknown' }}</div>
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
                                                @elseif($complaint->status == 'semakan') bg-purple-100 text-purple-800
                                                @elseif($complaint->status == 'assigned') bg-indigo-100 text-indigo-800
                                                @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($complaint->status == 'completed') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $complaint->created_at->format('M d, Y') }}
                                            <div class="text-xs text-gray-400">{{ $complaint->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('complaints.show', $complaint->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($complaint->status == 'pending')
                                                    <button onclick="reviewComplaint({{ $complaint->id }})" 
                                                            class="text-purple-600 hover:text-purple-900" title="Review">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                @endif
                                                @if(in_array($complaint->status, ['pending', 'semakan']))
                                                    <button onclick="setPriority({{ $complaint->id }})" 
                                                            class="text-yellow-600 hover:text-yellow-900" title="Set Priority">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                                @if($complaint->status == 'semakan')
                                                    <button onclick="assignContractor({{ $complaint->id }})" 
                                                            class="text-green-600 hover:text-green-900" title="Assign">
                                                        <i class="fas fa-user-plus"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">Tiada aduan terkini.</p>
                            <a href="{{ route('complaints.create') }}" class="mt-2 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Submit Aduan Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dashboard Actions -->
    <script>
        function refreshComplaints() {
            location.reload();
        }

        function reviewComplaint(id) {
            if (confirm('Adakah anda ingin mula semakan aduan ini?')) {
                // Update status to 'semakan'
                fetch(`/complaints/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: 'semakan' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshComplaints();
                    }
                });
            }
        }

        function setPriority(id) {
            const priority = prompt('Masukkan prioriti (urgent/tinggi/sederhana/rendah):');
            if (priority && ['urgent', 'tinggi', 'sederhana', 'rendah'].includes(priority.toLowerCase())) {
                fetch(`/complaints/${id}/priority`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ priority: priority.toLowerCase() })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshComplaints();
                    }
                });
            }
        }

        function assignContractor(id) {
            // Simple redirect to assign page - you can make this more sophisticated
            window.location.href = `/complaints/${id}/assign`;
        }

        // Auto-refresh every 30 seconds
        setInterval(function() {
            // Only refresh if user is active (to avoid unnecessary requests)
            if (document.visibilityState === 'visible') {
                refreshComplaints();
            }
        }, 30000);
    </script>
</x-app-layout>
