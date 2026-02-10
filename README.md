# Sistem Peminjaman Barang BMN dengan QR Code

Sistem peminjaman barang inventaris BMN (Barang Milik Negara) berbasis web untuk BPS dengan fitur scan QR Code menggunakan kamera smartphone.

## Deskripsi

Sistem ini memudahkan pegawai BPS dalam meminjam barang inventaris kantor dengan cara scan QR Code pada barang. Admin dapat mengelola barang, memproses pengajuan, pengembalian, dan laporan kerusakan.

## Fitur Utama

### User (Pegawai)
- Login dengan NIP dan password
- Scan QR Code barang menggunakan kamera
- Lihat detail barang (kode BMN, brand, tipe, kondisi, status)
- Ajukan peminjaman barang (status menunggu persetujuan admin)
- Lihat histori peminjaman pribadi

### Admin
- Login dengan akun admin
- Dashboard statistik barang dan peminjaman
- Kelola barang (CRUD) dan import CSV
- Setujui / tolak pengajuan peminjaman
- Scan QR untuk proses pengembalian
- Kelola tiket kerusakan
- Export histori peminjaman ke CSV

## Teknologi

- Backend: Laravel (PHP)
- Database: MySQL
- Frontend: Blade + Tailwind CSS (CDN)
- JS: Alpine.js
- QR Scanner: html5-qrcode

## Struktur Folder

```text
pinjam_qr/
|-- app/
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- Admin/           # Controller Admin (Barang, Histori, Tiket, Opname)
|   |   |   |-- Auth/            # Controller Login/Logout
|   |   |   |-- User/            # Controller User (Dashboard, Scan, Peminjaman, Waitlist)
|   |   |   `-- Concerns/        # Trait helper (Audit Log)
|   |   |-- Middleware/          # Middleware (Auth, EnsureAdmin)
|   |   `-- Requests/
|   |       |-- Admin/           # Form Request admin
|   |       `-- User/            # Form Request user
|   |-- Models/                  # Model Eloquent (Barang, Histori, Tiket, Waitlist, Opname)
|   `-- Services/                # Service layer (Parser, Import)
|-- resources/
|   `-- views/
|       |-- admin/               # View Admin (Blade)
|       |   |-- barang/
|       |   |-- histori/
|       |   |-- tiket/
|       |   `-- opname/
|       |-- auth/                # View Login
|       |-- layouts/             # Master Layout (Tailwind)
|       |-- return/              # View Scan Pengembalian
|       `-- user/                # View User
|-- routes/
|   `-- web.php                  # Definisi Route Aplikasi
|-- public/                      # Entry point & Assets
|   |-- css/                     # CSS tambahan (fallback theme)
|   |-- js/                      # JS tambahan (theme, qr-scan, dll)
|   `-- index.php
|-- database/
|   `-- migrations/              # Definisi Schema Database
|-- tests/
|   |-- Feature/                 # Feature tests (Borrow, Return, Waitlist, Opname, Ticket)
|   `-- Unit/                    # Unit tests
`-- README.md
```

## Instalasi

### 1. Setup Database

Sesuaikan konfigurasi database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pinjam_qr
DB_USERNAME=root
DB_PASSWORD=
```

Buat database:

```bash
mysql -u root -p
CREATE DATABASE pinjam_qr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### 2. Jalankan Migration dan Seeder

```bash
php artisan migrate
php artisan db:seed
```

### 3. Akses Aplikasi

Buka browser dan akses:

```
http://pinjam_qr.test
```

## Demo Credentials

### Admin
- NIP: 198001012006041001
- Password: password123

### User (Pegawai)
- NIP: 199001012015041001
- Password: password123

## Alur Sistem

### Alur Peminjaman (User)
1. User login
2. Scan QR Code
3. Sistem menampilkan detail barang
4. Ajukan peminjaman
5. Status pengajuan: menunggu
6. Admin menyetujui atau menolak

### Alur Pengembalian (Admin/User)
1. Scan QR Code barang
2. Konfirmasi kondisi barang
3. Jika rusak, sistem membuat tiket kerusakan
4. Status barang kembali tersedia

## Database Schema (Ringkas)

### Tabel users
- id
- nip (unique)
- nama
- password (hashed)
- role (user/admin)

### Tabel barang
- id
- kode_barang
- nup
- brand
- tipe
- kondisi_terakhir (baik/rusak_ringan/rusak_berat)
- ketersediaan (tersedia/dipinjam/hilang/reparasi)
- pic_user_id
- peminjam_terakhir
- waktu_pinjam
- waktu_kembali

### Tabel histori_peminjaman
- id
- kode_barang
- nup
- nip_peminjam
- nama_peminjam
- waktu_pengajuan
- waktu_pinjam
- waktu_kembali
- tanggal_jatuh_tempo
- status (menunggu/dipinjam/ditolak/dikembalikan)
- kondisi_awal
- kondisi_kembali
- catatan_kondisi
- approved_by
- approved_at
- rejected_at
- rejection_reason

### Tabel tiket_kerusakan
- id
- nomor_bmn
- pelapor
- jenis_kerusakan (ringan/berat)
- deskripsi
- tanggal_lapor
- status (open/diproses/selesai)

### Tabel audit_logs
- id
- user_id
- action
- entity
- entity_id
- meta
- created_at

## Troubleshooting

### Kamera tidak bisa diakses
- Gunakan HTTPS atau localhost
- Pastikan browser diizinkan mengakses kamera

### Database connection error
- Pastikan konfigurasi `.env` sesuai
- Pastikan MySQL berjalan

### QR Code tidak terbaca
- Pastikan QR Code jelas dan tidak blur
- Coba jarak kamera yang berbeda

## Catatan Penting

- Nomor BMN dibentuk dari `kode_barang-nup`
- Sistem tidak membuat QR Code baru, hanya membaca QR yang sudah ada
- Untuk production, ganti password default semua user

## Lisensi

Sistem ini dibuat untuk keperluan internal BPS.
