<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use Illuminate\Http\Request;

class TiketKerusakanController extends Controller
{
    use LogsAudit;
    public function index(Request $request)
    {
        $query = \App\Models\TiketKerusakan::query()->orderBy('tanggal_lapor', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $tickets = $query->get(); // Simple get for student project (or paginate)

        return view('admin.tiket.index', compact('tickets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,diproses,selesai'
        ]);

        $ticket = \App\Models\TiketKerusakan::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        $this->logAudit('update', 'tiket_kerusakan', $ticket->id, [
            'status' => $request->status,
            'nomor_bmn' => $ticket->nomor_bmn,
        ]);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }
}
