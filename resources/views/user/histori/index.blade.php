@extends('layouts.app')

@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $histori */
@endphp

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">Histori Peminjaman Saya</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Riwayat aktivitas peminjaman barang Anda</p>
        </div>

        @if($histori->count() > 0)
            <div class="space-y-4">
                @foreach ($histori as $item)
                    <!-- Card Item (Mobile First approach) -->
                    <div class="bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-4 transition-all duration-200 hover:shadow-md hover:border-slate-300 dark:hover:border-slate-600">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 text-xs px-2 py-1 rounded font-mono">
                                    {{ $item->nomor_bmn }}
                                </span>
                            </div>
                            <div>
                                @if ($item->status === 'dipinjam')
                                    <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs px-2 py-1 rounded-full font-medium">
                                        Sedang Dipinjam
                                    </span>
                                @else
                                    <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs px-2 py-1 rounded-full font-medium">
                                        Dikembalikan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <h3 class="font-semibold text-slate-900 dark:text-white mb-1">
                            {{ $item->barang->brand ?? 'Barang' }} {{ $item->barang->tipe ?? 'Tidak Diketahui' }}
                        </h3>

                        <div class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Pinjam: {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d M Y, H:i') }}</span>
                            </div>
                            
                            @if ($item->waktu_kembali)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Kembali: {{ \Carbon\Carbon::parse($item->waktu_kembali)->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $histori->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-lg border border-dashed border-slate-300 dark:border-slate-700">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">Belum ada riwayat</h3>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Anda belum pernah melakukan peminjaman barang.</p>
                <div class="mt-4">
                    <a href="{{ route('user.dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        Mulai Pinjam &rarr;
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
