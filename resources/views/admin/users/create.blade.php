@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('page-title', 'Tambah Pengguna Baru')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Form Tambah Pengguna
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Silakan isi data berikut untuk menambahkan pengguna baru.
        </p>
    </div>
    
    <form id="create-user-form" action="{{ route('admin.users.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
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
                       value="{{ old('email') }}" 
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
                       value="{{ old('phone') }}" 
                       required 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('phone') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="08xxxxxxxxxx">
                @error('phone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('password') border-red-500 dark:border-red-500 @enderror" 
                           placeholder="Minimal 8 karakter">
                    <button type="button" 
                            id="toggle-password" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                        <i id="password-icon" class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Password harus minimal 8 karakter.
                </p>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
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
                           checked 
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
                           {{ old('role') == 'admin' ? 'checked' : '' }} 
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
                    <input id="verified" 
                           name="verified" 
                           type="checkbox" 
                           value="1" 
                           {{ old('verified') ? 'checked' : '' }} 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                </div>
                <div class="ml-3 text-sm">
                    <label for="verified" class="font-medium text-gray-700 dark:text-gray-300">
                        Tandai Email Sebagai Terverifikasi
                    </label>
                    <p class="text-gray-500 dark:text-gray-400">
                        Jika dicentang, pengguna tidak perlu melakukan verifikasi email.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Batal
            </a>
            <button type="button" 
                    id="submit-button"
                    onclick="handleFormSubmissionWithConfirmation()"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        
        if (togglePassword && passwordInput && passwordIcon) {
            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                }
            });
        }
    });

    function handleFormSubmissionWithConfirmation() {
        // Get form data for confirmation message
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const role = document.querySelector('input[name="role"]:checked').value;
        const isVerified = document.getElementById('verified').checked;
        
        let confirmOptions = {
            title: 'Konfirmasi Tambah Pengguna',
            text: `Apakah Anda yakin ingin menambahkan pengguna baru dengan nama "${name}" dan role "${role}"?`,
            icon: 'question',
            confirmButtonText: 'Ya, Tambahkan Pengguna',
            cancelButtonText: 'Batal'
        };
        
        // Add special warning for admin role
        if (role === 'admin') {
            confirmOptions = {
                title: 'Konfirmasi Tambah Admin',
                text: `Anda akan menambahkan pengguna baru dengan role Administrator. Admin akan memiliki akses penuh ke panel admin. Lanjutkan menambahkan "${name}" sebagai admin?`,
                icon: 'warning',
                confirmButtonText: 'Ya, Tambahkan Admin',
                cancelButtonText: 'Batal'
            };
        }
        
        window.submitFormWithConfirmation('#create-user-form', confirmOptions);
    }
</script>
@endpush