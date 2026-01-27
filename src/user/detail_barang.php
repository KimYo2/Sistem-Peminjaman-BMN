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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="dashboard.php"
                    class="flex-shrink-0 text-gray-600 hover:text-gray-900 active:text-gray-700 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">Detail Barang</h1>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">
                        <?= htmlspecialchars($barang['nomor_bmn']) ?>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-5 sm:p-6 text-white">
                <h2 class="text-xl sm:text-2xl font-bold mb-1 sm:mb-2 break-words">
                    <?= htmlspecialchars($barang['brand']) ?>
                </h2>
                <p class="text-sm sm:text-base text-blue-100 break-words">
                    <?= htmlspecialchars($barang['tipe']) ?>
                </p>
            </div>

            <!-- Details -->
            <div class="p-4 sm:p-6 space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Nomor BMN</p>
                        <p class="font-semibold text-sm sm:text-base text-gray-900 break-words">
                            <?= htmlspecialchars($barang['nomor_bmn']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Kondisi</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php
                        echo $barang['kondisi_terakhir'] === 'baik' ? 'bg-green-100 text-green-800' :
                            ($barang['kondisi_terakhir'] === 'rusak ringan' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                        ?>">
                            <?= ucfirst($barang['kondisi_terakhir']) ?>
                        </span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-2">Status Ketersediaan</p>
                    <div class="flex items-center">
                        <span class="inline-block px-4 py-2 rounded-lg text-sm font-semibold <?php
                        echo $barang['ketersediaan'] === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                        ?>">
                            <?= $barang['ketersediaan'] === 'tersedia' ? '✓ Tersedia' : '✗ Sedang Dipinjam' ?>
                        </span>
                    </div>
                </div>

                <?php if ($barang['ketersediaan'] === 'dipinjam'): ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-900 mb-2">Informasi Peminjaman</h4>
                        <div class="text-sm text-yellow-800 space-y-1">
                            <p><strong>Peminjam:</strong>
                                <?= htmlspecialchars($barang['peminjam_terakhir']) ?>
                            </p>
                            <p><strong>Waktu Pinjam:</strong>
                                <?= date('d/m/Y H:i', strtotime($barang['waktu_pinjam'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Action Button -->
            <div class="p-4 sm:p-6 bg-gray-50 border-t">
                <?php if ($user_borrowing): ?>
                    <!-- User is currently borrowing this item -->
                    <button onclick="kembalikanBarang()" id="returnBtn"
                        class="w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg touch-manipulation text-sm sm:text-base">
                        ✓ Kembalikan Barang
                    </button>
                <?php elseif ($barang['ketersediaan'] === 'tersedia'): ?>
                    <button onclick="ajukanPeminjaman()" id="pinjamBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg touch-manipulation text-sm sm:text-base">
                        Ajukan Peminjaman
                    </button>
                <?php else: ?>
                    <button disabled
                        class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed text-sm sm:text-base">
                        Barang Sedang Dipinjam
                    </button>
                <?php endif; ?>
            </div>

        </div>

    </main>

    <script src="/src/assets/js/main.js"></script>
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
            const btn = document.getElementById('returnBtn');

            // Confirm before returning
            if (!confirm('Apakah Anda yakin ingin mengembalikan barang ini?')) {
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Memproses...';

            const result = await apiCall('kembalikan_barang.php', 'POST', {
                nomor_bmn: '<?= $barang['nomor_bmn'] ?>'
            });

            if (result.success) {
                showToast('Barang berhasil dikembalikan!', 'success');
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1500);
            } else {
                showToast(result.message, 'error');
                btn.disabled = false;
                btn.textContent = '✓ Kembalikan Barang';
            }
        }
    </script>
</body>

</html>