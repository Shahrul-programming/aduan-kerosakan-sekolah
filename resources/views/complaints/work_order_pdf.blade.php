<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Work Order - {{ $complaint->complaint_number }}</title>
    <style>
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #222; }
        .container { width: 100%; padding: 24px; }
        .header { display:flex; justify-content:space-between; align-items:center; }
        .brand { font-size:18px; font-weight:700; }
        .meta { text-align:right; font-size:12px; }
        .section { margin-top:18px; }
        .section h2 { font-size:14px; margin-bottom:8px; border-bottom:1px solid #e5e7eb; padding-bottom:6px; }
        .table { width:100%; border-collapse:collapse; margin-top:8px; }
        .table td, .table th { padding:8px 6px; vertical-align:top; }
        .small { font-size:12px; color:#555; }
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Work Order - {{ $complaint->complaint_number }}</title>
            <style>
                /* Landscape ultra-compact A4 */
                @page { size: A4 landscape; margin: 6mm 6mm; }
                body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #222; margin:0; padding:0; font-size:10px; line-height:1.05; }
                .page { padding: 6px 6px; }

                /* Header */
                .header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:1px solid #e6f5ea; padding-bottom:6px; }
                .brand { display:block; }
                .brand .title { font-size:13px; font-weight:700; color:#0b5; margin-bottom:1px; }
                .brand .subtitle { font-size:10px; color:#555; margin-bottom:1px; }
                .meta { text-align:right; font-size:10px; color:#333 }
                .meta .muted { color:#666; font-size:9px; }

                /* Sections */
                .section { margin-top:8px; }
                .section h2 { font-size:11px; margin:0 0 4px 0; color:#333; background:#fbfffb; padding:4px 6px; border:1px solid #f0faf0; }

                /* Info table ultra-compact */
                .info { width:100%; border-collapse:collapse; margin-top:4px; table-layout:fixed; }
                .info td { padding:4px 6px; vertical-align:top; border:1px solid #f0faf0; word-wrap:break-word; }
                .info .label { width:150px; background:#fbfffb; font-weight:700; color:#222; font-size:10px; }
                .info .value { color:#111; font-size:10px; }

                /* Notes & signatures more compact */
                .notes { margin-top:6px; font-size:10px; color:#222; }
                .notes ol { margin:4px 0 0 18px; padding:0; }
                .signature { margin-top:8px; display:flex; justify-content:space-between; gap:6px; }
                .sig-box { width:49%; border:1px dashed #ccc; padding:6px; min-height:44px; font-size:10px; }

                /* Footer */
                .footer { margin-top:6px; font-size:9px; color:#666; text-align:center; border-top:1px solid #f6f6f6; padding-top:4px; }

                .muted { color:#666; font-size:10px; }
            </style>
        </head>
        <body>
        <div class="page">
            <div class="header">
                <div class="brand">
                    <div>
                        <div class="title">Sistem Penyelenggaraan Sekolah (SiPeS)</div>
                        <div class="subtitle">Work Order / Arahan Kerja</div>
                        <div class="muted">No. Aduan: <strong>{{ $complaint->complaint_number }}</strong></div>
                    </div>
                </div>

                <div class="meta">
                    <div class="wo">Tarikh Work Order: {{ $work_order_date }}</div>
                    <div class="muted">Diproses oleh: {{ $generated_by }}</div>
                    <div class="muted">Cetak: {{ now()->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="section">
                <h2>Maklumat Aduan</h2>
                <table class="info">
                    <tr>
                        <td class="label">Sekolah</td>
                        <td class="value">{{ $complaint->school->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tarikh & Masa Lapor</td>
                        <td class="value">{{ optional($complaint->reported_at)->format('d/m/Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Pengadu (Guru)</td>
                        <td class="value">{{ $complaint->user->name ?? ($complaint->reported_by_name ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label">No Telefon Pengadu</td>
                        <td class="value">{{ $complaint->reporter_phone ?? $complaint->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kategori</td>
                        <td class="value">{{ $complaint->category }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lokasi / Deskripsi</td>
                        <td class="value">{{ $complaint->description }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2>Maklumat Tugasan</h2>
                <table class="info">
                    <tr>
                        <td class="label">Ditugaskan Kepada</td>
                        <td class="value">{{ $contractor->name }} @if($contractor->company_name) ({{ $contractor->company_name }}) @endif</td>
                    </tr>
                    <tr>
                        <td class="label">No. Telefon / Email</td>
                        <td class="value">{{ $contractor->phone ?? '-' }} @if($contractor->email) &middot; {{ $contractor->email }} @endif</td>
                    </tr>
                    <tr>
                        <td class="label">Tarikh / Masa Ditugaskan</td>
                        <td class="value">{{ $assigned_at }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ditugaskan Oleh</td>
                        <td class="value">{{ optional($complaint->assigner)->name ?? ($complaint->school->admin_name ?? ($complaint->assigned_by_name ?? '-')) }}</td>
                    </tr>
                </table>
            </div>

            <div class="section notes">
                <h2>Arahan Kerja</h2>
                <div class="muted">Sila patuhi arahan berikut semasa menjalankan kerja:</div>
                <ol style="margin-top:8px;">
                    <li>Hadir ke lokasi dan dokumentasikan keadaan sebelum dan selepas kerja (gambar).</li>
                    <li>Jika perlu bahan tambahan, dapatkan kelulusan pihak sekolah sebelum membelanjakan kos tambahan.</li>
                    <li>Kemaskini progres di sistem selepas setiap lawatan.</li>
                    <li>Pastikan kerja selamat dan mematuhi pekeliling sekolah.</li>
                </ol>

                <div class="signature">
                    <div class="sig-box">
                        <div class="muted">Diterima oleh Kontraktor</div>
                        <div style="height:36px"></div>
                        <div>Nama: ________________________</div>
                        <div>Tarikh: _______________________</div>
                    </div>
                    <div class="sig-box">
                        <div class="muted">Disediakan oleh / Pejabat Sekolah</div>
                        <div style="height:36px"></div>
                        <div>Nama: ________________________</div>
                        <div>Tarikh: _______________________</div>
                    </div>
                </div>
            </div>

            <div class="footer">Sistem Penyelenggaraan Sekolah (SiPeS) — Untuk maklumat lanjut hubungi pejabat sekolah / pentadbir sistem.</div>
        </div>
        </body>
        </html>
