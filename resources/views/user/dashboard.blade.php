@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <!-- Scan QR Card -->
            <a href="{{ route('user.scan') }}"
                class="block bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg shadow-sm hover:shadow-md transition p-6 text-white group relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-full opacity-10 pointer-events-none">
                    <svg class="w-full h-full transform translate-x-10 scale-150" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                </div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-2xl font-bold mb-2">Scan QR Code</h3>
                        <p class="text-blue-100 text-sm">Pindai kode QR pada stiker barang untuk memulai peminjaman atau
                            pengembalian.</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Peminjaman Aktif Status -->
            <div
                class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col h-full transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white transition-colors">Status Peminjaman</h3>
                    @if($activeLoans > 0)
                        <span
                            class="bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs px-2 py-1 rounded-md font-medium transition-colors">
                            {{ $activeLoans }} Barang Dipinjam
                        </span>
                    @else
                        <span
                            class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs px-2 py-1 rounded-md font-medium transition-colors">
                            Tidak Ada Peminjaman
                        </span>
                    @endif
                </div>

                <div class="flex-1 flex flex-col justify-center">
                    @if(isset($overdueLoans) && $overdueLoans > 0)
                        <div
                            class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 text-sm text-red-700 dark:text-red-300">
                            Terdapat {{ $overdueLoans }} peminjaman yang melewati jatuh tempo. Segera lakukan pengembalian.
                        </div>
                    @endif
                    @if($currentActiveLoan)
                        <div
                            class="bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 rounded-lg p-4">
                            <h4 class="font-semibold text-slate-900 dark:text-white mb-1">Peminjaman Terbaru</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                BMN: {{ $currentActiveLoan->kode_barang }}-{{ $currentActiveLoan->nup }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                Pinjam: {{ \Carbon\Carbon::parse($currentActiveLoan->waktu_pinjam)->format('d M Y H:i') }}
                            </p>
                            @if(!empty($currentActiveLoan->tanggal_jatuh_tempo))
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Jatuh tempo: {{ \Carbon\Carbon::parse($currentActiveLoan->tanggal_jatuh_tempo)->format('d M Y') }}
                                </p>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('return.index') }}"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Kembalikan Barang
                                    &rarr;</a>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-slate-400 dark:text-slate-500">
                            <p class="text-sm">Anda tidak sedang meminjam barang apapun saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Histori (Placeholder for now, keeping legacy link for full list) -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 transition-colors">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2 transition-colors">
                <span>Histori Peminjaman Saya</span>
                <a href="{{ route('user.histori.index') }}"
                    class="text-sm font-normal text-blue-600 dark:text-blue-400 hover:underline ml-auto">Lihat Semua</a>
            </h3>

            <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                @if(isset($recentLoans) && $recentLoans->count() > 0)
                    <div class="space-y-3 mb-4">
                        @foreach($recentLoans as $loan)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg gap-2">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                        {{ $loan->brand ?? 'Barang' }} {{ $loan->tipe ?? '' }}
                                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400 ml-1">({{ $loan->kode_barang }}-{{ $loan->nup }})</span>
                                    </p>
                                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1">
                                        <div class="flex items-center gap-1">
                                            <span class="w-16">Pinjam:</span>
                                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                                {{ \Carbon\Carbon::parse($loan->waktu_pinjam)->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        @if($loan->waktu_kembali)
                                            <div class="flex items-center gap-1">
                                                <span class="w-16">Kembali:</span>
                                                <span class="font-medium text-slate-700 dark:text-slate-300">
                                                    {{ \Carbon\Carbon::parse($loan->waktu_kembali)->format('d M Y, H:i') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($loan->status === 'menunggu')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            Menunggu
                                        </span>
                                    @elseif($loan->status === 'dipinjam')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                            Dipinjam
                                        </span>
                                    @elseif($loan->status === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-slate-500 dark:text-slate-400 mb-4">Belum ada riwayat peminjaman.</p>
                @endif
                <div class="text-center">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Total: <span class="font-semibold text-slate-900 dark:text-white">{{ $totalLoans }}</span> transaksi</p>
                </div>
            </div>
        </div>

    </div>
@endsection
