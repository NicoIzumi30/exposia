@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('page-title', 'Edit Pengguna')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit Pengguna
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Perbarui informasi pengguna: {{ $user->name }}
                </p>
            </div>
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>
    </div>
    
    <form id="edit-user-form" action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('name') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('email') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="contoh@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Phone Field -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone', $user->phone) }}" 
                       required 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('phone') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="08xxxxxxxxxx">
                @error('phone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Account Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Status Akun
                </label>
                <div class="flex flex-col space-y-2">
                    <div class="flex items-center">
                        <span class="mr-3 text-sm text-gray-700 dark:text-gray-300">Status:</span>
                        @if($user->is_suspended)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                Dinonaktifkan
                            </span>
                        @elseif(!$user->is_active || !$user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <span class="w-1.5 h-1.5 inline-block bg-yellow-500 rounded-full mr-1.5"></span>
                                Belum Verifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                Aktif
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <span class="mr-3 text-sm text-gray-700 dark:text-gray-300">Verifikasi:</span>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1.5"></i>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <i class="fas fa-clock mr-1.5"></i>
                                Belum Verifikasi
                            </span>
                        @endif
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Untuk mengubah status akun, gunakan tombol aktivasi/nonaktivasi di halaman detail pengguna.
                </p>
            </div>
        </div>
        
        <!-- Role Field -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <input id="role-user" 
                           name="role" 
                           type="radio" 
                           value="user" 
                           {{ (old('role', $user->role) == 'user') ? 'checked' : '' }} 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <label for="role-user" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        User (Pengguna)
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="role-admin" 
                           name="role" 
                           type="radio" 
                           value="admin" 
                           {{ (old('role', $user->role) == 'admin') ? 'checked' : '' }} 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <label for="role-admin" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Admin (Administrator)
                    </label>
                </div>
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Pengguna dengan role Admin akan memiliki akses ke panel admin.
            </p>
        </div>
        
        <!-- Email Verification Field -->
        <div class="mt-6">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="verify_email" 
                           name="verify_email" 
                           type="checkbox" 
                           value="1" 
                           {{ $user->email_verified_at ? 'checked' : (old('verify_email') ? 'checked' : '') }} 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                </div>
                <div class="ml-3 text-sm">
                    <label for="verify_email" class="font-medium text-gray-700 dark:text-gray-300">
                        Tandai Email Sebagai Terverifikasi
                    </label>
                    <p class="text-gray-500 dark:text-gray-400">
                        Jika dicentang, email pengguna akan dianggap sudah terverifikasi.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Reset Password Note -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                Reset Password
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Untuk mereset password pengguna, gunakan tombol "Reset Password" di halaman detail pengguna.
            </p>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Batal
            </a>
            <button type="button" 
                    id="submit-button"
                    onclick="handleFormSubmissionWithConfirmation()"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function handleFormSubmissionWithConfirmation() {
        // Check if there are significant changes (role change)
        const currentRole = '{{ $user->role }}';
        const newRole = document.querySelector('input[name="role"]:checked').value;
        const isRoleChanged = currentRole !== newRole;
        
        let confirmOptions = {
            title: 'Konfirmasi Perubahan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan data pengguna?',
            icon: 'question',
            confirmButtonText: 'Ya, Simpan Perubahan',
            cancelButtonText: 'Batal'
        };
        
        // If role is being changed, show a more specific warning
        if (isRoleChanged) {
            confirmOptions = {
                title: 'Konfirmasi Perubahan Role',
                text: `Role pengguna akan diubah dari "${currentRole}" menjadi "${newRole}". Hal ini akan mengubah hak akses pengguna. Lanjutkan?`,
                icon: 'warning',
                confirmButtonText: 'Ya, Ubah Role',
                cancelButtonText: 'Batal'
            };
        }
        
        window.submitFormWithConfirmation('#edit-user-form', confirmOptions);
    }
</script>
@endpush