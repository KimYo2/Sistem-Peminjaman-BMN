# Sistem Peminjaman Barang BMN dengan QR Code

Sistem peminjaman barang inventaris BMN (Barang Milik Negara) berbasis web untuk BPS dengan fitur scan QR Code menggunakan kamera smartphone.

## 📋 Deskripsi

Sistem ini dirancang untuk memudahkan pegawai BPS dalam meminjam barang inventaris kantor dengan cara scan QR Code yang sudah ada pada barang. Admin dapat mengelola barang dan memproses pengembalian.

## 🚀 Fitur Utama

### User (Pegawai)
- Login dengan NIP dan password
- Scan QR Code barang menggunakan kamera smartphone
- Lihat detail barang (nomor BMN, brand, tipe, kondisi, status)
- Ajukan peminjaman barang yang tersedia
- Lihat histori peminjaman pribadi

### Admin
- Login dengan akun admin
- Dashboard dengan statistik barang
- Lihat daftar semua barang dengan filter
- Scan QR untuk proses pengembalian barang
- Lihat histori peminjaman semua pegawai

## 🛠️ Teknologi

- **Backend**: PHP Native (PDO untuk database)
- **Database**: MySQL
- **Frontend**: HTML5, Tailwind CSS (via CDN), JavaScript
- **QR Scanner**: html5-qrcode library

## 📁 Struktur Folder

```
pinjam_qr/
├── database/
│   └── database_schema.sql      # SQL schema dan sample data
├── src/
│   ├── config/
│   │   ├── database.php         # Konfigurasi database
│   │   └── auth.php             # Helper autentikasi
│   ├── api/
│   │   ├── login.php            # Endpoint login
│   │   ├── logout.php           # Endpoint logout
│   │   ├── scan_qr.php          # Endpoint scan QR
│   │   ├── ajukan_peminjaman.php
│   │   ├── kembalikan_barang.php
│   │   ├── get_barang.php       # Get daftar barang
│   │   └── get_histori.php      # Get histori peminjaman
│   ├── assets/
│   │   └── js/
│   │       ├── main.js          # JavaScript utilities
│   │       └── qr-scanner.js    # QR scanner functions
│   ├── user/
│   │   ├── dashboard.php        # Dashboard pegawai
│   │   ├── scan.php             # Halaman scan QR
│   │   └── detail_barang.php    # Detail barang
│   ├── admin/
│   │   ├── dashboard.php        # Dashboard admin
│   │   ├── daftar_barang.php    # Daftar barang
│   │   ├── scan_return.php      # Scan pengembalian
│   │   └── histori.php          # Histori peminjaman
│   ├── index.php                # Landing page
│   └── login.php                # Halaman login
└── README.md
```

## 💾 Instalasi

### 1. Setup Database

#### Konfigurasi .env

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pinjam_bmn
DB_USERNAME=root
DB_PASSWORD=
```

#### Buat Database

```bash
# Buat database di MySQL
mysql -u root -p

# Di MySQL prompt:
CREATE DATABASE pinjam_bmn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### 2. Jalankan Migration dan Seeder

```bash
# Jalankan migration untuk membuat tabel
php artisan migrate

# Jalankan seeder untuk mengisi data
php artisan db:seed

# Atau jalankan sekaligus (fresh migration + seed)
php artisan migrate:fresh --seed
```

Ini akan membuat:
- 3 tabel (users, barang, histori_peminjaman)
- 4 user (1 admin + 3 pegawai)
- 79 barang inventaris BPS

