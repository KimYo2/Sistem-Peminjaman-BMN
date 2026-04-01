@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Daftar Barang
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">Kelola inventaris BMN
                </p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin.barang.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center gap-2">
                    @csrf
                    <label
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-lg text-sm font-medium cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Import CSV
                        <input type="file" name="file" accept=".csv" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>

                <a href="{{ route('admin.barang.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Barang
                </a>
            </div>
        </div>

        <form id="bulk-qr-form" action="{{ route('admin.barang.qr-label.bulk') }}" method="POST" target="_blank">
            @csrf
            <div id="bulk-qr-bar"
                class="hidden mb-4 px-4 py-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-center justify-between">
                <span class="text-sm text-blue-700 dark:text-blue-300"><strong id="bulk-count">0</strong> barang dipilih</span>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                    Cetak QR Terpilih
                </button>
            </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200
                    dark:border-slate-700 shadow-sm mb-6 transition-colors">

            @php
                $activeFilters = collect([
                    request('ketersediaan'),
                    request('status_barang'),
                    request('kategori_id'),
                    request('ruangan_id'),
                    request('search'),
                ])->filter()->count();
            @endphp

            {{-- Filter header with collapsible toggle --}}
            <button type="button" id="filter-toggle"
                class="w-full flex items-center justify-between px-5 py-3.5
                       text-sm font-semibold text-slate-700 dark:text-slate-300
                       hover:bg-slate-50 dark:hover:bg-slate-700/50
                       rounded-t-lg transition-colors">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    <span>Filter &amp; Pencarian</span>
                    @if($activeFilters > 0)
                        <span class="inline-flex items-center justify-center
                                     w-5 h-5 rounded-full bg-indigo-600 text-white
                                     text-xs font-bold">
                            {{ $activeFilters }}
                        </span>
                    @endif
                </div>
                <svg id="filter-chevron"
                     class="w-4 h-4 text-slate-400 transition-transform duration-200
                            {{ $activeFilters > 0 ? 'rotate-180' : '' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Filter body (collapsible) --}}
            <div id="filter-body"
                 class="{{ $activeFilters > 0 ? '' : 'hidden' }} border-t
                        border-slate-100 dark:border-slate-700">
                <form action="{{ route('admin.barang.index') }}" method="GET"
                      id="filter-form" class="p-5">

                    @if(request('per_page') && request('per_page') != 10)
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif

                    {{-- Row 1: 4 dropdowns --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">

                        <div>
                            <label class="block text-xs font-medium text-slate-500
                                          dark:text-slate-400 mb-1 uppercase tracking-wide">
                                Ketersediaan
                            </label>
                            <select name="ketersediaan"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900
                                       border border-slate-300 dark:border-slate-600
                                       rounded-lg text-sm text-slate-900 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition">
                                <option value="">Semua</option>
                                <option value="tersedia"
                                    {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                                <option value="dipinjam"
                                    {{ request('ketersediaan') == 'dipinjam' ? 'selected' : '' }}>
                                    Dipinjam
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-500
                                          dark:text-slate-400 mb-1 uppercase tracking-wide">
                                Status
                            </label>
                            <select name="status_barang"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900
                                       border border-slate-300 dark:border-slate-600
                                       rounded-lg text-sm text-slate-900 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition">
                                <option value="">Semua</option>
                                <option value="aktif"
                                    {{ request('status_barang') == 'aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="rusak_total"
                                    {{ request('status_barang') == 'rusak_total' ? 'selected' : '' }}>
                                    Rusak Total
                                </option>
                                <option value="hilang"
                                    {{ request('status_barang') == 'hilang' ? 'selected' : '' }}>
                                    Hilang
                                </option>
                                <option value="dihapuskan"
                                    {{ request('status_barang') == 'dihapuskan' ? 'selected' : '' }}>
                                    Dihapuskan
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-500
                                          dark:text-slate-400 mb-1 uppercase tracking-wide">
                                Kategori
                            </label>
                            <select name="kategori_id"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900
                                       border border-slate-300 dark:border-slate-600
                                       rounded-lg text-sm text-slate-900 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-500
                                          dark:text-slate-400 mb-1 uppercase tracking-wide">
                                Ruangan
                            </label>
                            <select name="ruangan_id"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900
                                       border border-slate-300 dark:border-slate-600
                                       rounded-lg text-sm text-slate-900 dark:text-white
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition">
                                <option value="">Semua Ruangan</option>
                                @foreach($ruanganList as $rng)
                                    <option value="{{ $rng->id }}"
                                        {{ request('ruangan_id') == $rng->id ? 'selected' : '' }}>
                                        {{ $rng->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: Search + action buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">

                        <div class="flex-1 relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0
                                        flex items-center pl-3">
                                <svg class="w-4 h-4 text-slate-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari nomor BMN, brand, atau tipe..."
                                   class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900
                                          border border-slate-300 dark:border-slate-600
                                          rounded-lg text-sm text-slate-900 dark:text-white
                                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                          transition placeholder-slate-400">
                        </div>

                        <div class="flex gap-2 shrink-0">
                            @if($activeFilters > 0)
                                <a href="{{ route('admin.barang.index') }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2
                                          bg-white dark:bg-slate-700
                                          border border-slate-300 dark:border-slate-600
                                          text-slate-600 dark:text-slate-300
                                          rounded-lg text-sm font-medium
                                          hover:bg-slate-50 dark:hover:bg-slate-600
                                          transition whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reset
                                </a>
                            @endif

                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-5 py-2
                                       bg-indigo-600 hover:bg-indigo-700
                                       text-white rounded-lg text-sm font-medium
                                       shadow-sm transition whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- Barang List -->
        <x-responsive-table>
                <thead
                    class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-10">
                            <input type="checkbox" id="select-all"
                                class="rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Nomor BMN</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Brand</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Ruangan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Kondisi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Peminjam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">PIC</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody
                    class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700 text-sm transition-colors">
                    @forelse($barang as $item)
                                    @php $isInactive = !$item->isAktif(); @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors {{ $isInactive ? 'opacity-60' : '' }}">
                                        <td class="px-4 py-3 text-center w-10">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" form="bulk-qr-form"
                                                class="bulk-checkbox rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <img src="{{ $item->foto_url }}"
                                                 alt="{{ $item->brand }}"
                                                 class="w-10 h-10 object-cover rounded-md"
                                                 loading="lazy">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $item->kode_barang }}-{{ $item->nup }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ $item->brand }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">{{ $item->tipe }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">
                                            {{ $item->kategori ? $item->kategori->nama_kategori : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">
                                            {{ $item->ruangan ? $item->ruangan->nama_ruangan : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                                                {{ $item->kondisi_badge_class }}">
                                                <span class="w-1.5 h-1.5 rounded-full
                                                                                                                    {{ $item->kondisi_dot_class }}"></span>
                                                {{ $item->kondisi_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md
                                                                                                                {{ $item->ketersediaan === 'tersedia' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                                                {{ ucfirst($item->ketersediaan) }}
                                            </span>
                                            @if(!$item->isAktif())
                                                <span class="mt-1 px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md {{ $item->status_barang_badge_class }}">
                                                    {{ $item->status_barang_label }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-slate-500 dark:text-slate-400 text-xs">
                                            @if(!empty($item->peminjam_terakhir))
                                                <div class="text-slate-700 dark:text-slate-200 text-xs font-medium">
                                                    {{ $item->peminjam_terakhir }}
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                                    @if($item->ketersediaan === 'dipinjam')
                                                        Dipinjam
                                                        {{ $item->waktu_pinjam ? \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/Y H:i') : '-' }}
                                                    @elseif($item->waktu_kembali)
                                                        Kembali
                                                        {{ \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/Y H:i') }}
                                                    @elseif($item->waktu_pinjam)
                                                        Terakhir dipinjam
                                                        {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/Y H:i') }}
                                                    @else
                                                        Terakhir dipinjam -
                                                    @endif
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300 text-xs">
                                            {{ $item->pic ? $item->pic->nama : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-1" x-data="{ openStatus: false }">
                                                {{-- QR Print --}}
                                                <div class="relative group">
                                                    <a href="{{ route('admin.barang.qr-label', $item->id) }}" target="_blank"
                                                        class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 p-1.5 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded transition block">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5
                                                                 whitespace-nowrap rounded-md bg-slate-800 dark:bg-slate-700
                                                                 px-2 py-1 text-xs text-white opacity-0 shadow-sm
                                                                 group-hover:opacity-100 transition-opacity duration-150 z-20">
                                                        Cetak QR
                                                    </span>
                                                </div>

                                                {{-- Edit --}}
                                                <div class="relative group">
                                                    <a href="{{ route('admin.barang.edit', $item->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1.5 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded transition block">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5
                                                                 whitespace-nowrap rounded-md bg-slate-800 dark:bg-slate-700
                                                                 px-2 py-1 text-xs text-white opacity-0 shadow-sm
                                                                 group-hover:opacity-100 transition-opacity duration-150 z-20">
                                                        Edit
                                                    </span>
                                                </div>

                                                {{-- Status Change --}}
                                                <div class="relative group">
                                                    <button @click="openStatus = true"
                                                        class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 p-1.5 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                        </svg>
                                                    </button>
                                                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5
                                                                 whitespace-nowrap rounded-md bg-slate-800 dark:bg-slate-700
                                                                 px-2 py-1 text-xs text-white opacity-0 shadow-sm
                                                                 group-hover:opacity-100 transition-opacity duration-150 z-20">
                                                        Ubah Status
                                                    </span>
                                                </div>

                                                {{-- Delete --}}
                                                <div class="relative group">
                                                    <form action="{{ route('admin.barang.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-300 p-1.5 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded transition">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5
                                                                 whitespace-nowrap rounded-md bg-slate-800 dark:bg-slate-700
                                                                 px-2 py-1 text-xs text-white opacity-0 shadow-sm
                                                                 group-hover:opacity-100 transition-opacity duration-150 z-20">
                                                        Hapus
                                                    </span>
                                                </div>

                                                    {{-- Status Change Modal --}}
                                                    <div x-show="openStatus"
                                                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                                                        style="display: none;">
                                                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full p-6"
                                                            @click.away="openStatus = false">
                                                            <div class="flex items-center gap-3 mb-4">
                                                                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <h3 class="text-base font-bold dark:text-white">Ubah Status Barang</h3>
                                                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->kode_barang }}-{{ sprintf('%03d', $item->nup) }} &mdash; {{ $item->brand }} {{ $item->tipe }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3 flex items-center gap-2">
                                                                <span class="text-xs text-slate-500 dark:text-slate-400">Status saat ini:</span>
                                                                <span class="inline-flex text-xs px-2 py-0.5 rounded font-semibold {{ $item->status_barang_badge_class }}">{{ $item->status_barang_label }}</span>
                                                            </div>

                                                            <form action="{{ route('admin.barang.update-status', $item->id) }}" method="POST" class="space-y-4">
                                                                @csrf
                                                                @method('PUT')

                                                                <div>
                                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status Baru <span class="text-red-500">*</span></label>
                                                                    <select name="status_barang" required
                                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5 text-sm">
                                                                        <option value="aktif" {{ ($item->status_barang ?? 'aktif') === 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                                                                        <option value="rusak_total" {{ $item->status_barang === 'rusak_total' ? 'selected' : '' }}>🔴 Rusak Total</option>
                                                                        <option value="hilang" {{ $item->status_barang === 'hilang' ? 'selected' : '' }}>⬛ Hilang</option>
                                                                        <option value="dihapuskan" {{ $item->status_barang === 'dihapuskan' ? 'selected' : '' }}>⚫ Dihapuskan</option>
                                                                    </select>
                                                                </div>

                                                                <div>
                                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Catatan</label>
                                                                    <textarea name="catatan_status" rows="2" placeholder="Alasan perubahan status..."
                                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5 text-sm">{{ $item->catatan_status }}</textarea>
                                                                </div>

                                                                <div class="flex justify-end gap-2">
                                                                    <button type="button" @click="openStatus = false"
                                                                        class="px-4 py-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 text-sm">Batal</button>
                                                                    <button type="submit"
                                                                        class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="12">
                                    <x-empty-state
                                        icon="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                        title="Belum ada data barang"
                                        description="Tambah barang pertama untuk memulai inventaris."
                                        actionLabel="Tambah Barang"
                                        actionRoute="{{ route('admin.barang.create') }}"
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

            <x-slot:pagination>
                <div class="flex flex-col sm:flex-row items-center justify-between
                            gap-3 px-4 py-3 border-t border-slate-200 dark:border-slate-700">

                    <div class="flex items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                        <span>
                            Menampilkan
                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $barang->firstItem() ?? 0 }}
                            </span>
                            &ndash;
                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $barang->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $barang->total() }}
                            </span>
                            barang
                        </span>

                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-slate-400 whitespace-nowrap">Tampilkan</span>
                            <select onchange="changePerPage(this.value)"
                                class="text-xs px-2 py-1.5 bg-slate-50 dark:bg-slate-900
                                       border border-slate-300 dark:border-slate-600
                                       rounded-md text-slate-700 dark:text-slate-300
                                       focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500
                                       cursor-pointer transition">
                                @foreach([10, 20, 50, 100] as $size)
                                    <option value="{{ $size }}"
                                        {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-xs text-slate-400">per halaman</span>
                        </div>
                    </div>

                    <div>
                        {{ $barang->links() }}
                    </div>
                </div>
            </x-slot:pagination>
        </x-responsive-table>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.bulk-checkbox');
            const bulkBar = document.getElementById('bulk-qr-bar');
            const bulkCount = document.getElementById('bulk-count');

            function updateBar() {
                const checked = document.querySelectorAll('.bulk-checkbox:checked').length;
                bulkCount.textContent = checked;
                bulkBar.classList.toggle('hidden', checked === 0);
            }

            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBar();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateBar));
        });

        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        (function () {
            const toggle  = document.getElementById('filter-toggle');
            const body    = document.getElementById('filter-body');
            const chevron = document.getElementById('filter-chevron');
            if (!toggle) return;
            toggle.addEventListener('click', function () {
                const isHidden = body.classList.toggle('hidden');
                chevron.classList.toggle('rotate-180', !isHidden);
            });
        })();
    </script>
@endsection
