<!-- Admin Dashboard Header -->
<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
    <div class="flex items-center justify-between h-16 px-4 md:px-6">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" 
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <!-- Page Title (Desktop Only) -->
        <div class="hidden lg:block">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                @yield('page-title', 'Admin Dashboard')
            </h1>
        </div>

        <!-- Spacer for empty middle section -->
        <div class="flex-1 lg:flex-none"></div>

        <!-- Header Actions -->
        <div class="flex items-center space-x-2 sm:space-x-4">
            <!-- Theme Toggle -->
            <button id="theme-toggle" 
                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                <i id="theme-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
            </button>

            <!-- Admin Profile Dropdown -->
            <div class="relative">
                <button id="user-profile-button" 
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                        onclick="toggleProfileDropdown()">
                    <!-- Admin Avatar -->
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    
                    <!-- Admin Info (Hidden on mobile) -->
                    <div class="hidden sm:block text-left">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Administrator
                        </p>
                    </div>
                    
                    <!-- Dropdown Arrow -->
                    <i id="dropdown-arrow" 
                       class="fas fa-chevron-down text-xs text-gray-400 dark:text-gray-500 transition-transform duration-200"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profile-dropdown" 
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50 hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                    
                    <!-- Admin Info Header -->
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->email }}
                        </p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            Administrator
                        </p>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="{{ route('admin.settings.account') }}" 
                           class="dropdown-item flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                            <i class="fas fa-user-cog mr-3 text-gray-400 dark:text-gray-500"></i>
                            Pengaturan Akun
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100 dark:border-gray-700"></div>

                    <!-- Logout -->
                    <div class="py-1">
                        <button onclick="logout()" 
                                class="dropdown-item w-full text-left flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-3 text-red-500 dark:text-red-400"></i>
                            Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* Dropdown animations */
#profile-dropdown.show {
    opacity: 1;
    transform: scale(1);
}

/* Mobile responsive dropdown */
@media (max-width: 768px) {
    #profile-dropdown {
        position: fixed !important;
        right: 20px !important;
        width: auto !important;
        min-width: 200px !important;
    }
}
</style>