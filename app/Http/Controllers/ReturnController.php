<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        return view('return.scan');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nomor_bmn' => 'required',
                'is_damaged' => 'nullable|boolean',
                'jenis_kerusakan' => 'required_if:is_damaged,true|in:ringan,berat',
                'deskripsi' => 'nullable|string|max:1000',
            ]);

            $nomor_bmn = $request->nomor_bmn;
            $kode_barang = '';
            $nup = '';

            if (!empty($nomor_bmn)) {
                // 1. Try BPS Long Format with Asterisks (Pre-parsing for raw scans)
                if (strpos($nomor_bmn, '*') !== false) {
                    $parts = explode('*', $nomor_bmn);
                    // Format: INV-xxx*xxx*xxx*KODE*NUP
                    if (count($parts) >= 4) {
                        $nomor_bmn = trim($parts[2]) . '-' . trim($parts[3]);
                    }
                }

                // 2. Logic requested by user (Code-NUP)
                if (strpos($nomor_bmn, '-') !== false) {
                    $parts = explode('-', $nomor_bmn);
                    $kode_barang = $parts[0];
                    $nup = intval($parts[1]);
                } else {
                    $kode_barang = $nomor_bmn;
                }
            }

            // Logic tambahan untuk parsing BPS QR Code (INV-...) jika tidak kena filter diatas
            // User snippet expects "-" split. 
            // If the raw input is "INV-...*Code*NUP", strpos('-') is true, but explode result is wrong.
            // But user insisted "nomer bmn ... sesuaikan logic scan sebelumnya".
            // So we TRUST the user that $nomor_bmn passed here is compatible or they want this specific check.

            // However, to avoid 500 error if "INV-..." comes in and split logic fails to get numeric NUP:
            // The user's snippet uses intval(). If parts[1] is "2021...*...", intval might return 2021.
            // This would cause mismatched item error (404), which is safe.

            if (empty($kode_barang)) {
                return response()->json(['success' => false, 'message' => 'Kode barang tidak valid'], 400);
            }

            $user = \Illuminate\Support\Facades\Auth::user();

            $query = \App\Models\HistoriPeminjaman::where('kode_barang', $kode_barang)
                ->where('status', 'dipinjam');

            if (!empty($nup)) {
                $query->where('nup', $nup);
            }

            $peminjaman = $query->orderBy('waktu_pinjam', 'desc')->first();

            if (!$peminjaman) {
                // Enhance error message for debug
                return response()->json([
                    'success' => false,
                    'message' => "Barang tidak sedang dipinjam (Kode: $kode_barang, NUP: $nup)"
                ], 404);
            }

            $waktu_kembali = \Carbon\Carbon::now('Asia/Jakarta');

            // Use found NUP from peminjaman if strict NUP was not possible (e.g. only code scanned)
            $target_nup = $peminjaman->nup;

            \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $kode_barang, $target_nup, $waktu_kembali, $nomor_bmn, $request, $user) {
                $isDamagedValue = $request->is_damaged;
                $shouldCreateTicket = ($isDamagedValue === true || $isDamagedValue === 'true' || $isDamagedValue === 1 || $isDamagedValue === '1');

                $kondisiKembali = 'baik';
                if ($shouldCreateTicket) {
                    $kondisiKembali = ($request->jenis_kerusakan === 'berat') ? 'rusak_berat' : 'rusak_ringan';
                }

                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'waktu_kembali' => $waktu_kembali,
                    'kondisi_kembali' => $kondisiKembali,
                    'catatan_kondisi' => $request->deskripsi,
                ]);

                \App\Models\Barang::where('kode_barang', $kode_barang)
                    ->where('nup', $target_nup)
                    ->update([
                        'ketersediaan' => 'tersedia',
                        'waktu_kembali' => $waktu_kembali
                    ]);

                // Create damage ticket if item is damaged
                \Log::info('Damage ticket check', [
                    'is_damaged_raw' => $isDamagedValue,
                    'is_damaged_type' => gettype($isDamagedValue),
                    'should_create' => $shouldCreateTicket,
                    'jenis_kerusakan' => $request->jenis_kerusakan,
                    'deskripsi' => $request->deskripsi
                ]);

                if ($shouldCreateTicket) {
                    $ticket = \App\Models\TiketKerusakan::create([
                        'nomor_bmn' => $kode_barang . '-' . $target_nup,
                        'pelapor' => $user->name ?? 'System',
                        'jenis_kerusakan' => $request->jenis_kerusakan ?? 'ringan',
                        'deskripsi' => $request->deskripsi ?? '-',
                        'status' => 'open'
                    ]);

                    \Log::info('Damage ticket created', ['ticket_id' => $ticket->id]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dikembalikan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
