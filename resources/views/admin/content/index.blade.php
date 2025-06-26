@extends('admin.layouts.app')

@section('title', 'Monitoring Konten')

@section('page-title', 'Monitoring Konten')

@section('content')
<!-- Filter & Type Header -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <!-- Content Type Tabs -->
        <div class="flex space-x-1 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
            <a href="{{ route('admin.content.products', request()->only(['business_id', 'search'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type', 'products') == 'products' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                <i class="fas fa-box mr-2 {{ request('type', 'products') == 'products' ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                Produk
            </a>
            <a href="{{ route('admin.content.galleries', request()->only(['business_id', 'search'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type') == 'galleries' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                <i class="fas fa-images mr-2 {{ request('type') == 'galleries' ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                Galeri
            </a>
            <a href="{{ route('admin.content.testimonials', request()->only(['business_id', 'search'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type') == 'testimonials' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                <i class="fas fa-quote-right mr-2 {{ request('type') == 'testimonials' ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                Testimonial
            </a>
            <a href="{{ route('admin.content.about', request()->only(['business_id'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type') == 'about' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                <i class="fas fa-info-circle mr-2 {{ request('type') == 'about' ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                Tentang Usaha
            </a>
        </div>

        <!-- Business Filter Dropdown -->
        <div class="relative inline-block">
            <button type="button" 
                    id="filter-dropdown-button" 
                    class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                <i class="fas fa-store mr-2 text-gray-400 dark:text-gray-500"></i>
                {{ $activeBusiness ? $activeBusiness->business_name : 'Semua Bisnis' }}
                <i class="fas fa-chevron-down ml-2 text-gray-400 dark:text-gray-500"></i>
            </button>
            <div id="filter-dropdown" 
                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10 hidden max-h-96 overflow-y-auto">
                <a href="{{ route('admin.content.' . request('type', 'products'), ['search' => request('search')]) }}" 
                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ !$activeBusiness ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                    <i class="fas fa-globe mr-2 text-gray-400 dark:text-gray-500"></i>
                    Semua Bisnis
                </a>
                @foreach($businesses as $business)
                    <a href="{{ route('admin.content.' . request('type', 'products'), ['business_id' => $business->id, 'search' => request('search')]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $activeBusiness && $activeBusiness->id == $business->id ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <i class="fas fa-store mr-2 text-gray-400 dark:text-gray-500"></i>
                        <span class="truncate">{{ $business->business_name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Content will be replaced by the specific content type view -->
@yield('content-section')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Business filter dropdown
        const filterButton = document.getElementById('filter-dropdown-button');
        const filterDropdown = document.getElementById('filter-dropdown');
        
        if (filterButton && filterDropdown) {
            filterButton.addEventListener('click', function() {
                filterDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
                    filterDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush