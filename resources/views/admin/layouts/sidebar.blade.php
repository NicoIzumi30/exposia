<!-- Admin Dashboard Sidebar -->
<div id="sidebar" 
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform lg:translate-x-0 -translate-x-full lg:block transition-transform duration-300 ease-in-out flex flex-col border-r border-gray-200 dark:border-gray-700">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
    <img src="{{ asset('img/logo-full-dark.svg') }}" 
        class="w-40 block dark:hidden" 
        alt="Logo Dark">
    <img src="{{ asset('img/logo-full-light.svg') }}" 
        class="w-40 hidden dark:block" 
        alt="Logo Light">
        <button id="close-sidebar" 
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Admin Info Section -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'Admin' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    Administrator
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 mt-4 px-4 space-y-1 overflow-y-auto scrollbar-hide">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Dashboard</span>
        </a>

        <!-- User Management -->
        <a href="{{ route('admin.users.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-users mr-3 {{ request()->routeIs('admin.users.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Manajemen Pengguna</span>
        </a>

        <!-- Website Management -->
        <a href="{{ route('admin.websites.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.websites.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-globe mr-3 {{ request()->routeIs('admin.websites.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Manajemen Website</span>
        </a>

        <!-- Content Monitoring -->
        <a href="{{ route('admin.content.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.content.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-file-alt mr-3 {{ request()->routeIs('admin.content.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Monitoring Konten</span>
        </a>

        <!-- Reports & Moderation -->
        <a href="{{ route('admin.reports.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-500' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-flag mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Laporan & Moderasi</span>
        </a>

   

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
        <!-- Logout Button -->
        <button onclick="logout()" 
                class="w-full flex items-center px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 rounded-lg transition-all duration-200 transform hover:scale-[1.02]">
            <i class="fas fa-sign-out-alt mr-3"></i>
            <span class="text-sm font-medium">Keluar</span>
        </button>
    </div>
</div>