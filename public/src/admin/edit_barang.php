<?php
require_once '../config/auth.php';
requireAdmin();

if (!isset($_GET['nomor_bmn'])) {
    header('Location: daftar_barang.php');
    exit;
}

$nomor_bmn = $_GET['nomor_bmn'];
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Sistem Peminjaman BMN</title>
    <link rel="stylesheet" href="/src/assets/css/light-mode-override.css?v=3">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
    </script>
</head>

<body class="dark:bg-slate-900 min-h-screen transition-colors duration-200">


    <!-- Header -->
    <header
        class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 sticky top-0 z-30 transition-colors">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="daftar_barang.php"
                    class="p-2 -ml-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Edit
                        Barang</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">
                        <?= htmlspecialchars($nomor_bmn) ?>
                    </p>
                </div>
            </div>
            <!-- Dark Mode Toggle -->
            <button onclick="toggleDarkMode()"
                class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="darkModeIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                    </path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div id="loading" class="text-center py-12">
            <span class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-2"></span>
            <p class="text-slate-500 dark:text-slate-400">Memuat data barang...</p>
        </div>

        <div id="formContainer"
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 hidden transition-colors">
            <form id="editForm" class="space-y-6">

                <input type="hidden" id="nomor_bmn" value="<?= htmlspecialchars($nomor_bmn) ?>">

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kode
                            Barang</label>
                        <input type="text" id="kode" disabled
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed transition-colors">
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NUP</label>
                        <input type="text" id="nup" disabled
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed transition-colors">
                    </div>
                </div>

                <div>
                    <label for="brand"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Brand
                        / Merk</label>
                    <input type="text" id="brand" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                </div>

                <div>
                    <label for="tipe"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Tipe
                        / Model</label>
                    <input type="text" id="tipe" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                </div>

                <!-- Hidden as per user request
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label for="tahun_perolehan" class="block text-sm font-medium text-slate-700 mb-1.5">Tahun
                            Perolehan</label>
                        <input type="number" id="tahun_perolehan" required min="1900" max="<?= date('Y') ?>"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900">
                    </div>

                    <div class="col-span-1">
                        <label for="nilai_perolehan" class="block text-sm font-medium text-slate-700 mb-1.5">Nilai
                            Perolehan (Rp)</label>
                        <input type="number" id="nilai_perolehan" required min="0"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900">
                    </div>
                </div>
                -->

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label for="kondisi"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kondisi
                            Terakhir</label>
                        <select id="kondisi" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="baik">Baik</option>
                            <option value="rusak ringan">Rusak Ringan</option>
                            <option value="rusak berat">Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label for="ketersediaan"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Status
                            Ketersediaan</label>
                        <select id="ketersediaan" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="hilang">Hilang</option>
                            <option value="reparasi">Dalam Perbaikan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="keterangan"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Keterangan
                        Tambahan</label>
                    <textarea id="keterangan" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white placeholder-slate-400 transition"
                        placeholder="Contoh: Baterai bocor, layar baret halus..."></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="daftar_barang.php"
                        class="px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg font-medium transition">Batal</a>
                    <button type="submit" id="submitBtn"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-8 py-2.5 rounded-lg font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </main>

    <script src="/src/assets/js/main.js?v=3"></script>
    <script>
        const nomorBmn = document.getElementById('nomor_bmn').value;

        // Fetch data
        async function loadData() {
            const result = await apiCall(`../api/get_barang.php?exact_nomor_bmn=${nomorBmn}`);

            if (result.success && result.data.length > 0) {
                const item = result.data[0];

                // Populate form
                document.getElementById('kode').value = item.kode_barang;
                document.getElementById('nup').value = item.nup;
                document.getElementById('brand').value = item.brand;
                document.getElementById('tipe').value = item.tipe;
                // document.getElementById('tahun_perolehan').value = item.tahun_perolehan;
                // document.getElementById('nilai_perolehan').value = item.nilai_perolehan;
                document.getElementById('kondisi').value = item.kondisi_terakhir;
                document.getElementById('ketersediaan').value = item.ketersediaan;
                document.getElementById('keterangan').value = item.keterangan || '';

                // Show form
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('formContainer').classList.remove('hidden');
            } else {
                showToast('Data barang tidak ditemukan', 'error');
                setTimeout(() => window.location.href = 'daftar_barang.php', 2000);
            }
        }

        loadData();

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const brand = document.getElementById('brand').value;
            const tipe = document.getElementById('tipe').value;
            // const tahun_perolehan = document.getElementById('tahun_perolehan').value;
            // const nilai_perolehan = document.getElementById('nilai_perolehan').value;
            const kondisi = document.getElementById('kondisi').value;
            const ketersediaan = document.getElementById('ketersediaan').value;
            const keterangan = document.getElementById('keterangan').value;

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            const result = await apiCall('../api/update_barang.php', 'POST', {
                nomor_bmn: nomorBmn,
                brand, tipe, /* tahun_perolehan, nilai_perolehan, */ kondisi, ketersediaan, keterangan
            });

            if (result.success) {
                showToast('Barang berhasil diperbarui!', 'success');
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