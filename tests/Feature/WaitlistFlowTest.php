<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\User;
use App\Models\Waitlist;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WaitlistFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_waitlist_is_fifo_and_first_user_gets_pending_request_on_return(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 14, 0, 0, 'Asia/Jakarta'));

        $borrower = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Peminjam Aktif',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $waiterOne = User::create([
            'nip' => '199001012015041002',
            'nama' => 'Antrian Satu',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $waiterTwo = User::create([
            'nip' => '199001012015041003',
            'nama' => 'Antrian Dua',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 7,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'dipinjam',
            'peminjam_terakhir' => $borrower->nama,
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subDays(1),
        ]);

        HistoriPeminjaman::create([
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
            'nip_peminjam' => $borrower->nip,
            'nama_peminjam' => $borrower->nama,
            'status' => 'dipinjam',
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subDays(1),
        ]);

        $this->actingAs($waiterOne)
            ->post(route('user.waitlist.join', "{$barang->kode_barang}-{$barang->nup}"))
            ->assertRedirect();

        Carbon::setTestNow(Carbon::create(2026, 2, 10, 14, 5, 0, 'Asia/Jakarta'));

        $this->actingAs($waiterTwo)
            ->post(route('user.waitlist.join', "{$barang->kode_barang}-{$barang->nup}"))
            ->assertRedirect();

        Carbon::setTestNow(Carbon::create(2026, 2, 10, 15, 0, 0, 'Asia/Jakarta'));

        $this->actingAs($borrower)
            ->postJson(route('return.store'), [
                'nomor_bmn' => "{$barang->kode_barang}-{$barang->nup}",
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $firstEntry = Waitlist::where('kode_barang', $barang->kode_barang)
            ->where('nup', $barang->nup)
            ->where('nip_peminjam', $waiterOne->nip)
            ->first();

        $secondEntry = Waitlist::where('kode_barang', $barang->kode_barang)
            ->where('nup', $barang->nup)
            ->where('nip_peminjam', $waiterTwo->nip)
            ->first();

        $this->assertNotNull($firstEntry);
        $this->assertNotNull($secondEntry);
        $this->assertSame('fulfilled', $firstEntry->status);
        $this->assertNotNull($firstEntry->notified_at);
        $this->assertSame('aktif', $secondEntry->status);

        $waiterOnePending = HistoriPeminjaman::where('kode_barang', $barang->kode_barang)
            ->where('nup', $barang->nup)
            ->where('nip_peminjam', $waiterOne->nip)
            ->where('status', 'menunggu')
            ->exists();

        $waiterTwoPending = HistoriPeminjaman::where('kode_barang', $barang->kode_barang)
            ->where('nup', $barang->nup)
            ->where('nip_peminjam', $waiterTwo->nip)
            ->where('status', 'menunggu')
            ->exists();

        $this->assertTrue($waiterOnePending);
        $this->assertFalse($waiterTwoPending);
    }
}
