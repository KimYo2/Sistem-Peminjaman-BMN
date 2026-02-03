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

        <!-- Filters -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6 transition-colors">
            <form action="{{ route('admin.barang.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Filter
                        Ketersediaan</label>
                    <select name="ketersediaan" onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                        <option value="">Semua</option>
                        <option value="tersedia" {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>Tersedia
                        </option>
                        <option value="dipinjam" {{ request('ketersediaan') == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                        </option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nomor BMN, brand, atau tipe..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition placeholder-slate-400">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg transition shadow-sm">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Barang List -->
        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead
                        class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider transition-colors">
                        <tr>
                            <th class="px-6 py-3 text-left">Nomor BMN</th>
                            <th class="px-6 py-3 text-left">Brand</th>
                            <th class="px-6 py-3 text-left">Tipe</th>
                            <th class="px-6 py-3 text-left">Kondisi</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Peminjam</th>
                            <th class="px-6 py-3 text-left">PIC</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 text-sm transition-colors">
                        @forelse($barang as $item)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">
                                                {{ $item->kode_barang }}-{{ $item->nup }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $item->brand }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $item->tipe }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                                                                    {{ $item->kondisi_terakhir === 'baik' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' :
                            ($item->kondisi_terakhir === 'rusak_ringan' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300') }}">
                                                    <span class="w-1.5 h-1.5 rounded-full 
                                                                                                                        {{ $item->kondisi_terakhir === 'baik' ? 'bg-green-500' :
                            ($item->kondisi_terakhir === 'rusak_ringan' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                                    {{ ucwords(str_replace('_', ' ', $item->kondisi_terakhir)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md 
                                                                                                                    {{ $item->ketersediaan === 'tersedia' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                                                    {{ ucfirst($item->ketersediaan) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400 font-mono text-xs">
                                                {{ $item->peminjam_terakhir ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300 text-xs">
                                                {{ $item->pic ? $item->pic->nama : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.barang.edit', $item->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('admin.barang.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-300 p-1 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded transition">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Tidak ada data barang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-slate-200 dark:border-slate-700 sm:px-6">
                {{ $barang->links() }}
            </div>
        </div>

    </div>
@endsection
