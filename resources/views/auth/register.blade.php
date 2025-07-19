<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Platform UMKM</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        // Tailwind configuration - Monochrome theme
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a'
                        }
                    },
                    animation: {
                        'slide-in-right': 'slideInRight 0.6s ease-out',
                        'fade-in': 'fadeIn 1s ease-out',
                        'spin-slow': 'spin 1s linear infinite',
                        'pulse-soft': 'pulseSoft 2s infinite'
                    },
                    keyframes: {
                        slideInRight: {
                            'from': { transform: 'translateX(100px)', opacity: '0' },
                            'to': { transform: 'translateX(0)', opacity: '1' }
                        },
                        fadeIn: {
                            'from': { opacity: '0' },
                            'to': { opacity: '1' }
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.5' }
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid currentColor;
            border-radius: 50%;
            border-right-color: transparent;
            animation: spin 1s linear infinite;
        }
        
        .glass-morphism {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="h-full bg-gray-50 transition-colors duration-300 font-sans">
    <div class="min-h-screen flex">
        <!-- Left Side - Image -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80"
                alt="Ruang kerja bisnis modern"
                class="absolute inset-0 w-full h-full object-cover animate-fade-in"
            />
            <!-- Monochrome Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900/90 via-gray-800/80 to-gray-700/70"></div>
            
            <!-- Left side content -->
            <div class="relative z-10 flex flex-col justify-end p-8 lg:p-12 text-white">
                <div class="mb-8">
                    <!-- Logo -->
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center glass-morphism border border-white/20">
                            <i class="fas fa-store text-2xl text-white"></i>
                        </div>
                        <h1 class="ml-4 text-2xl font-bold text-shadow">Platform Exposia</h1>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Main Heading -->
                    <h2 class="text-3xl lg:text-4xl font-bold leading-tight text-shadow">
                        Mulai Perjalanan 
                        <span class="text-white bg-gradient-to-r from-white to-gray-200 bg-clip-text">
                            Digital
                        </span> 
                        Anda
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-lg lg:text-xl opacity-90 leading-relaxed max-w-md">
                        Bergabunglah dengan ribuan bisnis UMKM yang telah mengubah 
                        kehadiran mereka dengan pembuat website yang mudah digunakan.
                    </p>
                    
                    <!-- Feature highlights -->
                    <div class="space-y-3">
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-white mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Setup website gratis dalam hitungan menit</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-white mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Template profesional tersedia</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-white mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Bantuan konten bertenaga AI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div id="register-container" class="w-full lg:w-1/2 flex flex-col justify-center bg-white transition-colors duration-300">
            <div class="px-6 sm:px-8 py-12 md:px-20 mx-auto w-full animate-slide-in-right">

                <!-- Register Header -->
                <div class="text-center mb-8">
                   <div class="mb-3 flex justify-center">
                        <div class="bg-gray-100 rounded-lg">
                            <img src="{{ asset('img/logo.svg') }}" class="w-60" alt="">
                        </div>
                    </div>
                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 transition-colors duration-300">
                        Buat Akun
                    </h1>
                    <p class="text-gray-600 transition-colors duration-300">
                        Bangun website bisnis Anda hari ini
                    </p>
                </div>

                <!-- Flash Messages (Hidden - will be handled by toast) -->
                @if (session('success'))
                    <div data-flash-success="{{ session('success') }}" class="hidden"></div>
                @endif

                @if (session('error'))
                    <div data-flash-error="{{ session('error') }}" class="hidden"></div>
                @endif

                @if (session('info'))
                    <div data-flash-info="{{ session('info') }}" class="hidden"></div>
                @endif

                @if (session('warning'))
                    <div data-flash-warning="{{ session('warning') }}" class="hidden"></div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div data-validation-error="{{ $error }}" class="hidden"></div>
                    @endforeach
                @endif

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
                    @csrf
                    
                    <!-- Row 1: Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name Field -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                                <i class="fas fa-user mr-2 text-gray-500"></i>
                                Nama Lengkap
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap Anda"
                                autocomplete="name"
                            />
                            @error('name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                                <i class="fas fa-envelope mr-2 text-gray-500"></i>
                                Alamat Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 @error('email') border-red-500 @enderror"
                                placeholder="Masukkan alamat email Anda"
                                autocomplete="email"
                            />
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Phone & Business Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone Field -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                                <i class="fas fa-phone mr-2 text-gray-500"></i>
                                Nomor Telepon
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 @error('phone') border-red-500 @enderror"
                                placeholder="contoh: 08123456789"
                                autocomplete="tel"
                            />
                            @error('phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Business Name Field -->
                        <div class="space-y-2">
                            <label for="business_name" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                                <i class="fas fa-store mr-2 text-gray-500"></i>
                                Nama Bisnis
                            </label>
                            <input
                                id="business_name"
                                name="business_name"
                                type="text"
                                value="{{ old('business_name') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 @error('business_name') border-red-500 @enderror"
                                placeholder="Masukkan nama bisnis Anda"
                                autocomplete="organization"
                            />
                            @error('business_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Business URL Helper Text -->
                    <div class="text-xs text-gray-500 -mt-3 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Nama bisnis Anda akan digunakan untuk membuat URL website
                    </div>

                    <!-- Row 3: Password & Confirm Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                                <i class="fas fa-lock mr-2 text-gray-500"></i>
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan kata sandi Anda"
                                    autocomplete="new-password"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-800 transition-colors duration-200 focus:outline-none"
                                    onclick="togglePasswordVisibility('password')"
                                >
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                                <i class="fas fa-lock mr-2 text-gray-500"></i>
                                Konfirmasi Kata Sandi
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400"
                                    placeholder="Konfirmasi kata sandi Anda"
                                    autocomplete="new-password"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-800 transition-colors duration-200 focus:outline-none"
                                    onclick="togglePasswordVisibility('password_confirmation')"
                                >
                                    <i id="password_confirmation-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="space-y-2">
                        <label class="flex items-start">
                            <input
                                type="checkbox"
                                name="agree_terms"
                                value="1"
                                required
                                class="w-4 h-4 mt-1 text-gray-800 bg-gray-100 border-gray-300 rounded focus:ring-gray-800 focus:ring-2 @error('agree_terms') border-red-500 @enderror"
                            />
                            <span class="ml-2 text-sm text-gray-700">
                                Saya setuju dengan 
                                <a href="#" class="text-gray-800 hover:text-gray-900 hover:underline focus:outline-none focus:ring-2 focus:ring-gray-800 rounded">
                                    Syarat dan Ketentuan
                                </a> 
                                dan 
                                <a href="#" class="text-gray-800 hover:text-gray-900 hover:underline focus:outline-none focus:ring-2 focus:ring-gray-800 rounded">
                                    Kebijakan Privasi
                                </a>
                            </span>
                        </label>
                        @error('agree_terms')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Create Account Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-gray-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        id="register-btn"
                    >
                        <i class="fas fa-user-plus"></i>
                        <span>Buat Akun</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium transition-colors duration-300">
                                Sudah punya akun?
                            </span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm bg-white hover:bg-gray-50 transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2 group">
                            <i class="fas fa-sign-in-alt mr-2 text-gray-500 group-hover:text-gray-800"></i>
                            <span class="text-gray-700 font-medium group-hover:text-gray-900 transition-colors duration-200">
                                Masuk ke akun yang sudah ada
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 transition-colors duration-300">
                        © {{ date('Y') }} Platform Exposia. Memberdayakan bisnis Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-all duration-300">
        <div id="loading-box" class="bg-white rounded-xl p-6 flex items-center space-x-4 shadow-2xl border border-gray-200 transform animate-pulse">
            <div class="loading-spinner text-gray-800"></div>
            <span id="loading-text" class="text-gray-900 font-medium">Membuat akun Anda...</span>
        </div>
    </div>
 <script src="{{ asset('js/toast-notification.js') }}"></script>
    <!-- Toast Notification System -->
    <script>
        window.toast = new ToastNotification();
        const style = document.createElement('style');
        style.textContent = `
            @keyframes progress {
                from { width: 100%; }
                to { width: 0%; }
            }
        `;
        document.head.appendChild(style);
        @if(session('success'))
        window.toast.success('{{ session('success') }}');
        @endif
        @if(session('error'))
        window.toast.error('{{ session('error') }}');
        @endif

    </script>

    <!-- JavaScript -->
    <script>
        // Password visibility toggle
        function togglePasswordVisibility(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');

            if (passwordInput && passwordIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.className = 'fas fa-eye-slash';
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.className = 'fas fa-eye';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const flashSuccess = document.querySelector('[data-flash-success]');
            const flashError = document.querySelector('[data-flash-error]');
            const flashInfo = document.querySelector('[data-flash-info]');
            const flashWarning = document.querySelector('[data-flash-warning]');
            
            const validationErrors = document.querySelectorAll('[data-validation-error]');
            if (flashSuccess) {
                const message = flashSuccess.getAttribute('data-flash-success');
                window.toast.success(message);
            }
            
            if (flashError) {
                const message = flashError.getAttribute('data-flash-error');
                window.toast.error(message);
            }
            
            if (flashInfo) {
                const message = flashInfo.getAttribute('data-flash-info');
                window.toast.info(message);
            }
            
            if (flashWarning) {
                const message = flashWarning.getAttribute('data-flash-warning');
                window.toast.warning(message);
            }

            if (validationErrors.length > 0) {
                validationErrors.forEach((errorElement, index) => {
                    const message = errorElement.getAttribute('data-validation-error');
                    
                    setTimeout(() => {
                        window.toast.error(message);
                    }, index * 500);
                });
            }

            // Enhanced form submission handling
            const form = document.querySelector('form');
            const registerBtn = document.getElementById('register-btn');
            const loadingOverlay = document.getElementById('loading-overlay');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Clear any existing validation errors
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.add('border-gray-300');
                    });
                    
                    // Client-side validation
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const phone = document.getElementById('phone').value;
                    const businessName = document.getElementById('business_name').value;
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;
                    const agreeTerms = document.querySelector('input[name="agree_terms"]').checked;
                    
                    // Validation checks
                    if (!name.trim()) {
                        e.preventDefault();
                        window.toast.error('Silakan masukkan nama lengkap Anda');
                        document.getElementById('name').focus();
                        return;
                    }
                    
                    if (!email.trim()) {
                        e.preventDefault();
                        window.toast.error('Silakan masukkan alamat email Anda');
                        document.getElementById('email').focus();
                        return;
                    }
                    
                    // Email format validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        window.toast.error('Silakan masukkan alamat email yang valid');
                        document.getElementById('email').focus();
                        return;
                    }
                    
                    if (!phone.trim()) {
                        e.preventDefault();
                        window.toast.error('Silakan masukkan nomor telepon Anda');
                        document.getElementById('phone').focus();
                        return;
                    }
                    
                    if (!businessName.trim()) {
                        e.preventDefault();
                        window.toast.error('Silakan masukkan nama bisnis Anda');
                        document.getElementById('business_name').focus();
                        return;
                    }
                    
                    if (!password) {
                        e.preventDefault();
                        window.toast.error('Silakan masukkan kata sandi');
                        document.getElementById('password').focus();
                        return;
                    }
                    
                    if (password.length < 8) {
                        e.preventDefault();
                        window.toast.error('Kata sandi harus minimal 8 karakter');
                        document.getElementById('password').focus();
                        return;
                    }
                    
                    if (password !== passwordConfirmation) {
                        e.preventDefault();
                        window.toast.error('Konfirmasi kata sandi tidak cocok');
                        document.getElementById('password_confirmation').focus();
                        return;
                    }
                    
                    if (!agreeTerms) {
                        e.preventDefault();
                        window.toast.error('Silakan setujui Syarat dan Ketentuan serta Kebijakan Privasi');
                        document.querySelector('input[name="agree_terms"]').focus();
                        return;
                    }
                    
                    // Show loading state
                    registerBtn.disabled = true;
                    registerBtn.innerHTML = '<div class="inline-block w-4 h-4 border-2 border-white border-r-transparent rounded-full animate-spin mr-2"></div>Membuat akun...';
                    loadingOverlay.classList.remove('hidden');
                    
                    // Show processing toast
                    window.toast.info('Membuat akun Anda...', 4000);
                });
            }

            // Real-time business URL validation
            const businessNameInput = document.getElementById('business_name');
            if (businessNameInput) {
                let timeout;
                businessNameInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        if (this.value.length >= 3) {
                            checkBusinessUrl(this.value);
                        }
                    }, 500);
                });
            }
        });

        // Check business URL availability
        function checkBusinessUrl(businessName) {
            if (!businessName) return;
            
            fetch('/check-business-url', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    business_name: businessName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    window.toast.warning(`Nama bisnis "${businessName}" sudah digunakan. URL akan menjadi: ${data.suggested_url}`, 4000);
                } else {
                    window.toast.success(`Bagus! "${data.original_url}" tersedia untuk URL bisnis Anda`, 3000);
                }
            })
            .catch(error => {
            });
        }
    </script>
</body>
</html>