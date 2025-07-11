<!-- Admin Dashboard Sidebar -->
<div id="sidebar" 
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform lg:translate-x-0 -translate-x-full lg:block transition-transform duration-300 ease-in-out flex flex-col border-r border-gray-200">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
    <img src="{{ asset('img/logo-full-dark.svg') }}" 
        class="w-40" 
        alt="Logo">
        <button id="close-sidebar" 
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Admin Info Section -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white font-semibold">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ auth()->user()->name ?? 'Admin' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    Administrator
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 mt-4 px-4 space-y-1 overflow-y-auto scrollbar-hide">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900 border-r-2 border-gray-800' : 'text-gray-700 hover:text-gray-900' }}">
            <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Dashboard</span>
        </a>

        <!-- User Management -->
        <a href="{{ route('admin.users.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900 border-r-2 border-gray-800' : 'text-gray-700 hover:text-gray-900' }}">
            <i class="fas fa-users mr-3 {{ request()->routeIs('admin.users.*') ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Manajemen Pengguna</span>
        </a>

        <!-- Website Management -->
        <a href="{{ route('admin.websites.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 {{ request()->routeIs('admin.websites.*') ? 'bg-gray-100 text-gray-900 border-r-2 border-gray-800' : 'text-gray-700 hover:text-gray-900' }}">
            <i class="fas fa-globe mr-3 {{ request()->routeIs('admin.websites.*') ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Manajemen Website</span>
        </a>

        <!-- Content Monitoring -->
        <a href="{{ route('admin.content.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 {{ request()->routeIs('admin.content.*') ? 'bg-gray-100 text-gray-900 border-r-2 border-gray-800' : 'text-gray-700 hover:text-gray-900' }}">
            <i class="fas fa-file-alt mr-3 {{ request()->routeIs('admin.content.*') ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Monitoring Konten</span>
        </a>

        <!-- Reports & Moderation -->
        <a href="{{ route('admin.reports.index') }}" 
           class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-100 text-gray-900 border-r-2 border-gray-800' : 'text-gray-700 hover:text-gray-900' }}">
            <i class="fas fa-flag mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
            <span>Laporan & Moderasi</span>
        </a>
</div>