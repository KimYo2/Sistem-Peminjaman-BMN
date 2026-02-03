@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <!-- Total Barang -->
            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Total Barang</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1 transition-colors">
                        {{ $totalBarang }}
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>

            <!-- Tersedia -->
            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Tersedia</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1 transition-colors">
                        {{ $tersedia }}
                    </p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Dipinjam -->
            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Dipinjam</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1 transition-colors">
                        {{ $dipinjam }}
                    </p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Aktif Peminjaman -->
            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Aktif Peminjaman</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1 transition-colors">
                        {{ $activeLoans }}
                    </p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 rounded-lg p-3 transition-colors">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Insight & SLA -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Peminjaman Terlambat</p>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $overdueCount ?? 0 }}</p>
                    <span
                        class="text-xs text-slate-500 dark:text-slate-400">Jatuh tempo terlewati</span>
                </div>
                @if(!empty($overdueList) && $overdueList->count() > 0)
                    <div class="mt-3 space-y-2 text-xs text-slate-600 dark:text-slate-300">
                        @foreach($overdueList as $loan)
                            <div class="flex items-center justify-between">
                                <span class="font-mono">{{ $loan->kode_barang }}-{{ $loan->nup }}</span>
                                <span class="text-red-600 dark:text-red-400">
                                    {{ \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->format('d M Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Rata-rata Durasi Pinjam</p>
                <div class="mt-2">
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ $avgBorrowHours ? round($avgBorrowHours / 24, 1) : 0 }} hari
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Berdasarkan pinjaman selesai</p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Utilisasi Barang</p>
                <div class="mt-2">
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ $totalBarang > 0 ? round(($dipinjam / $totalBarang) * 100, 1) : 0 }}%
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Barang sedang dipinjam</p>
                </div>
            </div>
        </div>

        <!-- Top Utilization -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <div
                class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 transition-colors">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4">Top Barang Paling Sering Dipinjam</h3>
                <div class="space-y-3">
                    @forelse($topItems ?? [] as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="text-slate-700 dark:text-slate-300">
                                <span class="font-mono">{{ $item->kode_barang }}-{{ $item->nup }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">
                                    {{ $item->brand ?? 'Barang' }} {{ $item->tipe ?? '' }}
                                </span>
                            </div>
                            <span class="text-slate-900 dark:text-white font-semibold">{{ $item->total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada data pinjaman.</p>
                    @endforelse
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 transition-colors">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4">Top Peminjam</h3>
                <div class="space-y-3">
                    @forelse($topBorrowers ?? [] as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="text-slate-700 dark:text-slate-300">
                                <span class="font-medium">{{ $item->nama_peminjam ?? '-' }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">{{ $item->nip_peminjam }}</span>
                            </div>
                            <span class="text-slate-900 dark:text-white font-semibold">{{ $item->total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada data peminjaman.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4 transition-colors">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <!-- Daftar Barang -->
            <a href="{{ route('admin.barang.index') }}"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-indigo-300 dark:hover:border-indigo-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-3 mr-4 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition">
                            Daftar Barang</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Kelola inventaris BMN
                        </p>
                    </div>
                </div>
            </a>

            <!-- Scan Pengembalian (Already Migrated) -->
            <a href="{{ route('return.index') }}"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-emerald-300 dark:hover:border-emerald-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-emerald-50 dark:bg-emerald-900/30 rounded-lg p-3 mr-4 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition">
                            Scan Pengembalian</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Proses pengembalian
                            barang</p>
                    </div>
                </div>
            </a>

            <!-- Histori Peminjaman -->
            <a href="{{ route('admin.histori.index') }}"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-3 mr-4 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/50 transition">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-purple-700 dark:group-hover:text-purple-400 transition">
                            Histori Peminjaman</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Lihat riwayat dan
                            laporan</p>
                    </div>
                </div>
            </a>

            <!-- Tiket Kerusakan (Already Migrated) -->
            <a href="{{ route('admin.tiket.index') }}"
                class="block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-5 hover:border-red-300 dark:hover:border-red-500 hover:shadow-md transition duration-200 group">
                <div class="flex items-start">
                    <div
                        class="bg-red-50 dark:bg-red-900/30 rounded-lg p-3 mr-4 group-hover:bg-red-100 dark:group-hover:bg-red-900/50 transition">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="font-semibold text-slate-800 dark:text-white group-hover:text-red-700 dark:group-hover:text-red-400 transition">
                            Tiket Kerusakan</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Kelola laporan
                            kerusakan</p>
                    </div>
                </div>
            </a>

        </div>

    </div>
@endsection
