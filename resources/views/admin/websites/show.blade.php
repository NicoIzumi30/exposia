@extends('admin.layouts.app')

@section('title', 'Detail Website')

@section('page-title', 'Detail Website')

@section('content')
<!-- Website Details Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Informasi Website
        </h2>
        <div class="flex space-x-2">
            <!-- Edit Button -->
            <a href="{{ route('admin.websites.edit', $website) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            
            <!-- Preview Button -->
            <a href="{{ route('admin.websites.preview', $website) }}" 
               target="_blank"
               class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                <i class="fas fa-external-link-alt mr-2"></i>
                Preview
            </a>
            
            <!-- Toggle Status Button -->
            @if($website->publish_status)
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800"
                        data-action="{{ route('admin.websites.unpublish', $website) }}"
                        data-method="POST"
                        data-confirm-type="toggle"
                        data-confirm-title="Konfirmasi Nonaktifkan Website"
                        data-confirm-text="Website {{ $website->business_name }} akan dinonaktifkan dan tidak dapat diakses publik. Lanjutkan?"
                        data-item-name="website"
                        data-is-activating="false"
                        onclick="handleActionWithConfirmation(this)">
                    <i class="fas fa-toggle-off mr-2"></i>
                    Nonaktifkan
                </button>
            @else
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800"
                        data-action="{{ route('admin.websites.publish', $website) }}"
                        data-method="POST"
                        data-confirm-type="toggle"
                        data-confirm-title="Konfirmasi Publikasi Website"
                        data-confirm-text="Website {{ $website->business_name }} akan dipublikasikan dan dapat diakses publik. Lanjutkan?"
                        data-item-name="website"
                        data-is-activating="true"
                        onclick="handleActionWithConfirmation(this)">
                    <i class="fas fa-toggle-on mr-2"></i>
                    Publikasikan
                </button>
            @endif
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    @if($website->logo_url)
                        <img src="{{ asset('storage/'.$website->logo_url) }}" alt="{{ $website->business_name }}" class="h-16 w-16 rounded-lg object-cover">
                    @else
                        <div class="h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-store text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $website->business_name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $website->publish_status ? 'Dipublikasikan' : 'Draft' }}
                        </p>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                URL
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                <a href="{{ url($website->public_url) }}" 
                                   target="_blank" 
                                   class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $website->public_url }}
                                    <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Pemilik
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                @if($website->user)
                                <a href="{{ route('admin.users.show', $website->user) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $website->user->name }}
                                </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Tidak diketahui</span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Alamat
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $website->main_address ?: 'Belum diisi' }}
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Jam Operasional
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $website->main_operational_hours ?: 'Belum diisi' }}
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Tanggal Dibuat
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $website->created_at->format('d M Y, H:i') }}
                                ({{ $website->created_at->diffForHumans() }})
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Website Statistics -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Statistik Website
                </h3>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Completion -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Kelengkapan
                                </span>
                                <span class="text-lg font-bold text-amber-600 dark:text-amber-400">
                                    {{ $website->progress_completion }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $website->progress_completion }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Visitor Count -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Total Pengunjung
                            </span>
                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $visitorStats->sum('visitors') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Content Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Cabang
                        </span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $branches }}
                        </span>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Produk
                        </span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $products }}
                        </span>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Galeri
                        </span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $galleries }}
                        </span>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Testimonial
                        </span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $testimonials }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Short Description -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Deskripsi Singkat
            </h3>
            <p class="text-sm text-gray-900 dark:text-white">
                {{ $website->short_description ?: 'Belum ada deskripsi singkat' }}
            </p>
        </div>
    </div>
</div>

<!-- Visitor Statistics Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Statistik Pengunjung (7 Hari Terakhir)
        </h2>
    </div>
    
    <div class="p-6">
        @if(count($visitorStats) > 0)
            <div class="h-64">
                <canvas id="visitorChart"></canvas>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-6">
                <i class="fas fa-chart-bar text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada data pengunjung</p>
            </div>
        @endif
    </div>
</div>

<!-- Content Overview Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Ringkasan Konten
        </h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Branches -->
            <a href="{{ route('admin.content.index', ['business_id' => $website->id, 'type' => 'branches']) }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-800/50 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mb-4">
                    <i class="fas fa-map-marker-alt text-lg"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Cabang
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    {{ $branches }} cabang terdaftar
                </p>
                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </span>
            </a>
            
            <!-- Products -->
            <a href="{{ route('admin.content.index', ['business_id' => $website->id, 'type' => 'products']) }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-800/50 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mb-4">
                    <i class="fas fa-box text-lg"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Produk
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    {{ $products }} produk terdaftar
                </p>
                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </span>
            </a>
            
            <!-- Gallery -->
            <a href="{{ route('admin.content.index', ['business_id' => $website->id, 'type' => 'galleries']) }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-800/50 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mb-4">
                    <i class="fas fa-images text-lg"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Galeri
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    {{ $galleries }} foto dalam galeri
                </p>
                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </span>
            </a>
            
            <!-- Testimonials -->
            <a href="{{ route('admin.content.index', ['business_id' => $website->id, 'type' => 'testimonials']) }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-800/50 transition-all duration-200 transform hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-4">
                    <i class="fas fa-quote-right text-lg"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Testimonial
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    {{ $testimonials }} testimonial pelanggan
                </p>
                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup visitor chart if data exists
        @if(count($visitorStats) > 0)
        const visitorData = @json($visitorStats);
        const ctx = document.getElementById('visitorChart').getContext('2d');
        
        // Process data
        const dates = visitorData.map(item => item.date).reverse();
        const visitors = visitorData.map(item => item.visitors).reverse();
        
        // Get theme
        const isDark = document.documentElement.classList.contains('dark');
        const fontColor = isDark ? '#D1D5DB' : '#4B5563';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        
        // Create chart
        const visitorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Pengunjung',
                    data: visitors,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: fontColor
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: fontColor
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                }
            }
        });
        
        // Update chart when theme changes
        window.addEventListener('theme-changed', function() {
            const isDarkNow = document.documentElement.classList.contains('dark');
            const newFontColor = isDarkNow ? '#D1D5DB' : '#4B5563';
            const newGridColor = isDarkNow ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            
            visitorChart.options.scales.y.ticks.color = newFontColor;
            visitorChart.options.scales.y.grid.color = newGridColor;
            visitorChart.options.scales.x.ticks.color = newFontColor;
            visitorChart.options.scales.x.grid.color = newGridColor;
            
            visitorChart.update();
        });
        @endif
    });
</script>
@endpush