### 3. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/pinjam_qr/src/
```

Atau jika menggunakan Laravel serve:
```bash
php artisan serve
```
Lalu akses: http://localhost:8000/src/

## 👤 Demo Credentials

### Admin
- **NIP**: 198001012006041001
- **Password**: password123

### User (Pegawai)
- **NIP**: 199001012015041001
- **Password**: password123

## 📊 Alur Sistem

### Alur Peminjaman (User)

1. User login dengan NIP dan password
2. Klik menu "Scan QR Code"
3. Izinkan akses kamera
4. Arahkan kamera ke QR Code barang
5. Sistem menampilkan detail barang
6. Jika tersedia, klik "Ajukan Peminjaman"
7. Status barang berubah menjadi "Dipinjam"
8. Data tersimpan di tabel `barang` dan `histori_peminjaman`

### Alur Pengembalian (Admin)

1. Admin login
2. Klik menu "Scan Pengembalian"
3. Scan QR Code barang yang dikembalikan
4. Sistem otomatis memproses pengembalian
5. Status barang kembali "Tersedia"
6. Waktu kembali tercatat di database

## 🗄️ Database Schema

Database menggunakan **Laravel Migrations** yang terletak di `database/migrations/`:

### Migration Files

1. **2024_01_01_000001_create_users_table.php** - Tabel users
2. **2024_01_01_000002_create_barang_table.php** - Tabel barang
3. **2024_01_01_000003_create_histori_peminjaman_table.php** - Tabel histori

### Tabel `users`
- `id` - Primary key
- `nip` - NIP pegawai (unique)
- `nama` - Nama pegawai
- `password` - Password (hashed)
- `role` - user / admin

### Tabel `barang`
- `id` - Primary key
- `nomor_bmn` - Nomor BMN (unique)
- `brand` - Merek barang
- `tipe` - Tipe/model barang
- `kondisi_terakhir` - baik / rusak ringan / rusak berat
- `ketersediaan` - tersedia / dipinjam
- `peminjam_terakhir` - Nama peminjam
- `waktu_pinjam` - Timestamp peminjaman
- `waktu_kembali` - Timestamp pengembalian

### Tabel `histori_peminjaman`
- `id` - Primary key
- `nomor_bmn` - Foreign key ke barang
- `nip_peminjam` - Foreign key ke users
- `nama_peminjam` - Nama peminjam
- `waktu_pinjam` - Timestamp peminjaman
- `waktu_kembali` - Timestamp pengembalian
- `status` - dipinjam / dikembalikan

## 🔍 Contoh Query

### Cek barang tersedia
```sql
SELECT * FROM barang WHERE ketersediaan = 'tersedia';
```

### Lihat peminjaman aktif
```sql
SELECT * FROM histori_peminjaman WHERE status = 'dipinjam';
```

### Histori peminjaman user tertentu
```sql
SELECT * FROM histori_peminjaman 
WHERE nip_peminjam = '199001012015041001'
ORDER BY waktu_pinjam DESC;
```

## 🔧 Troubleshooting

### Kamera tidak bisa diakses
- Pastikan browser memiliki izin akses kamera
- Gunakan HTTPS atau localhost (HTTP5 camera API requirement)
- Coba browser lain (Chrome/Firefox recommended)

### Database connection error
- Periksa konfigurasi di `src/config/database.php`
- Pastikan MySQL service berjalan
- Pastikan database `pinjam_bmn` sudah dibuat

### QR Code tidak terbaca
- Pastikan QR Code jelas dan tidak blur
- Coba adjust jarak kamera ke QR Code
- Pastikan pencahayaan cukup

## 📱 Penggunaan di Smartphone

1. Pastikan smartphone terhubung ke jaringan yang sama dengan server
2. Akses menggunakan IP server, contoh: `http://192.168.1.100/pinjam_qr/src/`
3. Untuk production, gunakan HTTPS agar kamera bisa diakses

## 🔐 Keamanan

- Password di-hash menggunakan `password_hash()` PHP
- Prepared statements untuk mencegah SQL injection
- Session-based authentication
- Role-based access control (user/admin)

## 📝 Catatan Penting

- QR Code pada barang harus berisi **nomor BMN** yang sesuai dengan database
- Sistem ini TIDAK generate QR Code baru, hanya membaca QR yang sudah ada
- Untuk production, ganti password default semua user
- Backup database secara berkala

## 👨‍💻 Untuk Mahasiswa Magang

Sistem ini dirancang sederhana dan mudah dipahami:
- Menggunakan PHP native (bukan framework)
- Struktur folder yang jelas
- Kode terdokumentasi dengan baik
- Menggunakan Tailwind CSS untuk styling yang mudah

Anda bisa mengembangkan lebih lanjut dengan menambahkan fitur:
- Export laporan ke Excel/PDF
- Notifikasi email/WhatsApp
- Reminder otomatis untuk pengembalian
- Upload foto kondisi barang
- Dan lain-lain

## 📄 Lisensi

Sistem ini dibuat untuk keperluan internal BPS.

---

**Dibuat dengan ❤️ untuk BPS**
