# CHANGELOG

Semua perubahan penting pada SIAP Inventaris didokumentasikan di file ini.

Format mengacu pada [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
dan proyek ini mengikuti [Semantic Versioning](https://semver.org/lang/id/).

## [Unreleased]
> Perubahan yang belum dirilis secara resmi.
>
> Belum ada commit baru di luar rilis [v1.4.0].

---

## [v1.4.0] - 2026-04-01

### 🔧 Changed
- Memperbarui header cetak label QR menjadi identitas BPS Jepara, sehingga hasil cetak label siap dipakai untuk kebutuhan inventaris instansi tanpa perlu penyesuaian manual pada template.

### 🐛 Fixed
- Mengubah alur logout agar pengguna diarahkan kembali ke halaman landing publik, bukan ke halaman login, sehingga pengalaman keluar aplikasi lebih konsisten dengan adanya homepage publik.

### 📝 Documentation
- Menyapu seluruh referensi lama bertema BMN yang masih tertinggal di panduan deployment dan dokumentasi terkait, sehingga istilah produk, nama aplikasi, dan instruksi operasional sudah sepenuhnya konsisten menggunakan branding SIAP.

### 🔨 Chore
- Menambahkan package-lock.json agar versi dependensi frontend terkunci dan hasil instalasi npm lebih konsisten antar mesin developer maupun server build.
- Menambahkan migration_log.txt ke .gitignore untuk mencegah artefak log migrasi lokal ikut masuk ke riwayat Git dan mengotori pull request.

---

## [v1.3.0] - 2026-04-01

### ✨ Added
- Menambahkan selector jumlah data per halaman pada modul Barang beserta redesain area filter, sehingga admin bisa menelusuri data inventaris dalam volume besar dengan lebih cepat dan lebih nyaman.

### 🔧 Changed
- Mengganti identitas aplikasi menjadi SIAP — Sistem Inventaris Aset Perkantoran di elemen antarmuka utama, sehingga nama sistem, judul halaman, dan konteks penggunaan menjadi selaras dengan kebutuhan BPS Kabupaten Jepara.

### 🐛 Fixed
- Memperbaiki mekanisme cache busting aset frontend agar perubahan CSS dan JavaScript langsung terbaca setelah deploy, tanpa mengandalkan hard refresh dari pengguna.
- Membuka akses dev server melalui jaringan LAN, sehingga proses demo, UAT, dan pengecekan tampilan dari perangkat lain dalam satu jaringan kantor dapat dilakukan tanpa konfigurasi tambahan yang rumit.

### 📝 Documentation
- Menulis ulang README dengan dokumentasi yang lebih lengkap, termasuk struktur folder, ringkasan fitur, dan langkah penggunaan, sehingga onboarding developer baru menjadi lebih cepat.
- Memperbarui BPS_QR_FORMAT.md dan menulis ulang DOCUMENTATION.md untuk menyesuaikan format QR, arsitektur aplikasi, serta alur modul setelah modernisasi Laravel.
- Menambahkan DEPLOYMENT.md yang merangkum panduan deployment ke VPS, server lokal, dan prosedur update aplikasi, sehingga tim memiliki referensi operasional yang terpusat.
- Memperbarui README dan DOCUMENTATION agar konsisten dengan nama repositori siap-inventaris dan struktur proyek terbaru setelah fase konsolidasi.

---

## [v1.2.0] - 2026-04-01

### ✨ Added
- Menambahkan homepage publik dengan section hero, fitur utama, alur kerja, dan call-to-action, sehingga sistem memiliki halaman depan informatif yang dapat diakses sebelum login.
- Menambahkan dukungan foto barang dan avatar pengguna, sehingga identifikasi aset dan akun menjadi lebih visual di halaman daftar, detail, dan navigasi aplikasi.
- Menambahkan upload foto pada modul User Management untuk halaman tambah, ubah, dan daftar pengguna, sehingga admin dapat mengelola profil pengguna secara lebih lengkap dari satu modul.

### 🔧 Changed
- Mendesain ulang halaman login dengan branding SIAP, tata letak yang lebih modern, dan identitas visual yang lebih formal agar pintu masuk aplikasi terasa konsisten dengan citra instansi.

### 🐛 Fixed
- Mencerahkan background halaman login dengan mengurangi overlay berlebih dan meningkatkan visibilitas elemen dekoratif, sehingga formulir tetap mudah dibaca pada berbagai kondisi layar.
- Menyelaraskan migrasi tiket kerusakan dengan kebutuhan controller, termasuk penambahan kolom yang belum ada dan penyesuaian enum, sehingga proses migrate:fresh tidak lagi menghasilkan skema yang timpang.
- Memperbaiki referensi kolom pengguna pada modul tiket dari nama ke name sesuai skema tabel users yang aktual, sehingga pencatatan dan penampilan data penanggung jawab tidak lagi gagal. [BREAKING] Bagi kode kustom yang sebelumnya mengasumsikan kolom users.nama, integrasi perlu diperbarui ke users.name.

---

## [v1.1.0] - 2026-04-01

### ✨ Added
- Menambahkan navigasi mobile berbasis hamburger menu dan slide-in drawer, sehingga seluruh menu utama tetap dapat diakses dengan baik dari ponsel dan tablet.
- Menambahkan komponen tabel responsif yang reusable, sehingga halaman daftar data tetap terbaca pada layar kecil tanpa perlu membuat ulang struktur tabel per modul.
- Menambahkan empty state dan komponen status badge lintas modul, sehingga halaman tanpa data tetap komunikatif dan status entitas dapat dikenali cepat secara visual.
- Menambahkan tooltip pada tombol aksi modul Barang agar fungsi ikon lebih jelas, terutama pada tampilan yang mengutamakan kepadatan informasi.
- Mengelompokkan menu Pengguna, Kategori, dan Ruangan ke dalam dropdown Master Data untuk merapikan navigasi dan mengurangi kepadatan sidebar.
- Memoles halaman login dengan animasi, toggle dark mode, pembersihan kredensial demo, dan perbaikan overflow agar tampilan awal aplikasi terasa lebih profesional.

### 🔧 Changed
- Memindahkan query notifikasi dari Blade ke View Composer, sehingga logika pengambilan data lebih terpusat, lebih mudah dirawat, dan tidak menimbulkan query berulang di view.

### 🐛 Fixed
- Menyinkronkan warna dan elemen visual chart dashboard saat dark mode berubah, termasuk warna legend dan border doughnut chart, sehingga grafik tetap terbaca di kedua tema.
- Menambahkan kembali tautan navigasi desktop yang hilang untuk role pengguna sekaligus merapikan class dark mode pada semua link navigasi.
- Menghapus pembungkus max-width yang bertumpuk pada layout utama agar lebar konten kembali proporsional dan tidak terasa sempit di halaman tertentu.
- Menyempurnakan posisi tooltip action button dengan menghapus title bawaan browser dan memindahkan posisi tooltip ke area yang lebih stabil saat hover.
- Menyebarkan tanggal seeder histori peminjaman ke rentang Januari sampai Maret 2026 agar data dashboard lebih realistis saat dipakai demo atau pengujian.
- Menambahkan truncate ke seluruh seeder untuk mencegah duplikasi data saat proses seed dijalankan berulang selama pengembangan.

---

## [v1.0.0] - 2026-04-01

### ✨ Added
- Menambahkan modul User Management lengkap dengan kontrol akses berbasis peran, sehingga admin dapat mengelola akun, otorisasi, dan hak akses pengguna dari antarmuka aplikasi.
- Menambahkan fitur cetak label QR Code pada data barang, sehingga aset dapat diberi label dan dipindai kembali tanpa proses identifikasi manual satu per satu.
- Menambahkan modul Kategori dan Ruangan untuk pengelompokan aset, sehingga data barang dapat diklasifikasikan berdasarkan jenis dan lokasi fisik secara lebih terstruktur.
- Menambahkan dashboard admin berbasis Chart.js untuk visualisasi ringkasan inventaris, tren aktivitas, dan distribusi kondisi barang agar pengambilan keputusan lebih cepat.
- Menambahkan filter rentang tanggal pada histori beserta ekspor CSV, sehingga data aktivitas peminjaman dapat difilter per periode dan diolah lebih lanjut di luar aplikasi.
- Menambahkan fitur Riwayat Kondisi Barang yang merekam perubahan kondisi aset dari waktu ke waktu, sehingga jejak perubahan tetap terdokumentasi untuk audit internal.
- Menambahkan sistem notifikasi in-app agar pengguna menerima pemberitahuan penting langsung di aplikasi, termasuk perubahan status proses dan aktivitas yang relevan.
- Menambahkan ekspor PDF untuk laporan histori dan berita acara stock opname, sehingga dokumen resmi dapat dihasilkan langsung dari sistem dalam format siap cetak.
- Menambahkan workflow barang rusak atau hilang dan resolusi tiket kerusakan, termasuk alur pelaporan, tindak lanjut, dan penutupan tiket agar penanganan insiden aset lebih tertib.
- Menambahkan seeders lengkap untuk seluruh tabel agar instalasi baru, demo internal, dan pengujian fitur dapat dilakukan dengan data contoh yang lebih siap pakai.

### 🐛 Fixed
- Membersihkan kode debug dan merapikan tampilan login, sehingga codebase lebih aman dibawa ke lingkungan rilis dan halaman autentikasi tampil lebih rapi.
- Menambahkan guard hasColumn pada migrasi tertentu agar proses migrasi tetap idempotent dan tidak gagal ketika dijalankan ulang di lingkungan yang sudah pernah dimigrasikan.

### ♻️ Refactored
- Mengonsolidasikan seluruh migrasi menjadi 12 file Schema::create yang lebih bersih dan terstruktur, sehingga bootstrap database dari nol menjadi lebih stabil dan lebih mudah dipahami developer.

### 🗑️ Removed
- Menghapus arsitektur legacy berbasis folder src, misc, dan route /src/* dari codebase Laravel modern. [BREAKING] Integrasi lama yang masih mengakses route /src/* wajib dipindahkan ke route Laravel yang baru.

### 🔨 Chore
- Membuat backup sebelum konsolidasi migrasi sebagai langkah pengamanan perubahan struktur database skala besar.

---

## [v0.5.0] - 2026-02-18

### ✨ Added
- Menambahkan label Logged In pada antarmuka untuk memberi umpan balik visual bahwa sesi autentikasi aktif, berguna pada fase akhir rebuild saat validasi alur login masih intensif.
- Memperbarui langkah instalasi proyek agar prasyarat dan urutan setup lebih jelas bagi developer yang baru menarik repositori.

### 📝 Documentation
- Memperbarui README agar mencerminkan fitur-fitur terbaru hasil rebuild Laravel, sehingga dokumentasi tidak tertinggal dari implementasi aplikasi.
- Menambahkan bagian struktur folder pada README untuk membantu developer memahami penempatan modul, aset, dan file konfigurasi dengan lebih cepat.

---

## [v0.4.0] - 2026-02-10

### ✨ Added
- Menambahkan fitur pengajuan perpanjangan masa pinjam beserta alur persetujuan, sehingga peminjam tidak perlu membuat transaksi baru saat masa pinjam perlu diperpanjang secara sah.

### 🔨 Chore
- Menambahkan feature test untuk alur peminjaman, pengembalian, penolakan, ekspor, dan skenario perpanjangan pinjam, sehingga regresi fitur inti dapat dideteksi lebih cepat sebelum rilis.
- Menyesuaikan migrasi agar ramah terhadap SQLite, sehingga test suite dapat dijalankan pada environment pengujian yang lebih ringan dan mudah diotomasi.

---

## [v0.3.0] - 2026-02-10

### ✨ Added
- Memulai fase Laravel rebuild dengan penambahan fitur inti lanjutan dan pembaruan beberapa bagian kode, sehingga proyek bergerak dari fondasi awal menuju aplikasi inventaris yang lebih terstruktur.
- Menambahkan waitlist dan meningkatkan modul tiket, sehingga barang yang belum tersedia dan penanganan insiden aset dapat dikelola dengan alur yang lebih jelas.
- Menambahkan modul stock opname untuk membantu pencocokan data sistem dengan kondisi fisik barang di lapangan.

### 🔧 Changed
- Menstabilkan toggle dark mode, mengisolasi fallback theme, dan memperbarui tema gelap agar perubahan tema tidak merembet ke style lain secara tidak terduga.
- Merapikan alur ekspor CSV sekaligus membersihkan integrasi Google API yang tidak lagi diperlukan, sehingga kode lebih efisien dan dependensi eksternal lebih terkendali.

### 🐛 Fixed
- Melakukan rangkaian bug fix dan peningkatan efisiensi kode pada fase rebuild, sehingga alur dasar aplikasi Laravel lebih stabil sebelum fitur baru ditambahkan lebih jauh.

### 📝 Documentation
- Memperbarui README beberapa kali sepanjang fase rebuild untuk menyesuaikan perubahan struktur proyek, perbaikan bug, dan daftar fitur yang terus bertambah.

---

## [v0.2.0] - 2026-02-02

### ✨ Added
- Menambahkan fitur upload bukti pada proses scan return beserta pembaruan gaya antarmuka, sehingga pengembalian barang dapat dilengkapi eviden visual langsung dari alur pemindaian.
- Memperbarui aset logo aplikasi agar identitas visual proyek lebih jelas pada antarmuka dan dokumentasi.

### 🐛 Fixed
- Menambahkan parser QR BPS pada API scan pengembalian, sehingga hasil pemindaian dapat diurai dan dikembalikan dengan format data yang sesuai kebutuhan sistem.
- Memperbaiki alur pengembalian dengan konfirmasi manual, validasi upload, dan timestamp SQL yang benar, sehingga proses return lebih aman dan data tersimpan konsisten.
- Memperbaiki logika pembuatan tiket kerusakan, validasi backend, dan pengaturan timezone, sehingga laporan kerusakan tidak lagi gagal karena inkonsistensi data waktu atau input.

### 📝 Documentation
- Memperbaiki formatting struktur folder pada README agar dokumentasi awal lebih mudah dibaca.
- Memperbarui BPS_QR_FORMAT.md mengikuti logika parsing terbaru, sehingga tim memiliki referensi format QR yang sesuai implementasi saat itu.

### 🔨 Chore
- Memindahkan script PHP utilitas ke folder misc agar struktur repositori lebih rapi dan file pendukung tidak bercampur dengan kode aplikasi utama.
- Mencatat penghentian eksperimen dark mode awal sebagai satu perubahan konsolidatif meskipun tersimpan dalam dua commit identik, sehingga riwayat changelog tetap ringkas tanpa kehilangan konteks bahwa tema dikembalikan ke mode standar.

---

## [v0.1.0] - 2026-01-27

### ✨ Added
- Membuat fondasi awal proyek inventaris sebagai commit pertama, mencakup struktur kerja awal aplikasi dan basis pengembangan yang kemudian berevolusi menjadi SIAP Inventaris.

### 🐛 Fixed
- Menghapus kredensial sensitif dari repositori dan memperbarui aturan gitignore, sehingga rahasia konfigurasi tidak lagi ikut terlacak dan praktik keamanan source control menjadi lebih aman sejak awal.

---

## Catatan Versioning

Proyek ini menggunakan [Semantic Versioning](https://semver.org/lang/id/).
- **MAJOR**: perubahan tidak kompatibel ke belakang
- **MINOR**: penambahan fitur baru yang kompatibel
- **PATCH**: perbaikan bug yang kompatibel

**Proyek**: SIAP Inventaris — Sistem Inventaris Aset Perkantoran
**Instansi**: BPS Kabupaten Jepara
**Repository**: https://github.com/KimYo2/siap-inventaris
**Total Commit**: 77 commits (Jan 2026 – Apr 2026)
