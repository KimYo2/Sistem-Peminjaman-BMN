<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExportHistoriTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_histori_csv(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 11, 0, 0, 'Asia/Jakarta'));

        $admin = User::create([
            'nip' => '198001012006041001',
            'nama' => 'Admin BPS',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'nip' => '199001012015041001',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'kode_barang' => '3100102001',
            'nup' => 5,
            'brand' => 'LENOVO',
            'tipe' => 'THINK CENTRE M80',
            'kondisi_terakhir' => 'baik',
            'ketersediaan' => 'dipinjam',
        ]);

        HistoriPeminjaman::create([
            'kode_barang' => $barang->kode_barang,
            'nup' => $barang->nup,
            'nip_peminjam' => $user->nip,
            'nama_peminjam' => $user->nama,
            'status' => 'dipinjam',
            'waktu_pengajuan' => Carbon::now('Asia/Jakarta')->subHours(2),
            'waktu_pinjam' => Carbon::now('Asia/Jakarta')->subHour(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.histori.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('Kode Barang', $content);
        $this->assertStringContainsString((string) $barang->kode_barang, $content);
        $this->assertStringContainsString((string) $barang->nup, $content);
        $this->assertStringContainsString('Budi Santoso', $content);
    }
}
