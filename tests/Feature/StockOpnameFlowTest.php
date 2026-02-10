<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\StockOpnameItem;
use App\Models\StockOpnameSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StockOpnameFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_run_stock_opname_from_start_to_finish(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 9, 0, 0, 'Asia/Jakarta'));

        $admin = User::create([
            'nip' => '198001012006041001',
            'nama' => 'Admin BPS',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 1,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'tersedia',
        ]);

        Barang::create([
            'kode_barang' => '3100102002',
            'nup' => 2,
            'brand' => 'DELL',
            'tipe' => 'OPTIPLEX 7090',
            'kondisi_terakhir' => 'rusak_ringan',
            'ketersediaan' => 'tersedia',
        ]);

        $startResponse = $this->actingAs($admin)->post(route('admin.opname.start'), [
            'nama' => 'Opname Lab Komputer',
            'notes' => 'Lantai 2',
        ]);

        $startResponse->assertRedirect();

        $session = StockOpnameSession::query()->first();
        $this->assertNotNull($session);
        $this->assertSame('berjalan', $session->status);
        $this->assertSame('Opname Lab Komputer', $session->nama);
        $this->assertSame(2, StockOpnameItem::query()->where('session_id', $session->id)->count());

        $scanResponse = $this->actingAs($admin)->postJson(route('admin.opname.scan', $session->id), [
            'nomor_bmn' => '3100102001-1',
        ]);

        $scanResponse->assertOk();
        $scanResponse->assertJson([
            'success' => true,
            'duplicate' => false,
        ]);
        $scanResponse->assertJsonPath('stats.total', 2);
        $scanResponse->assertJsonPath('stats.found', 1);
        $scanResponse->assertJsonPath('stats.missing', 1);

        $item = StockOpnameItem::query()
            ->where('session_id', $session->id)
            ->where('kode_barang', '3100102001')
            ->where('nup', 1)
            ->first();

        $this->assertNotNull($item);
        $this->assertSame('found', $item->status);
        $this->assertSame($admin->id, $item->scanned_by);
        $this->assertNotNull($item->scanned_at);

        $finishResponse = $this->actingAs($admin)->post(route('admin.opname.finish', $session->id));
        $finishResponse->assertRedirect(route('admin.opname.show', $session->id));

        $session->refresh();
        $this->assertSame('selesai', $session->status);
        $this->assertNotNull($session->finished_at);
    }

    public function test_scan_rejected_after_session_closed(): void
    {
        $admin = User::create([
            'nip' => '198001012006041001',
            'nama' => 'Admin BPS',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 1,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'tersedia',
        ]);

        $session = StockOpnameSession::create([
            'nama' => 'Opname Selesai',
            'status' => 'selesai',
            'started_by' => $admin->id,
            'started_at' => Carbon::now('Asia/Jakarta')->subHour(),
            'finished_at' => Carbon::now('Asia/Jakarta'),
        ]);

        StockOpnameItem::create([
            'session_id' => $session->id,
            'kode_barang' => '3100102001',
            'nup' => 1,
            'status' => 'missing',
            'expected_kondisi' => 'baik',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.opname.scan', $session->id), [
            'nomor_bmn' => '3100102001-1',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_non_admin_cannot_access_stock_opname_page(): void
    {
        $user = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('admin.opname.index'));

        $response->assertForbidden();
    }
}
