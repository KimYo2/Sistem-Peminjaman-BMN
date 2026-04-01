<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Requests\Admin\StoreKategoriRequest;
use App\Http\Requests\Admin\UpdateKategoriRequest;
use App\Models\Kategori;

class KategoriController extends Controller
{
    use LogsAudit;

    public function index()
    {
        $kategori = Kategori::withCount('barang')->orderBy('nama_kategori')->paginate(10);

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = Kategori::create($request->validated());

        $this->logAudit('create', 'kategori', $kategori->id, [
            'nama_kategori' => $kategori->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->validated());

        $this->logAudit('update', 'kategori', $kategori->id, [
            'nama_kategori' => $kategori->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        if ($kategori->barang()->exists()) {
            return redirect()->back()->withErrors(['delete' => 'Kategori tidak dapat dihapus karena masih memiliki barang.']);
        }

        $kategori->delete();

        $this->logAudit('delete', 'kategori', $kategori->id, [
            'nama_kategori' => $kategori->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
