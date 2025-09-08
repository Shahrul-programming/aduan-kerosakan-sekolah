@extends('layouts.app')
@section('content')
@unless(auth()->user() && auth()->user()->role === 'super_admin')
    <?php abort(403); ?>
@endunless
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Pengurusan Kontraktor</h1>
        <a href="{{ route('contractors.manage.create') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded">➕ Tambah Kontraktor</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Syarikat</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sekolah Ditugaskan</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($contractors as $c)
                    <tr>
                        <td class="px-4 py-3">{{ $c->name }}</td>
                        <td class="px-4 py-3">{{ $c->company_name }}</td>
                        <td class="px-4 py-3">{{ $c->email ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @foreach($c->schools as $s)
                                <span class="inline-block px-2 py-1 text-xs bg-gray-100 rounded mr-1">{{ $s->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('contractors.manage.edit', $c) }}" class="inline-flex items-center px-2 py-1 bg-yellow-500 text-white rounded">Edit</a>
                            <form action="{{ route('contractors.manage.destroy', $c) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Padam kontraktor?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 bg-red-600 text-white rounded">Padam</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $contractors->links() }}
        </div>
    </div>
</div>
@endsection
