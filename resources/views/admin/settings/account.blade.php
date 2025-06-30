@extends('admin.layouts.app')

@section('title', 'Pengaturan Akun Admin')

@section('page-title', 'Pengaturan Akun')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6 animate-fade-in text-center lg:text-left">
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">
            Pengaturan Akun Admin
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 transition-colors duration-300">
            Kelola informasi akun dan keamanan administrator
        </p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Left Column - Profile Information -->
        <div class="space-y-6">

            <!-- Profile Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Admin</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Perbarui informasi profil administrator</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <form action="{{ route('admin.settings.update-profile') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Admin Avatar Section -->
                        <div class="flex flex-col items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mb-3">
                                <span class="text-white text-2xl font-bold">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $admin->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $admin->email }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300 mt-2">
                                <i class="fas fa-crown mr-1"></i>
                                Administrator
                            </span>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-indigo-500"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-200 @error('name') border-red-500 dark:border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-500"></i>
                                Email Administrator
                            </label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ $admin->email }}" required class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gray-400 cursor-not-allowed" disabled>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                    <i class="fas fa-lock text-gray-400 dark:text-gray-500"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Email admin tidak dapat diubah untuk keamanan
                            </p>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-green-500"></i>
                                Nomor Telepon
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-200 @error('phone') border-red-500 dark:border-red-500 @enderror">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: 08xxxxxxxxxx (tanpa spasi atau tanda hubung)
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" id="profile-submit-btn" class="w-full px-5 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transform hover:scale-[1.02] transition-all duration-200">
                                <span class="btn-text">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </span>
                                <span class="btn-loading hidden">
                                    <div class="loading-spinner mr-2"></div>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Security Settings -->
        <div class="space-y-6">

            <!-- Change Password Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ubah Password</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Perbarui password administrator</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <form action="{{ route('admin.settings.update-password') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-key mr-2 text-gray-500"></i>
                                Password Saat Ini
                            </label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200 @error('current_password') border-red-500 dark:border-red-500 @enderror">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 focus:outline-none transition-colors duration-200" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-shield-alt mr-2 text-purple-500"></i>
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200 @error('password') border-red-500 dark:border-red-500 @enderror">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 focus:outline-none transition-colors duration-200" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Minimal 8 karakter, kombinasi huruf dan angka
                            </p>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 focus:outline-none transition-colors duration-200" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" id="password-submit-btn" class="w-full px-5 py-3 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-lg shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transform hover:scale-[1.02] transition-all duration-200">
                                <span class="btn-text">
                                    <i class="fas fa-key mr-2"></i>
                                    Ubah Password
                                </span>
                                <span class="btn-loading hidden">
                                    <div class="loading-spinner mr-2"></div>
                                    Mengubah...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Keamanan Administrator</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pengaturan keamanan dan aktivitas</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-4">

                    <!-- Admin Status -->
                    <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-semibold text-green-800 dark:text-green-300">
                                    Status Administrator
                                </h3>
                                <p class="text-sm text-green-700 dark:text-green-400 mt-1">
                                    Akun administrator aktif dan terverifikasi
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Bergabung sejak</p>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $admin->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $admin->updated_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logout All Devices -->
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-sign-out-alt text-gray-500 dark:text-gray-400 text-lg"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-300">Logout dari Semua Perangkat</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Keluar dari akun administrator di semua perangkat lain yang mungkin sedang login.
                                </p>
                                <div class="mt-3">
                                    <form action="{{ route('admin.settings.logout-all-devices') }}" method="POST" class="inline" id="logout-devices-form">
                                        @csrf
                                        <button type="submit" id="logout-devices-btn" class="text-sm px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transform hover:scale-[1.02] transition-all duration-200">
                                            <span class="btn-text">
                                                <i class="fas fa-power-off mr-1"></i>
                                                Logout Semua Perangkat
                                            </span>
                                            <span class="btn-loading hidden">
                                                <div class="loading-spinner mr-1"></div>
                                                Memproses...
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .loading-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid currentColor;
        border-radius: 50%;
        border-right-color: transparent;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setButtonLoading(button, isLoading) {
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');
            if (isLoading) {
                button.disabled = true;
                button.classList.add('opacity-75', 'cursor-not-allowed');
                if (btnText) btnText.classList.add('hidden');
                if (btnLoading) btnLoading.classList.remove('hidden');
            } else {
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
                if (btnText) btnText.classList.remove('hidden');
                if (btnLoading) btnLoading.classList.add('hidden');
            }
        }

        function displayErrors(errors, form) {
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('border-red-500', 'dark:border-red-500');
                    const parent = input.closest('div');
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                    errorMsg.textContent = errors[field][0];
                    parent.appendChild(errorMsg);
                }
            });
        }

        function clearErrors() {
            document.querySelectorAll('.text-red-600, .text-red-400').forEach(msg => {
                if (msg.classList.contains('mt-1')) {
                    msg.remove();
                }
            });
            document.querySelectorAll('input.border-red-500').forEach(input => {
                input.classList.remove('border-red-500', 'dark:border-red-500');
            });
        }

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        async function handleFormSubmit(form, options = {}) {
            const submitBtn = form.querySelector('button[type="submit"]');
            setButtonLoading(submitBtn, true);
            clearErrors();

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw data;
                }

                if (options.onSuccess) {
                    options.onSuccess(data);
                }

            } catch (error) {
                const errorMessage = error.message || 'Terjadi kesalahan. Silakan coba lagi.';
                showToast(errorMessage, 'error');
                if (error.errors) {
                    displayErrors(error.errors, form);
                }
            } finally {
                if (!options.redirect) {
                    setButtonLoading(submitBtn, false);
                }
            }
        }

        // Initialize Profile Form
        function initProfileForm() {
            const form = document.querySelector('form[action*="update-profile"]');
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    handleFormSubmit(form, {
                        onSuccess: (data) => {
                            showToast(data.message || 'Profil admin berhasil diperbarui!', 'success');
                            const newName = form.querySelector('#name').value;
                            const avatarText = document.querySelector('.w-20.h-20 span');
                            const nameDisplay = document.querySelector('h3.text-lg.font-semibold');
                            if (avatarText) avatarText.textContent = newName.charAt(0).toUpperCase();
                            if (nameDisplay) nameDisplay.textContent = newName;
                        }
                    });
                });
            }
        }

        // Initialize Password Form
        function initPasswordForm() {
            const form = document.querySelector('form[action*="update-password"]');
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    handleFormSubmit(form, {
                        onSuccess: (data) => {
                            showToast(data.message || 'Password admin berhasil diubah!', 'success');
                            form.reset();
                        }
                    });
                });
            }
        }

        // Initialize Logout Devices Form
        function initLogoutDevicesForm() {
            const form = document.getElementById('logout-devices-form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    showConfirmation({
                        title: 'Logout dari Semua Perangkat?',
                        text: 'Anda akan diminta untuk login kembali di semua perangkat, termasuk yang ini.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, Logout!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            handleFormSubmit(form, {
                                redirect: true,
                                onSuccess: (data) => {
                                    showToast('Berhasil logout dari semua perangkat!', 'success');
                                    setTimeout(() => {
                                        window.location.href = data.redirect || '/login';
                                    }, 2000);
                                }
                            });
                        }
                    });
                });
            }
        }

        // Initialize Password Toggle
        function initPasswordToggle() {
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });
        }

        // Initialize Input Events
        function initInputEvents() {
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500')) {
                        this.classList.remove('border-red-500', 'dark:border-red-500');
                        const parent = this.closest('div');
                        const errorMsg = parent.querySelector('.text-red-600, .text-red-400');
                        if (errorMsg && errorMsg.classList.contains('mt-1')) {
                            errorMsg.remove();
                        }
                    }
                });
            });
        }

        initProfileForm();
        initPasswordForm();
        initLogoutDevicesForm();
        initPasswordToggle();
        initInputEvents();
    });
</script>
@endpush