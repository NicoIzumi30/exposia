@extends('user.layouts.app')

@section('title', 'Dashboard Beranda')

@section('page-title', 'Beranda')

@section('content')
<!-- Welcome Section -->
<div class="animate-fade-in">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    Selamat datang, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-blue-100 text-lg">
                    @if($business)
                    Kelola bisnis "{{ $business->business_name }}" dengan mudah dari sini
                    @else
                    Mari mulai dengan melengkapi data bisnis Anda
                    @endif
                </p>
            </div>
            <div class="text-center">
                @if($business && $websiteStatus['is_published'])
                <a href="{{ $websiteStatus['public_url'] }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Lihat Website Saya
                </a>
                @else
                <a href="{{ route('user.business.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Lengkapi Data Bisnis
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Progress Completion -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                <i class="fas fa-chart-pie text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $stats['progress_completion'] }}%
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Kelengkapan Profil
        </h3>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['progress_completion'] }}%"></div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            @if($stats['progress_completion'] < 50) Masih perlu dilengkapi @elseif($stats['progress_completion'] < 80) Hampir selesai @else Profil sudah lengkap! @endif </p>
    </div>

    <!-- Visitors This Week -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['visitors_last_7_days']) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Pengunjung 7 Hari Terakhir
        </h3>
        <p class="text-xs {{ $stats['growth_percentage'] >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
            <i class="fas {{ $stats['growth_percentage'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
            {{ abs($stats['growth_percentage']) }}% dari kemarin
        </p>
    </div>

    <!-- Total Products -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                <i class="fas fa-box text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['products_count']) }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Total Produk
        </h3>
        <a href="{{ route('user.products.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            Kelola produk →
        </a>
    </div>

    <!-- Website Status -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-{{ $websiteStatus['status_color'] }}-500 to-{{ $websiteStatus['status_color'] }}-600 rounded-xl">
                <i class="fas fa-globe text-white text-xl"></i>
            </div>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $websiteStatus['status_color'] }}-100 text-{{ $websiteStatus['status_color'] }}-800 dark:bg-{{ $websiteStatus['status_color'] }}-900/20 dark:text-{{ $websiteStatus['status_color'] }}-400">
                {{ $websiteStatus['status_text'] }}
            </span>
        </div>
        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
            Status Website
        </h3>
        @if($websiteStatus['is_published'])
        <a href="{{ $websiteStatus['public_url'] }}" target="_blank" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            Lihat website →
        </a>
        @else
        <a href="{{ route('user.publish') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            Publikasikan →
        </a>
        @endif
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
                <i class="fas fa-history text-gray-400"></i>
            </div>

            @if($recentActivities->count() > 0)
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
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
                <i class="fas fa-clock text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                    Mulai dengan menambahkan produk atau mengedit profil bisnis
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions & Website Info -->
    <div class="space-y-6">
        <!-- Website QR Code & Link -->
        @if($business && $websiteStatus['is_published'])
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Link Website Anda
            </h3>

            <!-- QR Code -->
            @if($websiteStatus['qr_code'])
            <div class="text-center mb-4">
                <div class="inline-block p-4 bg-white rounded-lg shadow-inner">
                    <img src="{{ $websiteStatus['qr_code'] }}" alt="QR Code Website" class="w-32 h-32 mx-auto">
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Scan untuk mengunjungi website
                </p>
            </div>
            @endif

            <!-- Website URL -->
            <div class="space-y-3">
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">URL Website:</p>
                    <a href="{{ $websiteStatus['public_url'] }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline break-all">
                        {{ $websiteStatus['public_url'] }}
                    </a>
                </div>

                <div class="flex space-x-2">
                    <button onclick="copyToClipboard('{{ $websiteStatus['public_url'] }}')" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-copy mr-1"></i> Copy Link
                    </button>
                    <a href="{{ $websiteStatus['public_url'] }}" target="_blank" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                        <i class="fas fa-external-link-alt mr-1"></i> Buka
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Aksi Cepat
            </h3>

            <div class="space-y-3">
                @if(!$business)
                <a href="{{ route('user.business.index') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200">
                    <i class="fas fa-building mr-3"></i>
                    <span class="font-medium">Setup Data Bisnis</span>
                </a>
                @endif

                <a href="{{ route('user.products.index') }}" class="flex items-center p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                    <i class="fas fa-plus mr-3"></i>
                    <span class="font-medium">Tambah Produk</span>
                </a>

                <a href="{{ route('user.gallery.index') }}" class="flex items-center p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:border-purple-500 hover:text-purple-600 dark:hover:text-purple-400 transition-all duration-200">
                    <i class="fas fa-images mr-3"></i>
                    <span class="font-medium">Upload Galeri</span>
                </a>

                <a href="{{ route('user.templates.index') }}" class="flex items-center p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:border-green-500 hover:text-green-600 dark:hover:text-green-400 transition-all duration-200">
                    <i class="fas fa-palette mr-3"></i>
                    <span class="font-medium">Pilih Template</span>
                </a>

                @if($business && !$websiteStatus['is_published'])
                <a href="{{ route('user.publish') }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200">
                    <i class="fas fa-globe mr-3"></i>
                    <span class="font-medium">Publikasikan Website</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Tips & Recommendations -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-6 border border-amber-200 dark:border-amber-800">
            <div class="flex items-center mb-3">
                <i class="fas fa-lightbulb text-amber-500 text-lg mr-2"></i>
                <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200">
                    Tips Hari Ini
                </h3>
            </div>

            @if($stats['progress_completion'] < 50) <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                Lengkapi profil bisnis Anda untuk meningkatkan kepercayaan pengunjung dan visibilitas online.
                </p>
                <a href="{{ route('user.business.index') }}" class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors duration-200">
                    Lengkapi Sekarang
                </a>
                @elseif($stats['products_count'] < 3) <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                    Tambahkan lebih banyak produk untuk memberikan pilihan yang lebih beragam kepada pelanggan.
                    </p>
                    <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        Tambah Produk
                    </a>
                    @elseif(!$websiteStatus['is_published'])
                    <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                        Website Anda sudah siap! Publikasikan sekarang agar dapat diakses oleh pelanggan.
                    </p>
                    <a href="{{ route('user.publish') }}" class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        Publikasikan
                    </a>
                    @else
                    <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                        Bagikan link website Anda di media sosial untuk menjangkau lebih banyak pelanggan!
                    </p>
                    <button onclick="shareWebsite()" class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        Bagikan Website
                    </button>
                    @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Link berhasil disalin!', 'success');
        }, function(err) {
            console.error('Could not copy text: ', err);
            showToast('Gagal menyalin link', 'error');
        });
    }

    // Share website function
    function shareWebsite() {
        @if($business && $websiteStatus['is_published'])
        const url = '{{ $websiteStatus['
        public_url '] }}';
        const title = '{{ $business->business_name }}';
        const text = 'Kunjungi website {{ $business->business_name }}';

        if (navigator.share) {
            navigator.share({
                title: title
                , text: text
                , url: url
            }).then(() => {
                showToast('Berhasil membagikan website!', 'success');
            }).catch(() => {
                copyToClipboard(url);
            });
        } else {
            copyToClipboard(url);
        }
        @else
        showToast('Website belum dipublikasikan', 'warning');
        @endif
    }

    // Simple toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-y-full opacity-0`;

        const bgColor = type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';

        toast.classList.add(bgColor);
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-y-full', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 100);

        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1
        , rootMargin: '0px 0px -50px 0px'
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
        // Observe all cards for animation
        const cards = document.querySelectorAll('.bg-white, .bg-gradient-to-br');
        cards.forEach(card => observer.observe(card));
    });

</script>

<style>
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }

</style>
@endpush
