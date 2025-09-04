<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Technician Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-blue-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $assignedTasks }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-wrench text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Assigned Tasks</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-orange-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $activeTasks }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-cogs text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Active Tasks</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-green-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $completedTasks }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-check-circle text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Completed</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-purple-500 text-white">
                        <div class="flex items-center">
                            <div class="text-2xl font-bold">{{ $todayTasks }}</div>
                            <div class="ml-auto">
                                <i class="fas fa-calendar-day text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-sm">Today's Tasks</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="#" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <i class="fas fa-tasks text-blue-500 mr-3"></i>
                                <span class="text-blue-700 font-medium">View My Tasks</span>
                            </a>
                            <a href="#" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-edit text-green-500 mr-3"></i>
                                <span class="text-green-700 font-medium">Update Task Status</span>
                            </a>
                            <a href="#" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                                <i class="fas fa-camera text-purple-500 mr-3"></i>
                                <span class="text-purple-700 font-medium">Upload Work Photos</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Work Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pending Tasks:</span>
                                <span class="font-medium">{{ $pendingTasks }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Completion Rate:</span>
                                <span class="font-medium text-green-600">{{ $completionRate }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Urgent Tasks:</span>
                                <span class="font-medium text-red-600">{{ $urgentTasks }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks -->
            @if($recentTasks->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Tasks</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentTasks as $task)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->school->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($task->priority == 'urgent') bg-red-100 text-red-800
                                            @elseif($task->priority == 'tinggi') bg-orange-100 text-orange-800
                                            @elseif($task->priority == 'sederhana') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($task->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($task->status == 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Update</a>
                                        <a href="#" class="text-green-600 hover:text-green-900">Complete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
