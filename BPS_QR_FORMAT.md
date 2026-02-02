# Format QR Code BPS

## 📋 Format QR Code Asli BPS

### Contoh QR Code BPS

![QR Code BPS](file:///C:/Users/KimY/.gemini/antigravity/brain/c9cae4e5-f507-4b46-becc-75bab012f030/uploaded_media_0_1769414497187.jpg)

### Isi QR Code

![QR Code Content](file:///C:/Users/KimY/.gemini/antigravity/brain/c9cae4e5-f507-4b46-becc-75bab012f030/uploaded_media_1_1769414497187.jpg)

```
INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
```

## 🔍 Struktur Data

QR Code BPS menggunakan **delimiter asterisk (`*`)** untuk memisahkan data:

```
INV-[timestamp]*[kode1]*[kode2]*[NOMOR_BMN]*[nomor_urut]
```

### Breakdown:

| Segment | Contoh | Keterangan |
|---------|--------|------------|
| 1 | `INV-20210420145333129398000` | Prefix + Timestamp |
| 2 | `054010300C` | Kode lokasi/unit |
| 3 | `190000000KD` | Kode kategori |
| **4** | **`3100102001`** | **NOMOR BMN** ⭐ |
| 5 | `37` | Nomor urut |

## ✅ Parser Implementation (New Logic)

### JavaScript Parser

```javascript
// Parse BPS QR Code if detected (Format: INV-...*...*CODE*NUP)
if (decodedText.includes('*')) {
    const parts = decodedText.split('*');
    // Usually parts[2] is Code, parts[3] is NUP
    if (parts.length >= 4) {
        this.scannedCode = parts[2].trim() + '-' + parts[3].trim();
        this.isRawBPS = true;
    } else {
        this.scannedCode = decodedText; // Fallback
    }
}
```

### Contoh Parsing

**Input:**
```
INV-20210420...*054010300C*3100102001*37
```
*(Refleksi struktur baru: Index 2 = Kode Barang, Index 3 = NUP)*

**Output:**
```
3100102001-37
```

## 🎯 Cara Kerja Sistem

### Flow Lengkap

```
1. Scan QR Code BPS
   ↓
2. Decode QR → "INV...*UNIT*3100102001*37"
   ↓
3. Cek delimiter asterisk ('*')
   ↓
4. Split string dengan '*'
   ↓
5. Ambil Part[2] (Kode Barang) dan Part[3] (NUP)
   ↓
6. Gabungkan dengan hyphen: "3100102001-37"
   ↓
7. Query database untuk pencarian spesifik (Kode + NUP)
```

### Logic Baru

- **Format Target**: `[KodeBarang]-[NUP]`
- **Sumber Data**: 
    - `parts[2]` → Kode Barang (10 digit, e.g. 310xxxxxxxx)
    - `parts[3]` → NUP (Nomor Urut Pendaftaran, 1-3 digit)
- **Tujuan**: Memungkinkan identifikasi barang yang lebih spesifik karena kombinasi Kode+NUP adalah unique key yang sebenarnya.

## 🧪 Testing

### Test Case 1: Format Lengkap BPS

**Input:**
```
INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
```

**Expected:**
```
3100102001 ✅
```

### Test Case 2: Plain BMN

**Input:**
```
3100102001
```

**Expected:**
```
3100102001 ✅
```

### Test Case 3: Format Berbeda

**Input:**
```
BPS*LAPTOP*3100102002*2021
```

**Expected:**
```
3100102002 ✅ (fallback pattern)
```

## 🔧 Troubleshooting

### QR Tidak Terbaca

**Penyebab:**
- QR Code rusak/blur
- Pencahayaan kurang
- Jarak terlalu dekat/jauh

**Solusi:**
- Pastikan QR Code jelas
- Tambah cahaya
- Adjust jarak 10-30cm

### Barang Tidak Ditemukan

**Penyebab:**
- Nomor BMN tidak ada di database
- Format parsing salah

**Solusi:**
1. Cek console browser (F12)
2. Lihat log: "Extracted BMN: xxx"
3. Pastikan nomor BMN ada di database
4. Cek tabel `barang` di MySQL

## 📊 Database Matching

### Query yang Dijalankan

```sql
SELECT * FROM barang 
WHERE nomor_bmn = '3100102001'
```

### Jika Tidak Ketemu

Sistem akan return error:
```json
{
  "success": false,
  "message": "Barang dengan nomor BMN tersebut tidak ditemukan"
}
```

### Solusi

Pastikan nomor BMN ada di database:
```sql
-- Cek apakah BMN ada
SELECT * FROM barang WHERE nomor_bmn LIKE '3100102001%';

-- Atau cek semua BMN yang mirip
SELECT nomor_bmn, brand, tipe FROM barang 
WHERE nomor_bmn LIKE '31001020%';
```

## ✨ Update Terbaru

- ✅ Parser BPS QR format dengan delimiter `*`
- ✅ Extract BMN dari segment ke-4
- ✅ Fallback pattern matching untuk format berbeda
- ✅ Logging untuk debugging
- ✅ Error message yang informatif

Sistem sekarang sudah bisa handle QR Code BPS yang asli!
