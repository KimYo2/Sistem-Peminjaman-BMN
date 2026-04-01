@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-colors">

            <div class="mb-6">
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Tambah User</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Buat akun baru untuk pegawai atau admin.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="nip"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NIP</label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required maxlength="18"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition"
                        placeholder="Contoh: 198001012006041001">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Nama
                        Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition"
                        placeholder="Nama lengkap pegawai">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition"
                        placeholder="email@bps.go.id">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Role</label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Pegawai)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Profil --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">
                        Foto Profil
                        <span class="text-xs font-normal text-slate-400 ml-1">(opsional, maks. 2MB)</span>
                    </label>

                    {{-- Live preview --}}
                    <div class="flex items-center gap-4 mb-3">
                        <div id="avatar-preview-wrapper"
                             class="w-16 h-16 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                            <img id="avatar-preview" src="" alt="Preview"
                                 class="w-full h-full object-cover hidden">
                            <svg id="avatar-placeholder" class="w-8 h-8 text-slate-400"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto" id="foto" accept="image/*"
                                   class="block w-full text-sm text-slate-500 dark:text-slate-400
                                          file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          dark:file:bg-indigo-900/30 dark:file:text-indigo-300
                                          cursor-pointer">
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG, atau WebP. Jika tidak diisi, avatar otomatis dibuat dari inisial nama.</p>
                        </div>
                    </div>

                    @error('foto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-2.5 text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg font-medium transition">Batal</a>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-lg font-medium shadow-sm transition">
                        Simpan User
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            const img = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            img.src = ev.target.result;
            img.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
    </script>
@endsection
