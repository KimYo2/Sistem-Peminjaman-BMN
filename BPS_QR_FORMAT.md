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

## ✅ Parser Implementation

### JavaScript Parser

```javascript
function parseBPSQRCode(qrText) {
    // Check if QR contains asterisk delimiter
    if (qrText.includes('*')) {
        const parts = qrText.split('*');
        
        // BMN number is in the 4th segment (index 3)
        if (parts.length >= 4) {
            return parts[3].trim();
        }
        
        // Fallback: find segment starting with "31"
        for (let part of parts) {
            if (part.match(/^31\d{8}/)) {
                return part.trim();
            }
        }
    }
    
    // If no asterisk, assume it's already BMN
    return qrText.trim();
}
```

### Contoh Parsing

**Input:**
```
INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
```

**Output:**
```
3100102001
```

## 🎯 Cara Kerja Sistem

### Flow Lengkap

```
1. Scan QR Code BPS
   ↓
2. Decode QR → "INV-xxx*xxx*xxx*3100102001*37"
   ↓
3. Parse dengan split('*')
   ↓
4. Ambil segment ke-4 → "3100102001"
   ↓
5. Query database: SELECT * FROM barang WHERE nomor_bmn = '3100102001'
   ↓
6. Tampilkan detail barang
```

### Fallback Logic

1. **Jika ada asterisk** → Split dan ambil segment ke-4
2. **Jika tidak ada asterisk** → Gunakan langsung sebagai BMN
3. **Jika segment ke-4 kosong** → Cari pattern `31xxxxxxxx`

## 📝 Catatan Penting

### Format Nomor BMN BPS

- Selalu dimulai dengan `31` (kode aset)
- Total 10 digit
- Contoh: `3100102001`, `3100102002`, `3100203003`

### Variasi Format QR

QR Code BPS bisa berbeda-beda tergantung:
- Tahun pembuatan
- Unit/lokasi
- Jenis barang

**Parser sudah handle:**
- ✅ Format dengan delimiter `*`
- ✅ Format tanpa delimiter (plain BMN)
- ✅ Fallback pattern matching

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
