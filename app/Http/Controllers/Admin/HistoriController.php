<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoriController extends Controller
{
    use LogsAudit;
    public function index(Request $request)
    {
        $query = HistoriPeminjaman::query()
            ->filter($request->only(['status', 'search']));

        $histori = $query->orderBy('waktu_pinjam', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.histori.index', compact('histori'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['status', 'search']);

        $query = HistoriPeminjaman::query()
            ->select([
                'id',
                'kode_barang',
                'nup',
                'nip_peminjam',
                'nama_peminjam',
                'status',
                'waktu_pengajuan',
                'waktu_pinjam',
                'waktu_kembali',
                'tanggal_jatuh_tempo',
                'kondisi_awal',
                'kondisi_kembali',
                'catatan_kondisi',
            ])
            ->filter($filters);

        $filename = 'histori_peminjaman_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.csv';

        $this->logAudit('export', 'histori_peminjaman', null, [
            'filters' => array_filter($filters, fn ($value) => $value !== null && $value !== ''),
            'format' => 'csv',
        ]);

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            $csvSafe = function ($value): string {
                if ($value === null) {
                    return '';
                }

                $value = (string) $value;

                if ($value === '') {
                    return '';
                }

                // Prevent CSV injection in Excel/Sheets
                if (preg_match('/^[=+\-@]/', $value)) {
                    return "'" . $value;
                }

                return $value;
            };

            $csvText = function ($value) use ($csvSafe): string {
                $value = $csvSafe($value);

                if ($value === '') {
                    return '';
                }

                // Force Excel to keep identifiers as text (NIP/NUP/kode)
                return "\t" . $value;
            };

            fputcsv($handle, [
                'Kode Barang',
                'NUP',
                'NIP Peminjam',
                'Nama Peminjam',
                'Status',
                'Waktu Pengajuan',
                'Waktu Pinjam',
                'Waktu Kembali',
                'Jatuh Tempo',
                'Kondisi Awal',
                'Kondisi Kembali',
                'Catatan Kondisi',
            ]);

            $query->orderBy('waktu_pinjam', 'desc')
                ->orderBy('id', 'desc')
                ->chunk(200, function ($rows) use ($handle, $csvSafe, $csvText) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $csvText($row->kode_barang),
                        $csvText($row->nup),
                        $csvText($row->nip_peminjam),
                        $csvSafe($row->nama_peminjam),
                        $csvSafe($row->status),
                        optional($row->waktu_pengajuan)->format('Y-m-d H:i:s'),
                        optional($row->waktu_pinjam)->format('Y-m-d H:i:s'),
                        optional($row->waktu_kembali)->format('Y-m-d H:i:s'),
                        optional($row->tanggal_jatuh_tempo)->format('Y-m-d'),
                        $csvSafe($row->kondisi_awal),
                        $csvSafe($row->kondisi_kembali),
                        $csvSafe($row->catatan_kondisi),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function approve($id)
    {
        $histori = HistoriPeminjaman::findOrFail($id);

        if ($histori->status !== 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Pengajuan ini sudah diproses.']);
        }

        $barang = Barang::where('kode_barang', $histori->kode_barang)
            ->where('nup', $histori->nup)
            ->first();

        if (!$barang || $barang->ketersediaan !== 'tersedia') {
            return redirect()->back()->withErrors(['status' => 'Barang tidak tersedia untuk dipinjam.']);
        }

        $now = Carbon::now('Asia/Jakarta');

        DB::transaction(function () use ($histori, $barang, $now) {
            $histori->update([
                'status' => 'dipinjam',
                'approved_by' => Auth::id(),
                'approved_at' => $now,
                'waktu_pinjam' => $now,
                'tanggal_jatuh_tempo' => $now->copy()->addDays(7),
                'kondisi_awal' => $histori->kondisi_awal ?: $barang->kondisi_terakhir,
            ]);

            $barang->update([
                'ketersediaan' => 'dipinjam',
                'peminjam_terakhir' => $histori->nama_peminjam,
                'waktu_pinjam' => $now,
                'waktu_kembali' => null,
            ]);
        });

        $this->logAudit('approve', 'histori_peminjaman', $histori->id, [
            'kode_barang' => $histori->kode_barang,
            'nup' => $histori->nup,
            'nip_peminjam' => $histori->nip_peminjam,
        ]);

        return redirect()->back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $histori = HistoriPeminjaman::findOrFail($id);

        if ($histori->status !== 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Pengajuan ini sudah diproses.']);
        }

        $now = Carbon::now('Asia/Jakarta');

        $histori->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'rejected_at' => $now,
            'rejection_reason' => $request->rejection_reason,
        ]);

        $this->logAudit('reject', 'histori_peminjaman', $histori->id, [
            'kode_barang' => $histori->kode_barang,
            'nup' => $histori->nup,
            'nip_peminjam' => $histori->nip_peminjam,
            'reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }
}
