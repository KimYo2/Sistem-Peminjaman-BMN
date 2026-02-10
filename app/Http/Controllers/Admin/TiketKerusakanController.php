<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
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
            ->with('assignee')
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

        $tickets = $query->paginate(15)->withQueryString();
        $admins = User::where('role', 'admin')->orderBy('nama')->get(['id', 'nama', 'nip']);

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
}
