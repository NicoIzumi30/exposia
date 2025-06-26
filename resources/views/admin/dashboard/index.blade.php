@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="animate-fade-in">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    Selamat datang di Admin Panel
                </h1>
                <p class="text-indigo-100 text-lg">
                    Kelola pengguna, website, dan konten platform Exposia
                </p>
            </div>
            <div class="text-center">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-flag mr-2"></i>
                    Lihat Laporan Baru
                    <span class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">5</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_users']) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Total Pengguna
        </h3>
        <a href="{{ route('admin.users.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            Lihat semua pengguna →
        </a>
    </div>

    <!-- Active Websites -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                <i class="fas fa-globe text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_active_sites']) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Website Aktif
        </h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Dari {{ number_format($stats['total_sites']) }} total website
        </p>
    </div>

    <!-- Total Visitors -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_visitors']) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Total Pengunjung
        </h3>
        <p class="text-xs {{ $stats['growth_percentage'] >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
            <i class="fas {{ $stats['growth_percentage'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
            {{ abs($stats['growth_percentage']) }}% dari kemarin
        </p>
    </div>

    <!-- Avg Completion Rate -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl">
                <i class="fas fa-tasks text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $stats['avg_completion_rate'] }}%
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Rata-rata Kelengkapan
        </h3>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['avg_completion_rate'] }}%"></div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activities -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Aktivitas Terkini
                </h2>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if($recentActivities->count() > 0)
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas {{ $activity['icon'] }} text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $activity['title'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $activity['time']->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-history text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions & Latest Users -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Aksi Cepat
            </h3>

            <div class="space-y-3">
                <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors duration-200">
                    <i class="fas fa-user-plus mr-3"></i>
                    <span class="font-medium">Tambah Admin Baru</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200">
                    <i class="fas fa-flag mr-3"></i>
                    <span class="font-medium">Moderasi Laporan</span>
                    <span class="ml-auto inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">5</span>
                </a>

                <a href="{{ route('admin.statistics.index') }}" class="flex items-center p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200">
                    <i class="fas fa-chart-pie mr-3"></i>
                    <span class="font-medium">Lihat Statistik</span>
                </a>

                <a href="{{ route('admin.settings.platform') }}" class="flex items-center p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200">
                    <i class="fas fa-cog mr-3"></i>
                    <span class="font-medium">Pengaturan Platform</span>
                </a>
            </div>
        </div>

        <!-- New Users -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Pengguna Baru
                </h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if($newUsers->count() > 0)
            <div class="space-y-3">
                @foreach($newUsers as $user)
                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ $user->email }}
                        </p>
                    </div>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-gray-500 dark:text-gray-400">Belum ada pengguna baru</p>
            </div>
            @endif
        </div>

        <!-- System Status -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl p-6 border border-indigo-200 dark:border-indigo-800">
            <div class="flex items-center mb-3">
                <i class="fas fa-server text-indigo-500 text-lg mr-2"></i>
                <h3 class="text-lg font-semibold text-indigo-800 dark:text-indigo-200">
                    Status Sistem
                </h3>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700 dark:text-indigo-300">Server</span>
                    <span class="inline-flex items-center text-xs font-medium text-green-800 dark:text-green-400">
                        <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-1"></span>
                        Aktif
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700 dark:text-indigo-300">Database</span>
                    <span class="inline-flex items-center text-xs font-medium text-green-800 dark:text-green-400">
                        <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-1"></span>
                        Optimal
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700 dark:text-indigo-300">Penggunaan CPU</span>
                    <span class="text-xs font-medium text-indigo-800 dark:text-indigo-300">23%</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700 dark:text-indigo-300">Penggunaan RAM</span>
                    <span class="text-xs font-medium text-indigo-800 dark:text-indigo-300">42%</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-indigo-700 dark:text-indigo-300">Disk Storage</span>
                    <span class="text-xs font-medium text-indigo-800 dark:text-indigo-300">1.3 TB / 2 TB</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = '0s';
                entry.target.classList.add('animate-slide-up');
            }
        });
    }, observerOptions);
    
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.bg-white, .bg-gradient-to-br');
        cards.forEach(card => observer.observe(card));
        
        // Show welcome message
        showToast('Selamat datang di Admin Dashboard!', 'info', 5000, {
            title: 'Admin Panel'
        });
    });
</script>
@endpush