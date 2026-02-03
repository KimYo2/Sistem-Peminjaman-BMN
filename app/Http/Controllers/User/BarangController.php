<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangController extends Controller
{
    public function show($nomor_bmn)
    {
        // Parse kode_barang and nup like legacy logic
        $parts = explode('-', $nomor_bmn);
        $kode_barang = $parts[0] ?? null;
        $nup = isset($parts[1]) ? intval($parts[1]) : null;

        if (!$kode_barang || $nup === null) {
            abort(404, 'Format Nomor BMN tidak valid');
        }

        $barang = Barang::where('kode_barang', $kode_barang)
            ->where('nup', $nup)
            ->firstOrFail();

        // Construct full nomor_bmn for view convenience
        $barang->nomor_bmn_full = $barang->kode_barang . '-' . $barang->nup;

        // Check if current user is borrowing this item
        $user = Auth::user();
        $isBorrowing = HistoriPeminjaman::where('kode_barang', $kode_barang)
            ->where('nup', $nup)
            ->where('nip_peminjam', $user->nip)
            ->where('status', 'dipinjam')
            ->exists();

        return view('user.barang.show', compact('barang', 'isBorrowing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_bmn' => 'required|string',
        ]);

        $parts = explode('-', $request->nomor_bmn);
        $kode_barang = $parts[0] ?? null;
        $nup = isset($parts[1]) ? intval($parts[1]) : null;

        if (!$kode_barang || $nup === null) {
            return response()->json(['success' => false, 'message' => 'Format Nomor BMN tidak valid'], 400);
        }

        $barang = Barang::where('kode_barang', $kode_barang)
            ->where('nup', $nup)
            ->first();

        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan'], 404);
        }

        if ($barang->ketersediaan !== 'tersedia') {
            return response()->json([
                'success' => false,
                'message' => 'Barang sedang dipinjam oleh ' . $barang->peminjam_terakhir
            ], 400);
        }

        $hasPendingOrActive = HistoriPeminjaman::where('kode_barang', $kode_barang)
            ->where('nup', $nup)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->exists();

        if ($hasPendingOrActive) {
            return response()->json([
                'success' => false,
                'message' => 'Barang sedang diproses peminjaman atau masih dipinjam.'
            ], 400);
        }

        $user = Auth::user();
        $waktu_pengajuan = Carbon::now('Asia/Jakarta');

        try {
            DB::transaction(function () use ($user, $waktu_pengajuan, $kode_barang, $nup) {
                // Create pending loan request (awaiting admin approval)
                HistoriPeminjaman::create([
                    'kode_barang' => $kode_barang,
                    'nup' => $nup,
                    'nip_peminjam' => $user->nip,
                    'nama_peminjam' => $user->nama,
                    'waktu_pengajuan' => $waktu_pengajuan,
                    'waktu_pinjam' => null,
                    'status' => 'menunggu',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil dikirim dan menunggu persetujuan admin.',
                'redirect_url' => route('user.dashboard')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }
}
