<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'UMKM Builder') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                        'pulse-soft': 'pulseSoft 2s infinite'
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
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <style>
        /* Scrollbar Styles */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            @apply bg-gray-100 dark:bg-gray-800;
        }

        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 dark:bg-gray-600 rounded-full;
        }

        ::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400 dark:bg-gray-500;
        }

        /* Dark Mode */
        .dark {
            color-scheme: dark;
        }

        /* Smooth transitions */
        *,
        *::before,
        *::after {
            transition: background-color 0.3s ease, 
                        border-color 0.3s ease, 
                        color 0.3s ease,
                        transform 0.3s ease,
                        opacity 0.3s ease;
        }

        /* Loading Spinner */
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
            to { transform: rotate(360deg); }
        }

        /* Navigation active state */
        .nav-item.active {
            @apply bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400;
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

        /* Gradient text */
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
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>

    <!-- Page specific styles -->
    @stack('styles')

    <!-- Theme initialization -->
    <script>
        // PENTING: Set theme sebelum page load untuk mencegah flash
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
        <!-- Mobile sidebar overlay -->
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
            <main class="flex-1 p-4 sm:p-6 space-y-6 sm:space-y-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-2"></div>

    <!-- Scripts - Hanya JS, tanpa CSS -->
    @vite(['resources/js/app.js'])
    @stack('scripts')

    <!-- Global JavaScript -->
    <script>
        // Initialize sidebar manager
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
                window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
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
                toast.className = `fixed bottom-4 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-y-full opacity-0 max-w-sm`;
                
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

        // Profile dropdown functions
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

        // Logout function
        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
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
            
            console.log('User dashboard initialized with CDN Tailwind');
        });
    </script>
</body>
</html>