@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-3">Laporan Aduan Sekolah</h2>
            <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="baru" @selected(request('status')=='baru')>Baru</option>
                <option value="semakan" @selected(request('status')=='semakan')>Dalam Semakan</option>
                <option value="assigned" @selected(request('status')=='assigned')>Assigned</option>
                <option value="proses" @selected(request('status')=='proses')>Dalam Proses</option>
                <option value="selesai" @selected(request('status')=='selesai')>Selesai</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Sekolah</label>
            <select name="school_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($schools as $s)
                <option value="{{ $s->id }}" @selected(request('school_id')==$s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Kategori</label>
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category')==$cat)>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Prioriti</label>
            <select name="priority" class="form-select" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($priorities as $pri)
                <option value="{{ $pri }}" @selected(request('priority')==$pri)>{{ ucfirst($pri) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Kontraktor</label>
            <select name="contractor_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($contractors as $con)
                <option value="{{ $con->id }}" @selected(request('contractor_id')==$con->id)>{{ $con->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label>Hingga</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" onchange="this.form.submit()">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('reports.export.pdf', request()->all()) }}" class="btn btn-danger btn-sm w-100">Export PDF</a>
        </div>
    </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Aduan</th>
                            <th>Sekolah</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Kontraktor</th>
                            <th>Tarikh Aduan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $c)
                        <tr>
                            <td>{{ $c->complaint_number }}</td>
                            <td>{{ $c->school->name ?? '-' }}</td>
                            <td>{{ $c->user->name ?? '-' }}</td>
                            <td>{{ $c->category }}</td>
                            <td>{{ $c->status }}</td>
                            <td>{{ $c->contractor->name ?? '-' }}</td>
                            <td>{{ $c->created_at }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tiada data aduan.</td>
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
</div>
@endsection
