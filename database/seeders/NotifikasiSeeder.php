<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notifikasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('notifikasi')->insert([
            // === Notifikasi untuk Budi (user 3) ===
            [
                'user_id' => 3,
                'judul' => 'Peminjaman Disetujui',
                'pesan' => 'Peminjaman PC Desktop Lenovo (NUP 13) telah disetujui oleh Admin.',
                'type' => 'approval',
                'is_read' => true,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 5,
                'created_at' => $now->copy()->subDays(4),
            ],
            [
                'user_id' => 3,
                'judul' => 'Pengingat Jatuh Tempo',
                'pesan' => 'Peminjaman PC Desktop Lenovo (NUP 13) akan jatuh tempo dalam 3 hari.',
                'type' => 'info',
                'is_read' => false,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 5,
                'created_at' => $now->copy()->subDays(1),
            ],

            // === Notifikasi untuk Siti (user 4) ===
            [
                'user_id' => 4,
                'judul' => 'Peminjaman Disetujui',
                'pesan' => 'Peminjaman Laptop HP (NUP 22) telah disetujui oleh Admin.',
                'type' => 'approval',
                'is_read' => true,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 6,
                'created_at' => $now->copy()->subDays(2),
            ],

            // === Notifikasi untuk Fajar (user 7) ===
            [
                'user_id' => 7,
                'judul' => 'Peminjaman Terlambat',
                'pesan' => 'Peminjaman PC Desktop Lenovo (NUP 12) sudah melewati jatuh tempo. Segera kembalikan barang.',
                'type' => 'overdue',
                'is_read' => false,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 10,
                'created_at' => $now->copy()->subDays(5),
            ],
            [
                'user_id' => 7,
                'judul' => 'Perpanjangan Menunggu Persetujuan',
                'pesan' => 'Pengajuan perpanjangan 7 hari untuk Laptop Asus (NUP 25) sedang menunggu persetujuan.',
                'type' => 'info',
                'is_read' => true,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 7,
                'created_at' => $now->copy()->subDays(1),
            ],

            // === Notifikasi untuk Rina (user 6) ===
            [
                'user_id' => 6,
                'judul' => 'Perpanjangan Disetujui',
                'pesan' => 'Perpanjangan 7 hari untuk PC Desktop Lenovo ThinkCentre M720t (NUP 38) telah disetujui.',
                'type' => 'approval',
                'is_read' => true,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 8,
                'created_at' => $now->copy()->subDays(1),
            ],

            // === Notifikasi untuk Admin (user 1) ===
            [
                'user_id' => 1,
                'judul' => 'Pengajuan Peminjaman Baru',
                'pesan' => 'Ahmad Wijaya mengajukan peminjaman Scanner Fujitsu (NUP 2). Menunggu persetujuan Anda.',
                'type' => 'approval',
                'is_read' => false,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 11,
                'created_at' => $now->copy()->subHours(6),
            ],
            [
                'user_id' => 1,
                'judul' => 'Pengajuan Peminjaman Baru',
                'pesan' => 'Budi Santoso mengajukan peminjaman PC Desktop Dell (NUP 45). Menunggu persetujuan Anda.',
                'type' => 'approval',
                'is_read' => false,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 12,
                'created_at' => $now->copy()->subHours(3),
            ],
            [
                'user_id' => 1,
                'judul' => 'Perpanjangan Menunggu Persetujuan',
                'pesan' => 'Fajar Prasetyo mengajukan perpanjangan 7 hari untuk Laptop Asus (NUP 25).',
                'type' => 'approval',
                'is_read' => false,
                'related_model' => 'HistoriPeminjaman',
                'related_id' => 7,
                'created_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id' => 1,
                'judul' => 'Laporan Kerusakan Baru',
                'pesan' => 'Budi Santoso melaporkan kerusakan pada PC Desktop Lenovo (NUP 19): Fan CPU berisik.',
                'type' => 'info',
                'is_read' => true,
                'related_model' => 'TiketKerusakan',
                'related_id' => 1,
                'created_at' => $now->copy()->subDays(3),
            ],
            [
                'user_id' => 1,
                'judul' => 'Laporan Kerusakan Prioritas Tinggi',
                'pesan' => 'Rina Wulandari melaporkan kerusakan prioritas TINGGI pada PC Desktop Dell (NUP 32).',
                'type' => 'info',
                'is_read' => false,
                'related_model' => 'TiketKerusakan',
                'related_id' => 2,
                'created_at' => $now->copy()->subDays(1),
            ],

            // === Notifikasi waitlist ===
            [
                'user_id' => 5, // Ahmad
                'judul' => 'Barang Tersedia dari Waitlist',
                'pesan' => 'Laptop HP 240 G8 yang Anda tunggu kini tersedia untuk dipinjam.',
                'type' => 'waitlist',
                'is_read' => false,
                'related_model' => 'Barang',
                'related_id' => null,
                'created_at' => $now->copy()->subHours(12),
            ],
        ]);
    }
}
