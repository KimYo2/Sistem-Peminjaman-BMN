<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Peminjaman BMN</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            color: #1a1a2e;
            background: #fff;
        }

        /* ── Header / Kop Surat ── */
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
            vertical-align: middle;
            padding-top: 6pt;
        }
        .kop-logo-box .logo-bps {
            font-size: 13pt;
            font-weight: bold;
            color: #0052A3;
            letter-spacing: 1pt;
        }
        .kop-logo-box .logo-sub {
            font-size: 5.5pt;
            color: #0052A3;
            display: block;
            margin-top: 2pt;
        }
        .kop-text-cell { padding-left: 10pt; }
        .kop-instansi {
            font-size: 9pt;
            font-weight: bold;
            color: #0052A3;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .kop-kantor {
            font-size: 7.5pt;
            color: #444;
            margin-top: 2pt;
        }

        /* ── Judul Laporan ── */
        .judul-laporan {
            text-align: center;
            margin: 4pt 0 10pt;
        }
        .judul-laporan h1 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
            color: #0052A3;
            margin-bottom: 3pt;
        }
        .judul-laporan p {
            font-size: 7.5pt;
            color: #555;
        }

        /* ── Info Meta Table ── */
        .meta-table {
            width: 100%;
            margin-bottom: 8pt;
            border-collapse: collapse;
        }
        .meta-table td {
            font-size: 7.5pt;
            padding: 2pt 4pt;
            vertical-align: top;
            color: #333;
        }
        .meta-table .label { font-weight: bold; width: 100pt; }
        .meta-table .sep   { width: 8pt; }

        /* ── Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
        }
        .data-table thead tr {
            background-color: #0052A3;
            color: #fff;
        }
        .data-table thead th {
            padding: 5pt 4pt;
            text-align: left;
            font-size: 7pt;
            font-weight: bold;
            border: 1pt solid #003d82;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f0f5ff;
        }
        .data-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .data-table tbody td {
            padding: 4pt 4pt;
            font-size: 7pt;
            border: 1pt solid #d1d5db;
            vertical-align: top;
            color: #1a1a2e;
        }
        .data-table tbody td.mono {
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 6.5pt;
        }
        .data-table tbody td.center { text-align: center; }
        .data-table tbody td.nowrap { white-space: nowrap; }

        /* ── Status Badges ── */
        .badge {
            display: inline-block;
            padding: 1pt 4pt;
            border-radius: 2pt;
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-dipinjam    { background: #dbeafe; color: #1e40af; border: 0.5pt solid #93c5fd; }
        .badge-dikembalikan{ background: #d1fae5; color: #065f46; border: 0.5pt solid #6ee7b7; }
        .badge-menunggu    { background: #fef9c3; color: #854d0e; border: 0.5pt solid #fde047; }
        .badge-ditolak     { background: #fee2e2; color: #991b1b; border: 0.5pt solid #fca5a5; }
        .badge-terlambat   { background: #ffedd5; color: #9a3412; border: 0.5pt solid #fdba74; }

        /* ── Kondisi Badges ── */
        .cond-baik         { background: #d1fae5; color: #065f46; }
        .cond-rusak_ringan { background: #fef9c3; color: #78350f; }
        .cond-rusak_berat  { background: #fee2e2; color: #991b1b; }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1pt solid #d1d5db;
            padding-top: 4pt;
            font-size: 6.5pt;
            color: #777;
        }
        .footer-table { width: 100%; }
        .footer-table td { vertical-align: middle; }
        .page-number { text-align: right; }

        /* ── Row counter ── */
        .no-col { width: 16pt; text-align: center; }
    </style>
</head>
<body>

    {{-- Footer (fixed, appears on every page) --}}
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
                    Dicetak oleh sistem otomatis &mdash; Tidak memerlukan tanda tangan fisik sebagai bukti cetak
                </div>
            </td>
        </tr>
    </table>

    {{-- Judul --}}
    <div class="judul-laporan">
        <h1>Laporan Rekap Peminjaman BMN</h1>
        <p>
            @if($fromPart || $toPart)
                Periode: {{ $fromPart ?? '—' }} s.d. {{ $toPart ?? 'Sekarang' }}
            @else
                Periode: Semua Waktu
            @endif
            &nbsp;&middot;&nbsp;
            @if(!empty($filters['status']))
                Status: {{ ucfirst($filters['status']) }}
            @else
                Status: Semua
            @endif
        </p>
    </div>

    {{-- Info ringkas --}}
    <table class="meta-table">
        <tr>
            <td class="label">Total Record</td>
            <td class="sep">:</td>
            <td>{{ $histori->count() }} data</td>
            <td style="width:40pt;"></td>
            <td class="label">Tanggal Cetak</td>
            <td class="sep">:</td>
            <td>{{ $generatedAt->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Filter Pencarian</td>
            <td class="sep">:</td>
            <td>{{ !empty($filters['search']) ? $filters['search'] : '—' }}</td>
            <td></td>
            <td class="label">Jam Cetak</td>
            <td class="sep">:</td>
            <td>{{ $generatedAt->format('H:i') }} WIB</td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th style="width:52pt;">Kode Barang</th>
                <th style="width:22pt;">NUP</th>
                <th style="width:70pt;">Nama Peminjam</th>
                <th style="width:60pt;">NIP</th>
                <th style="width:52pt;">Waktu Pinjam</th>
                <th style="width:42pt;">Jatuh Tempo</th>
                <th style="width:52pt;">Waktu Kembali</th>
                <th style="width:36pt;">Status</th>
                <th style="width:36pt;">Kond. Awal</th>
                <th>Kond. Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histori as $idx => $item)
                @php
                    $statusClass = match($item->status) {
                        'dipinjam'    => 'badge-dipinjam',
                        'dikembalikan'=> 'badge-dikembalikan',
                        'menunggu'    => 'badge-menunggu',
                        'ditolak'     => 'badge-ditolak',
                        'terlambat'   => 'badge-terlambat',
                        default       => '',
                    };
                    $kondisiClass = fn($k) => match($k) {
                        'baik'        => 'cond-baik',
                        'rusak_ringan'=> 'cond-rusak_ringan',
                        'rusak_berat' => 'cond-rusak_berat',
                        default       => '',
                    };
                    $kondisiLabel = fn($k) => match($k) {
                        'baik'        => 'Baik',
                        'rusak_ringan'=> 'Rusak Ringan',
                        'rusak_berat' => 'Rusak Berat',
                        default       => ucfirst(str_replace('_', ' ', $k ?? '-')),
                    };
                @endphp
                <tr>
                    <td class="center no-col">{{ $idx + 1 }}</td>
                    <td class="mono">{{ $item->kode_barang }}</td>
                    <td class="center mono">{{ $item->nup }}</td>
                    <td>{{ $item->nama_peminjam ?? '—' }}</td>
                    <td class="mono">{{ $item->nip_peminjam }}</td>
                    <td class="nowrap">
                        {{ $item->waktu_pinjam ? \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td class="nowrap center">
                        {{ $item->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') : '—' }}
                    </td>
                    <td class="nowrap">
                        {{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td class="center">
                        <span class="badge {{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                    </td>
                    <td class="center">
                        @if($item->kondisi_awal)
                            <span class="badge {{ $kondisiClass($item->kondisi_awal) }}">{{ $kondisiLabel($item->kondisi_awal) }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="center">
                        @if($item->kondisi_kembali)
                            <span class="badge {{ $kondisiClass($item->kondisi_kembali) }}">{{ $kondisiLabel($item->kondisi_kembali) }}</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="center" style="padding:10pt; color:#777;">
                        Tidak ada data peminjaman untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($histori->count() >= 500)
        <p style="font-size:7pt; color:#c0392b; text-align:center; margin-top:4pt;">
            * Laporan ini dibatasi 500 baris. Gunakan filter tanggal untuk laporan lebih spesifik.
        </p>
    @endif

</body>
</html>
