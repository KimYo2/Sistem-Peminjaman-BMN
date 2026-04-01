# SIAP — Sistem Inventaris Aset Perkantoran

Sistem manajemen inventaris dan peminjaman aset perkantoran berbasis web untuk BPS Kabupaten Jepara. Aplikasi ini memungkinkan pegawai meminjam dan mengembalikan inventaris kantor melalui scan QR Code, dengan alur persetujuan admin, antrian tunggu otomatis, dan pencatatan audit yang lengkap.

---

## Fitur Utama

- **Peminjaman & Pengembalian** — Ajukan peminjaman barang via scan QR Code; kembalikan melalui halaman return scan
- **QR Code Scanner** — Scan label QR pada barang BMN untuk melihat detail dan mengajukan pinjam
- **Approval Workflow** — Admin menyetujui atau menolak setiap pengajuan peminjaman
- **Perpanjangan Masa Pinjam** — Pegawai dapat mengajukan perpanjangan dengan alasan; admin menyetujui/menolak
- **Waitlist (Antrian Tunggu)** — Bila barang sedang dipinjam, pegawai bisa masuk antrian; saat barang kembali, antrian pertama otomatis diproses
- **Tiket Kerusakan** — Laporan kerusakan otomatis dibuat saat barang dikembalikan dalam kondisi rusak; dilengkapi prioritas, penugasan, dan log riwayat
- **Stock Opname** — Mulai sesi, scan barang satu per satu (found/missing), selesaikan sesi, dan ekspor hasilnya
- **Audit Log** — Pencatatan otomatis untuk aksi penting: approve, reject, export, stock opname, tiket kerusakan
- **Export CSV** — Ekspor histori peminjaman dan hasil stock opname ke file CSV (UTF-8 BOM, anti CSV injection)
- **Dashboard & Statistik** — Dashboard terpisah untuk admin dan pegawai dengan ringkasan data real-time
- **Dark Mode** — Tema terang dan gelap dengan BPS color scheme, toggle persist di browser

---

## Tech Stack

| Layer        | Teknologi                        |
| ------------ | -------------------------------- |
| Backend      | Laravel 12, PHP 8.2+             |
| Frontend     | Blade Templates, Tailwind CSS    |
| Database     | MySQL                            |
| Build Tool   | Vite                             |
| Library      | Carbon, html5-qrcode, Alpine.js  |

---

## Struktur Proyek

Lihat dokumentasi lengkap di [DOCUMENTATION.md](DOCUMENTATION.md).

---

## Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/KimYo2/siap-inventaris.git
cd siap-inventaris
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siap
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seed Database

```bash
php artisan migrate
php artisan db:seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`.

### Akun Demo

| Role  | NIP                  | Password      |
| ----- | -------------------- | ------------- |
| Admin | `198001012006041001` | `password123` |
| User  | `199001012015041001` | `password123` |

---

## Struktur Role

### Admin

- Dashboard statistik (total barang, tersedia, dipinjam, overdue, top items & borrowers)
- CRUD barang inventaris, termasuk import CSV dan penunjukan PIC
- Menyetujui atau menolak pengajuan peminjaman dan perpanjangan
- Mengelola tiket kerusakan (status, prioritas, penugasan, target selesai)
- Menjalankan sesi stock opname dan ekspor hasilnya
- Ekspor histori peminjaman ke CSV
- Melihat audit log seluruh aksi penting

### User (Pegawai)

- Login menggunakan NIP dan password
- Scan QR Code barang untuk melihat detail dan mengajukan peminjaman
- Melihat histori peminjaman pribadi beserta status perpanjangan
- Mengajukan perpanjangan masa pinjam
- Mendaftar antrian tunggu (waitlist) bila barang tidak tersedia
- Mengembalikan barang via scan dan melaporkan kerusakan jika ada
- Dashboard pribadi: pinjaman aktif, jatuh tempo terdekat, overdue

---

## Alur Peminjaman

1. **Scan QR Code** — Pegawai membuka halaman scan dan mengarahkan kamera ke label QR pada barang BMN
2. **Lihat Detail Barang** — Sistem menampilkan informasi lengkap: kode BMN, NUP, brand, tipe, kondisi, dan status ketersediaan
3. **Ajukan Peminjaman** — Pegawai mengajukan peminjaman; status awal tercatat sebagai `menunggu`
4. **Persetujuan Admin** — Admin meninjau pengajuan dan menyetujui atau menolak dengan alasan
5. **Barang Dipinjam** — Setelah disetujui, status berubah menjadi `dipinjam` dengan jatuh tempo default 7 hari
6. **Perpanjangan** *(opsional)* — Pegawai dapat mengajukan perpanjangan masa pinjam; admin menyetujui atau menolak
7. **Pengembalian** — Pegawai membuka halaman return, scan QR, dan konfirmasi pengembalian
8. **Lapor Kerusakan** *(jika ada)* — Saat mengembalikan, pegawai dapat menandai barang rusak; sistem otomatis membuat tiket kerusakan
9. **Antrian Diproses** — Setelah barang kembali, sistem otomatis memproses pegawai pertama dalam waitlist

---

## Pengujian

```bash
php artisan test
```

Test suite mencakup alur: peminjaman, pengembalian, waitlist, perpanjangan, penolakan, export CSV, stock opname, dan tiket kerusakan.

---

## Catatan Teknis

- Format QR mengikuti standar BPS (`kode_barang-nup`); parser mendukung variasi format `INV-...`
- Export CSV menggunakan BOM UTF-8 dan sanitasi prefix `=`, `+`, `-`, `@` untuk keamanan di Excel/Google Sheets
- Scan kamera memerlukan HTTPS atau localhost agar browser mengizinkan akses kamera

---

## Screenshot

*[ Coming soon ]*

---

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## Author

**Danang Yoga Andimas**
Magang — BPS Kabupaten Jepara
