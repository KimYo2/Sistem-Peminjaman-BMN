# Dokumentasi SIAP — Sistem Inventaris Aset Perkantoran

> Sistem manajemen inventaris dan peminjaman aset perkantoran berbasis web, dibangun dengan Laravel 12 untuk BPS Kabupaten Jepara.

---

## 📋 Daftar Isi

1. [Overview](#1-overview)
2. [Fitur Utama](#2-fitur-utama)
3. [Struktur Folder Penting](#3-struktur-folder-penting)
4. [Instalasi & Setup](#4-instalasi--setup)
5. [Akses dari HP / Jaringan Lokal](#5-akses-dari-hp--jaringan-lokal)
6. [BPS Color Scheme](#6-bps-color-scheme)
7. [Dark Mode](#7-dark-mode)
8. [Default Login Credentials](#8-default-login-credentials)
9. [Changelog / Riwayat Update](#9-changelog--riwayat-update)

---

## 1. Overview

**SIAP (Sistem Inventaris Aset Perkantoran)** adalah aplikasi web manajemen inventaris dan peminjaman aset perkantoran yang dirancang khusus untuk BPS Kabupaten Jepara.

| Atribut | Detail |
|---------|--------|
| **Nama Proyek** | SIAP — Sistem Inventaris Aset Perkantoran |
| **Dibangun untuk** | BPS Kabupaten Jepara |
| **Tujuan** | Manajemen inventaris & peminjaman barang via QR Code |
| **Framework** | Laravel 12 |
| **Bahasa** | PHP 8.2+ |
| **Database** | MySQL |
| **Frontend** | Blade + Tailwind CSS (CDN) + Alpine.js |
| **Build Tool** | Vite |
| **Repository** | https://github.com/KimYo2/siap-inventaris |

### Dua Role Pengguna

- **Admin** — mengelola inventaris, menyetujui peminjaman, melihat laporan
- **User (Pegawai)** — melakukan pengajuan peminjaman via scan QR Code

---

## 2. Fitur Utama

### Manajemen Barang
- CRUD barang inventaris (kode BMN, NUP, brand, tipe, kondisi)
- Import massal dari file CSV
- Upload foto barang (opsional, disimpan di Laravel storage)
- Filter inventaris: ketersediaan, kategori, ruangan, status barang
- Cetak label QR Code per barang atau batch (bulk)
- Status barang: Aktif, Rusak Total, Hilang, Dihapuskan

### Kategori & Ruangan
- CRUD kategori barang
- CRUD ruangan/lokasi penyimpanan

### Peminjaman
- Pengajuan peminjaman oleh user via scan QR Code
- Alur status: `menunggu` → `disetujui` → `aktif` → `dikembalikan`
- Persetujuan/penolakan oleh admin
- Perpanjangan masa peminjaman (dengan approval admin)
- Jatuh tempo & notifikasi overdue

### Waitlist / Antrian
- Waitlist otomatis jika barang sedang dipinjam
- Notifikasi ke user berikutnya saat barang dikembalikan

### Pengembalian
- Scan QR untuk pengembalian
- Laporan kondisi barang saat kembali
- Pembuatan tiket kerusakan otomatis jika kondisi buruk

### Stock Opname
- Sesi stock opname dengan scan QR per barang
- Rekap hasil opname
- Export laporan ke CSV dan PDF

### Tiket Kerusakan
- Pembuatan tiket manual atau otomatis dari pengembalian
- Status tiket: open, in_progress, resolved
- Prioritas: low, medium, high, critical
- Assign ke admin tertentu
- Log aktivitas tiket

### Dashboard Admin
- Ringkasan statistik: total barang, peminjaman aktif, overdue, tiket terbuka
- Daftar Top Peminjam
- Grafik tren peminjaman 6 bulan
- Daftar peminjaman terbaru dan overdue

### Audit Log
- Setiap aksi admin (create, update, delete, approve, dsb.) tercatat otomatis
- Metadata: user, action, resource, detail, timestamp

### Dark Mode
- Toggle di navbar, persisten via `localStorage`
- Diterapkan ke seluruh halaman dengan `darkMode: 'class'` Tailwind

### QR Code Scan
- Scan via kamera menggunakan `html5-qrcode`
- Mendukung format QR BPS lama (format `INV-...*...*...*KODE*NUP`)
- Mendukung format QR yang di-generate sistem ini (`KODE-NUP`)

### Avatar User
- Foto profil opsional (upload ke Laravel storage)
- Fallback otomatis ke [UI Avatars](https://ui-avatars.com/) berdasarkan inisial nama
- User dapat upload/hapus foto dari halaman profil
- Admin dapat update avatar user dari panel manajemen user

---

## 3. Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          → BarangController, UserController, HistoriController,
│   │   │                     DashboardController, TiketKerusakanController,
│   │   │                     StockOpnameController, QrLabelController, dll.
│   │   ├── User/           → BarangController, HistoriController, ScanController,
│   │   │                     ProfileController, WaitlistController, dll.
│   │   ├── Auth/           → LoginController
│   │   └── Concerns/       → LogsAudit (trait)
│   ├── Middleware/
│   │   └── EnsureAdmin.php → Proteksi route admin
│   └── Requests/
│       └── Admin/          → Form Request validation
├── Models/
│   ├── Barang.php
│   ├── User.php
│   ├── HistoriPeminjaman.php
│   ├── Kategori.php
│   ├── Ruangan.php
│   ├── TiketKerusakan.php
│   ├── StockOpnameSession.php
│   ├── Waitlist.php
│   ├── Notifikasi.php
│   └── AuditLog.php
└── Services/
    ├── BmnParser.php           → Parser nomor BMN dari QR
    ├── BarangImportService.php → Import CSV barang
    └── KondisiHistoryService.php

resources/views/
├── layouts/
│   └── app.blade.php       → Layout utama (navbar, dark mode, notifikasi)
├── components/             → Komponen Blade (responsive-table, empty-state, dll.)
├── admin/
│   ├── barang/             → index, create, edit
│   ├── users/              → index, create, edit
│   ├── histori/            → index, pdf
│   ├── dashboard.blade.php
│   ├── opname/
│   └── tiket/
└── user/
    ├── barang/             → show
    ├── histori/            → index
    ├── profile/            → show
    └── dashboard.blade.php

database/
├── migrations/             → Semua file migrasi tabel
└── seeders/
    ├── UserSeeder.php
    └── DatabaseSeeder.php
```

---

## 4. Instalasi & Setup

### Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- Node.js & npm
- MySQL 8.0+

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/KimYo2/siap-inventaris.git
cd siap-inventaris

# 2. Install dependensi PHP
composer install

# 3. Install dependensi JavaScript
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate app key
php artisan key:generate
```

### Konfigurasi Database

Edit file `.env`, sesuaikan bagian berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siap
DB_USERNAME=root
DB_PASSWORD=
```

### Selesaikan Setup

```bash
# 6. Jalankan migrasi dan seed data awal
php artisan migrate --seed

# 7. Buat symlink untuk storage (foto barang & avatar)
php artisan storage:link

# 8. Build aset frontend
npm run build

# 9. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses di: `http://localhost:8000`

---

## 5. Akses dari HP / Jaringan Lokal

Untuk mengakses aplikasi dari perangkat lain dalam jaringan yang sama (misalnya HP untuk scan QR):

### 1. Jalankan server dengan host terbuka

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Cari IP laptop/PC

**Windows:**
```
ipconfig
```
Cari baris **IPv4 Address** (contoh: `192.168.1.5`)

**macOS/Linux:**
```
ifconfig
```

### 3. Akses dari HP

Buka browser di HP, ketik:
```
http://192.168.1.5:8000
```
(ganti dengan IP laptop Anda)

### Catatan Firewall

Windows Firewall mungkin memblokir koneksi masuk ke port 8000.
Jika HP tidak bisa terhubung:

1. Buka **Windows Defender Firewall** → *Advanced Settings*
2. Pilih **Inbound Rules** → **New Rule**
3. Pilih **Port** → TCP → Port `8000`
4. Pilih **Allow the connection**
5. Simpan aturan

Atau gunakan perintah PowerShell (run as Administrator):
```powershell
netsh advfirewall firewall add rule name="Laravel Dev" dir=in action=allow protocol=TCP localport=8000
```

---

## 6. BPS Color Scheme

Sistem menggunakan palet warna yang mengikuti panduan visual BPS (Badan Pusat Statistik).

### Color Palette

#### 🟦 Primary (Blue)
- **Warna utama**: `#2563EB` (Tailwind: `blue-600`)
- **Digunakan untuk**: Navbar, tombol utama, ikon header
- **Dark Mode**: `#3B82F6` (Tailwind: `blue-500`)

#### 🟧 Accent (Orange — Digunakan Secukupnya)
- **Warna aksen**: `#F59E0B` (Tailwind: `amber-500`)
- **Digunakan untuk**: Badge status "Dipinjam", indikator aktif
- ⚠️ **Jangan digunakan untuk**: Latar belakang utama, header, tombol primary

#### Status Colors

| Status | Warna | Kode | Tailwind |
|--------|-------|------|----------|
| Tersedia | Hijau | `#16A34A` | `green-600` |
| Dipinjam | Merah | `#DC2626` | `red-600` |
| Menunggu | Kuning | `#EAB308` | `yellow-500` |
| Selesai | Hijau | `#16A34A` | `green-600` |
| Overdue | Merah tua | `#B91C1C` | `red-700` |

---

## 7. Dark Mode

### Cara Kerja

- Tombol toggle (ikon matahari/bulan) tersedia di pojok kanan navbar
- Pilihan tema tersimpan di `localStorage` dan persisten antar sesi
- Mode gelap diterapkan dengan menambahkan class `dark` pada elemen `<html>`

### Konfigurasi Tailwind

```javascript
// Di dalam <script> di layout utama
window.tailwind.config = {
    darkMode: 'class'
};
```

### Kelas Dark Mode di Blade

Seluruh elemen menggunakan pola `dark:` prefix Tailwind secara konsisten:

```html
<div class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
    ...
</div>
```

### File Pendukung

- `public/js/theme.js` — Inisialisasi tema saat halaman dimuat (sebelum render)
- Dipasang di `<head>` sebelum Tailwind CSS agar tidak terjadi flicker

---

## 8. Default Login Credentials

Data akun berikut dibuat oleh `UserSeeder` saat menjalankan `php artisan migrate --seed`.

> ⚠️ **Ganti semua password ini segera setelah pertama kali login di lingkungan produksi.**

### Akun Admin

| Field | Nilai |
|-------|-------|
| Email | `admin@bps.go.id` |
| Password | `password123` |
| Role | Admin |
| NIP | `198001012006041001` |

| Field | Nilai |
|-------|-------|
| Email | `dewi.kartika@bps.go.id` |
| Password | `password123` |
| Role | Admin |
| NIP | `198505152008041002` |

### Akun User (Pegawai)

| Email | Password | NIP |
|-------|----------|-----|
| `budi.santoso@bps.go.id` | `password123` | `199001012015041001` |
| `siti.nurhaliza@bps.go.id` | `password123` | `199505012018041001` |
| `ahmad.wijaya@bps.go.id` | `password123` | `199808012020041001` |
| `rina.wulandari@bps.go.id` | `password123` | `199203152019041003` |
| `fajar.prasetyo@bps.go.id` | `password123` | `199607202021041004` |

> Akun `hendra.gunawan@bps.go.id` berstatus **nonaktif** (`is_active = false`) dan tidak dapat login.

---

## 9. Changelog / Riwayat Update

| Tanggal | Versi | Perubahan |
|---------|-------|-----------|
| 2025 | v0.1 | Build awal — arsitektur legacy PHP (public/src/) |
| 2026-01 | v1.0 | Migrasi penuh ke **Laravel 12** |
| 2026-01 | v1.1 | Tambah fitur: Stock Opname, Waitlist, Tiket Kerusakan |
| 2026-01 | v1.2 | Tambah: Audit Log, Dark Mode, BPS Color Scheme |
| 2026-01 | v1.3 | Tambah: Perpanjangan peminjaman, overdue notifikasi |
| 2026-04 | v1.4 | Tambah: **Foto Barang** (upload ke storage) |
| 2026-04 | v1.4 | Tambah: **Avatar User** (UI Avatars fallback + upload opsional) |
| 2026-04 | v1.5 | Perbaikan dokumentasi BPS QR Format (indeks segmen parser) |
| 2026-04 | v1.6 | Rename aplikasi: SIAP — Sistem Inventaris Aset Perkantoran |

---

## 10. Troubleshooting

### Kamera tidak muncul saat scan QR
Scan QR menggunakan `html5-qrcode` memerlukan akses kamera via **HTTPS atau localhost**.
Pastikan aplikasi diakses melalui `http://localhost:8000` atau domain HTTPS.

### Tampilan tidak update di HP setelah perubahan
Browser HP melakukan cache agresif. Lakukan hard refresh (tarik layar ke bawah),
atau buka di mode incognito. Sistem sudah menerapkan cache-busting pada file CSS/JS
dengan `?v=filemtime(...)` sehingga versi baru selalu terambil.

### Foreign key error saat `migrate:fresh`
Biasanya terjadi karena ada database lama (misalnya `pinjam_qr`) dengan FK lintas-database
yang menunjuk ke tabel di database baru. Solusi: hapus database lama sebelum migrasi.

```bash
# Via MySQL
DROP DATABASE IF EXISTS pinjam_qr;
```

### Storage tidak dapat diakses (foto/avatar)
Jalankan symlink storage jika belum:
```bash
php artisan storage:link
```

---

> **Last Updated**: 2026-04-01 | **Version**: 1.6
