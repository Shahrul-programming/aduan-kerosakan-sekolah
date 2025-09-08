@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">📱 Pengurusan WhatsApp</h1>
        <div>
            <button id="openAddModalBtn" class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none" type="button">➕ Tambah Nombor</button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded bg-green-50 text-green-800">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded bg-red-50 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">📋 Senarai Nombor WhatsApp</h2>
                        <p class="text-xs text-gray-500">Senarai dan status sambungan</p>
                    </div>
                    <div class="text-sm text-gray-500"></div>
                </div>
                <div class="px-4 py-4 sm:px-6">
                    @if($whatsappNumbers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">📱 Nombor</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">📊 Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">🕐 Sambungan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">🔗 QR</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">⚙️ Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($whatsappNumbers as $number)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">+{{ $number->number }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($number->status === 'active')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">🟢 Aktif</span>
                                            @elseif($number->status === 'scanning')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">🔄 Scan QR</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">🔴 Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $number->last_connected_at ? $number->last_connected_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($number->qr_code && $number->status === 'scanning')
                                                <button class="inline-flex items-center px-2 py-1 text-sm bg-indigo-100 text-indigo-800 rounded" onclick="openQRModal('{{ $number->qr_code }}')">📱 Lihat QR</button>
                                            @else
                                                <form action="{{ route('whatsapp.generate-qr', $number) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-sm bg-gray-100 text-gray-800 rounded">🔄 Jana QR</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex flex-wrap gap-2">
                                                @if($number->status === 'active')
                                                    <form action="{{ route('whatsapp.test', $number) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-sm bg-green-600 text-white rounded">🧪 Test</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('whatsapp.update-status', $number) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $number->status === 'active' ? 'inactive' : 'active' }}">
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-sm {{ $number->status === 'active' ? 'bg-yellow-500 text-white' : 'bg-green-600 text-white' }} rounded">{{ $number->status === 'active' ? '⏸️ Nyahaktif' : '▶️ Aktifkan' }}</button>
                                                </form>
                                                <form action="{{ route('whatsapp.destroy', $number) }}" method="POST" class="inline" onsubmit="return confirm('Padam nombor ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-sm bg-red-600 text-white rounded">🗑️ Padam</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">📱 Tiada nombor WhatsApp didaftarkan</h3>
                            <p class="text-sm text-gray-500 mt-2">Klik butang "Tambah Nombor" atau gunakan borang di sebelah untuk mula menggunakan notifikasi WhatsApp.</p>
                            <div class="mt-4">
                                <button id="emptyAddBtn" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-md" type="button">➕ Tambah Nombor</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">⏰ Auto-Reminder System</h3>
                <p class="text-sm text-gray-500 mt-2">📋 Peringatan automatik dihantar untuk:</p>
                <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                    <li>🔔 Aduan baru yang belum ditugaskan (selepas 3 hari)</li>
                    <li>⚠️ Tugasan kontraktor yang tiada kemaskini (selepas 3 hari)</li>
                </ul>
                <p class="text-sm font-medium text-gray-700 mt-3">📝 Cara menggunakan:</p>
                <ol class="list-decimal list-inside text-sm text-gray-600 mt-2">
                    <li>Tambah nombor WhatsApp sistem</li>
                    <li>Scan QR code dengan WhatsApp Web</li>
                    <li>Test sambungan untuk pastikan berfungsi</li>
                    <li>Sistem akan auto-hantar notifikasi & reminder</li>
                </ol>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">➕ Tambah Nombor Pantas</h3>
                <form action="{{ route('whatsapp.store') }}" method="POST" class="mt-3 space-y-3">
                    @csrf
                    <div>
                        <label for="inline-number" class="block text-sm font-medium text-gray-700">Nombor Telefon</label>
                        <input type="text" id="inline-number" name="number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="60123456789">
                        <p class="text-xs text-gray-500 mt-1">Masukkan nombor tanpa + (contoh: 60123456789)</p>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-md">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Number Modal (Tailwind) -->
<div id="addModal" class="fixed inset-0 hidden z-50 items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">➕ Tambah Nombor WhatsApp</h3>
            <button id="closeAddModal" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>
        <form action="{{ route('whatsapp.store') }}" method="POST">
            @csrf
            <div class="px-4 py-4">
                <label for="modal-number" class="block text-sm font-medium text-gray-700">📱 Nombor Telefon</label>
                <input type="text" name="number" id="modal-number" placeholder="60123456789" required class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <p class="text-xs text-gray-500 mt-2">Masukkan nombor dalam format 60123456789 (tanpa +)</p>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                <button type="button" id="cancelAdd" class="px-3 py-2 rounded-md bg-gray-100 text-gray-700">Batal</button>
                <button type="submit" class="px-3 py-2 rounded-md bg-blue-600 text-white">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="fixed inset-0 hidden z-50 items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-sm mx-4 text-center p-4">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">📱 Scan QR Code</h4>
        <div class="mt-4">
            <img id="qrImage" src="" alt="QR Code" class="mx-auto max-w-xs" />
        </div>
        <div class="mt-4 text-sm text-gray-600">Buka WhatsApp → WhatsApp Web → Scan QR</div>
        <div class="mt-4 flex justify-center">
            <button id="closeQr" class="px-3 py-2 rounded-md bg-gray-100">Tutup</button>
        </div>
    </div>
</div>

<script>
function openQRModal(qr) {
    document.getElementById('qrImage').src = qr;
    document.getElementById('qrModal').classList.remove('hidden');
}

document.getElementById('openAddModalBtn')?.addEventListener('click', function(){
    document.getElementById('addModal').classList.remove('hidden');
});
document.getElementById('emptyAddBtn')?.addEventListener('click', function(){
    document.getElementById('addModal').classList.remove('hidden');
});
document.getElementById('cancelAdd')?.addEventListener('click', function(){
    document.getElementById('addModal').classList.add('hidden');
});
document.getElementById('closeAddModal')?.addEventListener('click', function(){
    document.getElementById('addModal').classList.add('hidden');
});
document.getElementById('closeQr')?.addEventListener('click', function(){
    document.getElementById('qrModal').classList.add('hidden');
});
</script>

<!-- Add Number Modal -->
<div class="modal fade" id="addNumberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Tambah Nombor WhatsApp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('whatsapp.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="number" class="form-label">📱 Nombor Telefon</label>
                        <input type="text" class="form-control" id="number" name="number" 
                               placeholder="60123456789" required>
                        <div class="form-text">Masukkan nombor dalam format 60123456789 (tanpa +)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📱 Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="qrImage" src="" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                <div class="mt-3">
                    <h6>📝 Langkah-langkah:</h6>
                    <ol class="text-start">
                        <li>Buka WhatsApp di telefon anda</li>
                        <li>Pilih "WhatsApp Web" dalam menu</li>
                        <li>Scan QR code di atas</li>
                        <li>Tunggu sambungan berjaya</li>
                    </ol>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showQR(qrCode) {
    document.getElementById('qrImage').src = qrCode;
    new bootstrap.Modal(document.getElementById('qrModal')).show();
}
</script>
@endsection
