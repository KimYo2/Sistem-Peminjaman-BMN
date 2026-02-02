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

        $user = Auth::user();
        $waktu_pinjam = Carbon::now('Asia/Jakarta');

        try {
            DB::transaction(function () use ($barang, $user, $waktu_pinjam, $kode_barang, $nup) {
                // Update barang status
                $barang->update([
                    'ketersediaan' => 'dipinjam',
                    'peminjam_terakhir' => $user->nama,
                    'waktu_pinjam' => $waktu_pinjam,
                    'waktu_kembali' => null,
                ]);

                // Insert into histori
                HistoriPeminjaman::create([
                    'kode_barang' => $kode_barang,
                    'nup' => $nup,
                    'nip_peminjam' => $user->nip,
                    // 'nama_peminjam' => $user->nama, // Assuming table has this, legacy code inserted it. 
                    // Let's check model fillable. If model doesn't have it, we might need to rely on relation or add it.
                    // Legacy code: INSERT INTO histori_peminjaman ... nama_peminjam ...
                    // Let's check if our HistoriPeminjaman model has 'nama_peminjam' in fillable.
                    // I'll add it to fillable below if needed, but for now I'll include it in create array 
                    // assuming the DB has the column (which legacy code proves).
                    'nama_peminjam' => $user->nama,
                    'waktu_pinjam' => $waktu_pinjam,
                    'status' => 'dipinjam',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diajukan',
                'redirect_url' => route('user.dashboard')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }
}
