<!-- User Dashboard Header -->
<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
    <div class="flex items-center justify-between h-16 px-4 md:px-6">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" 
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <!-- Page Title (Desktop Only) -->
        <div class="hidden lg:block">
            <h1 class="text-lg font-semibold text-gray-900">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center space-x-2 sm:space-x-4">
            <!-- Website Quick Link (if published) -->
            @if(auth()->user()->business && auth()->user()->business->publish_status === 'published')
            <a href="{{ auth()->user()->business->public_url }}" 
               target="_blank"
               class="hidden sm:flex items-center px-3 py-2 text-sm font-medium text-gray-900 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200">
                <i class="fas fa-external-link-alt mr-2"></i>
                <span>Lihat Website</span>
            </a>
            @endif

            <!-- User Profile Dropdown -->
            <div class="relative">
                <button id="user-profile-button" 
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        onclick="toggleProfileDropdown()">
                    <!-- User Avatar -->
                    <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    
                    <!-- User Info (Hidden on mobile) -->
                    <div class="hidden sm:block text-left">
                        <span class="text-sm font-medium text-gray-700 block">
                            {{ auth()->user()->name ?? 'User' }}
                        </span>
                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                    
                    <!-- Dropdown Arrow -->
                    <i id="dropdown-arrow" 
                       class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profile-dropdown" 
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50 hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                    
                    <!-- User Info Header -->
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">
                            {{ auth()->user()->name ?? 'User' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                        @if(auth()->user()->business)
                        <p class="text-xs text-gray-600 mt-1">
                            {{ auth()->user()->business->business_name }}
                        </p>
                        @endif
                    </div>

                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="{{ route('user.account.index') }}" 
                           class="dropdown-item flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-user mr-3 text-gray-400"></i>
                            Pengaturan Akun
                        </a>
                        
                        @if(auth()->user()->business)
                        <a href="{{ route('user.business.index') }}" 
                           class="dropdown-item flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-building mr-3 text-gray-400"></i>
                            Pengaturan Bisnis
                        </a>
                        @endif

                        @if(auth()->user()->business && auth()->user()->business->publish_status === 'published')
                        <a href="{{ auth()->user()->business->public_url }}" 
                           target="_blank"
                           class="dropdown-item flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-external-link-alt mr-3 text-gray-400"></i>
                            Lihat Website Saya
                        </a>
                        @endif

                        <a href="{{ route('user.support') }}" 
                           class="dropdown-item flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-life-ring mr-3 text-gray-400"></i>
                            Bantuan & Support
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>

                    <!-- Logout -->
                    <div class="py-1">
                        <button onclick="logout()" 
                                class="dropdown-item w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-3 text-red-500"></i>
                            Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Website Link (if published) -->
    @if(auth()->user()->business && auth()->user()->business->publish_status === 'published')
    <div class="sm:hidden px-4 pb-3">
        <a href="{{ auth()->user()->business->public_url }}" 
           target="_blank"
           class="flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-gray-900 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200">
            <i class="fas fa-external-link-alt mr-2"></i>
            <span>Lihat Website Saya</span>
        </a>
    </div>
    @endif
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