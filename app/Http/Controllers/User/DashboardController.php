<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HistoriPeminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Note: We assume the User model has a 'nip' attribute. 
        // If not, we might need to rely on 'username' or another field depending on how Auth is set up.
        // Based on HistoriPeminjaman, it links via 'nip_peminjam'.

        // Count active loans for this user
        $activeLoans = HistoriPeminjaman::where('nip_peminjam', $user->nip)
            ->where('status', 'dipinjam')
            ->count();

        // Count total history for this user
        $totalLoans = HistoriPeminjaman::where('nip_peminjam', $user->nip)
            ->count();

        // Check if there is an active loan to warn/show specific UI
        $currentActiveLoan = HistoriPeminjaman::where('nip_peminjam', $user->nip)
            ->where('status', 'dipinjam')
            ->orderBy('waktu_pinjam', 'desc')
            ->first();

        $now = Carbon::now('Asia/Jakarta');
        $overdueLoans = HistoriPeminjaman::where('nip_peminjam', $user->nip)
            ->where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->where('tanggal_jatuh_tempo', '<', $now)
            ->count();

        $nextDueLoan = HistoriPeminjaman::where('nip_peminjam', $user->nip)
            ->where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->first();

        // Get recent 5 history items with Join for composite key (kode_barang + nup)
        $recentLoans = HistoriPeminjaman::leftJoin('barang', function ($join) {
            $join->on('histori_peminjaman.kode_barang', '=', 'barang.kode_barang')
                ->on('histori_peminjaman.nup', '=', 'barang.nup');
        })
            ->where('histori_peminjaman.nip_peminjam', $user->nip)
            ->select('histori_peminjaman.*', 'barang.brand', 'barang.tipe')
            ->orderBy('histori_peminjaman.waktu_pinjam', 'desc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact(
            'activeLoans',
            'totalLoans',
            'currentActiveLoan',
            'recentLoans',
            'overdueLoans',
            'nextDueLoan'
        ));
    }
}
