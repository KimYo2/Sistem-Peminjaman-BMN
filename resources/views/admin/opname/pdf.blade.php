<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Berita Acara Stock Opname</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8.5pt;
            color: #1a1a2e;
            background: #fff;
        }

        /* ── Kop Surat ── */
        .kop-table {
            width: 100%;
            border-bottom: 3px solid #0052A3;
            margin-bottom: 8pt;
            padding-bottom: 6pt;
        }
        .kop-table td { vertical-align: middle; }
        .kop-logo-cell { width: 60pt; text-align: center; }
        .kop-logo-box {
            display: inline-block;
            width: 46pt; height: 46pt;
            border: 2pt solid #0052A3;
            border-radius: 4pt;
            text-align: center;
            padding-top: 7pt;
        }
        .kop-logo-box .logo-bps {
            font-size: 13pt; font-weight: bold;
            color: #0052A3; letter-spacing: 1pt;
        }
        .kop-logo-box .logo-sub {
            font-size: 5.5pt; color: #0052A3;
            display: block; margin-top: 2pt;
        }
        .kop-text-cell { padding-left: 10pt; }
        .kop-instansi {
            font-size: 10pt; font-weight: bold;
            color: #0052A3; text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .kop-kantor { font-size: 7.5pt; color: #444; margin-top: 2pt; }

        /* ── Nomor Surat / Judul ── */
        .judul-wrap {
            text-align: center;
            margin-bottom: 10pt;
        }
        .judul-wrap h1 {
            font-size: 12pt; font-weight: bold;
            text-transform: uppercase; letter-spacing: 1pt;
            color: #0052A3; margin-bottom: 4pt;
        }
        .judul-wrap .nomor {
            font-size: 7.5pt; color: #555;
        }

        /* ── Info Session ── */
        .info-table {
            width: 100%;
            margin-bottom: 10pt;
            border-collapse: collapse;
        }
        .info-table td {
            font-size: 8pt; padding: 2.5pt 4pt;
            vertical-align: top; color: #333;
        }
        .info-table .label { font-weight: bold; width: 100pt; }
        .info-table .sep   { width: 8pt; }

        /* ── Summary Cards (table-based) ── */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
        }
        .summary-table td {
            width: 25%;
            padding: 6pt 8pt;
            text-align: center;
            border: 1pt solid #d1d5db;
        }
        .summary-val {
            font-size: 16pt; font-weight: bold;
        }
        .summary-lbl {
            font-size: 6.5pt; color: #555;
            text-transform: uppercase; letter-spacing: 0.3pt;
            margin-top: 2pt;
        }
        .card-blue   { background: #eff6ff; }
        .card-green  { background: #f0fdf4; }
        .card-red    { background: #fef2f2; }
        .card-yellow { background: #fffbeb; }
        .val-blue    { color: #1d4ed8; }
        .val-green   { color: #15803d; }
        .val-red     { color: #b91c1c; }
        .val-yellow  { color: #b45309; }

        /* ── Section Title ── */
        .section-title {
            font-size: 8.5pt; font-weight: bold;
            color: #0052A3; border-bottom: 1pt solid #0052A3;
            padding-bottom: 2pt; margin-bottom: 6pt;
            text-transform: uppercase; letter-spacing: 0.5pt;
        }

        /* ── Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14pt;
        }
        .data-table thead tr {
            background: #0052A3;
            color: #fff;
        }
        .data-table thead th {
            padding: 5pt 4pt;
            text-align: left; font-size: 7.5pt;
            font-weight: bold; border: 1pt solid #003d82;
            text-transform: uppercase; letter-spacing: 0.3pt;
        }
        .data-table tbody tr:nth-child(even)  { background: #f0f5ff; }
        .data-table tbody tr:nth-child(odd)   { background: #ffffff; }
        .data-table tbody td {
            padding: 4pt 4pt; font-size: 7.5pt;
            border: 1pt solid #d1d5db;
            vertical-align: top; color: #1a1a2e;
        }
        .data-table tbody td.center { text-align: center; }
        .data-table tbody td.mono   { font-family: 'DejaVu Sans Mono', monospace; font-size: 7pt; }

        /* ── Status row highlight for missing ── */
        tr.row-missing td { background: #fff5f5 !important; }

        /* ── Badges ── */
        .badge {
            display: inline-block; padding: 1.5pt 5pt;
            border-radius: 2pt; font-size: 6.5pt;
            font-weight: bold; text-transform: uppercase;
        }
        .badge-found   { background: #d1fae5; color: #065f46; border: 0.5pt solid #a7f3d0; }
        .badge-missing { background: #fee2e2; color: #991b1b; border: 0.5pt solid #fca5a5; }
        .badge-baik         { background: #d1fae5; color: #065f46; }
        .badge-rusak_ringan { background: #fef9c3; color: #78350f; }
        .badge-rusak_berat  { background: #fee2e2; color: #991b1b; }
        .badge-na           { background: #f3f4f6; color: #6b7280; }

        /* ── Signature Block ── */
        .ttd-table {
            width: 100%;
            margin-top: 14pt;
            border-collapse: collapse;
        }
        .ttd-table td {
            width: 50%; padding: 4pt 16pt;
            text-align: center; vertical-align: top;
            font-size: 8pt;
        }
        .ttd-title   { font-weight: bold; margin-bottom: 38pt; }
        .ttd-line    { border-top: 1pt solid #333; padding-top: 4pt; }
        .ttd-name    { font-weight: bold; }
        .ttd-nip     { font-size: 7pt; color: #555; margin-top: 2pt; }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            border-top: 1pt solid #d1d5db;
            padding-top: 4pt;
            font-size: 6.5pt; color: #777;
        }
        .footer-table { width: 100%; }
        .footer-table td { vertical-align: middle; }
        .page-number { text-align: right; }

        .no-col { width: 18pt; text-align: center; }
    </style>
</head>
<body>

    {{-- Fixed Footer --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>Dicetak pada: {{ $generatedAt->format('d M Y, H:i') }} WIB &nbsp;&middot;&nbsp; SIAP &nbsp;&middot;&nbsp; BPS Kabupaten Jepara</td>
                <td class="page-number">Halaman <span class="pagenum"></span></td>
            </tr>
        </table>
    </div>

    {{-- Kop Surat --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                <div class="kop-logo-box">
                    <div class="logo-bps">BPS</div>
                    <span class="logo-sub">Statistik Indonesia</span>
                </div>
            </td>
            <td class="kop-text-cell">
                <div class="kop-instansi">Badan Pusat Statistik</div>
                <div class="kop-kantor">Sistem Pengelolaan Barang Milik Negara (BMN)</div>
                <div class="kop-kantor" style="margin-top:2pt; font-size:6.5pt; color:#777;">
                    Dokumen ini dicetak secara otomatis oleh sistem &mdash; Sah tanpa tanda tangan basah
                </div>
            </td>
        </tr>
    </table>

    {{-- Judul --}}
    <div class="judul-wrap">
        <h1>Berita Acara Stock Opname BMN</h1>
        <div class="nomor">Sesi #{{ $session->id }} &nbsp;&middot;&nbsp; {{ $session->tanggal_mulai ? \Carbon\Carbon::parse($session->tanggal_mulai)->format('d M Y') : '—' }}</div>
    </div>

    {{-- Info Sesi --}}
    <table class="info-table">
        <tr>
            <td class="label">ID Sesi</td>
            <td class="sep">:</td>
            <td>#{{ $session->id }}</td>
            <td style="width:30pt;"></td>
            <td class="label">Petugas</td>
            <td class="sep">:</td>
            <td>{{ $session->starter->nama ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Mulai Opname</td>
            <td class="sep">:</td>
            <td>{{ $session->tanggal_mulai ? \Carbon\Carbon::parse($session->tanggal_mulai)->format('d M Y, H:i') : '—' }}</td>
            <td></td>
            <td class="label">Selesai Opname</td>
            <td class="sep">:</td>
            <td>
                @if($session->tanggal_selesai)
                    {{ \Carbon\Carbon::parse($session->tanggal_selesai)->format('d M Y, H:i') }}
                @else
                    <em style="color:#c0392b;">Belum Selesai</em>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Status Sesi</td>
            <td class="sep">:</td>
            <td>
                @if($session->status === 'selesai')
                    <span style="color:#15803d; font-weight:bold;">Selesai</span>
                @else
                    <span style="color:#b45309; font-weight:bold;">Berjalan</span>
                @endif
            </td>
            <td></td>
            <td class="label">Tanggal Cetak</td>
            <td class="sep">:</td>
            <td>{{ $generatedAt->format('d M Y, H:i') }} WIB</td>
        </tr>
        @if($session->keterangan)
        <tr>
            <td class="label">Keterangan</td>
            <td class="sep">:</td>
            <td colspan="5">{{ $session->keterangan }}</td>
        </tr>
        @endif
    </table>

    {{-- Ringkasan --}}
    <div class="section-title">Ringkasan Hasil Opname</div>
    <table class="summary-table">
        <tr>
            <td class="card-blue">
                <div class="summary-val val-blue">{{ $stats['total'] }}</div>
                <div class="summary-lbl">Total Item</div>
            </td>
            <td class="card-green">
                <div class="summary-val val-green">{{ $stats['found'] }}</div>
                <div class="summary-lbl">Ditemukan</div>
            </td>
            <td class="card-red">
                <div class="summary-val val-red">{{ $stats['missing'] }}</div>
                <div class="summary-lbl">Tidak Ditemukan</div>
            </td>
            <td class="card-yellow">
                <div class="summary-val val-yellow">{{ $kondisiChanges }}</div>
                <div class="summary-lbl">Perubahan Kondisi</div>
            </td>
        </tr>
    </table>

    {{-- Detail Items --}}
    <div class="section-title">Detail Item Opname</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th style="width:60pt;">Kode Barang</th>
                <th style="width:22pt;">NUP</th>
                <th>Nama / Tipe Barang</th>
                <th style="width:48pt;">Status Scan</th>
                <th style="width:56pt;">Kond. Ekspektasi</th>
                <th style="width:56pt;">Kond. Aktual</th>
                <th style="width:60pt;">Discan Oleh</th>
                <th style="width:50pt;">Waktu Scan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $idx => $item)
                @php
                    $isMissing = $item->status !== 'found';
                    $kondisiLabel = fn($k) => match($k) {
                        'baik'        => 'Baik',
                        'rusak_ringan'=> 'Rusak Ringan',
                        'rusak_berat' => 'Rusak Berat',
                        default       => $k ? ucfirst(str_replace('_',' ',$k)) : '—',
                    };
                    $kondisiClass = fn($k) => match($k) {
                        'baik'        => 'badge-baik',
                        'rusak_ringan'=> 'badge-rusak_ringan',
                        'rusak_berat' => 'badge-rusak_berat',
                        default       => 'badge-na',
                    };
                    $kondisiChanged = !$isMissing
                        && $item->expected_kondisi
                        && $item->actual_kondisi
                        && $item->expected_kondisi !== $item->actual_kondisi;
                @endphp
                <tr class="{{ $isMissing ? 'row-missing' : '' }}">
                    <td class="center no-col">{{ $idx + 1 }}</td>
                    <td class="mono">{{ $item->kode_barang }}</td>
                    <td class="center mono">{{ $item->nup }}</td>
                    <td>
                        {{ $item->brand ?? '' }}
                        @if($item->brand && $item->tipe) &mdash; @endif
                        {{ $item->tipe ?? '' }}
                        @if(!$item->brand && !$item->tipe) <em style="color:#aaa;">—</em> @endif
                    </td>
                    <td class="center">
                        @if($isMissing)
                            <span class="badge badge-missing">Tidak Ditemukan</span>
                        @else
                            <span class="badge badge-found">Ditemukan</span>
                        @endif
                    </td>
                    <td class="center">
                        @if($item->expected_kondisi)
                            <span class="badge {{ $kondisiClass($item->expected_kondisi) }}">{{ $kondisiLabel($item->expected_kondisi) }}</span>
                        @else
                            <span style="color:#aaa;">—</span>
                        @endif
                    </td>
                    <td class="center">
                        @if($item->actual_kondisi)
                            <span class="badge {{ $kondisiClass($item->actual_kondisi) }}"
                                  @if($kondisiChanged) style="outline: 1.5pt solid #f59e0b;" @endif>
                                {{ $kondisiLabel($item->actual_kondisi) }}
                            </span>
                            @if($kondisiChanged)
                                <br><span style="font-size:5.5pt; color:#b45309;">&#9650; Berubah</span>
                            @endif
                        @else
                            <span style="color:#aaa;">—</span>
                        @endif
                    </td>
                    <td>{{ $item->scanner_nama ?? '—' }}</td>
                    <td class="center">
                        {{ $item->scanned_at ? \Carbon\Carbon::parse($item->scanned_at)->format('d/m/Y H:i') : '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="center" style="padding:10pt; color:#777;">
                        Belum ada item yang di-scan dalam sesi ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Signature Block --}}
    <table class="ttd-table">
        <tr>
            <td>
                <div class="ttd-title">Petugas Inventaris,</div>
                <div class="ttd-line">
                    <div class="ttd-name">{{ $session->starter->nama ?? '.............................' }}</div>
                    <div class="ttd-nip">NIP: {{ $session->starter->nip ?? '.............................' }}</div>
                </div>
            </td>
            <td>
                <div class="ttd-title">Mengetahui,<br><span style="font-weight:normal; font-size:7.5pt;">Kepala Sub Bagian Tata Usaha</span></div>
                <div class="ttd-line">
                    <div class="ttd-name">( ......................................... )</div>
                    <div class="ttd-nip">NIP: .........................................</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
