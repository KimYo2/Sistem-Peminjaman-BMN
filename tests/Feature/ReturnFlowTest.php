<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\TiketKerusakan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReturnFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_return_item_without_damage(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 10, 0, 0, 'Asia/Jakarta'));

        $user = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 1,
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

        $response = $this->actingAs($user)->postJson(route('return.store'), [
            'nomor_bmn' => "{$barang->kode_barang}-{$barang->nup}",
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $histori->refresh();
        $barang->refresh();

        $this->assertSame('dikembalikan', $histori->status);
        $this->assertNotNull($histori->waktu_kembali);
        $this->assertSame('baik', $histori->kondisi_kembali);
        $this->assertNull($histori->catatan_kondisi);

        $this->assertSame('tersedia', $barang->ketersediaan);
        $this->assertSame('baik', $barang->kondisi_terakhir);
        $this->assertNotNull($barang->waktu_kembali);
    }

    public function test_user_return_with_damage_creates_ticket(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 12, 0, 0, 'Asia/Jakarta'));

        $user = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 2,
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
        ]);

        $response = $this->actingAs($user)->postJson(route('return.store'), [
            'nomor_bmn' => "{$barang->kode_barang}-{$barang->nup}",
            'is_damaged' => true,
            'jenis_kerusakan' => 'berat',
            'deskripsi' => 'Unit mati total.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $histori->refresh();
        $barang->refresh();

        $this->assertSame('dikembalikan', $histori->status);
        $this->assertNotNull($histori->waktu_kembali);
        $this->assertSame('rusak_berat', $histori->kondisi_kembali);
        $this->assertSame('Unit mati total.', $histori->catatan_kondisi);

        $this->assertSame('tersedia', $barang->ketersediaan);
        $this->assertSame('rusak_berat', $barang->kondisi_terakhir);
        $this->assertNotNull($barang->waktu_kembali);

        $ticket = TiketKerusakan::where('nomor_bmn', "{$barang->kode_barang}-{$barang->nup}")->first();
        $this->assertNotNull($ticket);
        $this->assertSame('berat', $ticket->jenis_kerusakan);
        $this->assertSame('Unit mati total.', $ticket->deskripsi);
        $this->assertSame('open', $ticket->status);
    }
}
