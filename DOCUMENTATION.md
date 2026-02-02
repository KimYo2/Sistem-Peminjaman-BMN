# Dokumentasi Sistem Peminjaman BMN

> **Dokumentasi Lengkap**: Implementasi BPS Color Scheme, Dark Mode, dan Custom Light Mode Theme

---

## 📋 Daftar Isi

1. [Overview](#overview)
2. [BPS Color Scheme](#bps-color-scheme)
3. [Dark Mode Implementation](#dark-mode-implementation)
4. [Custom Light Mode Theme](#custom-light-mode-theme)
5. [Technical Implementation](#technical-implementation)
6. [Issues Fixed](#issues-fixed)
7. [Files Modified](#files-modified)
8. [Verification](#verification)

---

## Overview

Proyek ini telah diupdate dengan implementasi lengkap:
- ✅ BPS Color Scheme (Official Government Branding)
- ✅ Dark Mode Support di semua halaman
- ✅ Custom Light Mode dengan background #cfcfcf
- ✅ Harmonisasi warna untuk konsistensi visual

---

## BPS Color Scheme

### Color Palette

#### 🟦 Primary (Blue)
- **Main Blue**: `#2563EB` (Tailwind: `blue-600`)
- **Usage**: Headers, navbar, primary buttons, icons
- **Dark Mode**: `#3B82F6` (Tailwind: `blue-500`)

#### 🟧 Accent (Orange - Use Sparingly!)
- **Orange**: `#F59E0B` (Tailwind: `amber-500`)
- **Usage**: "Dipinjam" status badges, active indicators
- ⚠️ **NOT for**: Main backgrounds, headers, primary buttons

#### 🟢🟥🟡 Status Colors
- **Tersedia (Available)**: `#16A34A` - Green (`green-600`)
- **Dipinjam (Borrowed)**: `#DC2626` - Red (`red-600`)
- **Selesai (Completed)**: `#16A34A` - Green (`green-600`)
- **Menunggu (Pending)**: `#EAB308` - Yellow (`yellow-500`)

### Pages Updated with BPS Colors

1. **login.php** - Logo container, login button
2. **admin/dashboard.php** - Header icon, stat cards, quick actions
3. **admin/daftar_barang.php** - "Tambah Barang" button
4. **admin/edit_barang.php** - "Simpan Perubahan" button
5. **admin/histori.php** - Filters, status badges
6. **admin/scan_return.php** - Loading spinner, messages
7. **user/dashboard.php** - Scan QR card, history badges
8. **user/scan.php** - Existing blue buttons maintained

---

## Dark Mode Implementation

### Features
- ✅ Dark mode toggle button on all pages
- ✅ Persistent theme preference (localStorage)
- ✅ Smooth transitions between light/dark modes
- ✅ Proper color contrast for accessibility

### Dark Mode Colors

| Element | Color | Tailwind Class |
|---------|-------|----------------|
| Background | #0f172a | `slate-900` |
| Cards | #1e293b | `slate-800` |
| Headers | #1e293b | `slate-800` |
| Borders | #334155 | `slate-700` |
| Text | #f1f5f9 | `slate-100` |

### Pages with Dark Mode

- ✅ Login page
- ✅ Admin Dashboard
- ✅ User Dashboard
- ✅ Scan pages (user & admin)
- ✅ Daftar Barang
- ✅ Edit Barang
- ✅ Tambah Barang
- ✅ Histori
- ✅ Scan Return

### Dark Mode Toggle Implementation

```javascript
// main.js
function toggleDarkMode() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
        updateToggleIcon('light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
        updateToggleIcon('dark');
    }
}

function initDarkMode() {
    if (localStorage.theme === 'dark' || 
        (!('theme' in localStorage) && 
         window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        updateToggleIcon('dark');
    } else {
        document.documentElement.classList.remove('dark');
        updateToggleIcon('light');
    }
}
```

---

## Custom Light Mode Theme

### Challenge
User requested custom background color `#cfcfcf` for light mode with harmonized element colors to create a softer, more cohesive aesthetic.

### Solution: CSS Override File

Created `public/src/assets/css/light-mode-override.css` with strategic color overrides that only apply in light mode.

### Light Mode Color Palette

| Element | Color | Description |
|---------|-------|-------------|
| Body Background | #cfcfcf | Custom gray (medium) |
| Headers/Navbar | #f5f5f5 | Very light gray |
| Cards/Containers | #e8e8e8 | Soft gray |
| Form Inputs | #f0f0f0 | Lighter gray |
| Table Headers | #e0e0e0 | Medium gray |

### CSS Implementation

```css
/* Light Mode Color Overrides for #cfcfcf Background Theme */
/* CRITICAL: Only apply these styles in LIGHT MODE (html:not(.dark)) */

/* Body Background - Custom Gray */
html:not(.dark) body {
    background-color: #cfcfcf !important;
}

/* Headers/Navbar - Light Gray */
html:not(.dark) header {
    background-color: #f5f5f5 !important;
}

/* Cards and Containers - Soft Gray */
html:not(.dark) .bg-white {
    background-color: #e8e8e8 !important;
}

/* Form Inputs - Slightly Lighter */
html:not(.dark) input[type="text"],
html:not(.dark) input[type="password"],
html:not(.dark) input[type="number"],
html:not(.dark) input[type="email"],
html:not(.dark) select,
html:not(.dark) textarea {
    background-color: #f0f0f0 !important;
}

/* Table headers */
html:not(.dark) thead {
    background-color: #e0e0e0 !important;
}
```

### Key Technical Decisions

1. **CSS Override File**: Created separate CSS file (`light-mode-override.css`) for maintainability
2. **Selector Strategy**: Used `html:not(.dark)` to target ONLY light mode
3. **No Inline Styles**: Removed ALL inline `style` attributes to prevent conflicts
4. **Dark Mode Class**: Leveraged Tailwind's `darkMode: 'class'` on `<html>` element
5. **Important Flag**: Used `!important` only for light mode overrides to ensure they apply

---

## Technical Implementation

### Tailwind Configuration

All pages include this Tailwind config:

```javascript
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                slate: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                }
            }
        }
    }
}
```

### HTML Structure

```html
<head>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Light Mode Override CSS -->
    <link rel="stylesheet" href="/src/assets/css/light-mode-override.css">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            // ... config
        }
    </script>
</head>

<body class="dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <!-- Dark Mode Toggle Button -->
    <button onclick="toggleDarkMode()" class="...">
        <svg id="darkModeIcon">...</svg>
    </button>
    
    <!-- Page Content -->
</body>
```

---

## Issues Fixed

### Issue 1: Dark Mode Not Working
**Problem**: Light mode colors appearing in dark mode  
**Root Cause**: CSS used `body:not(.dark)` but dark class was on `<html>` element  
**Solution**: Changed all selectors to `html:not(.dark)`

### Issue 2: Inline Styles Conflicting
**Problem**: Inline `style="background-color: #cfcfcf"` overriding dark mode  
**Root Cause**: Inline styles have higher specificity than CSS classes  
**Solution**: Removed ALL inline styles from all pages, let CSS handle everything

### Issue 3: Cards Still Light in Dark Mode
**Problem**: Cards showing #e8e8e8 in dark mode  
**Root Cause**: CSS `!important` overriding Tailwind dark mode classes  
**Solution**: Only apply overrides with `html:not(.dark)` selector

### Issue 4: Header Background Conflicts
**Problem**: Headers still showing light colors in dark mode  
**Root Cause**: Inline styles and scripts trying to manipulate background  
**Solution**: Removed inline styles and scripts, added `bg-white` class for CSS override

---

## Files Modified

### New Files Created
- `public/src/assets/css/light-mode-override.css` - Light mode color overrides

### Updated Files

#### User Pages
1. `public/src/user/dashboard.php`
   - Added CSS link
   - Removed inline styles
   - Added `bg-white` class to cards
   - Updated BPS colors

2. `public/src/user/scan.php`
   - Added CSS link
   - Removed inline styles
   - Maintained existing blue buttons

#### Admin Pages
3. `public/src/login.php`
   - Added CSS link
   - Removed inline styles
   - Updated login button to BPS blue

4. `public/src/admin/dashboard.php`
   - Added CSS link
   - Removed inline styles
   - Updated header icon to BPS blue
   - Updated stat cards

5. `public/src/admin/daftar_barang.php`
   - Added CSS link
   - Removed inline styles
   - Updated "Tambah Barang" button

6. `public/src/admin/edit_barang.php`
   - Added CSS link
   - Removed inline styles
   - Updated "Simpan" button

7. `public/src/admin/tambah_barang.php`
   - Added CSS link
   - Removed inline styles

8. `public/src/admin/histori.php`
   - Added CSS link
   - Removed inline styles
   - Updated filter buttons
   - Updated status badges with BPS colors

9. `public/src/admin/scan_return.php`
   - Added CSS link
   - Removed inline styles
   - Added dark mode support
   - Updated loading spinner to BPS blue

#### JavaScript
10. `public/src/assets/js/main.js`
    - Updated `getKetersediaanBadge()` with BPS colors
    - Dark mode toggle functions
    - Theme persistence in localStorage

---

## Verification

### ✅ Light Mode Testing
1. ✅ Background is #cfcfcf on all pages
2. ✅ Cards are #e8e8e8 (soft gray)
3. ✅ Headers are #f5f5f5 (very light gray)
4. ✅ Form inputs are #f0f0f0
5. ✅ Colors harmonize well together
6. ✅ BPS blue used for primary actions
7. ✅ Orange used sparingly for status badges

### ✅ Dark Mode Testing
1. ✅ Background is slate-900 (#0f172a)
2. ✅ Cards are slate-800 (#1e293b)
3. ✅ Headers are slate-800 (#1e293b)
4. ✅ Toggle button works on all pages
5. ✅ No light mode colors bleeding through
6. ✅ Smooth transitions between modes
7. ✅ Theme preference persists in localStorage

### ✅ Cross-Page Consistency
1. ✅ All pages use same color scheme
2. ✅ Dark mode toggle icon updates correctly (sun/moon)
3. ✅ Theme preference persists across page navigation
4. ✅ BPS blue used consistently for primary actions
5. ✅ Status colors consistent across all pages

### ✅ Accessibility
1. ✅ Color contrast ratios meet WCAG AA standards
2. ✅ Text readable on all backgrounds
3. ✅ Status colors distinguishable
4. ✅ Dark mode provides better readability in low light

---

## Task Checklist

### Color Scheme Implementation
- [x] Define BPS color palette
- [x] Apply to login page
- [x] Apply to admin dashboard
- [x] Apply to user dashboard
- [x] Apply to daftar barang page
- [x] Apply to edit barang page
- [x] Apply to scan pages

### Dark Mode Support
- [x] Add dark mode to scan_return.php
- [x] Add dark mode to histori.php
- [x] Add dark mode to tambah_barang.php
- [x] Verify dark mode toggle on all pages
- [x] Fix dark mode compatibility issues

### Light Mode Custom Background (#cfcfcf)
- [x] Change background to #cfcfcf for all pages
- [x] Adjust card colors (#e8e8e8) to harmonize with background
- [x] Adjust header colors (#f5f5f5) to harmonize with background
- [x] Create CSS override file for light mode theming
- [x] Apply CSS to all pages
- [x] Fix dark mode conflicts with inline styles
- [x] Remove all inline styles causing dark mode issues
- [x] Verify light/dark mode switching works correctly

### Final Verification
- [x] Test all pages in light mode
- [x] Test all pages in dark mode
- [x] Ensure smooth transitions between modes
- [x] Verify BPS color consistency
- [x] Check accessibility standards

---

9. [Troubleshooting Guide](#troubleshooting-guide)

---

## Troubleshooting Guide

### 📱 Masalah Tampilan di HP (Mobile)

**Masalah**: Tampilan di HP masih versi lama atau berantakan setelah update.  
**Penyebab**: Browser HP melakukan caching agresif terhadap file CSS/JS.  
**Solusi**:
1. **Cache Busting (Implemented)**: Sistem sudah diupdate dengan `?v=time()` pada setiap link CSS/JS. Ini memaksa browser untuk selalu mengambil file versi terbaru.
2. **Refresh Hard**: Tarik layar ke bawah (pull-to-refresh) beberapa kali.
3. **Incognito Mode**: Coba buka menggunakan "New Incognito Tab" atau "Private Mode".
4. **Clear Cache**: Hapus cache browser jika masalah berlanjut.

### 🌐 Akses Local Network (Laravel Serve)

Agar website bisa dibuka dari HP:

1. **Jalankan Server dengan Host 0.0.0.0**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   *Atau jika menggunakan php built-in server:*
   ```bash
   php -S 0.0.0.0:8000 -t public
   ```

2. **Cek IP Address Komputer**:
   - Buka CMD -> ketik `ipconfig`
   - Cari **IPv4 Address** (contoh: `192.168.1.15`)

3. **Akses dari HP**:
   - Pastikan HP dan Laptop di WiFi yang sama
   - Buka browser HP -> ketik `http://192.168.1.15:8000` (Ganti IP sesuai komputer Anda)

---

## Final Result

### Light Mode
- Professional gray theme with #cfcfcf background
- Harmonized element colors (#e8e8e8 cards, #f5f5f5 headers)
- BPS blue for primary actions
- Orange accents for status indicators

### Dark Mode
- Clean dark theme with slate-900 background
- Proper slate-800 cards and headers
- Excellent contrast for readability
- Consistent BPS color scheme

### User Experience
- Smooth theme switching with transitions
- Persistent theme preference
- Consistent branding across all pages
- Professional government application aesthetic

---

## Notes

> [!IMPORTANT]
> Orange accent should be used **sparingly** - only for specific status indicators and important elements. It should NOT dominate the interface.

> [!TIP]
> The blue primary color (#2563EB) is modern yet professional, suitable for government applications and reports.

> [!WARNING]
> Do NOT use inline styles for theming - always use CSS classes or the override CSS file to maintain consistency and avoid dark mode conflicts.

---

**Last Updated**: 2026-01-27  
**Version**: 1.0  
**Status**: ✅ Complete
