<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Requests\Admin\StoreBarangRequest;
use App\Http\Requests\Admin\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\User;
use App\Services\BarangImportService;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    use LogsAudit;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $barang = Barang::query()
            ->with(['pic', 'kategori', 'ruangan'])
            ->filter($request->only(['ketersediaan', 'kategori_id', 'ruangan_id', 'search']))
            ->paginate(10)
            ->withQueryString();

        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        return view('admin.barang.index', compact('barang', 'kategoriList', 'ruanganList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = $this->getUserOptions();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        return view('admin.barang.create', compact('users', 'kategoriList', 'ruanganList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBarangRequest $request)
    {
        $data = $request->validated();

        // Check for uniqueness of composite key (kode_barang + nup)
        $exists = Barang::where('kode_barang', $data['kode_barang'])
            ->where('nup', $data['nup'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['nup' => 'Barang dengan Kode dan NUP ini sudah ada.'])->withInput();
        }

        Barang::create([
            'kode_barang' => $data['kode_barang'],
            'nup' => $data['nup'],
            'brand' => $data['brand'],
            'tipe' => $data['tipe'],
            'kondisi_terakhir' => $data['kondisi'],
            'keterangan' => $data['keterangan'] ?? null,
            'ketersediaan' => 'tersedia',
            'pic_user_id' => $data['pic_user_id'] ?? null,
            'kategori_id' => $data['kategori_id'] ?? null,
            'ruangan_id' => $data['ruangan_id'] ?? null,
        ]);

        $this->logAudit('create', 'barang', null, [
            'kode_barang' => $data['kode_barang'],
            'nup' => $data['nup'],
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $users = $this->getUserOptions();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        return view('admin.barang.edit', compact('barang', 'users', 'kategoriList', 'ruanganList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarangRequest $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $data = $request->validated();

        $barang->update([
            'brand' => $data['brand'],
            'tipe' => $data['tipe'],
            'kondisi_terakhir' => $data['kondisi'],
            'keterangan' => $data['keterangan'] ?? null,
            'ketersediaan' => $data['ketersediaan'],
            'pic_user_id' => $data['pic_user_id'] ?? null,
            'kategori_id' => $data['kategori_id'] ?? null,
            'ruangan_id' => $data['ruangan_id'] ?? null,
        ]);

        $this->logAudit('update', 'barang', $barang->id, [
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        $this->logAudit('delete', 'barang', $barang->id, [
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus');
    }

    public function import(Request $request, BarangImportService $importService)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $result = $importService->importFromCsv($request->file('file'));
        } catch (\RuntimeException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }

        $this->logAudit('import', 'barang', null, [
            'inserted' => $result['inserted'],
            'skipped' => $result['skipped'],
        ]);

        return redirect()->route('admin.barang.index')->with(
            'success',
            "Import selesai. Berhasil: {$result['inserted']}, dilewati: {$result['skipped']}."
        );
    }

    private function getUserOptions()
    {
        return User::select(['id', 'nama', 'nip'])
            ->orderBy('nama')
            ->get();
    }
}
