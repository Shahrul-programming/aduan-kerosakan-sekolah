@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📱 Pengurusan WhatsApp</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNumberModal">
                    ➕ Tambah Nombor
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- WhatsApp Numbers Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📋 Senarai Nombor WhatsApp</h5>
                </div>
                <div class="card-body">
                    @if($whatsappNumbers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>📱 Nombor</th>
                                        <th>📊 Status</th>
                                        <th>🕐 Sambungan Terakhir</th>
                                        <th>🔗 QR Code</th>
                                        <th>⚙️ Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($whatsappNumbers as $number)
                                    <tr>
                                        <td>+{{ $number->number }}</td>
                                        <td>
                                            @if($number->status === 'active')
                                                <span class="badge bg-success">🟢 Aktif</span>
                                            @elseif($number->status === 'scanning')
                                                <span class="badge bg-warning">🔄 Scan QR</span>
                                            @else
                                                <span class="badge bg-danger">🔴 Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $number->last_connected_at ? $number->last_connected_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            @if($number->qr_code && $number->status === 'scanning')
                                                <button class="btn btn-sm btn-info" onclick="showQR('{{ $number->qr_code }}')">
                                                    📱 Lihat QR
                                                </button>
                                            @else
                                                <form action="{{ route('whatsapp.generate-qr', $number) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary">🔄 Jana QR</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($number->status === 'active')
                                                    <form action="{{ route('whatsapp.test', $number) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">🧪 Test</button>
                                                    </form>
                                                @endif
                                                
                                                <form action="{{ route('whatsapp.update-status', $number) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $number->status === 'active' ? 'inactive' : 'active' }}">
                                                    <button type="submit" class="btn btn-sm {{ $number->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                                        {{ $number->status === 'active' ? '⏸️ Nyahaktif' : '▶️ Aktifkan' }}
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('whatsapp.destroy', $number) }}" method="POST" style="display: inline;" onsubmit="return confirm('Padam nombor ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">🗑️ Padam</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <h5>📱 Tiada nombor WhatsApp didaftarkan</h5>
                            <p class="text-muted">Klik butang "Tambah Nombor" untuk mula menggunakan notifikasi WhatsApp.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Auto-Reminder Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">⏰ Auto-Reminder System</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6>📋 Peringatan automatik dihantar untuk:</h6>
                        <ul class="mb-0">
                            <li>🔔 Aduan baru yang belum ditugaskan (selepas 3 hari)</li>
                            <li>⚠️ Tugasan kontraktor yang tiada kemaskini (selepas 3 hari)</li>
                        </ul>
                    </div>
                    <div class="mt-3">
                        <strong>📝 Cara menggunakan:</strong>
                        <ol>
                            <li>Tambah nombor WhatsApp sistem</li>
                            <li>Scan QR code dengan WhatsApp Web</li>
                            <li>Test sambungan untuk pastikan berfungsi</li>
                            <li>Sistem akan auto-hantar notifikasi & reminder</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
