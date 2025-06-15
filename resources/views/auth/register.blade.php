<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - UMKM Platform</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        // Tailwind configuration
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#012c4e',
                            600: '#011f37',
                            700: '#001529'
                        },
                        accent: {
                            400: '#fcab1b',
                            500: '#f59e0b',
                            600: '#d97706'
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

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300 font-sans">
    <div class="min-h-screen flex">
        <!-- Left Side - Image -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80"
                alt="Modern business workspace"
                class="absolute inset-0 w-full h-full object-cover animate-fade-in"
            />
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
                        <h1 class="ml-4 text-2xl font-bold text-shadow">Exposia Platform</h1>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Main Heading -->
                    <h2 class="text-3xl lg:text-4xl font-bold leading-tight text-shadow">
                        Start Your 
                        <span class="text-accent-400 bg-gradient-to-r from-accent-400 to-orange-400 bg-clip-text text-transparent">
                            Digital
                        </span> 
                        Journey
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-lg lg:text-xl opacity-90 leading-relaxed max-w-md">
                        Join thousands of UMKM businesses who have transformed their 
                        presence with our easy-to-use website builder.
                    </p>
                    
                    <!-- Feature highlights -->
                    <div class="space-y-3">
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-accent-400 mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Free website setup in minutes</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-accent-400 mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">Professional templates included</span>
                        </div>
                        <div class="flex items-center group">
                            <i class="fas fa-check-circle text-accent-400 mr-3 transform group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-white/90 group-hover:text-white transition-colors duration-200">AI-powered content assistance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div id="register-container" class="w-full lg:w-1/2 flex flex-col justify-center bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="px-6 sm:px-8 py-12 md:px-20 mx-auto w-full animate-slide-in-right">
                
                <!-- Dark Mode Toggle -->
                <div class="flex justify-end mb-8">
                    <button
                        id="theme-toggle"
                        class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                        title="Toggle theme"
                    >
                        <i id="theme-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                    </button>
                </div>

                <!-- Register Header -->
                <div class="text-center mb-8">
                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-primary-500 to-accent-400 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-200">
                            <i class="fas fa-user-plus text-2xl text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-primary-500 dark:text-white mb-2 transition-colors duration-300">
                        Create Account
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 transition-colors duration-300">
                        Build your business website today
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
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                                <i class="fas fa-user mr-2 text-primary-500 dark:text-accent-400"></i>
                                Full Name
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('name') border-red-500 dark:border-red-400 @enderror"
                                placeholder="Enter your full name"
                                autocomplete="name"
                            />
                            @error('name')
                                <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                                <i class="fas fa-envelope mr-2 text-primary-500 dark:text-accent-400"></i>
                                Email Address
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('email') border-red-500 dark:border-red-400 @enderror"
                                placeholder="Enter your email address"
                                autocomplete="email"
                            />
                            @error('email')
                                <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Phone & Business Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone Field -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                                <i class="fas fa-phone mr-2 text-primary-500 dark:text-accent-400"></i>
                                Phone Number
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('phone') border-red-500 dark:border-red-400 @enderror"
                                placeholder="e.g., 08123456789"
                                autocomplete="tel"
                            />
                            @error('phone')
                                <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Business Name Field -->
                        <div class="space-y-2">
                            <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                                <i class="fas fa-store mr-2 text-primary-500 dark:text-accent-400"></i>
                                Business Name
                            </label>
                            <input
                                id="business_name"
                                name="business_name"
                                type="text"
                                value="{{ old('business_name') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('business_name') border-red-500 dark:border-red-400 @enderror"
                                placeholder="Enter your business name"
                                autocomplete="organization"
                            />
                            @error('business_name')
                                <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Business URL Helper Text -->
                    <div class="text-xs text-gray-500 dark:text-gray-400 -mt-3 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Your business name will be used to create your website URL
                    </div>

                    <!-- Row 3: Password & Confirm Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                                <i class="fas fa-lock mr-2 text-primary-500 dark:text-accent-400"></i>
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    class="w-full px-4 py-3 pr-12 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500 @error('password') border-red-500 dark:border-red-400 @enderror"
                                    placeholder="Enter your password"
                                    autocomplete="new-password"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-accent-400 transition-colors duration-200 focus:outline-none"
                                    onclick="togglePasswordVisibility('password')"
                                >
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300">
                                <i class="fas fa-lock mr-2 text-primary-500 dark:text-accent-400"></i>
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    class="w-full px-4 py-3 pr-12 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-accent-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-accent-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 hover:border-gray-400 dark:hover:border-gray-500"
                                    placeholder="Confirm your password"
                                    autocomplete="new-password"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-accent-400 transition-colors duration-200 focus:outline-none"
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
                                class="w-4 h-4 mt-1 text-primary-500 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-accent-400 dark:ring-offset-gray-900 focus:ring-2 dark:bg-gray-800 dark:border-gray-600 @error('agree_terms') border-red-500 @enderror"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                I agree to the 
                                <a href="#" class="text-primary-500 dark:text-accent-400 hover:underline focus:outline-none focus:ring-2 focus:ring-primary-500 rounded">
                                    Terms of Service
                                </a> 
                                and 
                                <a href="#" class="text-primary-500 dark:text-accent-400 hover:underline focus:outline-none focus:ring-2 focus:ring-primary-500 rounded">
                                    Privacy Policy
                                </a>
                            </span>
                        </label>
                        @error('agree_terms')
                            <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Create Account Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        id="register-btn"
                    >
                        <i class="fas fa-user-plus"></i>
                        <span>Create Account</span>
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
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-accent-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900 group">
                            <i class="fas fa-sign-in-alt mr-2 text-gray-500 dark:text-gray-400 group-hover:text-primary-500 dark:group-hover:text-accent-400"></i>
                            <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-gray-900 dark:group-hover:text-white transition-colors duration-200">
                                Sign in to existing account
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-300">
                        © 2024 Exposia Platform. Empowering Indonesian businesses.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-all duration-300">
        <div id="loading-box" class="bg-white dark:bg-gray-800 rounded-xl p-6 flex items-center space-x-4 shadow-2xl border border-gray-200 dark:border-gray-700 transform animate-pulse">
            <div class="loading-spinner text-primary-500 dark:text-accent-400"></div>
            <span id="loading-text" class="text-gray-900 dark:text-white font-medium">Creating your account...</span>
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

        // Form submission with loading state and validation
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme manager
            new ThemeManager();
            
            // Debug: Check if flash elements exist
            console.log('Checking for flash messages...');
            
            // Handle Laravel flash messages with better detection
            const flashSuccess = document.querySelector('[data-flash-success]');
            const flashError = document.querySelector('[data-flash-error]');
            const flashInfo = document.querySelector('[data-flash-info]');
            const flashWarning = document.querySelector('[data-flash-warning]');
            
            // Handle validation errors (multiple possible)
            const validationErrors = document.querySelectorAll('[data-validation-error]');
            
            console.log('Flash elements found:', {
                success: !!flashSuccess,
                error: !!flashError,
                info: !!flashInfo,
                warning: !!flashWarning,
                validationErrors: validationErrors.length
            });
            
            // Show flash messages
            if (flashSuccess) {
                const message = flashSuccess.getAttribute('data-flash-success');
                console.log('Showing success:', message);
                window.toast.success(message);
            }
            
            if (flashError) {
                const message = flashError.getAttribute('data-flash-error');
                console.log('Showing error:', message);
                window.toast.error(message);
            }
            
            if (flashInfo) {
                const message = flashInfo.getAttribute('data-flash-info');
                console.log('Showing info:', message);
                window.toast.info(message);
            }
            
            if (flashWarning) {
                const message = flashWarning.getAttribute('data-flash-warning');
                console.log('Showing warning:', message);
                window.toast.warning(message);
            }

            // Show validation errors (can be multiple)
            if (validationErrors.length > 0) {
                console.log('Showing validation errors:', validationErrors.length);
                validationErrors.forEach((errorElement, index) => {
                    const message = errorElement.getAttribute('data-validation-error');
                    console.log(`Validation error ${index + 1}:`, message);
                    
                    // Delay multiple errors so they don't overlap
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
                        el.classList.remove('border-red-500', 'dark:border-red-400');
                        el.classList.add('border-gray-300', 'dark:border-gray-600');
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
                        window.toast.error('Please enter your full name');
                        document.getElementById('name').focus();
                        return;
                    }
                    
                    if (!email.trim()) {
                        e.preventDefault();
                        window.toast.error('Please enter your email address');
                        document.getElementById('email').focus();
                        return;
                    }
                    
                    // Email format validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        window.toast.error('Please enter a valid email address');
                        document.getElementById('email').focus();
                        return;
                    }
                    
                    if (!phone.trim()) {
                        e.preventDefault();
                        window.toast.error('Please enter your phone number');
                        document.getElementById('phone').focus();
                        return;
                    }
                    
                    if (!businessName.trim()) {
                        e.preventDefault();
                        window.toast.error('Please enter your business name');
                        document.getElementById('business_name').focus();
                        return;
                    }
                    
                    if (!password) {
                        e.preventDefault();
                        window.toast.error('Please enter a password');
                        document.getElementById('password').focus();
                        return;
                    }
                    
                    if (password.length < 8) {
                        e.preventDefault();
                        window.toast.error('Password must be at least 8 characters long');
                        document.getElementById('password').focus();
                        return;
                    }
                    
                    if (password !== passwordConfirmation) {
                        e.preventDefault();
                        window.toast.error('Password confirmation does not match');
                        document.getElementById('password_confirmation').focus();
                        return;
                    }
                    
                    if (!agreeTerms) {
                        e.preventDefault();
                        window.toast.error('Please agree to the Terms of Service and Privacy Policy');
                        document.querySelector('input[name="agree_terms"]').focus();
                        return;
                    }
                    
                    // Show loading state
                    registerBtn.disabled = true;
                    registerBtn.innerHTML = '<div class="inline-block w-4 h-4 border-2 border-white border-r-transparent rounded-full animate-spin mr-2"></div>Creating account...';
                    loadingOverlay.classList.remove('hidden');
                    
                    // Show processing toast
                    window.toast.info('Creating your account...', 4000);
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
                    window.toast.warning(`Business name "${businessName}" is taken. URL will be: ${data.suggested_url}`, 4000);
                } else {
                    window.toast.success(`Great! "${data.original_url}" is available for your business URL`, 3000);
                }
            })
            .catch(error => {
                console.log('URL check error:', error);
            });
        }
    </script>
</body>
</html>