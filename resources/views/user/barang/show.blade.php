@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div
            class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <h2 class="text-2xl font-bold mb-1 break-words">
                    {{ $barang->brand }}
                </h2>
                <p class="text-blue-100 break-words">
                    {{ $barang->tipe }}
                </p>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor BMN</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white break-words">
                            {{ $barang->nomor_bmn_full }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Kondisi</p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $barang->kondisi_terakhir === 'baik' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
        ($barang->kondisi_terakhir === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300') }}">
                            {{ ucfirst($barang->kondisi_terakhir) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-3">Status Ketersediaan</p>
                    <div>
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium 
                                {{ $barang->ketersediaan === 'tersedia' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                            {{ $barang->ketersediaan === 'tersedia' ? '✓ Tersedia' : '✗ Sedang Dipinjam' }}
                        </span>
                    </div>
                </div>

                @if($barang->ketersediaan === 'dipinjam')
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-lg p-4">
                        <h4 class="font-semibold text-amber-900 dark:text-amber-200 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Peminjaman
                        </h4>
                        <div class="text-sm text-amber-800 dark:text-amber-300 space-y-1 ml-7">
                            <p><span class="opacity-75">Peminjam:</span> <span
                                    class="font-medium">{{ $barang->peminjam_terakhir }}</span></p>
                            <p><span class="opacity-75">Waktu:</span> <span
                                    class="font-medium">{{ \Carbon\Carbon::parse($barang->waktu_pinjam)->format('d/m/Y H:i') }}</span>
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Action Button -->
            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                @if($isBorrowing)
                    <!-- User is currently borrowing this item -->
                    <a href="{{ route('return.index') }}?code={{ $barang->nomor_bmn_full }}"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kembalikan & Lapor Kerusakan
                    </a>
                @elseif($barang->ketersediaan === 'tersedia')
                    <button onclick="ajukanPeminjaman('{{ $barang->nomor_bmn_full }}')" id="pinjamBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <span>Ajukan Peminjaman</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                            </path>
                        </svg>
                    </button>
                @else
                    <button disabled
                        class="w-full bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Barang Sedang Dipinjam
                    </button>
                @endif
            </div>

        </div>

    </div>

    <script>
        async function ajukanPeminjaman(nomorBmn) {
            const btn = document.getElementById('pinjamBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Memproses...</span>';

            try {
                // Use standard fetch instead of custom apiCall to ensure compatibility with Laravel CSRF
                const response = await fetch("{{ route('user.barang.borrow') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        nomor_bmn: nomorBmn
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Show success feedback (using simple alert for now, can be improved to Toast if available in layout)
                    // Assuming layout has showToast or similar from legacy scripts? 
                    // Let's use standard alert for safety or reuse legacy showToast if included in layouts.app

                    // Check if showToast exists (from legacy main.js)
                    if (typeof showToast === 'function') {
                        showToast('Peminjaman berhasil diajukan!', 'success');
                    } else {
                        alert('Peminjaman berhasil diajukan!');
                    }

                    setTimeout(() => {
                        window.location.href = result.redirect_url;
                    }, 1000);
                } else {
                    if (typeof showToast === 'function') {
                        showToast(result.message || 'Terjadi kesalahan', 'error');
                    } else {
                        alert(result.message || 'Terjadi kesalahan');
                    }
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
@endsection