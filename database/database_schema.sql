-- Database: Sistem Peminjaman Barang BMN
-- Untuk BPS (Badan Pusat Statistik)

-- Buat database
CREATE DATABASE IF NOT EXISTS pinjam_bmn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pinjam_bmn;

-- Tabel Users (Pegawai dan Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Barang (sesuai struktur yang sudah ada)
CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_bmn VARCHAR(50) UNIQUE NOT NULL,
    brand VARCHAR(100) NOT NULL,
    tipe VARCHAR(100) NOT NULL,
    kondisi_terakhir ENUM('baik', 'rusak ringan', 'rusak berat') DEFAULT 'baik',
    ketersediaan ENUM('tersedia', 'dipinjam') DEFAULT 'tersedia',
    peminjam_terakhir VARCHAR(100) NULL,
    waktu_pinjam TIMESTAMP NULL,
    waktu_kembali TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Histori Peminjaman (untuk tracking lengkap)
CREATE TABLE IF NOT EXISTS histori_peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_bmn VARCHAR(50) NOT NULL,
    nip_peminjam VARCHAR(20) NOT NULL,
    nama_peminjam VARCHAR(100) NOT NULL,
    waktu_pinjam TIMESTAMP NOT NULL,
    waktu_kembali TIMESTAMP NULL,
    status ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nomor_bmn) REFERENCES barang(nomor_bmn) ON DELETE CASCADE,
    FOREIGN KEY (nip_peminjam) REFERENCES users(nip) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index untuk performa
CREATE INDEX idx_ketersediaan ON barang(ketersediaan);
CREATE INDEX idx_nomor_bmn ON barang(nomor_bmn);
CREATE INDEX idx_status_histori ON histori_peminjaman(status);
CREATE INDEX idx_nip_peminjam ON histori_peminjaman(nip_peminjam);

-- Insert data user default
-- Password: "password123" (sudah di-hash dengan password_hash)
INSERT INTO users (nip, nama, password, role) VALUES
('198001012006041001', 'Admin BPS', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('199001012015041001', 'Budi Santoso', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('199505012018041001', 'Siti Nurhaliza', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('199808012020041001', 'Ahmad Wijaya', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert data barang contoh
INSERT INTO barang (nomor_bmn, brand, tipe, kondisi_terakhir, ketersediaan) VALUES
('BMN-2023-001-LPT', 'Dell', 'Latitude 5420', 'baik', 'tersedia'),
('BMN-2023-002-LPT', 'HP', 'EliteBook 840 G8', 'baik', 'tersedia'),
('BMN-2023-003-LPT', 'Lenovo', 'ThinkPad X1 Carbon', 'baik', 'dipinjam'),
('BMN-2023-004-PRJ', 'Epson', 'EB-X05', 'baik', 'tersedia'),
('BMN-2023-005-PRJ', 'BenQ', 'MH535A', 'rusak ringan', 'tersedia'),
('BMN-2023-006-CAM', 'Canon', 'EOS 700D', 'baik', 'tersedia'),
('BMN-2023-007-CAM', 'Sony', 'Alpha A6000', 'baik', 'dipinjam'),
('BMN-2023-008-TAB', 'Samsung', 'Galaxy Tab S7', 'baik', 'tersedia'),
('BMN-2023-009-TAB', 'Apple', 'iPad Air 4', 'baik', 'tersedia'),
('BMN-2023-010-MON', 'LG', '27 inch 4K Monitor', 'baik', 'tersedia');

-- Update barang yang dipinjam dengan data peminjam
UPDATE barang 
SET peminjam_terakhir = 'Budi Santoso', 
    waktu_pinjam = '2026-01-20 08:30:00'
WHERE nomor_bmn = 'BMN-2023-003-LPT';

UPDATE barang 
SET peminjam_terakhir = 'Siti Nurhaliza', 
    waktu_pinjam = '2026-01-22 10:15:00'
WHERE nomor_bmn = 'BMN-2023-007-CAM';

-- Insert histori peminjaman untuk barang yang sedang dipinjam
INSERT INTO histori_peminjaman (nomor_bmn, nip_peminjam, nama_peminjam, waktu_pinjam, status) VALUES
('BMN-2023-003-LPT', '199001012015041001', 'Budi Santoso', '2026-01-20 08:30:00', 'dipinjam'),
('BMN-2023-007-CAM', '199505012018041001', 'Siti Nurhaliza', '2026-01-22 10:15:00', 'dipinjam');

-- Insert beberapa histori peminjaman yang sudah dikembalikan
INSERT INTO histori_peminjaman (nomor_bmn, nip_peminjam, nama_peminjam, waktu_pinjam, waktu_kembali, status) VALUES
('BMN-2023-001-LPT', '199001012015041001', 'Budi Santoso', '2026-01-10 09:00:00', '2026-01-15 16:00:00', 'dikembalikan'),
('BMN-2023-004-PRJ', '199808012020041001', 'Ahmad Wijaya', '2026-01-12 13:00:00', '2026-01-18 14:30:00', 'dikembalikan'),
('BMN-2023-006-CAM', '199505012018041001', 'Siti Nurhaliza', '2026-01-08 10:00:00', '2026-01-11 15:00:00', 'dikembalikan');
