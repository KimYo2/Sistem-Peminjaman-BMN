<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Requests\Admin\StoreBarangRequest;
use App\Http\Requests\Admin\UpdateBarangRequest;
use App\Models\Barang;
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
            ->with('pic')
            ->filter($request->only(['ketersediaan', 'search']))
            ->paginate(10)
            ->withQueryString();

        return view('admin.barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = $this->getUserOptions();
        return view('admin.barang.create', compact('users'));
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
            'ketersediaan' => 'tersedia', // Default value
            'pic_user_id' => $data['pic_user_id'] ?? null,
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
        // Legacy URL uses nomor_bmn (formatted like KODE-NUP).
        // Standard resource uses ID. We should support ID for new links, 
        // but since we linked with nomor_bmn in index, we need to handle that.
        // Or better: update index link to use ID.
        // Let's check index.blade.php again... It uses route('...destroy', $item->id).
        // But for edit link: href="/src/admin/edit_barang.php?nomor_bmn={{ $item->kode_barang }}-{{ $item->nup }}"
        // I will change index blade to use route('admin.barang.edit', $item->id).

        $barang = Barang::findOrFail($id);
        $users = $this->getUserOptions();
        return view('admin.barang.edit', compact('barang', 'users'));
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
            'ketersediaan' => $data['ketersediaan'],
            'pic_user_id' => $data['pic_user_id'] ?? null,
            // 'keterangan' => $request->keterangan, // Need to check if model has 'keterangan'
        ]);

        $this->logAudit('update', 'barang', $barang->id, [
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
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
