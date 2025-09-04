<h2>Laporan Aduan Sekolah</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
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
        @foreach($complaints as $c)
        <tr>
            <td>{{ $c->complaint_number }}</td>
            <td>{{ $c->school->name ?? '-' }}</td>
            <td>{{ $c->user->name ?? '-' }}</td>
            <td>{{ $c->category }}</td>
            <td>{{ $c->status }}</td>
            <td>{{ $c->contractor->name ?? '-' }}</td>
            <td>{{ $c->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
