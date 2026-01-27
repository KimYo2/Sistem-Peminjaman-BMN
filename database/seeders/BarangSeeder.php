<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang = [
            // Komputer Desktop
            ['3100102001', 10, 'LENOVO', 'THINK CENTRE M80', 'Rusak Berat', 'tersedia'],
            ['3100102001', 11, 'LENOVO', 'THINK CENTRE M80', '', 'tersedia'],
            ['3100102001', 12, 'LENOVO', 'THINK CENTRE M80', 'Baik', 'tersedia'],
            ['3100102001', 13, 'LENOVO', 'THINK CENTRE M80', 'Baik', 'tersedia'],
            ['3100102001', 15, 'LENOVO', 'THINK CENTRE M80', 'Baik', 'tersedia'],
            ['3100102001', 16, 'LENOVO', 'THINK CENTRE M80', '', 'tersedia'],
            ['3100102001', 18, 'LENOVO', 'THINK CENTRE M80', 'Baik', 'tersedia'],
            ['3100102001', 19, 'LENOVO', 'THINK CENTRE M80', 'Rusak Ringan', 'tersedia'],
            ['3100102001', 22, 'LENOVO', 'THINK CENTRE M80', '', 'tersedia'],
            ['3100102001', 24, 'DELL', 'OPTIPLEX 3010', 'Baik', 'tersedia'],
            ['3100102001', 26, 'DELL', 'OPTIPLEX 3010', 'Baik', 'tersedia'],
            ['3100102001', 27, 'DELL', 'OPTIPLEX 3010', '', 'tersedia'],
            ['3100102001', 29, 'DELL', 'OPTIPLEX 780', '', 'tersedia'],
            ['3100102001', 31, 'DELL', 'OPTIPLEX 3020 Micro', 'Baik', 'tersedia'],
            ['3100102001', 32, 'DELL', 'OPTIPLEX 3020 Micro', 'Rusak Ringan', 'tersedia'],
            ['3100102001', 33, 'DELL', 'OPTIPLEX 3020 Micro', 'Baik', 'tersedia'],
            ['3100102001', 36, 'LENOVO', 'ThinkStation P320', '', 'tersedia'],
            ['3100102001', 37, 'LENOVO', 'ThinkCentre M720t', 'Baik', 'tersedia'],
            ['3100102001', 38, 'LENOVO', 'ThinkCentre M720t', 'Baik', 'tersedia'],
            ['3100102001', 39, 'DELL', 'Inspiron 3471 with ssd ex vga', '', 'tersedia'],
            ['3100102001', 40, 'DELL', 'Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB', '', 'tersedia'],
            ['3100102001', 41, 'DELL', 'Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB', '', 'tersedia'],
            ['3100102001', 42, 'DELL', 'Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB', 'Baik', 'tersedia'],
            ['3100102001', 43, 'DELL', 'Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB', '', 'tersedia'],
            ['3100102001', 44, 'DELL', 'Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB', 'Baik', 'tersedia'],
            ['3100102001', 45, 'DELL', 'Inspiron 3471 Intelcore i79700/16 GB/256 SSD', 'Baik', 'tersedia'],
            ['3100102001', 46, 'ASUS', 'D700MA-581000000W/15', '', 'tersedia'],
            ['3100102001', 47, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 48, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 49, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 50, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 51, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 52, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 53, 'ACER', 'VERITON M - CORE 17 (VM/0015)', 'Baik', 'tersedia'],
            ['3100102001', 54, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],
            ['3100102001', 55, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],
            ['3100102001', 56, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],
            ['3100102001', 57, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],
            ['3100102001', 58, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],
            ['3100102001', 59, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],
            ['3100102001', 60, 'ACER', 'VERITON M17', 'Baik', 'tersedia'],

            // Laptop
            ['3100102002', 20, 'HP', 'Pavilion 14/v043tx', '', 'tersedia'],
            ['3100102002', 21, 'HP', '14-bp063TX 3PH00', '', 'tersedia'],
            ['3100102002', 22, 'HP', '14-cm0077AU(Ryzen5-2500U/4GB/1TB/Vega 8/Win 10/', '', 'tersedia'],
            ['3100102002', 23, 'ASUS', 'EXPERTBOOK P2451FB-EK7810T / 8 GB DDR4', '', 'tersedia'],
            ['3100102002', 24, 'ASUS', 'EXPERTBOOK P2451FB-EK781OT', '', 'tersedia'],
            ['3100102002', 25, 'ASUS', 'EXPERTBOOK P2451FB-EK781OT', '', 'tersedia'],
            ['3100102002', 26, 'ASUS', 'EXPERTBOOK P2451FB-EK781OT', '', 'tersedia'],
            ['3100102002', 27, 'HP', '240 G8', '', 'tersedia'],
            ['3100102002', 28, 'HP', '240 G8', '', 'tersedia'],
            ['3100102002', 29, 'HP', '240 G8', '', 'tersedia'],
            ['3100102002', 30, 'LENOVO', 'IDEAPAD SLIM 3 RBID (Artic Grey)', '', 'tersedia'],
            ['3100102002', 31, 'LENOVO', '14.OFHD_IPS/17-1165G7/MX450_2GB_G6_64B/16GB', '', 'tersedia'],
            ['3100102002', 33, 'ASUS', 'BG 1408 CVA-EB7110W', '', 'tersedia'],
            ['3100102002', 34, 'ASUS', 'BG 1408 CVA-EB7110W', '', 'tersedia'],
            ['3100102002', 35, 'ASUS', 'BG 1408 CVA-EB7110W', '', 'tersedia'],
            ['3100102003', 2, 'FUJITSU', 'SH782', '', 'tersedia'],
            ['3100102003', 3, 'FUJITSU', 'SH782', '', 'tersedia'],
            ['3100102003', 4, 'DELL', 'INSPIRON 5447 A/8GB/IT', '', 'tersedia'],
            ['3100102003', 6, 'HP', 'Business Notebook 348 G4', '', 'tersedia'],

            // Printer
            ['3100203003', 9, 'HP', 'P1606 DN', '', 'tersedia'],
            ['3100203003', 10, 'HP', 'Laserjet Pro M401 dw', '', 'tersedia'],
            ['3100203003', 11, 'HP', 'Laserjet Pro M402dn', '', 'tersedia'],
            ['3100203003', 12, 'CANON', 'IMAGECLASS MF-244DW', '', 'tersedia'],
            ['3100203003', 13, 'HP', 'M227FDN Mono', '', 'tersedia'],
            ['3100203003', 17, 'HP', 'OJ 200 Mobile Printer', '', 'tersedia'],
            ['3100203003', 18, 'HP', 'M227FDN', '', 'tersedia'],
            ['3100203003', 19, 'HP', 'SMART TANK 515', '', 'tersedia'],
            ['3100203003', 20, 'BROTHER', 'MFC T920DW', '', 'tersedia'],
            ['3100203003', 21, 'BROTHER', 'Colour LED Multi Fuction centre', '', 'tersedia'],
            ['3100203003', 22, 'BROTHER', 'Colour LED Multi Fuction centre', '', 'tersedia'],
            ['3100203003', 23, 'EPSON', 'L5190', '', 'tersedia'],
            ['3100203003', 24, 'HP', 'Laserjet Enterprise M507', '', 'tersedia'],
            ['3100203003', 16, 'EPSON', 'Inkjet L 1455', 'Baik', 'tersedia'],
            ['3100203003', 25, 'EPSON', 'L 5190', 'Baik', 'tersedia'],
            ['3100203003', 26, 'EPSON', 'Eco tank L15160', 'Baik', 'tersedia'],

            // Scanner
            ['3100203004', 4, 'EPSON', 'WORKFORCE DS-970 A4 DUPLEX SHEET-FED DOCUMENT SCANNER', 'Baik', 'tersedia'],
            ['3100203004', 2, 'FUJITSU', 'Fujitsu Image Scanner fi-7260', 'Baik', 'tersedia'],
        ];

        foreach ($barang as $item) {
            DB::table('barang')->insert([
                'kode_barang' => $item[0],
                'nup' => $item[1],
                'brand' => $item[2],
                'tipe' => $item[3],
                'kondisi_terakhir' => $item[4],
                'ketersediaan' => $item[5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
