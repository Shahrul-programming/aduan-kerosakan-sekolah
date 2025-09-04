@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-3">Dashboard Technician</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Aduan</th>
                            <th>Sekolah</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                        <tr>
                            <td>{{ $complaint->complaint_number }}</td>
                            <td>{{ $complaint->school->name ?? '-' }}</td>
                            <td>{{ $complaint->category }}</td>
                            <td>{{ $complaint->status }}</td>
                            <td>
                                <a href="{{ route('complaints.show', $complaint->id) }}" class="btn btn-primary btn-sm">Lihat</a>
                                <button class="btn btn-success btn-sm" onclick="showStatusForm({{ $complaint->id }}, '{{ $complaint->status }}')">Kemaskini Status</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tiada aduan untuk anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form for Update Status -->
    <div class="modal" id="statusModal" tabindex="-1" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-dialog" style="margin:10vh auto; max-width:400px;">
            <div class="modal-content p-3">
                <form id="statusForm" method="POST">
                    @csrf
                    <input type="hidden" name="complaint_id" id="complaint_id">
                    <div class="mb-2">
                        <label for="status">Status Baru</label>
                        <select name="status" id="status" class="form-control">
                            <option value="proses">Mula Kerja</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="note">Catatan (optional)</label>
                        <textarea name="note" id="note" class="form-control"></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="closeStatusForm()">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function showStatusForm(id, currentStatus) {
    document.getElementById('complaint_id').value = id;
    document.getElementById('status').value = currentStatus;
    document.getElementById('statusModal').style.display = 'block';
    document.getElementById('statusForm').action = '/technician/complaint/' + id + '/update-status';
}
function closeStatusForm() {
    document.getElementById('statusModal').style.display = 'none';
}
</script>
@endsection
