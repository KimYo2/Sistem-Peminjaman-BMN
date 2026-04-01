<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Requests\Admin\StoreRuanganRequest;
use App\Http\Requests\Admin\UpdateRuanganRequest;
use App\Models\Ruangan;

class RuanganController extends Controller
{
    use LogsAudit;

    public function index()
    {
        $ruangan = Ruangan::withCount('barang')->orderBy('nama_ruangan')->paginate(10);

        return view('admin.ruangan.index', compact('ruangan'));
    }

    public function create()
    {
        return view('admin.ruangan.create');
    }

    public function store(StoreRuanganRequest $request)
    {
        $ruangan = Ruangan::create($request->validated());

        $this->logAudit('create', 'ruangan', $ruangan->id, [
            'kode_ruangan' => $ruangan->kode_ruangan,
        ]);

        return redirect()->route('admin.ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        return view('admin.ruangan.edit', compact('ruangan'));
    }

    public function update(UpdateRuanganRequest $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($request->validated());

        $this->logAudit('update', 'ruangan', $ruangan->id, [
            'kode_ruangan' => $ruangan->kode_ruangan,
        ]);

        return redirect()->route('admin.ruangan.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        if ($ruangan->barang()->exists()) {
            return redirect()->back()->withErrors(['delete' => 'Ruangan tidak dapat dihapus karena masih memiliki barang.']);
        }

        $ruangan->delete();

        $this->logAudit('delete', 'ruangan', $ruangan->id, [
            'kode_ruangan' => $ruangan->kode_ruangan,
        ]);

        return redirect()->route('admin.ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
