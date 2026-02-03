<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HistoriPeminjaman;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $hasActiveLoan = HistoriPeminjaman::where('nip_peminjam', $user->nip)
            ->where('status', 'dipinjam')
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('return.index');
        }

        return view('user.scan');
    }
}
