<?php
require_once 'public/src/config/database.php';

$data_raw = <<<EOD
3100102001-10,LENOVO,THINK CENTRE M80,Rusak Ringan
3100102001-11,LENOVO,THINK CENTRE M80,Rusak Ringan
3100102001-12,LENOVO,THINK CENTRE M80,Baik
3100102001-13,LENOVO,THINK CENTRE M80,Baik
3100102001-15,LENOVO,THINK CENTRE M80,Baik
3100102001-16,LENOVO,THINK CENTRE M80,Baik
3100102001-18,LENOVO,THINK CENTRE M80,Baik
3100102001-19,LENOVO,THINK CENTRE M80,Baik
3100102001-22,LENOVO,THINK CENTRE M80,Baik
3100102001-24,DELL,OPTIPLEX 3010,Baik
3100102001-26,DELL,OPTIPLEX 3010,Baik
3100102001-27,DELL,OPTIPLEX 3010,Baik
3100102001-29,DELL,OPTIPLEX 780,Baik
3100102001-31,DELL,OPTIPLEX 3020 Micro,Baik
3100102001-32,DELL,OPTIPLEX 3020 Micro,Baik
3100102001-33,DELL,OPTIPLEX 3020 Micro,Baik
3100102001-36,LENOVO,ThinkStation P320,Baik
3100102001-37,LENOVO,ThinkCentre M720t,Baik
3100102001-38,LENOVO,ThinkCentre M720t,Baik
3100102001-39,DELL,Inspiron 3471 with ssd ex vga,Baik
3100102001-40,DELL,Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB,Baik
3100102001-41,DELL,Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB,Baik
3100102001-42,DELL,Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB,Baik
3100102001-43,DELL,Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB,Baik
3100102001-44,DELL,Inspiron 3471 Intelcore i7 9700/256GB SSD/8GB,Baik
3100102001-45,DELL,Inspiron 3471 Intelcore i79700/16 GB/256 SSD,Baik
3100102001-46,ASUS,D700MA-581000000W/15,Baik
3100102001-47,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-48,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-49,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-50,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-51,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-52,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-53,ACER,VERITON M - CORE 17 (VM/0015),Baik
3100102001-54,ACER,VERITON M17,Baik
3100102001-55,ACER,VERITON M17,Baik
3100102001-56,ACER,VERITON M17,Baik
3100102001-57,ACER,VERITON M17,Baik
3100102001-58,ACER,VERITON M17,Baik
3100102001-59,ACER,VERITON M17,Baik
3100102001-60,ACER,VERITON M17,Baik
3100102002-20,HP,Pavilion 14/v043tx,Baik
3100102002-21,HP,14-bp063TX 3PH00,Baik
3100102002-22,HP,14-cm0077AU(Ryzen5-2500U/4GB/1TB/Vega 8/Win 10/,Baik
3100102002-23,ASUS,EXPERTBOOK P2451FB-EK7810T / 8 GB DDR4,Baik
3100102002-24,ASUS,EXPERTBOOK P2451FB-EK781OT,Baik
3100102002-25,ASUS,EXPERTBOOK P2451FB-EK781OT,Baik
3100102002-26,ASUS,EXPERTBOOK P2451FB-EK781OT,Baik
3100102002-27,HP,240 G8,Baik
3100102002-28,HP,240 G8,Baik
3100102002-29,HP,240 G8,Baik
3100102002-30,LENOVO,IDEAPAD SLIM 3 RBID (Artic Grey),Baik
3100102002-31,LENOVO,14.OFHD_IPS/17-1165G7/MX450_2GB_G6_64B/16GB,Baik
3100102002-33,ASUS,BG 1408 CVA-EB7110W,Baik
3100102002-34,ASUS,BG 1408 CVA-EB7110W,Baik
3100102002-35,ASUS,BG 1408 CVA-EB7110W,Baik
3100102003-2,FUJITSU,SH782,Rusak Ringan
3100102003-3,FUJITSU,SH782,Baik
3100102003-4,DELL,INSPIRON 5447 A/8GB/IT,Baik
3100102003-6,HP,Business Notebook 348 G4,Baik
3100203003-0009,HP,P1606 DN,Baik
3100203003-0010,HP,Laserjet Pro M401 dw,Baik
3100203003-0011,HP,Laserjet Pro M402dn,Baik
3100203003-0012,CANON,IMAGECLASS MF-244DW,Baik
3100203003-0013,HP,M227FDN Mono,Baik
3100203003-0017,HP,OJ 200 Mobile Printer,Baik
3100203003-0018,HP,M227FDN,Baik
3100203003-0019,HP,SMART TANK 515,Baik
3100203003-0020,BROTHER,MFC T920DW,Baik
3100203003-0021,BROTHER,Colour LED Multi Fuction centre,Baik
3100203003-0022,BROTHER,Colour LED Multi Fuction centre,Baik
3100203003-0023,EPSON,L5190,Baik
3100203003-0024,HP,Laserjet Enterprise M507,Baik
3100203003-0016,EPSON,Inkjet L 1455,Baik
3100203003-0025,EPSON,L 5190,Baik
3100203003-0026,EPSON,Eco tank L15160,Baik
3100203004-0004,EPSON,WORKFORCE DS-970 A4 DUPLEX SHEET-FED DOCUMENT SCANNER,Baik
3100203004-0002,FUJITSU,Fujitsu Image Scanner fi-7260,Baik
EOD;

$lines = explode("\n", trim($data_raw));
$count = 0;
$errors = 0;

$stmt = $pdo->prepare("UPDATE barang SET kondisi_terakhir = ? WHERE kode_barang = ? AND nup = ?");

foreach ($lines as $line) {
    if (empty(trim($line)))
        continue;

    // Split by comma manually, respecting the format provided
    // Format: ID-NUP, BRAND, TIPE, KONDISI
    // Note: Use regex or simple explode if commas are simple separators
    $parts = str_getcsv($line);

    if (count($parts) < 4) {
        // Fallback for simple comma split
        $parts = explode(',', $line);
    }

    if (count($parts) >= 4) {
        $full_id = trim($parts[0]); // e.g., 3100102001-10
        $kondisi_raw = trim(end($parts)); // Last element is conditions

        // Normalize Full ID
        $id_parts = explode('-', $full_id);
        if (count($id_parts) !== 2) {
            echo "Invalid ID format: $full_id\n";
            $errors++;
            continue;
        }

        $kode = $id_parts[0];
        $nup = intval($id_parts[1]);

        // Normalize Kondisi
        $kondisi = strtolower($kondisi_raw);
        if ($kondisi == 'rusak ringan')
            $kondisi = 'rusak ringan';
        else if ($kondisi == 'rusak berat')
            $kondisi = 'rusak berat';
        else
            $kondisi = 'baik'; // Default or if string "Baik"

        try {
            $stmt->execute([$kondisi, $kode, $nup]);
            if ($stmt->rowCount() > 0) {
                // echo "Updated: $full_id -> $kondisi\n";
                $count++;
            } else {
                // echo "No change or not found: $full_id\n";
            }
        } catch (PDOException $e) {
            echo "Error updating $full_id: " . $e->getMessage() . "\n";
            $errors++;
        }
    }
}

echo "Proses selesai. $count data berhasil diperbarui.\n";
