<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExtendFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_and_admin_can_approve_extension(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 8, 0, 0, 'Asia/Jakarta'));

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
            'nup' => 6,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'dipinjam',
            'peminjam_terakhir' => $user->nama,
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subDays(2),
        ]);

        $histori = HistoriPeminjaman::create([
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
            'nip_peminjam' => $user->nip,
            'nama_peminjam' => $user->nama,
            'status' => 'dipinjam',
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subDays(2),
            'tanggal_jatuh_tempo' => Carbon::now('Asia/Jakarta')->addDays(3),
        ]);

        $requestResponse = $this->actingAs($user)->post(route('user.histori.extend', $histori->id), [
            'hari' => 7,
            'alasan' => 'Perlu tambahan waktu.',
        ]);

        $requestResponse->assertRedirect();

        $histori->refresh();
        $this->assertSame('menunggu', $histori->perpanjangan_status);
        $this->assertSame(7, $histori->perpanjangan_hari);
        $this->assertSame('Perlu tambahan waktu.', $histori->perpanjangan_alasan);

        $approveResponse = $this->actingAs($admin)->post(route('admin.histori.extend.approve', $histori->id));
        $approveResponse->assertRedirect();

        $histori->refresh();
        $this->assertSame('disetujui', $histori->perpanjangan_status);
        $this->assertSame($admin->id, $histori->perpanjangan_disetujui_by);
        $this->assertNotNull($histori->perpanjangan_disetujui_at);
        $this->assertSame(
            Carbon::now('Asia/Jakarta')->addDays(10)->toDateString(),
            Carbon::parse($histori->tanggal_jatuh_tempo)->toDateString()
        );
    }
}
