<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics mirroring the legacy dashboard.php logic

        // SELECT COUNT(*) as total FROM barang
        $totalBarang = Barang::count();

        // SELECT COUNT(*) as total FROM barang WHERE ketersediaan = 'tersedia'
        $tersedia = Barang::where('ketersediaan', 'tersedia')->count();

        // SELECT COUNT(*) as total FROM barang WHERE ketersediaan = 'dipinjam'
        $dipinjam = Barang::where('ketersediaan', 'dipinjam')->count();

        // SELECT COUNT(*) as total FROM histori_peminjaman WHERE status = 'dipinjam'
        $activeLoans = HistoriPeminjaman::where('status', 'dipinjam')->count();

        $now = Carbon::now('Asia/Jakarta');

        $overdueCount = HistoriPeminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->where('tanggal_jatuh_tempo', '<', $now)
            ->count();

        $overdueList = HistoriPeminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->where('tanggal_jatuh_tempo', '<', $now)
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(5)
            ->get();

        $topItems = HistoriPeminjaman::leftJoin('barang', function ($join) {
            $join->on('histori_peminjaman.kode_barang', '=', 'barang.kode_barang')
                ->on('histori_peminjaman.nup', '=', 'barang.nup');
        })
            ->select(
                'histori_peminjaman.kode_barang',
                'histori_peminjaman.nup',
                'barang.brand',
                'barang.tipe',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('histori_peminjaman.kode_barang', 'histori_peminjaman.nup', 'barang.brand', 'barang.tipe')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topBorrowers = HistoriPeminjaman::select(
            'nip_peminjam',
            'nama_peminjam',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('nip_peminjam', 'nama_peminjam')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $avgBorrowHours = HistoriPeminjaman::whereNotNull('waktu_kembali')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, waktu_pinjam, waktu_kembali)) as avg_hours')
            ->value('avg_hours');

        return view('admin.dashboard', compact(
            'totalBarang',
            'tersedia',
            'dipinjam',
            'activeLoans',
            'overdueCount',
            'overdueList',
            'topItems',
            'topBorrowers',
            'avgBorrowHours'
        ));
    }
}
