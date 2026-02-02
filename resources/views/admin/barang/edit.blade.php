@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-colors">

            <div class="mb-6">
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Edit Barang</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $barang->kode_barang }}-{{ sprintf('%03d', $barang->nup) }}
                </p>
            </div>

            <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kode
                            Barang</label>
                        <input type="text" value="{{ $barang->kode_barang }}" disabled
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed transition-colors">
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NUP</label>
                        <input type="text" value="{{ $barang->nup }}" disabled
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed transition-colors">
                    </div>
                </div>

                <div>
                    <label for="brand"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Brand
                        / Merk</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $barang->brand) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipe"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Tipe /
                        Model</label>
                    <input type="text" name="tipe" id="tipe" value="{{ old('tipe', $barang->tipe) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('tipe')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label for="kondisi"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kondisi
                            Terakhir</label>
                        <select name="kondisi" id="kondisi" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="baik" {{ old('kondisi', $barang->kondisi_terakhir) == 'baik' ? 'selected' : '' }}>
                                Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi', $barang->kondisi_terakhir) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi', $barang->kondisi_terakhir) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="ketersediaan"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Status
                            Ketersediaan</label>
                        <select name="ketersediaan" id="ketersediaan" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="tersedia" {{ old('ketersediaan', $barang->ketersediaan) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="dipinjam" {{ old('ketersediaan', $barang->ketersediaan) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="hilang" {{ old('ketersediaan', $barang->ketersediaan) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            <option value="reparasi" {{ old('ketersediaan', $barang->ketersediaan) == 'reparasi' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        </select>
                        @error('ketersediaan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Note: Keterangan field was in legacy code but unclear if in DB. Excluding for now to avoid error, as fillable update didn't include it. -->

                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.barang.index') }}"
                        class="px-5 py-2.5 text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg font-medium transition">Batal</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-8 py-2.5 rounded-lg font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
@endsection