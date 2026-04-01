@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight transition-colors">Manajemen User
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight transition-colors">Kelola akun pegawai dan
                    admin</p>
            </div>

            <a href="{{ route('admin.users.create') }}"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah User
            </a>
        </div>

        @if(session('success'))
            <div
                class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('error'))
            <div
                class="mb-4 px-4 py-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg text-sm">
                {{ $errors->first('error') }}
            </div>
        @endif

        <div class="mb-4">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..."
                        class="w-full sm:w-80 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <button type="submit"
                        class="px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <x-responsive-table>
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                            Nama</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                            Email</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                            Role</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                            Status</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}"
                                         alt="{{ $user->name }}"
                                         class="w-9 h-9 rounded-full object-cover flex-shrink-0 ring-1 ring-slate-200 dark:ring-slate-600"
                                         loading="lazy">
                                    <div>
                                        <p class="font-medium text-slate-800 dark:text-white text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $user->nip }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' => $user->role === 'admin',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' => $user->role === 'user',
                                ])>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' => $user->is_active,
                                    'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' => !$user->is_active,
                                ])>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" title="Edit"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition inline-flex items-center gap-1 px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    @if($user->id !== Auth::id())
                                        @if($user->is_active)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Nonaktifkan user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Nonaktifkan"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium transition inline-flex items-center gap-1 px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/30">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                                    <span class="hidden sm:inline">Nonaktifkan</span>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                Tidak ada user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            <x-slot:pagination>
                {{ $users->links() }}
            </x-slot:pagination>
        </x-responsive-table>

    </div>
@endsection
