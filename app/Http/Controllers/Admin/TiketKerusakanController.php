<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Models\Barang;
use App\Models\TiketKerusakan;
use App\Models\TiketKerusakanLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TiketKerusakanController extends Controller
{
    use LogsAudit;

    public function index(Request $request)
    {
        $query = TiketKerusakan::query()
            ->with(['assignee', 'resolver'])
            ->orderByDesc('tanggal_lapor');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('resolusi')) {
            if ($request->resolusi === 'unresolved') {
                $query->whereNull('resolusi');
            } else {
                $query->where('resolusi', $request->resolusi);
            }
        }

        $tickets = $query->paginate(15)->withQueryString();
        $admins = User::where('role', 'admin')->orderBy('name')->get(['id', 'name', 'nip']);

        return view('admin.tiket.index', compact('tickets', 'admins'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,diproses,selesai',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'target_selesai_at' => 'nullable|date',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $ticket = TiketKerusakan::findOrFail($id);
        $fromStatus = $ticket->status;
        $now = Carbon::now('Asia/Jakarta');

        DB::transaction(function () use ($ticket, $request, $fromStatus, $now) {
            $nextStatus = $request->status;

            $ticket->update([
                'status' => $nextStatus,
                'priority' => $request->priority,
                'assigned_to' => $request->assigned_to,
                'target_selesai_at' => $request->target_selesai_at,
                'admin_notes' => $request->admin_notes,
                'closed_at' => $nextStatus === 'selesai'
                    ? ($ticket->closed_at ?: $now)
                    : null,
            ]);

            TiketKerusakanLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'from_status' => $fromStatus,
                'to_status' => $nextStatus,
                'note' => $request->admin_notes,
                'created_at' => $now,
            ]);
        });

        $this->logAudit('update', 'tiket_kerusakan', $ticket->id, [
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'nomor_bmn' => $ticket->nomor_bmn,
        ]);

        return redirect()->back()->with('success', 'Tiket kerusakan berhasil diperbarui.');
    }

    public function resolve(Request $request, $id)
    {
        $request->validate([
            'resolusi'        => 'required|in:diperbaiki,dihapuskan,hilang,diabaikan',
            'catatan_resolusi' => 'nullable|string|max:2000',
        ]);

        $ticket = TiketKerusakan::findOrFail($id);

        if ($ticket->resolusi !== null) {
            return redirect()->back()->with('error', 'Tiket ini sudah memiliki resolusi sebelumnya.');
        }

        $now        = Carbon::now('Asia/Jakarta');
        $resolusi   = $request->resolusi;
        $fromStatus = $ticket->status;

        // Find associated barang by nomor_bmn (format: kode_barang-NNN)
        $barang = null;
        if ($ticket->nomor_bmn) {
            $barang = Barang::whereRaw(
                "CONCAT(kode_barang, '-', LPAD(nup, 3, '0')) = ?",
                [$ticket->nomor_bmn]
            )->first();
        }

        DB::transaction(function () use ($ticket, $request, $barang, $resolusi, $now, $fromStatus) {
            if ($barang) {
                $barangFields = [
                    'catatan_status'     => $request->catatan_resolusi,
                    'status_diupdate_at' => $now,
                    'status_diupdate_by' => Auth::id(),
                ];

                if ($resolusi === 'dihapuskan') {
                    $barangFields['status_barang'] = 'rusak_total';
                    $barangFields['ketersediaan']  = 'reparasi';
                } elseif ($resolusi === 'hilang') {
                    $barangFields['status_barang'] = 'hilang';
                    $barangFields['ketersediaan']  = 'hilang';
                } elseif ($resolusi === 'diperbaiki') {
                    $barangFields['status_barang']    = 'aktif';
                    $barangFields['kondisi_terakhir'] = 'baik';
                }

                $barang->update($barangFields);
            }

            $ticket->update([
                'resolusi'         => $resolusi,
                'catatan_resolusi' => $request->catatan_resolusi,
                'diselesaikan_at'  => $now,
                'diselesaikan_by'  => Auth::id(),
                'status'           => 'selesai',
                'closed_at'        => $now,
            ]);

            TiketKerusakanLog::create([
                'ticket_id'   => $ticket->id,
                'user_id'     => Auth::id(),
                'from_status' => $fromStatus,
                'to_status'   => 'selesai',
                'note'        => "Resolusi: {$resolusi}" . ($request->catatan_resolusi ? ". {$request->catatan_resolusi}" : ''),
                'created_at'  => $now,
            ]);
        });

        $this->logAudit('resolve', 'tiket_kerusakan', $ticket->id, [
            'nomor_bmn' => $ticket->nomor_bmn,
            'resolusi'  => $resolusi,
        ]);

        $resolusiLabel = [
            'diperbaiki' => 'Diperbaiki',
            'dihapuskan' => 'Dihapuskan (Rusak Total)',
            'hilang'     => 'Hilang',
            'diabaikan'  => 'Diabaikan',
        ][$resolusi];

        return redirect()->back()->with('success', "Tiket #{$ticket->id} diselesaikan: {$resolusiLabel}.");
    }
}
