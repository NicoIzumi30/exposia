@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Welcome Section -->
<div class="animate-fade-in">
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 text-white mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    Selamat datang di Admin Panel
                </h1>
                <p class="text-gray-200 text-lg">
                    Kelola pengguna, website, dan konten platform Exposia dengan mudah
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gray-800 rounded-xl">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900">
                {{ number_format($stats['total_users'] ?? 1250) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 mb-1">
            Total Pengguna
        </h3>
        <div class="flex items-center text-xs text-green-600">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>+12% dari bulan lalu</span>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-800 hover:underline block mt-2">
            Lihat semua pengguna →
        </a>
    </div>

    <!-- Active Websites -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gray-700 rounded-xl">
                <i class="fas fa-globe text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900">
                {{ number_format($stats['total_active_sites'] ?? 890) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 mb-1">
            Website Aktif
        </h3>
        <p class="text-xs text-gray-500 mb-2">
            Dari {{ number_format($stats['total_sites'] ?? 1100) }} total website
        </p>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gray-800 h-2 rounded-full transition-all duration-300" style="width: {{ ($stats['total_active_sites'] ?? 890) / ($stats['total_sites'] ?? 1100) * 100 }}%"></div>
        </div>
    </div>

    <!-- Total Visitors -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gray-600 rounded-xl">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900">
                {{ number_format($stats['total_visitors'] ?? 45780) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 mb-1">
            Total Pengunjung
        </h3>
        <p class="text-xs {{ ($stats['growth_percentage'] ?? 8.5) >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
            <i class="fas {{ ($stats['growth_percentage'] ?? 8.5) >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
            {{ abs($stats['growth_percentage'] ?? 8.5) }}% dari kemarin
        </p>
    </div>

    <!-- Avg Completion Rate -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gray-500 rounded-xl">
                <i class="fas fa-tasks text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900">
                {{ $stats['avg_completion_rate'] ?? 73 }}%
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 mb-1">
            Rata-rata Kelengkapan
        </h3>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gray-600 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['avg_completion_rate'] ?? 73 }}%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">
            Profile completion rate
        </p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activities -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Aktivitas Sistem Terkini
                </h2>
                <button class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                    <i class="fas fa-sync-alt mr-1"></i>
                    Refresh
                </button>
            </div>

            @if(isset($recentActivities) && $recentActivities->count() > 0)
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center">
                            <i class="fas {{ $activity['icon'] ?? 'fa-info' }} text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $activity['title'] ?? 'User baru mendaftar' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $activity['time']->diffForHumans() ?? '2 menit yang lalu' }}
                        </p>
                        @if(isset($activity['user']))
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $activity['user'] }}
                        </p>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activity['status_class'] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $activity['status'] ?? 'Info' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="space-y-4">
                <!-- Sample activities if no data -->
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            User baru mendaftar
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            5 menit yang lalu
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            john.doe@example.com
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Berhasil
                        </span>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-globe text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            Website baru dipublikasikan
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            12 menit yang lalu
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Toko Elektronik ABC
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Publish
                        </span>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-flag text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            Laporan konten diterima
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            1 jam yang lalu
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Konten tidak pantas dilaporkan
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Perlu Review
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions & System Info -->
    <div class="space-y-6">

        <!-- New Users -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Pengguna Baru
                </h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if(isset($newUsers) && $newUsers->count() > 0)
            <div class="space-y-3">
                @foreach($newUsers as $user)
                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $user->email }}
                        </p>
                    </div>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="p-1 text-gray-400 hover:text-gray-700">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="space-y-3">
                <!-- Sample new users -->
                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white font-semibold">
                            J
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            John Doe
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            john.doe@example.com
                        </p>
                    </div>
                    <button class="p-1 text-gray-400 hover:text-gray-700">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-white font-semibold">
                            M
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            Maria Smith
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            maria.smith@example.com
                        </p>
                    </div>
                    <button class="p-1 text-gray-400 hover:text-gray-700">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-white font-semibold">
                            A
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            Ahmad Rahman
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            ahmad.rahman@example.com
                        </p>
                    </div>
                    <button class="p-1 text-gray-400 hover:text-gray-700">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            @endif
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
        const cards = document.querySelectorAll('.bg-white, .bg-gradient-to-r');
        cards.forEach(card => observer.observe(card));
        
        // Auto-refresh activities every 30 seconds
        setInterval(() => {
            console.log('Auto-refreshing activities...');
            // Add AJAX call here to refresh activities
        }, 30000);
        
    });

    // Function to refresh activities
    function refreshActivities() {
        // Add AJAX implementation here
        showToast('Aktivitas diperbarui', 'success', 2000);
    }
</script>
@endpush