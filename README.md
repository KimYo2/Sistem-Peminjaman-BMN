# Sistem Peminjaman Barang BMN dengan QR Code

Aplikasi web untuk peminjaman inventaris BMN BPS dengan scan QR Code, persetujuan admin, antrian tunggu, perpanjangan, dan stock opname.

## Fitur Utama

### User (Pegawai)
- Login berbasis NIP & password.
- Scan QR barang -> lihat detail lengkap (kode BMN, NUP, brand, tipe, kondisi, status).
- Ajukan peminjaman (status awal `menunggu`).
- Antrian tunggu (waitlist) bila barang tidak tersedia; posisi antrian ditampilkan, bisa batal sendiri.
- Perpanjangan masa pinjam dengan alasan & durasi (default 7 hari).
- Histori pribadi paginasi, lengkap dengan status perpanjangan.
- Dashboard user: ringkasan pinjaman aktif, jatuh tempo terdekat, overdue, riwayat terakhir.
- Pengembalian via `/return` (scan); bisa tandai rusak → otomatis buat tiket kerusakan.
- Auto-notify: saat barang dikembalikan, antrian tunggu pertama otomatis dibuatkan permintaan pinjam baru.

### Admin
- Dashboard statistik: total/tersedia/dipinjam, pinjaman aktif, overdue list, top items & borrowers, rata-rata durasi pinjam.
- Barang: CRUD + pilih PIC, impor CSV, enum ketersediaan dan kondisi yang dinormalisasi.
- Peminjaman:
  - Setujui/tolak pengajuan.
  - Kelola pengembalian (melalui return flow).
  - Kelola perpanjangan (setujui/tolak, perpanjang jatuh tempo).
  - Ekspor histori ke CSV aman-Excel (anti CSV injection, BOM UTF-8).
- Antrian tunggu: otomatis diproses saat barang kembali.
- Tiket kerusakan: status (open/diproses/selesai), prioritas (low/medium/high), penugasan admin, target selesai, riwayat log.
- Stock opname:
  - Mulai sesi (satu sesi berjalan sekaligus).
  - Scan barang per sesi (found/missing), statistik ditemukan/missing.
  - Selesaikan sesi dengan catatan.
  - Ekspor hasil per sesi ke CSV.
- Audit log untuk aksi penting (approve/reject/export/stock-opname/tiket).

### Antarmuka & Tema
- BPS color scheme, light mode khusus (#cfcfcf) dan dark mode penuh.
- Toggle tema persist di localStorage + sessionStorage + cookie (`public/js/theme.js`), fallback bila Tailwind tidak termuat.
- Blade + Tailwind CDN; aset tambahan di `public/js` dan `public/css`.

## Struktur Folder
```
pinjam_qr/
├─ app/
│  ├─ Http/
│  │  ├─ Controllers/
│  │  │  ├─ Admin/            # Barang, Histori, StockOpname, TiketKerusakan
│  │  │  ├─ User/             # Dashboard, Scan, Barang, Histori, Waitlist
│  │  │  ├─ Auth/             # Login/Logout
│  │  │  └─ ReturnController.php
│  │  └─ Requests/            # Form Request Admin & User
│  ├─ Models/                 # Barang, HistoriPeminjaman, Waitlist, TiketKerusakan(+Log), StockOpname*, AuditLog, User
│  └─ Services/               # BmnParser, BarangImportService
├─ database/
│  └─ migrations/             # Schema (perpanjangan, waitlist, tiket kerusakan, stock opname, audit)
├─ resources/
│  └─ views/                  # Blade: admin/, user/, auth/, return/
├─ public/
│  ├─ js/                     # theme.js, scanner, helper JS
│  ├─ css/                    # tambahan CSS
│  └─ index.php
├─ routes/
│  └─ web.php                 # Definisi route (user/admin/return)
├─ tests/
│  ├─ Feature/                # Borrow/Return/Waitlist/Extend/Reject/Export/StockOpname/Ticket flows
│  └─ Unit/
├─ DOCUMENTATION.md           # Catatan tema & warna BPS
└─ README.md
```

## Arsitektur Singkat
- Backend: Laravel 12, MySQL.
- Frontend: Blade + Tailwind CDN, Alpine.js, html5-qrcode.
- Service/helper penting: `BmnParser` (format kode BMN), `BarangImportService`.
- Middleware: auth + `EnsureAdmin`.
- Model utama: Barang, HistoriPeminjaman, Waitlist, TiketKerusakan (+Log), StockOpnameSession/Item, AuditLog, User.

## Alur Penting
- Peminjaman: scan → detail → ajukan → admin setujui/tolak → status `dipinjam` → jatuh tempo default +7 hari.
- Perpanjangan: user ajukan; admin setujui/tolak (ubah jatuh tempo, rekam alasan/penolak).
- Pengembalian: scan `/return`; bila rusak → tiket kerusakan; setelah update stok, antrian tunggu pertama otomatis dibuatkan permintaan pinjam.
- Waitlist: join/cancel oleh user; status `aktif/notified/fulfilled/cancelled`.
- Stock opname: start sesi → scan setiap BMN → selesai → ekspor CSV.

## Instalasi & Menjalankan
### Dari GitHub (clone)
1) Clone repo:
   ```
   git clone https://github.com/KimYo2/Sistem-Peminjaman-BMN.git
   cd Sistem-Peminjaman-BMN
   ```
2) Salin `.env` dari contoh:
   ```
   cp .env.example .env
   ```
3) Lanjut ke langkah konfigurasi DB di bawah.

### Dari ZIP
1) Download ZIP dari GitHub, ekstrak.
2) Buka folder hasil ekstrak.
3) Duplikasi `.env.example` menjadi `.env`.
4) Lanjut ke langkah konfigurasi DB di bawah.

1) Salin `.env`, set MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pinjam_qr
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2) Migrasi & seed:
   ```
   php artisan migrate
   php artisan db:seed
   ```
   (Mencakup tabel baru: waitlists, tiket_kerusakan(+logs), stock_opname_sessions/items, kolom perpanjangan di histori.)
3) Jalankan:
   ```
   php artisan serve --host=0.0.0.0 --port=8000
   ```
4) Akses: `http://pinjam_qr.test` atau IP LAN sesuai host.
5) Demo akun:
   - Admin: NIP `198001012006041001` / `password123`
   - User: NIP `199001012015041001` / `password123`

## Database Ringkas
- `histori_peminjaman`: status, waktu_pinjam/kembali, jatuh_tempo, kondisi_awal/kembali, alasan/reason penolakan, kolom perpanjangan (status, hari, diminta/disetujui/ditolak, alasan).
- `waitlists`: kode_barang, nup, nip_peminjam, status, requested/notified/fulfilled/cancelled timestamps.
- `tiket_kerusakan` + `tiket_kerusakan_logs`: priority, assigned_to, target_selesai_at, admin_notes, closed_at.
- `stock_opname_sessions` + `stock_opname_items`: status found/missing, expected/actual kondisi, scanned_by/at.
- `audit_logs`: user_id, action, entity, meta JSON.

## Pengujian
- Jalankan semua tes: `php artisan test`
- Cakupan utama: Borrow/Return/Waitlist/Extend/Reject/Export histori, Stock Opname flow, Ticket upgrade (`tests/Feature/*FlowTest.php`).

## Catatan
- QR yang dibaca mengikuti format BPS (`kode_barang-nup`); parser toleran pada variasi `INV-...`.
- Ekspor CSV memakai BOM UTF-8 dan proteksi prefix `'=+-@` agar aman di Excel/Sheets.
- Gunakan HTTPS atau localhost agar kamera bisa diakses untuk scan.
