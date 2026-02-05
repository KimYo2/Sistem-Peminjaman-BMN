@extends('layouts.app')

@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $histori */
@endphp


@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Histori
                    Peminjaman</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">Riwayat aktivitas
                    peminjaman barang BMN</p>
            </div>

            <!-- Optional: Export Button Placeholder (Legacy had it) -->
            <a href="{{ route('admin.histori.export', request()->query()) }}"
                class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium py-2 px-4 rounded-lg transition inline-flex items-center gap-2 shadow-sm text-sm">
                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export CSV
            </a>
        </div>

        <!-- Filters -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6 transition-colors">
            <form action="{{ route('admin.histori.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Filter
                        Status</label>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan
                        </option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam
                        </option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah
                            Dikembalikan</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <!-- Search placeholder if needed or just empty space usage -->
                    <div class="w-full">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Pencarian</label>
                        <div class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari NUP, Nama Peminjam..."
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition placeholder-slate-400">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg transition shadow-sm">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Histori List -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead
                        class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider transition-colors">
                        <tr>
                            <th class="px-6 py-3 text-left">Nomor BMN</th>
                            <th class="px-6 py-3 text-left">Peminjam</th>
                            <th class="px-6 py-3 text-left">NIP</th>
                            <th class="px-6 py-3 text-left">Waktu Pinjam</th>
                            <th class="px-6 py-3 text-left">Waktu Kembali</th>
                            <th class="px-6 py-3 text-left">Jatuh Tempo</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 text-sm transition-colors">
                        @forelse($histori as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">
                                    {{ $item->kode_barang }}-{{ $item->nup }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ $item->nama_peminjam ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400 font-mono text-xs">
                                    {{ $item->nip_peminjam }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    @if($item->waktu_pinjam)
                                        {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/Y H:i') }}
                                    @elseif($item->waktu_pengajuan)
                                        {{ \Carbon\Carbon::parse($item->waktu_pengajuan)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ $item->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md border 
                                            {{ $item->status_badge_class }}">
                                        {{ $item->status_label }}
                                    </span>
                                    @if($item->status === 'dipinjam' && $item->tanggal_jatuh_tempo && \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->isPast())
                                        <span
                                            class="ml-2 px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md border bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800">
                                            Terlambat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status === 'menunggu')
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('admin.histori.approve', $item->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.histori.reject', $item->id) }}">
                                                @csrf
                                                <input type="hidden" name="rejection_reason" value="Ditolak admin">
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Tidak ada riwayat peminjaman.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-slate-200 dark:border-slate-700 sm:px-6">
                {{ $histori->links() }}
            </div>
        </div>

    </div>
@endsection
