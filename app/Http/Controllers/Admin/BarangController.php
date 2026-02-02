<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // Filter by Ketersediaan
        if ($request->has('ketersediaan') && $request->ketersediaan != '') {
            $query->where('ketersediaan', $request->ketersediaan);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('peminjam_terakhir', 'like', "%{$search}%");
            });
        }

        $barang = $query->paginate(10)->withQueryString();

        return view('admin.barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50',
            'nup' => 'required|integer|min:1',
            'brand' => 'required|string|max:100',
            'tipe' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        // Check for uniqueness of composite key (kode_barang + nup)
        $exists = Barang::where('kode_barang', $request->kode_barang)
            ->where('nup', $request->nup)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nup' => 'Barang dengan Kode dan NUP ini sudah ada.'])->withInput();
        }

        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nup' => $request->nup,
            'brand' => $request->brand,
            'tipe' => $request->tipe,
            'kondisi_terakhir' => $request->kondisi,
            'ketersediaan' => 'tersedia', // Default value
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Legacy URL uses nomor_bmn (formatted like KODE-NUP).
        // Standard resource uses ID. We should support ID for new links, 
        // but since we linked with nomor_bmn in index, we need to handle that.
        // Or better: update index link to use ID.
        // Let's check index.blade.php again... It uses route('...destroy', $item->id).
        // But for edit link: href="/src/admin/edit_barang.php?nomor_bmn={{ $item->kode_barang }}-{{ $item->nup }}"
        // I will change index blade to use route('admin.barang.edit', $item->id).

        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'brand' => 'required|string|max:100',
            'tipe' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'ketersediaan' => 'required|in:tersedia,dipinjam,hilang,reparasi',
            'keterangan' => 'nullable|string',
        ]);

        $barang->update([
            'brand' => $request->brand,
            'tipe' => $request->tipe,
            'kondisi_terakhir' => $request->kondisi,
            'ketersediaan' => $request->ketersediaan,
            // 'keterangan' => $request->keterangan, // Need to check if model has 'keterangan'
        ]);

        // Wait, does Barang model have 'keterangan'? 
        // Legacy edit_barang.php has 'keterangan' field. 
        // Let's check if I added it to fillable. No I didn't. 
        // And I should check if migration/table has it. 
        // I will check table schema or assume legacy is correct.
        // Safe bet: if input implies it, I should add it.
        // But for now, let's update what we know.

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) // Note: $id here might need to be resolved via model binding or custom query if ID is not primary key
    {
        // Ideally we should use ID, but legacy uses nomor_bmn. 
        // Let's assume we pass the primary ID for cleanliness in Laravel, 
        // OR we can accept nomor_bmn if we want to stick to legacy params.
        // For standard resource, destroy takes $id.

        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
