<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnStoreRequest;
use App\Services\BmnParser;

class ReturnController extends Controller
{
    public function index()
    {
        return view('return.scan');
    }

    public function store(ReturnStoreRequest $request)
    {
        try {
            $data = $request->validated();
            $nomor_bmn = $data['nomor_bmn'];
            try {
                $parsed = BmnParser::parse($nomor_bmn, false);
            } catch (\InvalidArgumentException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            $kode_barang = $parsed['kode_barang'];
            $nup = $parsed['nup'];

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

            if (!is_null($nup)) {
                $query->where('nup', $nup);
            }
            if ($user && ($user->role ?? 'user') !== 'admin') {
                $query->where('nip_peminjam', $user->nip);
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

            \Illuminate\Support\Facades\DB::transaction(function () use ($peminjaman, $kode_barang, $target_nup, $waktu_kembali, $nomor_bmn, $request, $user, $data) {
                $isDamagedValue = $request->boolean('is_damaged');
                $shouldCreateTicket = $isDamagedValue;

                $kondisiKembali = 'baik';
                if ($shouldCreateTicket) {
                    $kondisiKembali = (($data['jenis_kerusakan'] ?? 'ringan') === 'berat') ? 'rusak_berat' : 'rusak_ringan';
                }

                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'waktu_kembali' => $waktu_kembali,
                    'kondisi_kembali' => $kondisiKembali,
                    'catatan_kondisi' => $data['deskripsi'] ?? null,
                ]);

                \App\Models\Barang::where('kode_barang', $kode_barang)
                    ->where('nup', $target_nup)
                    ->update([
                        'ketersediaan' => 'tersedia',
                        'kondisi_terakhir' => $kondisiKembali,
                        'waktu_kembali' => $waktu_kembali
                    ]);

                // Create damage ticket if item is damaged
                \Log::info('Damage ticket check', [
                    'is_damaged_raw' => $isDamagedValue,
                    'is_damaged_type' => gettype($isDamagedValue),
                    'should_create' => $shouldCreateTicket,
                    'jenis_kerusakan' => $data['jenis_kerusakan'] ?? null,
                    'deskripsi' => $data['deskripsi'] ?? null
                ]);

                if ($shouldCreateTicket) {
                    $ticket = \App\Models\TiketKerusakan::create([
                        'nomor_bmn' => $kode_barang . '-' . $target_nup,
                        'pelapor' => $user->nama ?? $user->name ?? 'System',
                        'jenis_kerusakan' => $data['jenis_kerusakan'] ?? 'ringan',
                        'deskripsi' => $data['deskripsi'] ?? '-',
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
