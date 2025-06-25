<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Exposia') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont',
                            'sans-serif'
                        ],
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
                        'pulse-soft': 'pulseSoft 2s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            'from': {
                                opacity: '0'
                            },
                            'to': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            'from': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            'to': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        cardSlideUp: {
                            'from': {
                                transform: 'translateY(30px)',
                                opacity: '0'
                            },
                            'to': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        scaleIn: {
                            'from': {
                                transform: 'scale(0.95)',
                                opacity: '0'
                            },
                            'to': {
                                transform: 'scale(1)',
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
        .ck-editor__editable {
            color: #000000 !important;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            @apply bg-gray-100 dark: bg-gray-800;
        }

        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 dark: bg-gray-600 rounded-full;
        }

        ::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400 dark: bg-gray-500;
        }

        /* Dark Mode */
        .dark {
            color-scheme: dark;
        }

        *,
        *::before,
        *::after {
            transition: background-color 0.3s ease,
                border-color 0.3s ease,
                color 0.3s ease,
                transform 0.3s ease,
                opacity 0.3s ease;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
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

        /* Navigation active state */
        .nav-item.active {
            @apply bg-blue-50 dark: bg-blue-900/20 text-blue-600 dark:text-blue-400;
            border-right: 2px solid theme('colors.blue.500');
        }

        /* Card hover effects */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .dark .card-hover:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        /* Toast Notifications */
        .toast {
            transition: all 0.3s ease;
        }

        .toast-enter {
            animation: toastSlideIn 0.3s ease-out forwards;
        }

        .toast-leave {
            animation: toastSlideOut 0.3s ease-out forwards;
        }

        @keyframes toastSlideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toastSlideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }

        .animate-bounce-in {
            animation: bounceIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    @stack('styles')

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
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300 font-sans">
    <div class="min-h-screen flex">
        <div id="sidebar-overlay"
            class="fixed inset-0 bg-black/50 dark:bg-black/70 z-40 lg:hidden hidden backdrop-blur-sm transition-all duration-300">
        </div>

        <!-- Sidebar -->
        @include('user.layouts.sidebar')

        <!-- Main Content -->
        <div class="lg:ml-64 flex-1 flex flex-col min-h-screen">
            <!-- Top Header -->
            @include('user.layouts.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-2"></div>

    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    <script>
        window.showConfirmation = function(options, onConfirm) {
            const defaultOptions = {
                title: 'Apakah Anda yakin?',
                text: "Aksi ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            };

            const finalOptions = {
                ...defaultOptions,
                ...options
            };

            Swal.fire(finalOptions).then((result) => {
                if (result.isConfirmed) {
                    if (typeof onConfirm === 'function') {
                        onConfirm();
                    }
                }
            });
        };
        window.showToast = function(message, type = 'info', duration = 5000, options = {}) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            // Generate unique ID
            const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            toast.id = toastId;

            // Toast configuration
            const bgColors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

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

            toast.className =
                `toast min-w-[320px] max-w-md p-4 rounded-lg shadow-xl ${bgColors[type] || bgColors.info} text-white transform translate-x-full transition-all duration-300`;

            const title = options.title || titles[type] || titles.info;
            const showProgress = duration > 0 && (options.showProgress !== false);

            toast.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${icons[type] || icons.info} text-lg"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        ${title ? `<p class="text-sm font-semibold">${title}</p>` : ''}
                        <p class="text-sm ${title ? 'mt-1' : ''}">${message}</p>
                    </div>
                    <button onclick="window.dismissToast('${toastId}')" class="ml-4 flex-shrink-0 text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                ${showProgress ? `<div class="toast-progress mt-3 h-1 bg-white/30 rounded-full overflow-hidden"><div class="h-full bg-white/70 rounded-full transition-all duration-linear" style="width: 100%; transition-duration: ${duration}ms;"></div></div>` : ''}
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');

                // Start progress bar animation if enabled
                if (showProgress) {
                    const progressBar = toast.querySelector('.toast-progress > div');
                    if (progressBar) {
                        setTimeout(() => {
                            progressBar.style.width = '0%';
                        }, 100);
                    }
                }
            }, 10);

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
                toast.classList.add('translate-x-full');
                toast.classList.remove('translate-x-0');

                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }
        };

        window.clearAllToasts = function() {
            const container = document.getElementById('toast-container');
            const toasts = container.querySelectorAll('.toast');

            toasts.forEach(toast => {
                toast.classList.add('translate-x-full');
                toast.classList.remove('translate-x-0');
            });

            setTimeout(() => {
                container.innerHTML = '';
            }, 300);
        };
        window.showServerMessages = function() {
            // Check for Laravel flash messages
            @if (session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif

            @if (session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif

            @if (session('warning'))
                showToast("{{ session('warning') }}", 'warning');
            @endif

            @if (session('info'))
                showToast("{{ session('info') }}", 'info');
            @endif

            // Check for validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}", 'error', 7000);
                @endforeach
            @endif
        };

        class SidebarManager {
            constructor() {
                this.sidebarOpen = false;
                this.init();
            }

            init() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const closeSidebar = document.getElementById('close-sidebar');
                const sidebarOverlay = document.getElementById('sidebar-overlay');

                if (mobileMenuButton) {
                    mobileMenuButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.openSidebar();
                    });
                }

                if (closeSidebar) {
                    closeSidebar.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.closeSidebar();
                    });
                }

                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', () => {
                        this.closeSidebar();
                    });
                }

                // Handle window resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        this.closeSidebar();
                    }
                });

                // Handle escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.sidebarOpen) {
                        this.closeSidebar();
                    }
                });
            }

            openSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');

                if (sidebar && overlay) {
                    this.sidebarOpen = true;
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            closeSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');

                if (sidebar && overlay) {
                    this.sidebarOpen = false;
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        }

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
                showToast(newTheme === 'dark' ? 'Mode gelap diaktifkan' : 'Mode terang diaktifkan', 'info', 2000);
            }
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const arrow = document.getElementById('dropdown-arrow');

            if (dropdown) {
                if (dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('hidden');
                    setTimeout(() => {
                        dropdown.classList.remove('opacity-0', 'scale-95');
                        dropdown.classList.add('opacity-100', 'scale-100');
                    }, 10);

                    if (arrow) {
                        arrow.style.transform = 'rotate(180deg)';
                    }
                } else {
                    closeProfileDropdown();
                }
            }
        }

        function closeProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const arrow = document.getElementById('dropdown-arrow');

            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('opacity-0', 'scale-95');
                dropdown.classList.remove('opacity-100', 'scale-100');

                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);

                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('logout') }}';
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Copy to clipboard with toast notification
        window.copyToClipboard = function(text, message = 'Teks berhasil disalin!') {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showToast(message, 'success', 3000);
                }, function(err) {
                    console.error('Could not copy text: ', err);
                    // Fallback method
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    textArea.style.top = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showToast(message, 'success', 3000);
                    } catch (err) {
                        showToast('Gagal menyalin teks', 'error');
                    }
                    textArea.remove();
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast(message, 'success', 3000);
                } catch (err) {
                    showToast('Gagal menyalin teks', 'error');
                }
                textArea.remove();
            }
        };


        document.addEventListener('DOMContentLoaded', () => {
            // Initialize managers
            window.themeManager = new ThemeManager();
            window.sidebarManager = new SidebarManager();

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                const profileDropdown = document.getElementById('profile-dropdown');
                const profileButton = document.getElementById('user-profile-button');

                if (profileDropdown && profileButton) {
                    if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                        closeProfileDropdown();
                    }
                }
            });

            // Show server messages
            showServerMessages();

        });
    </script>
</body>

</html>
