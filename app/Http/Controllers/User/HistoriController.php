<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HistoriPeminjaman;
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
}
