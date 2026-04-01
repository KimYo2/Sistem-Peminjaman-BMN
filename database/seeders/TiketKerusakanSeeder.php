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
            // Tiket open — belum diproses
            [
                'nomor_bmn' => '3100102001-019',
                'kode_barang' => '3100102001', 'nup' => 19,
                'histori_id' => null,
                'dilaporkan_oleh' => 3,
                'assigned_to' => null,
                'deskripsi' => 'Fan CPU berisik dan sering mati sendiri saat beban tinggi',
                'admin_notes' => null,
                'status' => 'open',
                'priority' => 'medium',
                'resolusi' => null, 'catatan_resolusi' => null,
                'diselesaikan_by' => null,
                'tanggal_lapor' => $now->copy()->subDays(3),
                'target_selesai_at' => null,
                'diselesaikan_at' => null,
                'closed_at' => null,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
            // Tiket open — prioritas tinggi
            [
                'nomor_bmn' => '3100102001-032',
                'kode_barang' => '3100102001', 'nup' => 32,
                'histori_id' => null,
                'dilaporkan_oleh' => 6,
                'assigned_to' => null,
                'deskripsi' => 'Layar monitor berkedip-kedip, tidak bisa digunakan untuk bekerja',
                'admin_notes' => null,
                'status' => 'open',
                'priority' => 'high',
                'resolusi' => null, 'catatan_resolusi' => null,
                'diselesaikan_by' => null,
                'tanggal_lapor' => $now->copy()->subDays(1),
                'target_selesai_at' => null,
                'diselesaikan_at' => null,
                'closed_at' => null,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            // Tiket sedang diproses
            [
                'nomor_bmn' => '3100203003-019',
                'kode_barang' => '3100203003', 'nup' => 19,
                'histori_id' => null,
                'dilaporkan_oleh' => 4,
                'assigned_to' => 1,
                'deskripsi' => 'Printer sering paper jam dan hasil cetak bergaris',
                'admin_notes' => 'Sedang menunggu suku cadang dari vendor.',
                'status' => 'diproses',
                'priority' => 'medium',
                'resolusi' => null, 'catatan_resolusi' => null,
                'diselesaikan_by' => null,
                'tanggal_lapor' => $now->copy()->subDays(7),
                'target_selesai_at' => $now->copy()->addDays(3),
                'diselesaikan_at' => null,
                'closed_at' => null,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(5),
            ],
            // Tiket selesai — diperbaiki
            [
                'nomor_bmn' => '3100102002-029',
                'kode_barang' => '3100102002', 'nup' => 29,
                'histori_id' => null,
                'dilaporkan_oleh' => 5,
                'assigned_to' => 1,
                'deskripsi' => 'Keyboard laptop beberapa tombol tidak berfungsi',
                'admin_notes' => 'Keyboard diganti unit baru.',
                'status' => 'selesai',
                'priority' => 'low',
                'resolusi' => 'diperbaiki',
                'catatan_resolusi' => 'Keyboard sudah diganti dengan unit baru, berfungsi normal',
                'diselesaikan_by' => 1,
                'tanggal_lapor' => $now->copy()->subDays(14),
                'target_selesai_at' => $now->copy()->subDays(7),
                'diselesaikan_at' => $now->copy()->subDays(2),
                'closed_at' => $now->copy()->subDays(2),
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now->copy()->subDays(2),
            ],
            // Tiket selesai — dari proses pengembalian
            [
                'nomor_bmn' => '3100203003-012',
                'kode_barang' => '3100203003', 'nup' => 12,
                'histori_id' => 3,
                'dilaporkan_oleh' => 5,
                'assigned_to' => 2,
                'deskripsi' => 'Ada goresan pada badan printer saat dikembalikan',
                'admin_notes' => null,
                'status' => 'selesai',
                'priority' => 'low',
                'resolusi' => 'diabaikan',
                'catatan_resolusi' => 'Goresan minor, tidak mempengaruhi fungsi printer',
                'diselesaikan_by' => 2,
                'tanggal_lapor' => $now->copy()->subDays(10),
                'target_selesai_at' => null,
                'diselesaikan_at' => $now->copy()->subDays(8),
                'closed_at' => $now->copy()->subDays(8),
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(8),
            ],
            // Tiket selesai — dihapuskan (barang rusak total)
            [
                'nomor_bmn' => '3100102001-010',
                'kode_barang' => '3100102001', 'nup' => 10,
                'histori_id' => null,
                'dilaporkan_oleh' => 2,
                'assigned_to' => 1,
                'deskripsi' => 'Motherboard mati total, tidak bisa dinyalakan. Sudah dicoba ganti PSU tetap tidak hidup.',
                'admin_notes' => 'Biaya perbaikan melebihi nilai aset.',
                'status' => 'selesai',
                'priority' => 'high',
                'resolusi' => 'dihapuskan',
                'catatan_resolusi' => 'Biaya perbaikan melebihi nilai aset, direkomendasikan untuk penghapusan BMN',
                'diselesaikan_by' => 1,
                'tanggal_lapor' => $now->copy()->subDays(25),
                'target_selesai_at' => $now->copy()->subDays(18),
                'diselesaikan_at' => $now->copy()->subDays(15),
                'closed_at' => $now->copy()->subDays(15),
                'created_at' => $now->copy()->subDays(25),
                'updated_at' => $now->copy()->subDays(15),
            ],
            // Tiket selesai — hilang
            [
                'nomor_bmn' => '3100102001-029',
                'kode_barang' => '3100102001', 'nup' => 29,
                'histori_id' => null,
                'dilaporkan_oleh' => 1,
                'assigned_to' => 1,
                'deskripsi' => 'Barang tidak ditemukan saat stock opname tahunan. Terakhir tercatat di Gudang BMN.',
                'admin_notes' => 'BAP kehilangan sedang diproses.',
                'status' => 'selesai',
                'priority' => 'high',
                'resolusi' => 'hilang',
                'catatan_resolusi' => 'Telah dilaporkan ke pimpinan. Proses BAP kehilangan sedang berjalan.',
                'diselesaikan_by' => 1,
                'tanggal_lapor' => $now->copy()->subDays(20),
                'target_selesai_at' => null,
                'diselesaikan_at' => $now->copy()->subDays(10),
                'closed_at' => $now->copy()->subDays(10),
                'created_at' => $now->copy()->subDays(20),
                'updated_at' => $now->copy()->subDays(10),
            ],
        ]);
    }
}
