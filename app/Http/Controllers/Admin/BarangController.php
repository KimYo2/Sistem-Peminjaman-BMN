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
use App\Services\KondisiHistoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    use LogsAudit;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = in_array((int) request('per_page'), [10, 20, 50, 100])
            ? (int) request('per_page')
            : 10;

        $barang = Barang::query()
            ->with(['pic', 'kategori', 'ruangan'])
            ->filter($request->only(['ketersediaan', 'kategori_id', 'ruangan_id', 'search', 'status_barang']))
            ->paginate($perPage)
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
            'foto_path' => $request->hasFile('foto')
                ? $request->file('foto')->store('barang/foto', 'public')
                : null,
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
        $kondisiHistory = $barang->kondisiHistory()
            ->with('changedBy:id,name')
            ->orderByDesc('created_at')
            ->get();
        $users = $this->getUserOptions();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        return view('admin.barang.edit', compact('barang', 'users', 'kategoriList', 'ruanganList', 'kondisiHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarangRequest $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $data = $request->validated();

        $updateData = [
            'brand' => $data['brand'],
            'tipe' => $data['tipe'],
            'keterangan' => $data['keterangan'] ?? null,
            'ketersediaan' => $data['ketersediaan'],
            'pic_user_id' => $data['pic_user_id'] ?? null,
            'kategori_id' => $data['kategori_id'] ?? null,
            'ruangan_id' => $data['ruangan_id'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            if ($barang->foto_path) {
                Storage::disk('public')->delete($barang->foto_path);
            }
            $updateData['foto_path'] = $request->file('foto')->store('barang/foto', 'public');
        }

        if ($request->boolean('hapus_foto') && $barang->foto_path) {
            Storage::disk('public')->delete($barang->foto_path);
            $updateData['foto_path'] = null;
        }

        $barang->update($updateData);

        app(KondisiHistoryService::class)->record(
            $barang,
            $data['kondisi'],
            'manual',
            null,
            null,
            Auth::id()
        );

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

        if ($barang->foto_path) {
            Storage::disk('public')->delete($barang->foto_path);
        }

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
        return User::select(['id', 'name', 'nip'])
            ->orderBy('name')
            ->get();
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_barang'  => 'required|in:aktif,rusak_total,hilang,dihapuskan',
            'catatan_status' => 'nullable|string|max:2000',
        ]);

        $barang = Barang::findOrFail($id);
        $now    = Carbon::now('Asia/Jakarta');

        $ketersediaan = $barang->ketersediaan;
        if (in_array($request->status_barang, ['rusak_total', 'dihapuskan'])) {
            $ketersediaan = 'reparasi';
        } elseif ($request->status_barang === 'hilang') {
            $ketersediaan = 'hilang';
        } elseif ($request->status_barang === 'aktif') {
            $ketersediaan = 'tersedia';
        }

        $barang->update([
            'status_barang'      => $request->status_barang,
            'catatan_status'     => $request->catatan_status,
            'status_diupdate_at' => $now,
            'status_diupdate_by' => Auth::id(),
            'ketersediaan'       => $ketersediaan,
        ]);

        $this->logAudit('update_status', 'barang', $barang->id, [
            'kode_barang'  => $barang->kode_barang,
            'nup'          => $barang->nup,
            'status_barang'=> $request->status_barang,
        ]);

        $statusLabel = [
            'aktif'       => 'Aktif',
            'rusak_total' => 'Rusak Total',
            'hilang'      => 'Hilang',
            'dihapuskan'  => 'Dihapuskan',
        ][$request->status_barang];

        return redirect()->back()->with('success', "Status barang berhasil diubah menjadi: {$statusLabel}.");
    }
}
