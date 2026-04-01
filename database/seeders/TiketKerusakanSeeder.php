<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TiketKerusakanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tiket_kerusakan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('tiket_kerusakan')->insert([
            // Tiket baru — belum diproses
            [
                'kode_barang' => '3100102001', 'nup' => 19,
                'histori_id' => null,
                'dilaporkan_oleh' => 3, // Budi
                'deskripsi' => 'Fan CPU berisik dan sering mati sendiri saat beban tinggi',
                'status' => 'baru',
                'prioritas' => 'sedang',
                'resolusi' => null, 'catatan_resolusi' => null,
                'diselesaikan_at' => null, 'diselesaikan_by' => null,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
            // Tiket baru — prioritas tinggi
            [
                'kode_barang' => '3100102001', 'nup' => 32,
                'histori_id' => null,
                'dilaporkan_oleh' => 6, // Rina
                'deskripsi' => 'Layar monitor berkedip-kedip, tidak bisa digunakan untuk bekerja',
                'status' => 'baru',
                'prioritas' => 'tinggi',
                'resolusi' => null, 'catatan_resolusi' => null,
                'diselesaikan_at' => null, 'diselesaikan_by' => null,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            // Tiket sedang diproses
            [
                'kode_barang' => '3100203003', 'nup' => 19,
                'histori_id' => null,
                'dilaporkan_oleh' => 4, // Siti
                'deskripsi' => 'Printer sering paper jam dan hasil cetak bergaris',
                'status' => 'diproses',
                'prioritas' => 'sedang',
                'resolusi' => null, 'catatan_resolusi' => null,
                'diselesaikan_at' => null, 'diselesaikan_by' => null,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(5),
            ],
            // Tiket selesai — diperbaiki
            [
                'kode_barang' => '3100102002', 'nup' => 29,
                'histori_id' => null,
                'dilaporkan_oleh' => 5, // Ahmad
                'deskripsi' => 'Keyboard laptop beberapa tombol tidak berfungsi',
                'status' => 'selesai',
                'prioritas' => 'rendah',
                'resolusi' => 'diperbaiki',
                'catatan_resolusi' => 'Keyboard sudah diganti dengan unit baru, berfungsi normal',
                'diselesaikan_at' => $now->copy()->subDays(2),
                'diselesaikan_by' => 1, // Admin
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now->copy()->subDays(2),
            ],
            // Tiket selesai — dari proses pengembalian (linked to histori_id 3)
            [
                'kode_barang' => '3100203003', 'nup' => 12,
                'histori_id' => 3,
                'dilaporkan_oleh' => 5, // Ahmad (pelapor saat return)
                'deskripsi' => 'Ada goresan pada badan printer saat dikembalikan',
                'status' => 'selesai',
                'prioritas' => 'rendah',
                'resolusi' => 'diabaikan',
                'catatan_resolusi' => 'Goresan minor, tidak mempengaruhi fungsi printer',
                'diselesaikan_at' => $now->copy()->subDays(8),
                'diselesaikan_by' => 2, // Dewi
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(8),
            ],
            // Tiket selesai — dihapuskan (barang rusak total)
            [
                'kode_barang' => '3100102001', 'nup' => 10,
                'histori_id' => null,
                'dilaporkan_oleh' => 2, // Dewi
                'deskripsi' => 'Motherboard mati total, tidak bisa dinyalakan. Sudah dicoba ganti PSU tetap tidak hidup.',
                'status' => 'selesai',
                'prioritas' => 'tinggi',
                'resolusi' => 'dihapuskan',
                'catatan_resolusi' => 'Biaya perbaikan melebihi nilai aset, direkomendasikan untuk penghapusan BMN',
                'diselesaikan_at' => $now->copy()->subDays(15),
                'diselesaikan_by' => 1,
                'created_at' => $now->copy()->subDays(25),
                'updated_at' => $now->copy()->subDays(15),
            ],
            // Tiket selesai — hilang
            [
                'kode_barang' => '3100102001', 'nup' => 29,
                'histori_id' => null,
                'dilaporkan_oleh' => 1, // Admin
                'deskripsi' => 'Barang tidak ditemukan saat stock opname tahunan. Terakhir tercatat di Gudang BMN.',
                'status' => 'selesai',
                'prioritas' => 'tinggi',
                'resolusi' => 'hilang',
                'catatan_resolusi' => 'Telah dilaporkan ke pimpinan. Proses BAP kehilangan sedang berjalan.',
                'diselesaikan_at' => $now->copy()->subDays(10),
                'diselesaikan_by' => 1,
                'created_at' => $now->copy()->subDays(20),
                'updated_at' => $now->copy()->subDays(10),
            ],
        ]);
    }
}
