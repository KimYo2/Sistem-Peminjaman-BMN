<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnStoreRequest;
use App\Models\HistoriPeminjaman;
use App\Models\TiketKerusakan;
use App\Models\User;
use App\Models\Waitlist;
use App\Services\BmnParser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            $user = Auth::user();

            $query = HistoriPeminjaman::where('kode_barang', $kode_barang)
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

            $waktu_kembali = Carbon::now('Asia/Jakarta');

            // Use found NUP from peminjaman if strict NUP was not possible (e.g. only code scanned)
            $target_nup = $peminjaman->nup;

            DB::transaction(function () use ($peminjaman, $kode_barang, $target_nup, $waktu_kembali, $request, $user, $data) {
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
                Log::info('Damage ticket check', [
                    'is_damaged_raw' => $isDamagedValue,
                    'is_damaged_type' => gettype($isDamagedValue),
                    'should_create' => $shouldCreateTicket,
                    'jenis_kerusakan' => $data['jenis_kerusakan'] ?? null,
                    'deskripsi' => $data['deskripsi'] ?? null
                ]);

                if ($shouldCreateTicket) {
                    $ticket = TiketKerusakan::create([
                        'nomor_bmn' => $kode_barang . '-' . $target_nup,
                        'pelapor' => $user->nama ?? $user->name ?? 'System',
                        'jenis_kerusakan' => $data['jenis_kerusakan'] ?? 'ringan',
                        'deskripsi' => $data['deskripsi'] ?? '-',
                        'status' => 'open'
                    ]);

                    Log::info('Damage ticket created', ['ticket_id' => $ticket->id]);
                }

                // Auto-process first waitlist entry (FIFO) when item becomes available
                $nextWaitlist = Waitlist::where('kode_barang', $kode_barang)
                    ->where('nup', $target_nup)
                    ->where('status', 'aktif')
                    ->orderBy('requested_at')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->first();

                if ($nextWaitlist) {
                    $waitUser = User::where('nip', $nextWaitlist->nip_peminjam)->first();

                    if ($waitUser) {
                        HistoriPeminjaman::create([
                            'kode_barang' => $kode_barang,
                            'nup' => $target_nup,
                            'nip_peminjam' => $waitUser->nip,
                            'nama_peminjam' => $waitUser->nama,
                            'waktu_pengajuan' => $waktu_kembali,
                            'waktu_pinjam' => null,
                            'status' => 'menunggu',
                        ]);

                        $nextWaitlist->update([
                            'status' => 'fulfilled',
                            'notified_at' => $waktu_kembali,
                            'fulfilled_at' => $waktu_kembali,
                        ]);
                    } else {
                        $nextWaitlist->update([
                            'status' => 'cancelled',
                            'cancelled_at' => $waktu_kembali,
                        ]);
                    }
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
