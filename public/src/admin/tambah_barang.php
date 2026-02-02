<?php
require_once '../config/auth.php';
requireAdmin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Sistem Peminjaman BMN</title>
    <link rel="stylesheet" href="/src/assets/css/light-mode-override.css?v=3">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
    </script>
</head>

<body class="min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-4">
            <a href="daftar_barang.php"
                class="p-2 -ml-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-tight">Tambah Barang</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 sm:p-8">
            <form id="addForm" class="space-y-6">

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label for="kode" class="block text-sm font-medium text-slate-700 mb-1.5">Kode Barang</label>
                        <input type="text" id="kode" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900"
                            placeholder="Contoh: 3101">
                    </div>

                    <div class="col-span-1">
                        <label for="nup" class="block text-sm font-medium text-slate-700 mb-1.5">NUP</label>
                        <input type="number" id="nup" required min="1"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900"
                            placeholder="Contoh: 1">
                    </div>
                </div>

                <div class="bg-blue-50 text-blue-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Nomor BMN akan dibuat otomatis dari Kode dan NUP. <br>Contoh: <strong>3101-001</strong></p>
                </div>

                <div>
                    <label for="brand" class="block text-sm font-medium text-slate-700 mb-1.5">Brand / Merk</label>
                    <input type="text" id="brand" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900"
                        placeholder="Contoh: Asus, Lenovo, Samsung">
                </div>

                <div>
                    <label for="tipe" class="block text-sm font-medium text-slate-700 mb-1.5">Tipe / Model</label>
                    <input type="text" id="tipe" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900"
                        placeholder="Contoh: ROG Strix G15">
                </div>

                <div>
                    <label for="kondisi" class="block text-sm font-medium text-slate-700 mb-1.5">Kondisi Awal</label>
                    <select id="kondisi" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="daftar_barang.php"
                        class="px-5 py-2.5 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition">Batal</a>
                    <button type="submit" id="submitBtn"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-lg font-medium shadow-sm transition">
                        Simpan Barang
                    </button>
                </div>

            </form>
        </div>

    </main>

    <script src="/src/assets/js/main.js"></script>
    <script>
        document.getElementById('addForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const kode = document.getElementById('kode').value;
            const nup = document.getElementById('nup').value;
            const brand = document.getElementById('brand').value;
            const tipe = document.getElementById('tipe').value;
            const kondisi = document.getElementById('kondisi').value;

            // Format Nomor BMN manually
            const nomor_bmn = `${kode}-${nup.toString().padStart(3, '0')}`; // Client side formatting for display mostly, backend handles split

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            // IMPORTANT: Sending formatted string because API expects it
            // Backend splits it back.
            // Wait, backend logic: explode('-', $nomor_bmn).
            // So if I send "3101-001", backend gets "3101" and "001".
            // Correct.

            const result = await apiCall('../api/add_barang.php', 'POST', {
                nomor_bmn: `${kode}-${nup}`, // Just verify format. Backend handles NUP intval.
                brand, tipe, kondisi
            });

            if (result.success) {
                showToast('Barang berhasil ditambahkan!', 'success');
                setTimeout(() => {
                    window.location.href = 'daftar_barang.php';
                }, 1000);
            } else {
                showToast(result.message, 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    </script>
    <script src="/src/assets/js/main.js?v=3"></script>
</body>

</html>