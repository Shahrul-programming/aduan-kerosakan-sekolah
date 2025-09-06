@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Senarai Sekolah</h1>
        <a href="{{ route('schools.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition duration-150">
            <i class="fas fa-plus mr-2"></i> Tambah Sekolah
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Nama</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Kod</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Alamat</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Pengetua</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">PK HEM</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr class="border-b last:border-none hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2">{{ $school->name }}</td>
                    <td class="px-4 py-2">{{ $school->code }}</td>
                    <td class="px-4 py-2">{{ $school->address }}</td>
                    <td class="px-4 py-2">{{ $school->principal_name }} <span class="text-xs text-gray-500">({{ $school->principal_phone }})</span></td>
                    <td class="px-4 py-2">{{ $school->hem_name }} <span class="text-xs text-gray-500">({{ $school->hem_phone }})</span></td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded shadow mr-2 transition duration-150">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded shadow transition duration-150" onclick="return confirm('Padam sekolah?')">
                                <i class="fas fa-trash mr-1"></i> Padam
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
