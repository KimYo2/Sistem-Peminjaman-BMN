<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ExtendLoanRequest;
use App\Models\HistoriPeminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoriController extends Controller
{
    public function index()
    {
        // Get the current user's NIP
        $nip = Auth::user()->nip;

        // Fetch history for this user, ordered by latest
        $histori = HistoriPeminjaman::query()
            ->leftJoin('barang', function ($join) {
                $join->on('histori_peminjaman.kode_barang', '=', 'barang.kode_barang')
                    ->on('histori_peminjaman.nup', '=', 'barang.nup');
            })
            ->where('histori_peminjaman.nip_peminjam', $nip)
            ->orderBy('histori_peminjaman.waktu_pinjam', 'desc')
            ->select('histori_peminjaman.*', 'barang.brand', 'barang.tipe')
            ->paginate(10)
            ->withQueryString();

        return view('user.histori.index', compact('histori'));
    }

    public function extend(ExtendLoanRequest $request, $id)
    {
        $user = Auth::user();

        $histori = HistoriPeminjaman::where('id', $id)
            ->where('nip_peminjam', $user->nip)
            ->firstOrFail();

        if ($histori->status !== 'dipinjam') {
            return redirect()->back()->withErrors(['status' => 'Peminjaman tidak aktif.']);
        }

        if ($histori->perpanjangan_status === 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Pengajuan perpanjangan sedang diproses.']);
        }

        $data = $request->validated();
        $hari = $data['hari'] ?? 7;

        $histori->update([
            'perpanjangan_status' => 'menunggu',
            'perpanjangan_hari' => $hari,
            'perpanjangan_diminta_at' => Carbon::now('Asia/Jakarta'),
            'perpanjangan_alasan' => $data['alasan'] ?? null,
            'perpanjangan_reject_reason' => null,
            'perpanjangan_disetujui_at' => null,
            'perpanjangan_disetujui_by' => null,
            'perpanjangan_ditolak_at' => null,
        ]);

        return redirect()->back()->with('success', 'Pengajuan perpanjangan berhasil dikirim.');
    }
}
