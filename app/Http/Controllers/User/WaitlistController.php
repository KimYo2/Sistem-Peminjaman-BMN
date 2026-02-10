<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Waitlist;
use App\Services\BmnParser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends Controller
{
    public function join($nomor_bmn)
    {
        try {
            $parsed = BmnParser::parse($nomor_bmn, true);
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withErrors(['status' => 'Nomor BMN tidak valid.']);
        }

        $barang = Barang::where('kode_barang', $parsed['kode_barang'])
            ->where('nup', $parsed['nup'])
            ->first();

        if (!$barang) {
            return redirect()->back()->withErrors(['status' => 'Barang tidak ditemukan.']);
        }

        if ($barang->ketersediaan === 'tersedia') {
            return redirect()->back()->withErrors(['status' => 'Barang masih tersedia, tidak perlu antre.']);
        }

        $user = Auth::user();

        $existing = Waitlist::where('kode_barang', $barang->kode_barang)
            ->where('nup', $barang->nup)
            ->where('nip_peminjam', $user->nip)
            ->whereIn('status', ['aktif', 'notified'])
            ->exists();

        if ($existing) {
            return redirect()->back()->withErrors(['status' => 'Anda sudah masuk waitlist untuk barang ini.']);
        }

        Waitlist::create([
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
            'nip_peminjam' => $user->nip,
            'status' => 'aktif',
            'requested_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->back()->with('success', 'Berhasil masuk waitlist barang.');
    }

    public function cancel($id)
    {
        $user = Auth::user();

        $waitlist = Waitlist::where('id', $id)
            ->where('nip_peminjam', $user->nip)
            ->whereIn('status', ['aktif', 'notified'])
            ->firstOrFail();

        $waitlist->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->back()->with('success', 'Waitlist dibatalkan.');
    }
}
