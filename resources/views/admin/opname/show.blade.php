@extends('layouts.app')

@section('title', 'Detail Stock Opname')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">
                    {{ $session->nama }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">
                    Mulai: {{ optional($session->started_at)->format('d/m/Y H:i') ?? '-' }}
                    | Petugas: {{ $session->starter->nama ?? '-' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.opname.index') }}"
                    class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Kembali
                </a>
                <a href="{{ route('admin.opname.export', $session->id) }}"
                    class="px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition">
                    Export CSV
                </a>
                @if($session->status === 'berjalan')
                    <form action="{{ route('admin.opname.finish', $session->id) }}" method="POST"
                        onsubmit="return confirm('Selesaikan sesi stock opname ini?')">
                        @csrf
                        <button type="submit"
                            class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                            Selesaikan
                        </button>
                    </form>
                @endif
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div
                class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-4 transition-colors">
                <p class="text-xs text-slate-500 dark:text-slate-400">Total Barang</p>
                <p id="stat-total" class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-4 transition-colors">
                <p class="text-xs text-slate-500 dark:text-slate-400">Ditemukan</p>
                <p id="stat-found" class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['found'] }}
                </p>
            </div>
            <div
                class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-4 transition-colors">
                <p class="text-xs text-slate-500 dark:text-slate-400">Belum Ditemukan</p>
                <p id="stat-missing" class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">{{ $stats['missing'] }}
                </p>
            </div>
        </div>

        @if($session->status === 'berjalan')
            <div
                class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6 transition-colors">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-white mb-4">Scan Barang</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <form id="manual-scan-form" class="lg:col-span-2 space-y-3">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Input Nomor BMN</label>
                        <div class="flex items-center gap-2">
                            <input id="manual-nomor-bmn" type="text" required placeholder="Contoh: 3100102001-1"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <button type="submit"
                                class="px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                                Scan
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Bisa menerima format QR BPS ataupun format `kode-nup`.
                        </p>
                    </form>

                    <div class="space-y-3">
                        <button id="toggle-camera-btn" type="button"
                            class="w-full px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                            Aktifkan Kamera
                        </button>
                        <div id="qr-reader-wrapper" class="hidden">
                            <div id="qr-reader" class="w-full rounded-lg overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div id="scan-feedback" class="mt-4"></div>
            </div>
        @endif

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-4 transition-colors">
            <form method="GET" action="{{ route('admin.opname.show', $session->id) }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Semua</option>
                        <option value="found" {{ request('status') === 'found' ? 'selected' : '' }}>Ditemukan</option>
                        <option value="missing" {{ request('status') === 'missing' ? 'selected' : '' }}>Belum Ditemukan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nup..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-sm">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.opname.show', $session->id) }}"
                        class="px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Reset
                    </a>
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
                            <th class="px-6 py-3 text-left">Nomor BMN</th>
                            <th class="px-6 py-3 text-left">Kondisi Ekspektasi</th>
                            <th class="px-6 py-3 text-left">Kondisi Aktual</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Waktu Scan</th>
                            <th class="px-6 py-3 text-left">Scanner</th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 text-sm transition-colors">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900 dark:text-white">
                                    {{ $item->kode_barang }}-{{ $item->nup }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ $item->expected_kondisi ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ $item->actual_kondisi ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status === 'found')
                                        <span
                                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                            Ditemukan
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ optional($item->scanned_at)->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                    {{ $item->scanner->nama ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    Tidak ada data item untuk filter saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-slate-200 dark:border-slate-700 sm:px-6">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection

@if($session->status === 'berjalan')
    @push('scripts')
        <script>
            (function () {
                const scanUrl = @json(route('admin.opname.scan', $session->id));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const feedbackEl = document.getElementById('scan-feedback');
                const statTotalEl = document.getElementById('stat-total');
                const statFoundEl = document.getElementById('stat-found');
                const statMissingEl = document.getElementById('stat-missing');
                const manualForm = document.getElementById('manual-scan-form');
                const manualInput = document.getElementById('manual-nomor-bmn');
                const cameraButton = document.getElementById('toggle-camera-btn');
                const cameraWrapper = document.getElementById('qr-reader-wrapper');

                let scanner = null;
                let scanning = false;
                let busy = false;
                let lastCode = '';
                let lastCodeAt = 0;

                function setFeedback(type, message) {
                    const map = {
                        success: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300',
                        warning: 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300',
                        error: 'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300',
                    };

                    feedbackEl.innerHTML = `
                        <div class="p-3 rounded-lg text-sm ${map[type] || map.error}">
                            ${message}
                        </div>
                    `;
                }

                function updateStats(stats) {
                    if (!stats) return;
                    statTotalEl.textContent = stats.total ?? 0;
                    statFoundEl.textContent = stats.found ?? 0;
                    statMissingEl.textContent = stats.missing ?? 0;
                }

                async function submitScan(rawValue) {
                    const parsedValue = window.QrScan ? window.QrScan.parseBmn(rawValue) : String(rawValue || '').trim();
                    if (!parsedValue) {
                        setFeedback('error', 'Nomor BMN tidak valid.');
                        return;
                    }

                    const now = Date.now();
                    if (parsedValue === lastCode && now - lastCodeAt < 1500) {
                        return;
                    }

                    if (busy) {
                        return;
                    }

                    busy = true;
                    lastCode = parsedValue;
                    lastCodeAt = now;

                    try {
                        const response = await fetch(scanUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                nomor_bmn: parsedValue,
                            }),
                        });

                        const payload = await response.json();
                        updateStats(payload.stats);

                        if (!response.ok || payload.success === false) {
                            setFeedback('error', payload.message || 'Scan gagal diproses.');
                            return;
                        }

                        if (payload.duplicate) {
                            setFeedback('warning', `${payload.item} sudah tercatat.`);
                        } else {
                            setFeedback('success', `${payload.item} berhasil ditandai ditemukan.`);
                        }
                    } catch (error) {
                        setFeedback('error', 'Terjadi kesalahan saat mengirim scan.');
                    } finally {
                        busy = false;
                    }
                }

                async function stopCamera() {
                    await window.QrScan.stopCamera(scanner);
                    scanner = null;
                    scanning = false;
                    cameraWrapper.classList.add('hidden');
                    cameraButton.textContent = 'Aktifkan Kamera';
                }

                async function startCamera() {
                    const cameraError = window.QrScan.getCameraError();
                    if (cameraError) {
                        setFeedback('error', cameraError);
                        return;
                    }

                    cameraWrapper.classList.remove('hidden');

                    try {
                        scanner = await window.QrScan.startCamera({
                            elementId: 'qr-reader',
                            onDecoded: function (decodedText) {
                                submitScan(decodedText);
                            },
                        });
                        scanning = true;
                        cameraButton.textContent = 'Matikan Kamera';
                    } catch (error) {
                        cameraWrapper.classList.add('hidden');
                        setFeedback('error', `Gagal mengaktifkan kamera: ${error.message || 'Unknown error'}`);
                    }
                }

                manualForm?.addEventListener('submit', function (event) {
                    event.preventDefault();
                    submitScan(manualInput.value);
                    manualInput.value = '';
                    manualInput.focus();
                });

                cameraButton?.addEventListener('click', async function () {
                    if (scanning) {
                        await stopCamera();
                        return;
                    }

                    await startCamera();
                });

                window.addEventListener('beforeunload', function () {
                    if (scanning) {
                        stopCamera();
                    }
                });
            })();
        </script>
    @endpush
@endif
