<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'UMKM Builder') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])

    <!-- Page specific styles -->
    @stack('styles')

    <script>
        // Theme configuration
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
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

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    <!-- Global JavaScript -->
    <script>
        // Initialize theme manager
        class ThemeManager {
            constructor() {
                this.currentTheme = localStorage.getItem('theme') || 'light';
                this.init();
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
                window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
            }

            toggleTheme() {
                const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            }
        }

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
        });
    </script>
</body>
</html>