# Panduan Deployment — Sistem Peminjaman BMN

Panduan ini menjelaskan cara men-deploy aplikasi **Sistem Peminjaman BMN** dari lingkungan pengembangan lokal ke server nyata — baik VPS, shared hosting, maupun server lokal kantor.

Panduan ini ditujukan untuk:
- Staff IT atau admin di lingkungan kantor BPS
- Mahasiswa / magang yang ingin mencoba deploy ke server sungguhan
- Developer yang ingin berpindah dari `localhost` ke production

---

## 📋 Prasyarat Server

Pastikan server yang akan digunakan memenuhi spesifikasi minimum berikut:

| Komponen | Minimum | Rekomendasi |
|----------|---------|-------------|
| PHP | 8.2 | 8.5 |
| MySQL | 8.0 | 8.0+ |
| MariaDB | 10.3 | 10.6+ |
| Composer | 2.x | terbaru |
| Node.js | 18.x | 20.x (LTS) |
| RAM | 512 MB | 1 GB+ |
| Storage | 1 GB free | 5 GB+ |

### PHP Extensions yang Diperlukan

- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_MySQL
- Tokenizer
- XML
- GD *(untuk upload foto barang & avatar)*

### Web Server

- **Apache** — pastikan `mod_rewrite` aktif
- **Nginx** — disarankan untuk VPS

---

## 🖥️ Opsi Server yang Umum Digunakan

### Opsi 1: VPS (Virtual Private Server)

Contoh penyedia: DigitalOcean, Vultr, Niagahoster VPS, IDCloudHost

- Kontrol penuh atas konfigurasi server
- Perlu setup manual (Nginx/Apache, PHP, MySQL)
- Paling fleksibel dan stabil untuk production
- Bisa diakses dari mana saja via internet
- **Estimasi biaya**: ~Rp 50.000–150.000/bulan

### Opsi 2: Shared Hosting (dengan akses SSH)

Contoh penyedia: Niagahoster, Hostinger, DomaiNesia

- Lebih mudah untuk memulai
- Kontrol versi PHP terbatas
- Beberapa fitur Laravel mungkin dibatasi
- Pastikan penyedia mendukung **PHP 8.2+** dan akses **SSH**

### Opsi 3: Server Lokal Kantor (LAN Server)

- Menggunakan PC/laptop lama yang dijadikan server
- Hanya bisa diakses dari jaringan internal kantor
- Cocok untuk penggunaan internal BPS
- Tidak memerlukan biaya hosting
- Data tidak keluar dari jaringan kantor
- Bisa menggunakan **Laragon** atau **XAMPP** sebagai base, atau install Apache + PHP + MySQL secara manual

---

## 🚀 Deployment ke VPS (Ubuntu 22.04)

### Step 1: Koneksi ke VPS

```bash
ssh root@IP_SERVER_KAMU
```

### Step 2: Update sistem & install dependensi

```bash
apt update && apt upgrade -y
apt install -y curl git unzip nginx mysql-server

# Install PHP 8.5 + extensions
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.5 php8.5-fpm php8.5-mysql php8.5-xml \
  php8.5-mbstring php8.5-curl php8.5-zip php8.5-bcmath \
  php8.5-gd php8.5-tokenizer php8.5-fileinfo

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

### Step 3: Clone repositori

```bash
cd /var/www
git clone https://github.com/KimYo2/Sistem-Peminjaman-BMN.git bmn
cd bmn
```

### Step 4: Install dependencies

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### Step 5: Setup environment

```bash
cp .env.example .env
php artisan key:generate

# Edit konfigurasi database dan app
nano .env
```

Isi minimal yang harus disesuaikan:

```env
APP_NAME="Sistem Peminjaman BMN"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://IP_ATAU_DOMAIN_KAMU

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pinjam_qr
DB_USERNAME=bmn_user
DB_PASSWORD=password_kuat_kamu
```

### Step 6: Setup database

```bash
mysql -u root -p
```

Di dalam MySQL, jalankan perintah berikut:

```sql
CREATE DATABASE pinjam_qr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bmn_user'@'localhost' IDENTIFIED BY 'password_kuat_kamu';
GRANT ALL PRIVILEGES ON pinjam_qr.* TO 'bmn_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Kembali ke terminal, jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

### Step 7: Setup storage

```bash
php artisan storage:link
chown -R www-data:www-data /var/www/bmn/storage
chown -R www-data:www-data /var/www/bmn/bootstrap/cache
chmod -R 775 /var/www/bmn/storage
chmod -R 775 /var/www/bmn/bootstrap/cache
```

### Step 8: Konfigurasi Nginx

Buat file konfigurasi baru:

```bash
nano /etc/nginx/sites-available/bmn
```

Paste konfigurasi berikut:

```nginx
server {
    listen 80;
    server_name IP_ATAU_DOMAIN_KAMU;
    root /var/www/bmn/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 10M;
}
```

Aktifkan konfigurasi dan reload Nginx:

