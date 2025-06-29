<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laporkan Website') - {{ config('app.name', 'Exposia') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
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
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'card-entrance': 'cardSlideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'pulse-soft': 'pulseSoft 2s infinite',
                        'float': 'float 6s ease-in-out infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            'from': { opacity: '0' },
                            'to': { opacity: '1' }
                        },
                        slideUp: {
                            'from': { opacity: '0', transform: 'translateY(20px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' }
                        },
                        cardSlideUp: {
                            'from': { transform: 'translateY(30px)', opacity: '0' },
                            'to': { transform: 'translateY(0)', opacity: '1' }
                        },
                        scaleIn: {
                            'from': { transform: 'scale(0.95)', opacity: '0' },
                            'to': { transform: 'scale(1)', opacity: '1' }
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.5' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Theme initialization -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') ||
                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            } else {
                document.documentElement.classList.add('light');
                document.body.classList.add('light');
            }
        })();
    </script>

    <style>
        body {
            background-color: #f9fafb;
        }
        
        .dark body {
            background-color: #111827;
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Toast Notifications */
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }
        
        .toast {
            min-width: 320px;
            max-width: 24rem;
            padding: 1rem;
            border-radius: 1rem;
            margin-bottom: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: toast-slide-in 0.3s ease-out forwards;
            color: white;
            display: flex;
            align-items: flex-start;
            backdrop-filter: blur(10px);
        }
        
        .toast-success { background: linear-gradient(135deg, #10b981, #059669); }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .toast-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .toast-info { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        
        .toast-progress {
            height: 4px;
            margin-top: 0.75rem;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
            overflow: hidden;
        }
        
        .toast-progress-inner {
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 9999px;
            transition-property: width;
            transition-timing-function: linear;
        }
        
        @keyframes toast-slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes toast-slide-out {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Floating shapes */
        .floating-shapes {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(252, 171, 27, 0.1), rgba(245, 158, 11, 0.05));
            animation: float 6s ease-in-out infinite;
        }
    </style>
    
    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="floating-shape w-20 h-20 top-[10%] left-[5%]" style="animation-delay: 0s;"></div>
        <div class="floating-shape w-32 h-32 top-[50%] right-[5%]" style="animation-delay: 2s;"></div>
        <div class="floating-shape w-16 h-16 bottom-[20%] left-[15%]" style="animation-delay: 4s;"></div>
        <div class="floating-shape w-24 h-24 top-[30%] right-[20%]" style="animation-delay: 1s;"></div>
    </div>

    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center group">
                <img src="{{ asset('img/logo-full-dark.svg') }}" alt="" class="h-10 block dark:hidden">
                <img src="{{ asset('img/logo-full-light.svg') }}" alt="" class="h-10 hidden dark:block">
                </a>
                
                <div class="flex items-center space-x-2">
                <button id="theme-toggle"
                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <i id="theme-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                </button>
                    <a href="{{ route('report.create') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-accent-400 dark:hover:text-accent-400 text-sm font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                        <span class="material-icons-outlined text-sm mr-2">report</span>
                        Laporkan Website
                    </a>
                    <a href="{{ route('report.check') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-accent-400 dark:hover:text-accent-400 text-sm font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                        <span class="material-icons-outlined text-sm mr-2">search</span>
                        Cek Status
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-accent-400 hover:bg-accent-600 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                        <span class="material-icons-outlined text-sm mr-2">login</span>
                        Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 shadow-sm border-t border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Exposia') }}. Semua hak dilindungi.
                    </p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-accent-400 transition-colors duration-200 transform hover:scale-110">
                        <i class="fab fa-facebook text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-accent-400 transition-colors duration-200 transform hover:scale-110">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-accent-400 transition-colors duration-200 transform hover:scale-110">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <div id="toast-container"></div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    
    <script>
        // Toast Notification System with enhanced styling
        window.showToast = function(message, type = 'info', duration = 5000, options = {}) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            toast.id = toastId;
            
            toast.className = 'toast toast-' + type;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            const titles = {
                success: 'Berhasil',
                error: 'Gagal',
                warning: 'Peringatan',
                info: 'Informasi'
            };
            
            const title = options.title || titles[type] || titles.info;
            const showProgress = duration > 0 && (options.showProgress !== false);
            
            toast.innerHTML = `
                <div class="flex-shrink-0">
                    <i class="fas ${icons[type] || icons.info} text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    ${title ? `<p class="text-sm font-semibold">${title}</p>` : ''}
                    <p class="text-sm ${title ? 'mt-1' : ''}">${message}</p>
                </div>
                <button onclick="window.dismissToast('${toastId}')" class="ml-4 flex-shrink-0 text-white hover:text-gray-200 focus:outline-none transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
                ${showProgress ? `<div class="toast-progress"><div class="toast-progress-inner" style="width: 100%;"></div></div>` : ''}
            `;
            
            container.appendChild(toast);
            
            if (showProgress) {
                const progressBar = toast.querySelector('.toast-progress-inner');
                if (progressBar) {
                    progressBar.style.transitionDuration = duration + 'ms';
                    setTimeout(() => {
                        progressBar.style.width = '0%';
                    }, 10);
                }
            }
            
            if (duration > 0) {
                setTimeout(() => {
                    window.dismissToast(toastId);
                }, duration);
            }
            
            return toastId;
        };
        
        window.dismissToast = function(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toast-slide-out 0.3s ease-out forwards';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        };
        
        // Show Laravel Flash Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                window.showToast("{{ session('success') }}", 'success');
            @endif
            
            @if(session('error'))
                window.showToast("{{ session('error') }}", 'error');
            @endif
            
            @if(session('warning'))
                window.showToast("{{ session('warning') }}", 'warning');
            @endif
            
            @if(session('info'))
                window.showToast("{{ session('info') }}", 'info');
            @endif
        });
    </script>
        <script>
        (function() {
            const theme = localStorage.getItem('theme') ||
                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            } else {
                document.documentElement.classList.add('light');
                document.body.classList.add('light');
            }

            console.log('Initial theme set:', theme);
        })();
    </script>

    <script>
        // Theme Manager
        class ThemeManager {
            constructor() {
                this.currentTheme = this.getStoredTheme();
                this.init();
            }

            getStoredTheme() {
                const stored = localStorage.getItem('theme');
                if (stored) return stored;

                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }

                return 'light';
            }

            init() {
                this.setTheme(this.currentTheme);

                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.addEventListener('click', () => this.toggleTheme());
                }
            }

            setTheme(theme) {
                this.currentTheme = theme;
                const html = document.documentElement;
                const body = document.body;
                const themeIcon = document.getElementById('theme-icon');

                // Remove existing theme classes
                html.classList.remove('dark', 'light');
                body.classList.remove('dark', 'light');

                // Add new theme class
                if (theme === 'dark') {
                    html.classList.add('dark');
                    body.classList.add('dark');

                    if (themeIcon) {
                        themeIcon.className = 'fas fa-sun text-yellow-400';
                    }
                } else {
                    html.classList.add('light');
                    body.classList.add('light');

                    if (themeIcon) {
                        themeIcon.className = 'fas fa-moon text-gray-600 dark:text-gray-300';
                    }
                }

                localStorage.setItem('theme', theme);
                window.dispatchEvent(new CustomEvent('themeChanged', {
                    detail: {
                        theme
                    }
                }));
            }

            toggleTheme() {
                const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
                this.showThemeChangeToast(newTheme);
            }

            showThemeChangeToast(theme) {
                const message = theme === 'dark' ? 'Mode gelap diaktifkan' : 'Mode terang diaktifkan';
                this.showToast(message, 'info', 2000);
            }

            showToast(message, type = 'info', duration = 3000) {
                const toast = document.createElement('div');
                toast.className =
                    `fixed bottom-4 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-y-full opacity-0 max-w-sm`;

                const bgColors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500',
                    info: 'bg-blue-500'
                };

                toast.classList.add(bgColors[type] || bgColors.info);

                toast.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-y-full', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 100);

                if (duration > 0) {
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.classList.add('translate-y-full', 'opacity-0');
                            setTimeout(() => {
                                if (toast.parentElement) {
                                    toast.remove();
                                }
                            }, 300);
                        }
                    }, duration);
                }
            }
        }
    </script>
</body>
</html>