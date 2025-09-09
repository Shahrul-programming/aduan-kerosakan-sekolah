<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $totalComplaints }}</div>
                                <div class="text-sm opacity-90">Total Complaints</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                <i class="fas fa-exclamation-circle text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs">
                            <i class="fas fa-arrow-up text-green-200 mr-1"></i>
                            <span class="opacity-90">+12% from last month</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $pendingComplaints }}</div>
                                <div class="text-sm opacity-90">Pending Review</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs">
                            <i class="fas fa-exclamation-triangle text-red-200 mr-1"></i>
                            <span class="opacity-90">Requires attention</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6 bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $reviewComplaints }}</div>
                                <div class="text-sm opacity-90">Dalam Semakan</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                <i class="fas fa-search text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs">
                            <i class="fas fa-eye text-blue-200 mr-1"></i>
                            <span class="opacity-90">Under review</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $assignedComplaints }}</div>
                                <div class="text-sm opacity-90">Assigned</div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                <i class="fas fa-user-check text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs">
                            <i class="fas fa-check text-green-200 mr-1"></i>
                            <span class="opacity-90">Active assignments</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Complaints Trend Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Complaints Trend</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Last 30 days</span>
                                <i class="fas fa-chart-line text-blue-500"></i>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="complaintsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Status Distribution</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Current</span>
                                <i class="fas fa-chart-pie text-green-500"></i>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('complaints.review') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all duration-200 group">
                                <i class="fas fa-search text-purple-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                <div>
                                    <span class="text-purple-700 font-medium">Semak Aduan</span>
                                    <p class="text-xs text-purple-600">Review pending complaints</p>
                                </div>
                            </a>
                            <a href="{{ route('complaints.prioritize') }}" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-all duration-200 group">
                                <i class="fas fa-star text-yellow-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                <div>
                                    <span class="text-yellow-700 font-medium">Tetapkan Prioriti</span>
                                    <p class="text-xs text-yellow-600">Set complaint priorities</p>
                                </div>
                            </a>
                            <a href="{{ route('complaints.assign.form') }}" class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all duration-200 group">
                                <i class="fas fa-user-check text-indigo-500 mr-3 group-hover:scale-110 transition-transform"></i>
                                <div>
                                    <span class="text-indigo-700 font-medium">Assign Kontraktor</span>
                                    <p class="text-xs text-indigo-600">Assign contractors to complaints</p>
                                </div>
                            </a>
                            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                <i class="fab fa-whatsapp text-green-500 mr-3"></i>
                                <div>
                                    <span class="text-green-700 font-medium">WhatsApp Notification</span>
                                    <p class="text-xs text-green-600">Automated notifications active</p>
                                </div>
                                <div class="ml-auto">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-circle text-green-500 mr-1"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-history text-blue-500 mr-2"></i>
                            Recent Activity
                        </h3>
                        <div class="space-y-4">
                            @forelse($recentComplaints ?? [] as $complaint)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-file-alt text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $complaint->complaint_number }}</p>
                                    <p class="text-xs text-gray-600">{{ Str::limit($complaint->description, 60) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $complaint->created_at->diffForHumans() }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ match($complaint->status) {
                                            'baru' => 'bg-blue-100 text-blue-800',
                                            'semakan' => 'bg-indigo-100 text-indigo-800',
                                            'assigned' => 'bg-purple-100 text-purple-800',
                                            'proses','in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'selesai','completed' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        } }}">
                                        {{ $statusLabels[$complaint->status] ?? ucfirst($complaint->status) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 text-sm">No recent activity</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
                            </div>
                            <a href="#" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-file-download text-gray-500 mr-3"></i>
                                <span class="text-gray-700 font-medium">Muat Turun Laporan</span>
                            </a>
                            <a href="{{ route('schools.qr') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-qrcode text-green-500 mr-3"></i>
                                <span class="text-green-700 font-medium">Generate QR Code Guru</span>
                            </a>
                            {{-- Only teachers should be able to submit complaints; hide this action for school admin --}}
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
                            @if(optional(auth()->user()->school)->code)
                            <div class="pt-3 border-t mt-3">
                                <label class="block text-sm text-gray-600">Pautan Pendaftaran Guru (Self-register)</label>
                                @php $dashRegisterUrl = url('/daftar-guru/' . auth()->user()->school->code); @endphp
                                <div class="flex gap-2 items-center mt-2">
                                    <input type="text" id="dashRegisterLink" class="w-full rounded border p-2 text-sm" value="{{ $dashRegisterUrl }}" readonly>
                                    <button type="button" onclick="copyDashRegisterLink()" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm">Salin</button>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">Semua pengguna yang mendaftar melalui pautan ini akan menjadi guru untuk sekolah ini.</div>
                            </div>
                            @endif
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

        // Copy register link from dashboard
        function copyDashRegisterLink(){
            const el = document.getElementById('dashRegisterLink');
            if(!el) return;
            const val = el.value || el.getAttribute('value');
            if(!val) return;
            if(navigator.clipboard && navigator.clipboard.writeText){
                navigator.clipboard.writeText(val).then(()=>{
                    alert('Pautan disalin ke papan klip');
                }).catch(()=>{
                    fallbackCopyText(val);
                });
            } else {
                fallbackCopyText(val);
            }
        }

        function fallbackCopyText(text){
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            try{
                document.execCommand('copy');
                alert('Pautan disalin ke papan klip');
            }catch(e){
                alert('Salin gagal — sila salin secara manual');
            }
            document.body.removeChild(textarea);
        }

        // Initialize Charts
        const initCharts = () => {
            // Complaints Trend Chart
            const complaintsCtx = document.getElementById('complaintsChart');
            if (complaintsCtx) {
                new Chart(complaintsCtx, {
                    type: 'line',
                    data: {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                        datasets: [{
                            label: 'New Complaints',
                            data: [12, 19, 15, 25],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Resolved',
                            data: [8, 15, 12, 20],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Status Distribution Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Baru', 'Semakan', 'Assigned', 'In Progress', 'Completed'],
                        datasets: [{
                            data: [{{ $pendingComplaints }}, {{ $reviewComplaints }}, {{ $assignedComplaints }}, {{ $inProgressComplaints ?? 0 }}, {{ $completedComplaints }}],
                            backgroundColor: [
                                'rgb(59, 130, 246)',   // blue
                                'rgb(147, 51, 234)',   // purple
                                'rgb(99, 102, 241)',   // indigo
                                'rgb(245, 158, 11)',   // yellow
                                'rgb(34, 197, 94)'     // green
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }
        };

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', initCharts);

        // Auto-refresh every 30 seconds
        setInterval(function() {
            // Only refresh if user is active (to avoid unnecessary requests)
            if (document.visibilityState === 'visible') {
                refreshComplaints();
            }
        }, 30000);
    </script>
</x-app-layout>
