<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Exposia</title>

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
                        sans: ['Inter', 'sans-serif']
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
                            'from': {
                                transform: 'translateX(100px)',
                                opacity: '0'
                            },
                            'to': {
                                transform: 'translateX(0)',
                                opacity: '1'
                            }
                        },
                        fadeIn: {
                            'from': {
                                opacity: '0'
                            },
                            'to': {
                                opacity: '1'
                            }
                        },
                        pulseSoft: {
                            '0%, 100%': {
                                opacity: '1'
                            },
                            '50%': {
                                opacity: '0.5'
                            }
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
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0f172a 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="h-full bg-gray-50 transition-colors duration-300 font-sans">
    <div class="min-h-screen flex">
        <!-- Left Side - Image -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80" alt="Ruang kerja bisnis modern" class="absolute inset-0 w-full h-full object-cover animate-fade-in" />
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
                        <h1 class="ml-4 text-2xl font-bold text-shadow">Platform UMKM</h1>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Main Heading -->
                    <h2 class="text-3xl lg:text-4xl font-bold leading-tight text-shadow">
                        Bangun Website
                        <span class="text-white bg-gradient-to-r from-white to-gray-200 bg-clip-text">
                            Bisnis
                        </span>
                        Anda
                    </h2>

                    <!-- Description -->
                    <p class="text-lg lg:text-xl opacity-90 leading-relaxed max-w-md">
                        Ciptakan website yang menarik untuk bisnis UMKM Anda.
                        Mudah digunakan, template profesional, dan fitur yang powerful.
                    </p>

                    <!-- Feature highlights -->
                    <div class="space-y-3">
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-white mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Template website profesional</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-white mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Kelola produk & galeri dengan mudah</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-white mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Pembuatan konten bertenaga AI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div id="login-container" class="w-full lg:w-1/2 flex flex-col justify-center bg-white transition-colors duration-300">
            <div class="px-6 sm:px-8 py-12 max-w-md mx-auto w-full animate-slide-in-right">

                <!-- Login Header -->
                <div class="text-center mb-8">
                    <!-- Icon -->
                    <div class="mb-3 flex justify-center">
                        <div class="bg-gray-100 rounded-lg">
                            <img src="{{ asset('img/logo.svg') }}" class="w-60" alt="">
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 transition-colors duration-300">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-gray-600 transition-colors duration-300">
                        Masuk ke akun Anda
                    </p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="#" class="space-y-6" novalidate>
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>
                            Alamat Email
                        </label>
                        <input id="email" name="email" type="email" value="demo@exposia.com" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400" placeholder="Masukkan alamat email Anda" autocomplete="email" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 transition-colors duration-300">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" value="password" required class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400" placeholder="Masukkan kata sandi Anda" autocomplete="current-password" />
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-800 transition-colors duration-200 focus:outline-none" onclick="togglePasswordVisibility()">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-gray-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" id="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Gunakan akun demo untuk melihat semua fitur</p>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium transition-colors duration-300">
                                Atau
                            </span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-gray-800 hover:text-gray-900 hover:underline font-medium focus:outline-none focus:ring-2 focus:ring-gray-800 rounded">
                                Buat akun baru
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 transition-colors duration-300">
                        © {{ date('Y') }} Platform Exposia. Dibuat untuk bisnis Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-all duration-300">
        <div id="loading-box" class="bg-white rounded-xl p-6 flex items-center space-x-4 shadow-2xl border border-gray-200 transform animate-pulse">
            <div class="loading-spinner text-gray-800"></div>
            <span id="loading-text" class="text-gray-900 font-medium">Sedang masuk...</span>
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
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

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

        // Form submission with loading state
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loginBtn = document.getElementById('login-btn');
            const loadingOverlay = document.getElementById('loading-overlay');

            form.addEventListener('submit', function() {
                loginBtn.disabled = true;
                loginBtn.innerHTML = '<div class="loading-spinner mr-2"></div>Signing in...';
                loadingOverlay.classList.remove('hidden');
            });

        });

    </script>
</body>
</html>
