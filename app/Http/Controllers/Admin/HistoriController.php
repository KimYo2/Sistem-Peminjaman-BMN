<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoriPeminjaman;
use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function index(Request $request)
    {
        $query = HistoriPeminjaman::query();

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search (Optional - legacy didn't have robust search on this page but why not?)
        // Legacy didn't have search, but let's add basic searching by BMN or Peminjam
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_peminjam', 'like', "%{$search}%")
                    ->orWhere('nip_peminjam', 'like', "%{$search}%");
            });
        }

        $histori = $query->orderBy('waktu_pinjam', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.histori.index', compact('histori'));
    }
}
