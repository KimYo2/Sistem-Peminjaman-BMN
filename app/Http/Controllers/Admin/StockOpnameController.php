<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\StockOpnameItem;
use App\Models\StockOpnameSession;
use App\Services\BmnParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    use LogsAudit;

    public function index()
    {
        $runningSession = StockOpnameSession::query()
            ->where('status', StockOpnameSession::STATUS_BERJALAN)
            ->latest('id')
            ->first();

        $sessions = StockOpnameSession::query()
            ->with('starter:id,nama')
            ->withCount([
                'items as total_items',
                'items as found_items' => function ($query) {
                    $query->where('status', StockOpnameItem::STATUS_FOUND);
                },
            ])
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.opname.index', compact('runningSession', 'sessions'));
    }

    public function start(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'notes' => 'nullable|string|max:255',
        ]);

        $hasRunningSession = StockOpnameSession::query()
            ->where('status', StockOpnameSession::STATUS_BERJALAN)
            ->exists();

        if ($hasRunningSession) {
            return redirect()->back()
                ->withErrors(['status' => 'Masih ada sesi stock opname yang berjalan.'])
                ->withInput();
        }

        $now = Carbon::now('Asia/Jakarta');

        $session = DB::transaction(function () use ($validated, $now) {
            $session = StockOpnameSession::create([
                'nama' => $validated['nama'],
                'status' => StockOpnameSession::STATUS_BERJALAN,
                'started_by' => Auth::id(),
                'started_at' => $now,
                'notes' => $validated['notes'] ?? null,
            ]);

            Barang::query()
                ->select(['kode_barang', 'nup', 'kondisi_terakhir'])
                ->orderBy('id')
                ->chunk(500, function ($rows) use ($session, $now) {
                    $payload = [];
                    foreach ($rows as $row) {
                        $payload[] = [
                            'session_id' => $session->id,
                            'kode_barang' => $row->kode_barang,
                            'nup' => $row->nup,
                            'status' => StockOpnameItem::STATUS_MISSING,
                            'expected_kondisi' => $row->kondisi_terakhir,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if ($payload !== []) {
                        StockOpnameItem::insert($payload);
                    }
                });

            return $session;
        });

        $this->logAudit('start', 'stock_opname', $session->id, [
            'nama' => $session->nama,
        ]);

        return redirect()->route('admin.opname.show', $session->id)
            ->with('success', 'Sesi stock opname berhasil dimulai.');
    }

    public function show(Request $request, $id)
    {
        $session = StockOpnameSession::query()
            ->with('starter:id,nama')
            ->findOrFail($id);

        $itemsQuery = StockOpnameItem::query()
            ->where('session_id', $session->id)
            ->with('scanner:id,nama');

        if ($request->filled('status') && in_array($request->status, ['found', 'missing'], true)) {
            $itemsQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $itemsQuery->where(function ($query) use ($search) {
                $query->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nup', 'like', "%{$search}%");
            });
        }

        $items = $itemsQuery
            ->orderByRaw("CASE WHEN status = 'found' THEN 0 ELSE 1 END")
            ->orderByDesc('scanned_at')
            ->orderBy('kode_barang')
            ->orderBy('nup')
            ->paginate(20)
            ->withQueryString();

        $stats = $this->sessionStats($session->id);

        return view('admin.opname.show', compact('session', 'items', 'stats'));
    }

    public function scan(Request $request, $id)
    {
        $session = StockOpnameSession::query()->findOrFail($id);

        if ($session->status !== StockOpnameSession::STATUS_BERJALAN) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi stock opname sudah selesai.',
            ], 422);
        }

        $request->validate([
            'nomor_bmn' => 'required|string|max:100',
        ]);

        try {
            $parsed = BmnParser::parse($request->nomor_bmn, true);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        $item = StockOpnameItem::query()
            ->where('session_id', $session->id)
            ->where('kode_barang', $parsed['kode_barang'])
            ->where('nup', $parsed['nup'])
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak termasuk dalam sesi stock opname ini.',
            ], 404);
        }

        if ($item->status === StockOpnameItem::STATUS_FOUND) {
            return response()->json([
                'success' => true,
                'duplicate' => true,
                'message' => 'Barang sudah tercatat sebelumnya.',
                'item' => $item->nomor_bmn,
                'stats' => $this->sessionStats($session->id),
            ]);
        }

        $barang = Barang::query()
            ->where('kode_barang', $item->kode_barang)
            ->where('nup', $item->nup)
            ->first();

        $item->update([
            'status' => StockOpnameItem::STATUS_FOUND,
            'expected_kondisi' => $item->expected_kondisi ?: ($barang->kondisi_terakhir ?? null),
            'actual_kondisi' => $barang->kondisi_terakhir ?? $item->expected_kondisi,
            'scanned_by' => Auth::id(),
            'scanned_at' => Carbon::now('Asia/Jakarta'),
        ]);

        $this->logAudit('scan', 'stock_opname', $session->id, [
            'kode_barang' => $item->kode_barang,
            'nup' => $item->nup,
        ]);

        return response()->json([
            'success' => true,
            'duplicate' => false,
            'message' => 'Barang berhasil ditandai ditemukan.',
            'item' => $item->nomor_bmn,
            'stats' => $this->sessionStats($session->id),
        ]);
    }

    public function finish(Request $request, $id)
    {
        $session = StockOpnameSession::query()->findOrFail($id);

        if ($session->status !== StockOpnameSession::STATUS_BERJALAN) {
            return redirect()->back()->withErrors(['status' => 'Sesi stock opname ini sudah ditutup.']);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:255',
        ]);

        $session->update([
            'status' => StockOpnameSession::STATUS_SELESAI,
            'finished_at' => Carbon::now('Asia/Jakarta'),
            'notes' => $validated['notes'] ?? $session->notes,
        ]);

        $stats = $this->sessionStats($session->id);

        $this->logAudit('finish', 'stock_opname', $session->id, $stats);

        return redirect()->route('admin.opname.show', $session->id)
            ->with('success', "Sesi stock opname selesai. Ditemukan {$stats['found']} dari {$stats['total']} barang.");
    }

    public function export($id)
    {
        $session = StockOpnameSession::query()->findOrFail($id);

        $query = StockOpnameItem::query()
            ->from('stock_opname_items as soi')
            ->where('soi.session_id', $session->id)
            ->leftJoin('barang as b', function ($join) {
                $join->on('b.kode_barang', '=', 'soi.kode_barang')
                    ->on('b.nup', '=', 'soi.nup');
            })
            ->leftJoin('users as u', 'u.id', '=', 'soi.scanned_by')
            ->select([
                'soi.kode_barang',
                'soi.nup',
                'b.brand',
                'b.tipe',
                'soi.status',
                'soi.expected_kondisi',
                'soi.actual_kondisi',
                'soi.scanned_at',
                'u.nama as scanner_nama',
            ])
            ->orderBy('soi.kode_barang')
            ->orderBy('soi.nup');

        $this->logAudit('export', 'stock_opname', $session->id, [
            'format' => 'csv',
        ]);

        $filename = 'stock_opname_' . $session->id . '_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query, $session) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            $csvSafe = function ($value): string {
                if ($value === null) {
                    return '';
                }

                $value = (string) $value;
                if ($value === '') {
                    return '';
                }

                if (preg_match('/^[=+\-@]/', $value)) {
                    return "'" . $value;
                }

                return $value;
            };

            fputcsv($handle, [
                'Sesi',
                'Nomor BMN',
                'Brand',
                'Tipe',
                'Status Opname',
                'Kondisi Ekspektasi',
                'Kondisi Aktual',
                'Waktu Scan',
                'Scanner',
            ]);

            $query->chunk(200, function ($rows) use ($handle, $csvSafe, $session) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $csvSafe($session->nama),
                        $csvSafe($row->kode_barang . '-' . $row->nup),
                        $csvSafe($row->brand),
                        $csvSafe($row->tipe),
                        $csvSafe($row->status === StockOpnameItem::STATUS_FOUND ? 'ditemukan' : 'belum ditemukan'),
                        $csvSafe($row->expected_kondisi),
                        $csvSafe($row->actual_kondisi),
                        $row->scanned_at ? Carbon::parse($row->scanned_at)->format('Y-m-d H:i:s') : '',
                        $csvSafe($row->scanner_nama),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    private function sessionStats(int $sessionId): array
    {
        $total = StockOpnameItem::query()
            ->where('session_id', $sessionId)
            ->count();

        $found = StockOpnameItem::query()
            ->where('session_id', $sessionId)
            ->where('status', StockOpnameItem::STATUS_FOUND)
            ->count();

        return [
            'total' => $total,
            'found' => $found,
            'missing' => max($total - $found, 0),
        ];
    }
}
