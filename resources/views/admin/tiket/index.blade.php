@extends('layouts.app')

@section('title', 'Daftar Tiket Kerusakan')
@section('header_title', 'Tiket Kerusakan')
@section('header_subtitle', 'Kelola laporan kerusakan barang')

@section('content')
    <div
        class="bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-200 dark:border-slate-700 p-6 transition-colors">

        <div class="mb-6">
            <form action="{{ route('admin.tiket.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Status</label>
                    <select name="status"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5 text-sm">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Prioritas</label>
                    <select name="priority"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5 text-sm">
                        <option value="">Semua Prioritas</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Assignee</label>
                    <select name="assigned_to"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5 text-sm">
                        <option value="">Semua Admin</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ (string) request('assigned_to') === (string) $admin->id ? 'selected' : '' }}>
                                {{ $admin->nama }} ({{ $admin->nip }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-300"
                role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->has('status') || $errors->has('priority') || $errors->has('assigned_to') || $errors->has('target_selesai_at'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                <thead
                    class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider transition-colors">
                    <tr>
                        <th class="px-6 py-3 text-left">No BMN</th>
                        <th class="px-6 py-3 text-left">Pelapor</th>
                        <th class="px-6 py-3 text-left">Jenis</th>
                        <th class="px-6 py-3 text-left">Prioritas</th>
                        <th class="px-6 py-3 text-left">Assignee</th>
                        <th class="px-6 py-3 text-left">Target</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Catatan</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr
                            class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                            <td class="px-6 py-4 font-medium text-slate-900 dark:text-white whitespace-nowrap">
                                {{ $ticket->nomor_bmn }}
                            </td>
                            <td class="px-6 py-4">{{ $ticket->pelapor }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $ticket->jenis_kerusakan == 'berat' ? 'text-red-500 font-bold' : 'text-yellow-500' }}">
                                    {{ ucfirst($ticket->jenis_kerusakan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $priorityColor = [
                                        'high' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'medium' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                        'low' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                    ];
                                @endphp
                                <span class="{{ $priorityColor[$ticket->priority] ?? $priorityColor['medium'] }} text-xs px-2 py-0.5 rounded">
                                    {{ strtoupper($ticket->priority ?? 'medium') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ optional($ticket->assignee)->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $ticket->target_selesai_at ? $ticket->target_selesai_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = [
                                        'open' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'diproses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    ];
                                @endphp
                                <span class="{{ $statusColor[$ticket->status] ?? '' }} text-xs px-2 py-0.5 rounded">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="truncate">{{ $ticket->admin_notes ? Str::limit($ticket->admin_notes, 50) : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div x-data="{ open: false }">
                                    <button @click="open = true"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Update</button>

                                    <div x-show="open"
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                                        style="display: none;">
                                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-lg w-full p-6"
                                            @click.away="open = false">
                                            <h3 class="text-lg font-bold mb-4 dark:text-white">Update Tiket</h3>
                                            <form action="{{ route('admin.tiket.update', $ticket->id) }}" method="POST"
                                                class="space-y-3">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Status</label>
                                                    <select name="status"
                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5">
                                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                                        <option value="diproses" {{ $ticket->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                        <option value="selesai" {{ $ticket->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Prioritas</label>
                                                    <select name="priority"
                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5">
                                                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                                        <option value="medium" {{ ($ticket->priority ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Assign To</label>
                                                    <select name="assigned_to"
                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5">
                                                        <option value="">-</option>
                                                        @foreach($admins as $admin)
                                                            <option value="{{ $admin->id }}" {{ (string) $ticket->assigned_to === (string) $admin->id ? 'selected' : '' }}>
                                                                {{ $admin->nama }} ({{ $admin->nip }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Target Selesai</label>
                                                    <input type="datetime-local" name="target_selesai_at"
                                                        value="{{ $ticket->target_selesai_at ? $ticket->target_selesai_at->format('Y-m-d\\TH:i') : '' }}"
                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5">
                                                </div>

                                                <div>
                                                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Catatan Admin</label>
                                                    <textarea name="admin_notes" rows="3"
                                                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg p-2.5">{{ $ticket->admin_notes }}</textarea>
                                                </div>

                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="open = false"
                                                        class="px-4 py-2 text-slate-500 hover:text-slate-700">Batal</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">Tidak ada data tiket kerusakan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
@endsection
