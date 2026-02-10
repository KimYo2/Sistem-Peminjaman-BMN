@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Stock Opname
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">
                    Validasi fisik inventaris BMN berdasarkan scan QR.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-300"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-300" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @if($runningSession)
            <div
                class="mb-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Masih ada sesi berjalan:
                        {{ $runningSession->nama }}</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        Mulai {{ optional($runningSession->started_at)->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </div>
                <a href="{{ route('admin.opname.show', $runningSession->id) }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold transition">
                    Lanjutkan Sesi
                </a>
            </div>
        @endif

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6 transition-colors">
            <form action="{{ route('admin.opname.start') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div class="md:col-span-2">
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Nama
                        Sesi</label>
                    <input type="text" name="nama" required value="{{ old('nama') }}"
                        placeholder="Contoh: Opname Ruang Server Februari 2026"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Catatan
                        (opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Ruang, lantai, PIC..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="flex items-end">
                    <button type="submit" {{ $runningSession ? 'disabled' : '' }}
                        class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-400 disabled:cursor-not-allowed text-white font-medium py-2.5 px-5 rounded-lg transition shadow-sm">
                        Mulai Opname
                    </button>
                </div>
            </form>
        </div>

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead
                        class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider transition-colors">
                        <tr>
                            <th class="px-6 py-3 text-left">Sesi</th>
                            <th class="px-6 py-3 text-left">Mulai</th>
                            <th class="px-6 py-3 text-left">Selesai</th>
                            <th class="px-6 py-3 text-left">Progress</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 text-sm transition-colors">
                        @forelse($sessions as $session)
                            @php
                                $missingItems = max(($session->total_items ?? 0) - ($session->found_items ?? 0), 0);
                                $progress = ($session->total_items ?? 0) > 0
                                    ? round((($session->found_items ?? 0) / $session->total_items) * 100)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900 dark:text-white">{{ $session->nama }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        Oleh {{ $session->starter->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ optional($session->started_at)->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ optional($session->finished_at)->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-600 dark:text-slate-300 mb-1">
                                        {{ $session->found_items ?? 0 }}/{{ $session->total_items ?? 0 }} ditemukan
                                        ({{ $missingItems }} belum)
                                    </div>
                                    <div class="w-36 bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $progress }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($session->status === 'berjalan')
                                        <span
                                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                            Berjalan
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.opname.show', $session->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs font-semibold">
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.opname.export', $session->id) }}"
                                            class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 text-xs font-semibold">
                                            Export CSV
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    Belum ada sesi stock opname.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-slate-200 dark:border-slate-700 sm:px-6">
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
@endsection
