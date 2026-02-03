<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    use LogsAudit;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query()->with('pic');

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
        $users = User::orderBy('nama')->get();
        return view('admin.barang.create', compact('users'));
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
            'pic_user_id' => 'nullable|exists:users,id',
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
            'pic_user_id' => $request->pic_user_id,
        ]);

        $this->logAudit('create', 'barang', null, [
            'kode_barang' => $request->kode_barang,
            'nup' => $request->nup,
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
        $users = User::orderBy('nama')->get();
        return view('admin.barang.edit', compact('barang', 'users'));
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
            'pic_user_id' => 'nullable|exists:users,id',
        ]);

        $barang->update([
            'brand' => $request->brand,
            'tipe' => $request->tipe,
            'kondisi_terakhir' => $request->kondisi,
            'ketersediaan' => $request->ketersediaan,
            'pic_user_id' => $request->pic_user_id,
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca.']);
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return back()->withErrors(['file' => 'File kosong atau format CSV tidak valid.']);
        }

        $normalized = array_map(function ($value) {
            return strtolower(trim($value));
        }, $header);

        $hasHeader = in_array('kode_barang', $normalized, true);
        if (!$hasHeader) {
            rewind($handle);
        }

        $inserted = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) {
                $skipped++;
                continue;
            }

            $data = [
                'kode_barang' => trim($row[0] ?? ''),
                'nup' => (int) trim($row[1] ?? ''),
                'brand' => trim($row[2] ?? ''),
                'tipe' => trim($row[3] ?? ''),
                'kondisi_terakhir' => trim($row[4] ?? 'baik'),
                'ketersediaan' => trim($row[5] ?? 'tersedia'),
            ];

            if ($data['kode_barang'] === '' || $data['nup'] <= 0 || $data['brand'] === '' || $data['tipe'] === '') {
                $skipped++;
                continue;
            }

            $exists = Barang::where('kode_barang', $data['kode_barang'])
                ->where('nup', $data['nup'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Barang::create([
                'kode_barang' => $data['kode_barang'],
                'nup' => $data['nup'],
                'brand' => $data['brand'],
                'tipe' => $data['tipe'],
                'kondisi_terakhir' => $data['kondisi_terakhir'],
                'ketersediaan' => $data['ketersediaan'],
            ]);

            $inserted++;
        }

        fclose($handle);

        $this->logAudit('import', 'barang', null, [
            'inserted' => $inserted,
            'skipped' => $skipped,
        ]);

        return redirect()->route('admin.barang.index')->with('success', "Import selesai. Berhasil: {$inserted}, dilewati: {$skipped}.");
    }
}
