<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\HistoriPeminjaman;
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

        return view('admin.dashboard', compact('totalBarang', 'tersedia', 'dipinjam', 'activeLoans'));
    }
}
