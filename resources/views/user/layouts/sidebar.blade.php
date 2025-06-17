<!-- User Dashboard Sidebar -->
<div id="sidebar" 
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform lg:translate-x-0 -translate-x-full lg:block transition-transform duration-300 ease-in-out flex flex-col border-r border-gray-200 dark:border-gray-700">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                <i class="fas fa-store text-white text-sm"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Exposia</h1>
        </div>
        <button id="close-sidebar" 
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- User Info Section -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'User' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ auth()->user()->business?->business_name ?? 'Belum ada bisnis' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 mt-4 px-4 space-y-1 overflow-y-auto scrollbar-hide">
        <!-- Dashboard / Beranda -->
        <a href="{{ route('user.dashboard') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-home mr-3 {{ request()->routeIs('user.dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Beranda</span>
        </a>

        <!-- Data Usaha -->
        <a href="{{ route('user.business.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.business.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-building mr-3 {{ request()->routeIs('user.business.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Data Usaha</span>
        </a>

        <!-- Cabang -->
        <a href="{{ route('user.branches.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.branches.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-map-marker-alt mr-3 {{ request()->routeIs('user.branches.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Cabang</span>
        </a>

        <!-- Produk -->
        <a href="{{ route('user.products.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.products.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-box mr-3 {{ request()->routeIs('user.products.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Produk</span>
        </a>

        <!-- Galeri -->
        <a href="{{ route('user.gallery') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.gallery') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-images mr-3 {{ request()->routeIs('user.gallery') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Galeri</span>
        </a>

        <!-- Testimoni -->
        <a href="{{ route('user.testimonials') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.testimonials') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-quote-right mr-3 {{ request()->routeIs('user.testimonials') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Testimoni</span>
        </a>

        <!-- Tentang Usaha -->
        <a href="{{ route('user.about') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.about') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-info-circle mr-3 {{ request()->routeIs('user.about') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Tentang Usaha</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                Kustomisasi
            </p>
        </div>

        <!-- Template & Tampilan -->
        <a href="{{ route('user.templates') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.templates') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-palette mr-3 {{ request()->routeIs('user.templates') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Template & Tampilan</span>
        </a>

        <!-- AI Konten Generator -->
        <a href="{{ route('user.ai-content') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.ai-content') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-robot mr-3 {{ request()->routeIs('user.ai-content') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>AI Konten Generator</span>
        </a>

        <!-- Publikasi & Link Website -->
        <a href="{{ route('user.publish') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.publish') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-globe mr-3 {{ request()->routeIs('user.publish') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Publikasi & Link Website</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                Bantuan
            </p>
        </div>

        <!-- Bantuan & Support -->
        <a href="{{ route('user.support') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.support') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-life-ring mr-3 {{ request()->routeIs('user.support') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Bantuan & Support</span>
        </a>

        <!-- Akun Saya -->
        <a href="{{ route('user.account') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('user.account') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-r-2 border-blue-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-user-cog mr-3 {{ request()->routeIs('user.account') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Akun Saya</span>
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
        <!-- Progress Indicator -->
        @if(auth()->user()->business)
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-900 dark:text-white">Kelengkapan Profil</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->business->progress_completion ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ auth()->user()->business->progress_completion ?? 0 }}%"></div>
            </div>
        </div>
        @endif

        <!-- Logout Button -->
        <button onclick="logout()" 
                class="w-full flex items-center px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
            <i class="fas fa-sign-out-alt mr-3"></i>
            <span class="text-sm font-medium">Keluar</span>
        </button>
    </div>
</div>