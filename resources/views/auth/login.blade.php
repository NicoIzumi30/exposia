<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - UMKM Platform</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // Tailwind configuration
        tailwind.config = {
            darkMode: 'class'
            , theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    , }
                    , colors: {
                        primary: {
                            50: '#f0f9ff'
                            , 500: '#012c4e'
                            , 600: '#011f37'
                            , 700: '#001529'
                        }
                        , accent: {
                            400: '#fcab1b'
                            , 500: '#f59e0b'
                            , 600: '#d97706'
                        }
                    }
                    , animation: {
                        'slide-in-right': 'slideInRight 0.6s ease-out'
                        , 'fade-in': 'fadeIn 1s ease-out'
                        , 'spin-slow': 'spin 1s linear infinite'
                        , 'pulse-soft': 'pulseSoft 2s infinite'
                    }
                    , keyframes: {
                        slideInRight: {
                            'from': {
                                transform: 'translateX(100px)'
                                , opacity: '0'
                            }
                            , 'to': {
                                transform: 'translateX(0)'
                                , opacity: '1'
                            }
                        }
                        , fadeIn: {
                            'from': {
                                opacity: '0'
                            }
                            , 'to': {
                                opacity: '1'
                            }
                        }
                        , pulseSoft: {
                            '0%, 100%': {
                                opacity: '1'
                            }
                            , '50%': {
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

    </style>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300 font-sans">
    <div class="min-h-screen flex">
        <!-- Left Side - Image -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80" alt="Modern business workspace" class="absolute inset-0 w-full h-full object-cover animate-fade-in" />
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/80 via-primary-600/70 to-accent-400/60"></div>

            <!-- Left side content -->
            <div class="relative z-10 flex flex-col justify-end p-8 lg:p-12 text-white">
                <div class="mb-8">
                    <!-- Logo -->
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center glass-morphism border border-white/20">
                            <i class="fas fa-store text-2xl text-white"></i>
                        </div>
                        <h1 class="ml-4 text-2xl font-bold text-shadow">UMKM Platform</h1>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Main Heading -->
                    <h2 class="text-3xl lg:text-4xl font-bold leading-tight text-shadow">
                        Build Your
                        <span class="text-accent-400 bg-gradient-to-r from-accent-400 to-orange-400 bg-clip-text text-transparent">
                            Business
                        </span>
                        Website
                    </h2>

                    <!-- Description -->
                    <p class="text-lg lg:text-xl opacity-90 leading-relaxed max-w-md">
                        Create beautiful websites for your UMKM business.
                        Easy to use, professional templates, and powerful features.
                    </p>

                    <!-- Feature highlights -->
                    <div class="space-y-3">
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-accent-400 mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Professional website templates</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-accent-400 mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Easy product & gallery management</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-accent-400 mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">AI-powered content generation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div id="login-container" class="w-full lg:w-1/2 flex flex-col justify-center bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="px-6 sm:px-8 py-12 max-w-md mx-auto w-full animate-slide-in-right">

                <!-- Dark Mode Toggle -->
                <div class="flex justify-end mb-8">
                    <button id="theme-toggle" class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900" title="Toggle theme">
                        <i id="theme-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                    </button>
                </div>

                <!-- Login Header -->
                <div class="text-center mb-8">
                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-primary-500 to-accent-400 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-200">
                            <i class="fas fa-user-shield text-2xl text-white"></i>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-primary-500 dark:text-white mb-2 transition-colors duration-300">
                        Welcome Back
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 transition-colors duration-300">
                        Sign in to your account
                    </p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                            <i class="fas fa-envelope mr-2 text-primary-500 dark:text-accent-400"></i>
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('email') border-red-500 dark:border-red-400 @enderror" placeholder="Enter your email address" autocomplete="email" />
                        @error('email')
                        <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                            <i class="fas fa-lock mr-2 text-primary-500 dark:text-accent-400"></i>
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required class="w-full px-4 py-3 pr-12 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('password') border-red-500 dark:border-red-400 @enderror" placeholder="Enter your password" autocomplete="current-password" />
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-accent-400 transition-colors duration-200 focus:outline-none" onclick="togglePasswordVisibility()">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                        <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary-500 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-accent-400 dark:ring-offset-gray-900 focus:ring-2 dark:bg-gray-800 dark:border-gray-600" />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Remember me</span>
                        </label>

                        <a href="#" class="text-sm text-primary-500 dark:text-accent-400 hover:underline focus:outline-none focus:ring-2 focus:ring-primary-500 rounded">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" id="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400 font-medium transition-colors duration-300">
                                Or
                            </span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-primary-500 dark:text-accent-400 hover:underline font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 rounded">
                                Create an account
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-300">
                        © 2024 Exposia Platform. Built for Indonesian businesses.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-all duration-300">
        <div id="loading-box" class="bg-white dark:bg-gray-800 rounded-xl p-6 flex items-center space-x-4 shadow-2xl border border-gray-200 dark:border-gray-700 transform animate-pulse">
            <div class="loading-spinner text-primary-500 dark:text-accent-400"></div>
            <span id="loading-text" class="text-gray-900 dark:text-white font-medium">Signing you in...</span>
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
        window.toast.success('{{ session('
            success ') }}');
        @endif
        @if(session('error'))
        window.toast.error('{{ session('
            error ') }}');
        @endif

    </script>

    <!-- JavaScript -->
    <script>
        // Theme Management
        class ThemeManager {
            constructor() {
                this.currentTheme = 'light';
                this.init();
            }

            init() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                this.setTheme(savedTheme);

                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.addEventListener('click', () => this.toggleTheme());
                }
            }

            setTheme(theme) {
                this.currentTheme = theme;
                const html = document.documentElement;
                const themeIcon = document.getElementById('theme-icon');

                if (theme === 'dark') {
                    html.classList.add('dark');
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-sun text-yellow-400';
                    }
                } else {
                    html.classList.remove('dark');
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-moon text-gray-600';
                    }
                }

                localStorage.setItem('theme', theme);
            }

            toggleTheme() {
                const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            }
        }

        // Password visibility toggle
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

            // Initialize theme manager
            new ThemeManager();
        });

    </script>
</body>
</html>
