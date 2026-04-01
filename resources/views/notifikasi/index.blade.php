@extends('layouts.app')

@section('title', 'Notifikasi — SIAP')

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Notifikasi</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Semua notifikasi ditandai telah dibaca saat halaman ini dibuka.
                </p>
            </div>
        </div>

        @if($notifikasi->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Tidak ada notifikasi</p>
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($notifikasi as $item)
                    <div class="flex gap-4 px-5 py-4 transition-colors
                        {{ $item->is_read
                            ? 'bg-white dark:bg-slate-800'
                            : 'bg-blue-50/60 dark:bg-blue-950/20' }}">

                        {{-- Type icon --}}
                        <div class="shrink-0 mt-0.5">
                            @if($item->type === 'overdue')
                                <div class="w-9 h-9 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @elseif($item->type === 'approval')
                                <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @elseif($item->type === 'waitlist')
                                <div class="w-9 h-9 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $item->judul }}
                                </p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $item->type_badge_class }}">
                                    {{ $item->type_label }}
                                </span>
                                @if(!$item->is_read)
                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500 shrink-0" title="Belum dibaca"></span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                {{ $item->pesan }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">
                                {{ $item->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                &middot;
                                {{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </p>
                        </div>

                        {{-- Mark read action (if unread) --}}
                        @if(!$item->is_read)
                            <div class="shrink-0">
                                <a href="{{ route('notifikasi.read', $item->id) }}"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">
                                    Tandai dibaca
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $notifikasi->links() }}
            </div>
        @endif

    </div>
@endsection