```bash
ln -s /etc/nginx/sites-available/bmn /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

### Step 9: Optimize Laravel untuk production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 10: Test akses

Buka browser dan akses:

```
http://IP_ATAU_DOMAIN_KAMU
```

Login menggunakan akun default dari seeder (lihat [DOCUMENTATION.md](DOCUMENTATION.md) bagian *Default Login Credentials*).

---

## 🏠 Deployment ke Server Lokal Kantor (Windows + Laragon)

Pilihan ini cocok untuk kantor BPS yang ingin menjalankan sistem di PC internal tanpa biaya hosting.

### Prasyarat

- **Laragon Full** — [laragon.org/download](https://laragon.org/download/) (sudah termasuk PHP, MySQL, Apache)
- **Git for Windows** — [git-scm.com](https://git-scm.com)
- **Node.js 20.x** — [nodejs.org](https://nodejs.org)
- **Composer** — [getcomposer.org](https://getcomposer.org)

### Langkah-langkah

**1. Install Laragon** dan pastikan PHP 8.2+ aktif melalui menu *Laragon → PHP*.

**2. Buka Laragon Terminal**, lalu clone repositori:

```bash
cd C:\laragon\www
git clone https://github.com/KimYo2/Sistem-Peminjaman-BMN.git bmn
cd bmn
```

**3. Install dependencies:**

```bash
composer install
npm install && npm run build
```

**4. Setup environment:**

```bash
copy .env.example .env
php artisan key:generate
```

Buka file `.env` dengan teks editor, sesuaikan bagian database:

```env
APP_NAME="Sistem Peminjaman BMN"
APP_ENV=local
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pinjam_qr
DB_USERNAME=root
DB_PASSWORD=
```

**5. Buat database:**

Buka browser → `http://localhost/phpmyadmin` → buat database baru bernama `pinjam_qr`.

**6. Migrasi dan seed:**

```bash
php artisan migrate --seed
php artisan storage:link
```

**7. Akses aplikasi:**

Klik kanan ikon Laragon di system tray → **www** → **bmn**, atau buka browser:

```
http://bmn.test          (jika pretty URL aktif di Laragon)
http://localhost/bmn/public
```

**8. Agar bisa diakses dari HP atau komputer lain di jaringan kantor:**

- Cari IP komputer server: buka Command Prompt → ketik `ipconfig` → catat **IPv4 Address** (contoh: `192.168.1.10`)
- Dari perangkat lain di jaringan yang sama, buka browser dan akses:
  ```
  http://192.168.1.10/bmn/public
  ```
- Pastikan **Windows Firewall** mengizinkan koneksi masuk di port 80.
  Jika diblokir, tambahkan rule via PowerShell (run as Administrator):
  ```powershell
  netsh advfirewall firewall add rule name="Laragon HTTP" dir=in action=allow protocol=TCP localport=80
  ```

---

## 🔄 Update Aplikasi (setelah ada perubahan kode)

Gunakan langkah-langkah ini setiap kali ada pembaruan kode dari repository:

```bash
cd /var/www/bmn          # sesuaikan dengan folder project

# Pull perubahan terbaru
git pull origin main

# Install dependency baru (jika ada perubahan composer.json atau package.json)
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Jalankan migrasi baru (jika ada file migrasi baru)
php artisan migrate

# Clear dan rebuild cache
php artisan optimize:clear
php artisan optimize

# Fix permission (VPS/Linux only)
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔒 Keamanan Dasar (Wajib untuk Production)

- **Set `APP_DEBUG=false`** di `.env` — jangan pernah `true` di production
- **Set `APP_ENV=production`** di `.env`
- **Ganti semua password default** dari seeder setelah deploy pertama
- Pastikan file `.env` tidak bisa diakses publik (Nginx/Apache sudah menangani ini secara default)
- **Aktifkan HTTPS** jika menggunakan domain — gunakan Let's Encrypt (gratis):
  ```bash
  apt install certbot python3-certbot-nginx
  certbot --nginx -d domain-kamu.com
  ```
- **Backup database secara rutin:**
  ```bash
  mysqldump -u bmn_user -p pinjam_qr > backup_$(date +%Y%m%d).sql
  ```

---

## ❗ Troubleshooting Umum

### Error: gambar/foto tidak muncul / broken image

```bash
php artisan storage:link
chmod -R 775 storage
```

### Error: "500 Server Error" setelah deploy

- Pastikan `APP_KEY` sudah di-generate:
  ```bash
  php artisan key:generate
  ```
- Periksa permission folder storage:
  ```bash
  chmod -R 775 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```
- Lihat detail error di log:
  ```bash
  tail -50 storage/logs/laravel.log
  ```

### Error: "Class not found" / Composer autoload error

```bash
composer dump-autoload
composer install --optimize-autoloader
```

### Tampilan CSS tidak muncul / layout berantakan

```bash
npm run build
php artisan view:clear
```

### Error saat menjalankan migrasi

```bash
# Cek status migrasi
php artisan migrate:status

# Jalankan ulang migrasi
php artisan migrate
```

### Tidak bisa upload foto (foto tidak tersimpan)

```bash
php artisan storage:link
chown -R www-data:www-data storage   # Linux/VPS
chmod -R 775 storage
```

### Halaman menampilkan "No input file specified" (Apache)

Pastikan file `.htaccess` ada di folder `public/` dan `mod_rewrite` aktif:

```bash
a2enmod rewrite
systemctl reload apache2
```

---

## 📞 Informasi Teknis

| Keterangan | Nilai |
|-----------|-------|
| Laravel version | 12.x |
| PHP minimum | 8.2 (rekomendasi: 8.5) |
| Database | MySQL 8.0+ / MariaDB 10.3+ |
| Port development | 8000 |
| Port production | 80 (HTTP) / 443 (HTTPS) |
| Path storage | `storage/app/public/` |
| Path log | `storage/logs/laravel.log` |
| Symlink storage | `public/storage` → `storage/app/public` |

---

> [!IMPORTANT]
> Selalu ubah password default setelah deployment pertama.
> Akun default dari seeder (`admin@bps.go.id` / `password123`) hanya untuk keperluan development dan testing.

> [!WARNING]
> Jangan pernah set `APP_DEBUG=true` di server production.
> Detail error bisa terekspos ke publik dan membahayakan keamanan sistem.

> [!TIP]
> Untuk penggunaan internal BPS, **Opsi 3 (Server Lokal Kantor)** adalah pilihan paling praktis karena tidak membutuhkan biaya hosting dan data tidak keluar dari jaringan kantor.
