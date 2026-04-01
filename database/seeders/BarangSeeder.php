<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('barang')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Kategori IDs: 1=Desktop, 2=Laptop, 3=Printer, 4=Scanner, 5=Proyektor, 6=Jaringan
        // Ruangan IDs: 1=Kabag TU, 2=Subag, 3=Frontdesk, 4=Teknis 1, 5=Teknis 2, 6=Gudang, 7=Aula

        // [kode_barang, nup, nama_barang, brand, tipe, kondisi, ketersediaan, status_barang, kategori_id, ruangan_id, pic_user_id]
        $barang = [
            // === Komputer Desktop (kategori 1) ===
            ['3100102001', 10, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'rusak_berat', 'reparasi', 'rusak_total', 1, 6, null],
            ['3100102001', 11, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 12, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 13, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'dipinjam', 'aktif', 1, 4, null],
            ['3100102001', 15, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 16, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 18, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 19, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'rusak_ringan', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 22, 'PC Desktop Lenovo', 'LENOVO', 'THINK CENTRE M80', 'baik', 'tersedia', 'aktif', 1, 3, null],
            ['3100102001', 24, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 3010', 'baik', 'tersedia', 'aktif', 1, 3, null],
            ['3100102001', 26, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 3010', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 27, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 3010', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 29, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 780', 'baik', 'reparasi', 'hilang', 1, null, null],
            ['3100102001', 31, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 3020 Micro', 'baik', 'tersedia', 'aktif', 1, 4, 2],
            ['3100102001', 32, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 3020 Micro', 'rusak_ringan', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 33, 'PC Desktop Dell', 'DELL', 'OPTIPLEX 3020 Micro', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 36, 'Workstation Lenovo', 'LENOVO', 'ThinkStation P320', 'baik', 'tersedia', 'aktif', 1, 4, 2],
            ['3100102001', 37, 'PC Desktop Lenovo', 'LENOVO', 'ThinkCentre M720t', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 38, 'PC Desktop Lenovo', 'LENOVO', 'ThinkCentre M720t', 'baik', 'dipinjam', 'aktif', 1, 4, null],
            ['3100102001', 39, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 with ssd ex vga', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 40, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 i7 9700/256GB SSD/8GB', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 41, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 i7 9700/256GB SSD/8GB', 'baik', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 42, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 i7 9700/256GB SSD/8GB', 'baik', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 43, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 i7 9700/256GB SSD/8GB', 'baik', 'tersedia', 'aktif', 1, 3, null],
            ['3100102001', 44, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 i7 9700/256GB SSD/8GB', 'baik', 'tersedia', 'aktif', 1, 3, null],
            ['3100102001', 45, 'PC Desktop Dell', 'DELL', 'Inspiron 3471 i7 9700/16GB/256 SSD', 'baik', 'tersedia', 'aktif', 1, 1, 2],
            ['3100102001', 46, 'PC Desktop Asus', 'ASUS', 'D700MA-581000000W/15', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 47, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 48, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 49, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 50, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 51, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 52, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 3, null],
            ['3100102001', 53, 'PC Desktop Acer', 'ACER', 'VERITON M - CORE i7 (VM/0015)', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 54, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 55, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 56, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 4, null],
            ['3100102001', 57, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 5, null],
            ['3100102001', 58, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 2, null],
            ['3100102001', 59, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 3, null],
            ['3100102001', 60, 'PC Desktop Acer', 'ACER', 'VERITON M17', 'baik', 'tersedia', 'aktif', 1, 1, null],

            // === Laptop (kategori 2) ===
            ['3100102002', 20, 'Laptop HP', 'HP', 'Pavilion 14/v043tx', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 21, 'Laptop HP', 'HP', '14-bp063TX 3PH00', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 22, 'Laptop HP', 'HP', '14-cm0077AU Ryzen5/4GB/1TB', 'baik', 'dipinjam', 'aktif', 2, null, null],
            ['3100102002', 23, 'Laptop Asus', 'ASUS', 'EXPERTBOOK P2451FB-EK7810T', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 24, 'Laptop Asus', 'ASUS', 'EXPERTBOOK P2451FB-EK781OT', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 25, 'Laptop Asus', 'ASUS', 'EXPERTBOOK P2451FB-EK781OT', 'baik', 'dipinjam', 'aktif', 2, null, null],
            ['3100102002', 26, 'Laptop Asus', 'ASUS', 'EXPERTBOOK P2451FB-EK781OT', 'baik', 'tersedia', 'aktif', 2, 4, null],
            ['3100102002', 27, 'Laptop HP', 'HP', '240 G8', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 28, 'Laptop HP', 'HP', '240 G8', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 29, 'Laptop HP', 'HP', '240 G8', 'rusak_ringan', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 30, 'Laptop Lenovo', 'LENOVO', 'IDEAPAD SLIM 3 RBID', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 31, 'Laptop Lenovo', 'LENOVO', '14" FHD IPS/i7-1165G7/MX450/16GB', 'baik', 'tersedia', 'aktif', 2, 4, 2],
            ['3100102002', 33, 'Laptop Asus', 'ASUS', 'BG 1408 CVA-EB7110W', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 34, 'Laptop Asus', 'ASUS', 'BG 1408 CVA-EB7110W', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102002', 35, 'Laptop Asus', 'ASUS', 'BG 1408 CVA-EB7110W', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102003', 2, 'Laptop Fujitsu', 'FUJITSU', 'SH782', 'baik', 'tersedia', 'aktif', 2, 6, null],
            ['3100102003', 3, 'Laptop Fujitsu', 'FUJITSU', 'SH782', 'rusak_berat', 'reparasi', 'dihapuskan', 2, null, null],
            ['3100102003', 4, 'Laptop Dell', 'DELL', 'INSPIRON 5447 A/8GB', 'baik', 'tersedia', 'aktif', 2, 5, null],
            ['3100102003', 6, 'Laptop HP', 'HP', 'Business Notebook 348 G4', 'baik', 'tersedia', 'aktif', 2, 2, null],

            // === Printer (kategori 3) ===
            ['3100203003', 9, 'Printer HP', 'HP', 'P1606 DN', 'baik', 'tersedia', 'aktif', 3, 2, null],
            ['3100203003', 10, 'Printer HP', 'HP', 'Laserjet Pro M401 dw', 'baik', 'tersedia', 'aktif', 3, 4, null],
            ['3100203003', 11, 'Printer HP', 'HP', 'Laserjet Pro M402dn', 'baik', 'tersedia', 'aktif', 3, 5, null],
            ['3100203003', 12, 'Printer Canon', 'CANON', 'IMAGECLASS MF-244DW', 'baik', 'tersedia', 'aktif', 3, 3, null],
            ['3100203003', 13, 'Printer HP', 'HP', 'M227FDN Mono', 'baik', 'tersedia', 'aktif', 3, 4, null],
            ['3100203003', 16, 'Printer Epson', 'EPSON', 'Inkjet L 1455', 'baik', 'tersedia', 'aktif', 3, 1, 2],
            ['3100203003', 17, 'Printer Mobile HP', 'HP', 'OJ 200 Mobile Printer', 'baik', 'dipinjam', 'aktif', 3, null, null],
            ['3100203003', 18, 'Printer HP', 'HP', 'M227FDN', 'baik', 'tersedia', 'aktif', 3, 2, null],
            ['3100203003', 19, 'Printer HP', 'HP', 'SMART TANK 515', 'rusak_ringan', 'tersedia', 'aktif', 3, 4, null],
            ['3100203003', 20, 'Printer Brother', 'BROTHER', 'MFC T920DW', 'baik', 'tersedia', 'aktif', 3, 5, null],
            ['3100203003', 21, 'Printer Brother', 'BROTHER', 'Colour LED MFC', 'baik', 'tersedia', 'aktif', 3, 3, null],
            ['3100203003', 22, 'Printer Brother', 'BROTHER', 'Colour LED MFC', 'baik', 'tersedia', 'aktif', 3, 4, null],
            ['3100203003', 23, 'Printer Epson', 'EPSON', 'L5190', 'baik', 'tersedia', 'aktif', 3, 4, null],
            ['3100203003', 24, 'Printer HP', 'HP', 'Laserjet Enterprise M507', 'baik', 'tersedia', 'aktif', 3, 1, null],
            ['3100203003', 25, 'Printer Epson', 'EPSON', 'L 5190', 'baik', 'tersedia', 'aktif', 3, 2, null],
            ['3100203003', 26, 'Printer Epson', 'EPSON', 'Eco tank L15160', 'baik', 'tersedia', 'aktif', 3, 4, null],

            // === Scanner (kategori 4) ===
            ['3100203004', 2, 'Scanner Fujitsu', 'FUJITSU', 'Image Scanner fi-7260', 'baik', 'tersedia', 'aktif', 4, 2, null],
            ['3100203004', 4, 'Scanner Epson', 'EPSON', 'WORKFORCE DS-970 A4 Duplex', 'baik', 'tersedia', 'aktif', 4, 4, 2],
        ];

        $now = now();

        foreach ($barang as $item) {
            $row = [
                'kode_barang'      => $item[0],
                'nup'              => $item[1],
                'nama_barang'      => $item[2],
                'brand'            => $item[3],
                'tipe'             => $item[4],
                'kondisi_terakhir' => $item[5],
                'ketersediaan'     => $item[6],
                'status_barang'    => $item[7],
                'kategori_id'      => $item[8],
                'ruangan_id'       => $item[9],
                'pic_user_id'      => $item[10],
                'created_at'       => $now,
                'updated_at'       => $now,
            ];

            // Set peminjam_terakhir & waktu_pinjam for items yang sedang dipinjam
            if ($item[6] === 'dipinjam') {
                $row['peminjam_terakhir'] = ['Budi Santoso', 'Siti Nurhaliza', 'Ahmad Wijaya', 'Rina Wulandari'][array_rand([0, 1, 2, 3])];
                $row['waktu_pinjam'] = $now->copy()->subDays(rand(1, 10));
            }

            // Set catatan_status for non-aktif items
            if ($item[7] !== 'aktif') {
                $row['catatan_status'] = match ($item[7]) {
                    'rusak_total' => 'Unit rusak total, tidak dapat diperbaiki',
                    'hilang' => 'Barang tidak ditemukan saat stock opname',
                    'dihapuskan' => 'Dihapuskan dari daftar BMN berdasarkan SK penghapusan',
                };
                $row['status_diupdate_at'] = $now->copy()->subDays(rand(5, 30));
                $row['status_diupdate_by'] = 1; // Admin
            }

            DB::table('barang')->insert($row);
        }
    }
}
