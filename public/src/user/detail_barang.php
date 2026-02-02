<?php
require_once '../config/auth.php';
require_once '../config/database.php';
requireLogin();

// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = getCurrentUser();
$nomor_bmn_param = $_GET['nomor_bmn'] ?? '';

if (empty($nomor_bmn_param)) {
    header('Location: scan.php');
    exit;
}

// Parse kode_barang and nup
$parts = explode('-', $nomor_bmn_param);
$kode_barang = $parts[0];
$nup = isset($parts[1]) ? intval($parts[1]) : null;

// Debug logging
error_log("Detail Barang Debug:");
error_log("nomor_bmn_param: " . $nomor_bmn_param);
error_log("kode_barang: " . $kode_barang);
error_log("nup: " . ($nup ?? 'NULL'));

if (empty($kode_barang) || $nup === null) {
    // Invalid format
    error_log("Invalid format - redirecting to scan.php");
    header('Location: scan.php');
    exit;
}

try {
    // Get barang data
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE kode_barang = ? AND nup = ?");
    $stmt->execute([$kode_barang, $nup]);
    $barang = $stmt->fetch();

    if (!$barang) {
        error_log("Barang not found for kode_barang: $kode_barang, nup: $nup");
        echo "<script>alert('Barang tidak ditemukan'); window.location.href='scan.php';</script>";
        exit;
    }

    // Construct nomor_bmn for display
    $barang['nomor_bmn'] = $barang['kode_barang'] . '-' . $barang['nup'];

    // Check if current user is borrowing this item
    $stmt = $pdo->prepare("
        SELECT * FROM histori_peminjaman 
        WHERE kode_barang = ? AND nup = ? AND nip_peminjam = ? AND status = 'dipinjam'
    ");
    $stmt->execute([$kode_barang, $nup, $user['nip']]);
    $user_borrowing = $stmt->fetch();

    error_log("Barang found: " . json_encode($barang));
    error_log("User borrowing: " . ($user_borrowing ? 'Yes' : 'No'));
} catch (Exception $e) {
    error_log("Error in detail_barang.php: " . $e->getMessage());
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang -
        <?= htmlspecialchars($barang['nomor_bmn']) ?>
    </title>
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
    <header class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700 sticky top-0 z-30 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="dashboard.php"
                        class="p-2 -ml-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white truncate">Detail Barang</h1>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">
                            <?= htmlspecialchars($barang['nomor_bmn']) ?>
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
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <h2 class="text-2xl font-bold mb-1 break-words">
                    <?= htmlspecialchars($barang['brand']) ?>
                </h2>
                <p class="text-blue-100 break-words">
                    <?= htmlspecialchars($barang['tipe']) ?>
                </p>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor BMN</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white break-words">
                            <?= htmlspecialchars($barang['nomor_bmn']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Kondisi</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php
                        echo $barang['kondisi_terakhir'] === 'baik' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                            ($barang['kondisi_terakhir'] === 'rusak ringan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300');
                        ?>">
                            <?= ucfirst($barang['kondisi_terakhir']) ?>
                        </span>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-3">Status Ketersediaan</p>
                    <div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium <?php
                        echo $barang['ketersediaan'] === 'tersedia' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
                        ?>">
                            <?= $barang['ketersediaan'] === 'tersedia' ? '✓ Tersedia' : '✗ Sedang Dipinjam' ?>
                        </span>
                    </div>
                </div>

                <?php if ($barang['ketersediaan'] === 'dipinjam'): ?>
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-lg p-4">
                        <h4 class="font-semibold text-amber-900 dark:text-amber-200 mb-2 flex items-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             Informasi Peminjaman
                        </h4>
                        <div class="text-sm text-amber-800 dark:text-amber-300 space-y-1 ml-7">
                            <p><span class="opacity-75">Peminjam:</span> <span class="font-medium"><?= htmlspecialchars($barang['peminjam_terakhir']) ?></span></p>
                            <p><span class="opacity-75">Waktu:</span> <span class="font-medium"><?= date('d/m/Y H:i', strtotime($barang['waktu_pinjam'])) ?></span></p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Action Button -->
            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                <?php if ($user_borrowing): ?>
                    <!-- User is currently borrowing this item -->
                    <button onclick="kembalikanBarang()" id="returnBtn"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kembalikan & Lapor Kerusakan
                    </button>
                <?php elseif ($barang['ketersediaan'] === 'tersedia'): ?>
                    <button onclick="ajukanPeminjaman()" id="pinjamBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <span>Ajukan Peminjaman</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                <?php else: ?>
                    <button disabled
                        class="w-full bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Barang Sedang Dipinjam
                    </button>
                <?php endif; ?>
            </div>

        </div>

    </main>

    <script src="/src/assets/js/main.js?v=3"></script>
    <script>
        async function ajukanPeminjaman() {
            const btn = document.getElementById('pinjamBtn');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            const result = await apiCall('ajukan_peminjaman.php', 'POST', {
                nomor_bmn: '<?= $barang['nomor_bmn'] ?>'
            });

            if (result.success) {
                showToast('Peminjaman berhasil diajukan!', 'success');
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1500);
            } else {
                showToast(result.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Ajukan Peminjaman';
            }
        }

        async function kembalikanBarang() {
            // Redirect to new /return page with code
            const bmn = '<?= $barang['nomor_bmn'] ?>';
            window.location.href = '/return?code=' + encodeURIComponent(bmn);
        }
    </script>
</body>

</html>