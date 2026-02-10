<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BorrowFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_and_admin_can_approve_borrow(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 10, 0, 0, 'Asia/Jakarta'));

        $user = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $admin = User::create([
            'nip' => '198001012006041001',
            'nama' => 'Admin BPS',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $barang = Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 1,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'tersedia',
        ]);

        $nomorBmn = "{$barang->kode_barang}-{$barang->nup}";

        $response = $this->actingAs($user)->postJson(route('user.barang.borrow'), [
            'nomor_bmn' => $nomorBmn,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $histori = HistoriPeminjaman::where('kode_barang', $barang->kode_barang)
            ->where('nup', $barang->nup)
            ->where('nip_peminjam', $user->nip)
            ->first();

        $this->assertNotNull($histori);
        $this->assertSame('menunggu', $histori->status);

        $approveResponse = $this->actingAs($admin)
            ->post(route('admin.histori.approve', $histori->id));

        $approveResponse->assertRedirect();

        $histori->refresh();
        $barang->refresh();

        $this->assertSame('dipinjam', $histori->status);
        $this->assertSame($admin->id, $histori->approved_by);
        $this->assertNotNull($histori->approved_at);
        $this->assertNotNull($histori->waktu_pinjam);
        $this->assertNotNull($histori->tanggal_jatuh_tempo);

        $this->assertSame('dipinjam', $barang->ketersediaan);
        $this->assertSame($user->nama, $barang->peminjam_terakhir);
        $this->assertNotNull($barang->waktu_pinjam);
        $this->assertNull($barang->waktu_kembali);
    }

    public function test_admin_cannot_approve_when_status_is_not_pending(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 10, 30, 0, 'Asia/Jakarta'));

        $user = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $admin = User::create([
            'nip' => '198001012006041001',
            'nama' => 'Admin BPS',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $barang = Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 2,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'dipinjam',
            'peminjam_terakhir' => $user->nama,
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subDays(1),
        ]);

        $histori = HistoriPeminjaman::create([
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
            'nip_peminjam' => $user->nip,
            'nama_peminjam' => $user->nama,
            'status' => 'dipinjam',
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subDays(1),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.histori.approve', $histori->id));

        $response->assertSessionHasErrors('status');

        $histori->refresh();
        $barang->refresh();

        $this->assertSame('dipinjam', $histori->status);
        $this->assertNull($histori->approved_by);
        $this->assertNull($histori->approved_at);
        $this->assertSame('dipinjam', $barang->ketersediaan);
    }
}
