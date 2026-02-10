<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RejectFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_reject_pending_borrow_request(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 9, 0, 0, 'Asia/Jakarta'));

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
            'nup' => 3,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'tersedia',
        ]);

        $histori = HistoriPeminjaman::create([
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
            'nip_peminjam' => $user->nip,
            'nama_peminjam' => $user->nama,
            'status' => 'menunggu',
            'waktu_pengajuan' => Carbon::now('Asia/Jakarta'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.histori.reject', $histori->id), [
            'rejection_reason' => 'Barang dibutuhkan untuk kegiatan internal.',
        ]);

        $response->assertRedirect();

        $histori->refresh();
        $barang->refresh();

        $this->assertSame('ditolak', $histori->status);
        $this->assertSame($admin->id, $histori->approved_by);
        $this->assertNotNull($histori->rejected_at);
        $this->assertSame('Barang dibutuhkan untuk kegiatan internal.', $histori->rejection_reason);

        $this->assertSame('tersedia', $barang->ketersediaan);
    }

    public function test_admin_cannot_reject_when_status_is_not_pending(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 9, 30, 0, 'Asia/Jakarta'));

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
            'nup' => 4,
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

        $response = $this->actingAs($admin)->post(route('admin.histori.reject', $histori->id), [
            'rejection_reason' => 'Tidak bisa ditolak.',
        ]);

        $response->assertSessionHasErrors('status');

        $histori->refresh();
        $barang->refresh();

        $this->assertSame('dipinjam', $histori->status);
        $this->assertNull($histori->rejected_at);
        $this->assertNull($histori->rejection_reason);
        $this->assertSame('dipinjam', $barang->ketersediaan);
    }
}
