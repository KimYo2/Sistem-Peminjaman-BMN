<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriPeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('histori_peminjaman')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Helpers: tanggal acuan per bulan (Januari–Maret 2026)
        $jan = fn(int $day, int $hour = 9) => Carbon::create(2026, 1, $day, $hour, 0, 0, 'Asia/Jakarta');
        $feb = fn(int $day, int $hour = 9) => Carbon::create(2026, 2, $day, $hour, 0, 0, 'Asia/Jakarta');
        $mar = fn(int $day, int $hour = 9) => Carbon::create(2026, 3, $day, $hour, 0, 0, 'Asia/Jakarta');

        // Users: 1=Admin, 2=Dewi(admin), 3=Budi, 4=Siti, 5=Ahmad, 6=Rina, 7=Fajar

        $histori = [
            // === Peminjaman yang sudah dikembalikan ===
            [
                'kode_barang' => '3100102002', 'nup' => 20,
                'nip_peminjam' => '199001012015041001', 'nama_peminjam' => 'Budi Santoso',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $jan(3),
                'waktu_pinjam' => $jan(4),
                'waktu_kembali' => $jan(11),
                'tanggal_jatuh_tempo' => $jan(18)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $jan(4, 10),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 23,
                'nip_peminjam' => '199505012018041001', 'nama_peminjam' => 'Siti Nurhaliza',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $jan(10),
                'waktu_pinjam' => $jan(11),
                'waktu_kembali' => $jan(20),
                'tanggal_jatuh_tempo' => $jan(25)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $jan(11, 10),
            ],
            [
                'kode_barang' => '3100203003', 'nup' => 12,
                'nip_peminjam' => '199808012020041001', 'nama_peminjam' => 'Ahmad Wijaya',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $jan(20),
                'waktu_pinjam' => $jan(21),
                'waktu_kembali' => $feb(3),
                'tanggal_jatuh_tempo' => $feb(4)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'rusak_ringan',
                'catatan_kondisi' => 'Ada goresan pada badan printer',
                'approved_by' => 2, 'approved_at' => $jan(21, 10),
            ],
            [
                'kode_barang' => '3100102001', 'nup' => 47,
                'nip_peminjam' => '199203152019041003', 'nama_peminjam' => 'Rina Wulandari',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $feb(5),
                'waktu_pinjam' => $feb(6),
                'waktu_kembali' => $feb(14),
                'tanggal_jatuh_tempo' => $feb(20)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $feb(6, 10),
            ],
            [
                'kode_barang' => '3100102001', 'nup' => 38,
                'nip_peminjam' => '199001012015041001', 'nama_peminjam' => 'Budi Santoso',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $feb(15),
                'waktu_pinjam' => $feb(16),
                'waktu_kembali' => $feb(28),
                'tanggal_jatuh_tempo' => $mar(1)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 2, 'approved_at' => $feb(16, 11),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 22,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $mar(1),
                'waktu_pinjam' => $mar(2),
                'waktu_kembali' => $mar(10),
                'tanggal_jatuh_tempo' => $mar(15)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $mar(2, 10),
            ],

            // === Peminjaman aktif (sedang dipinjam) ===
            [
                'kode_barang' => '3100102001', 'nup' => 13,
                'nip_peminjam' => '199001012015041001', 'nama_peminjam' => 'Budi Santoso',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $mar(18),
                'waktu_pinjam' => $mar(19),
                'tanggal_jatuh_tempo' => $mar(31)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $mar(19, 10),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 25,
                'nip_peminjam' => '199505012018041001', 'nama_peminjam' => 'Siti Nurhaliza',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $mar(20),
                'waktu_pinjam' => $mar(21),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(5)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 2, 'approved_at' => $mar(21, 10),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 27,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $mar(22),
                'waktu_pinjam' => $mar(23),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(2)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $mar(23, 10),
                'perpanjangan_status' => 'menunggu',
                'perpanjangan_hari' => 7,
                'perpanjangan_diajukan_at' => $now->copy()->subDays(1),
            ],
            [
                'kode_barang' => '3100203003', 'nup' => 17,
                'nip_peminjam' => '199808012020041001', 'nama_peminjam' => 'Ahmad Wijaya',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $mar(25),
                'waktu_pinjam' => $mar(26),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(14)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 2, 'approved_at' => $mar(26, 11),
            ],
            [
                'kode_barang' => '3100102001', 'nup' => 55,
                'nip_peminjam' => '199203152019041003', 'nama_peminjam' => 'Rina Wulandari',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $mar(27),
                'waktu_pinjam' => $mar(28),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(7)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $mar(28, 9),
                'perpanjangan_status' => 'disetujui',
                'perpanjangan_hari' => 7,
                'perpanjangan_diajukan_at' => $now->copy()->subDays(2),
                'perpanjangan_disetujui_by' => 1,
                'perpanjangan_disetujui_at' => $now->copy()->subDays(1),
            ],

            // === Terlambat (overdue) ===
            [
                'kode_barang' => '3100102001', 'nup' => 12,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'terlambat',
                'waktu_pengajuan' => $mar(5),
                'waktu_pinjam' => $mar(6),
                'tanggal_jatuh_tempo' => $mar(20)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $mar(6, 10),
            ],

            // === Menunggu persetujuan ===
            [
                'kode_barang' => '3100102001', 'nup' => 45,
                'nip_peminjam' => '199808012020041001', 'nama_peminjam' => 'Ahmad Wijaya',
                'status' => 'menunggu',
                'waktu_pengajuan' => $now->copy()->subHours(3),
            ],
            [
                'kode_barang' => '3100203004', 'nup' => 2,
                'nip_peminjam' => '199203152019041003', 'nama_peminjam' => 'Rina Wulandari',
                'status' => 'menunggu',
                'waktu_pengajuan' => $now->copy()->subHours(1),
            ],

            // === Ditolak ===
            [
                'kode_barang' => '3100102002', 'nup' => 26,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'ditolak',
                'waktu_pengajuan' => $feb(10),
                'rejected_at' => $feb(11),
                'rejection_reason' => 'Barang sedang digunakan untuk kebutuhan Kepala BPS',
                'approved_by' => 1,
            ],
        ];


        foreach ($histori as $row) {
            $data = array_merge([
                'waktu_pinjam' => null,
                'waktu_kembali' => null,
                'tanggal_jatuh_tempo' => null,
                'kondisi_awal' => null,
                'kondisi_kembali' => null,
                'catatan_kondisi' => null,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_at' => null,
                'rejection_reason' => null,
                'perpanjangan_status' => 'tidak_ada',
                'perpanjangan_hari' => null,
                'perpanjangan_diajukan_at' => null,
                'perpanjangan_disetujui_by' => null,
                'perpanjangan_disetujui_at' => null,
                'perpanjangan_ditolak_at' => null,
                'perpanjangan_reject_reason' => null,
                'created_at' => $row['waktu_pengajuan'] ?? $now,
                'updated_at' => $row['waktu_kembali'] ?? $row['waktu_pinjam'] ?? $row['waktu_pengajuan'] ?? $now,
            ], $row);

            DB::table('histori_peminjaman')->insert($data);
        }
    }
}
