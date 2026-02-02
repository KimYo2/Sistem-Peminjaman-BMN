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
        $histori = HistoriPeminjaman::with('barang')
            ->where('nip_peminjam', $nip)
            ->orderBy('waktu_pinjam', 'desc')
            ->paginate(10);

        return view('user.histori.index', compact('histori'));
    }
}
