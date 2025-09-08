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
                        <div class="flex flex-col sm:flex-row gap-1 justify-center items-center">
                            <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded shadow transition duration-150 w-16">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button onclick="showLoginInfo(this)" data-school-id="{{ $school->id }}" class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded shadow transition duration-150 w-16">
                                <i class="fas fa-key mr-1"></i> Login
                            </button>
                            <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded shadow transition duration-150 w-16" onclick="return confirm('Padam sekolah?')">
                                    <i class="fas fa-trash mr-1"></i> Del
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal untuk Login Info -->
<div id="loginModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-green-600 px-6 py-4 rounded-t-lg">
            <h3 class="text-lg font-medium text-white">Maklumat Login Sekolah</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email/Username:</label>
                    <div class="flex items-center">
                        <input type="text" id="loginEmail" readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                        <button id="copyEmailBtn" onclick="copyToClipboard('loginEmail')" 
                                class="ml-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
                    <div class="flex items-center">
                        <input type="text" id="loginPassword" readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                        <button id="copyPasswordBtn" onclick="copyToClipboard('loginPassword')" 
                                class="ml-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span id="passwordHintText">Password default adalah "password". Admin sekolah boleh tukar password selepas login.</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end">
            <button onclick="closeLoginModal()" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function showLoginInfo(button) {
    const schoolId = button.getAttribute('data-school-id');
    if (!schoolId) return;

    // build endpoint and include CSRF token header for Laravel
    const url = '/schools/' + schoolId + '/login-info';
    const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        }
    }).then(res => res.json()).then(data => {
        document.getElementById('loginEmail').value = data.email || '';
        document.getElementById('loginPassword').value = data.password_hint || '';
        document.getElementById('passwordHintText').textContent = (data.password_hint === 'password') ? 'Password default adalah "password". Admin sekolah boleh tukar password selepas login.' : 'Password: ' + (data.password_hint || '') + '.';
        document.getElementById('loginModal').classList.remove('hidden');
        document.getElementById('loginModal').classList.add('flex');
    }).catch(err => {
        console.error('Gagal ambil login info', err);
        alert('Gagal ambil maklumat login. Sila cuba lagi.');
    });
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.getElementById('loginModal').classList.remove('flex');
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');

    // Show feedback on the button that was clicked
    // event may not be available depending on how called, so try to get active element
    const btn = document.activeElement && document.activeElement.tagName === 'BUTTON' ? document.activeElement : null;
    if (btn) {
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        btn.classList.add('bg-green-600');

        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.classList.remove('bg-green-600');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 1000);
    }
}

// Close modal when clicking outside
document.getElementById('loginModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLoginModal();
    }
});
</script>
@endsection
