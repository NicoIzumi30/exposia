@extends('admin.layouts.app')

@section('title', 'Monitoring Konten')

@section('page-title', 'Monitoring Konten')

@section('content')
<!-- Filter & Type Header -->
<div class="bg-white shadow-sm rounded-xl p-6 mb-6 border border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <!-- Content Type Tabs -->
        <div class="flex space-x-1 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
            <a href="{{ route('admin.content.products', request()->only(['business_id', 'search'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type', 'products') == 'products' ? 'bg-gray-100 text-gray-900' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-box mr-2 {{ request('type', 'products') == 'products' ? 'text-gray-800' : 'text-gray-500' }}"></i>
                Produk
            </a>
            <a href="{{ route('admin.content.galleries', request()->only(['business_id', 'search'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type') == 'galleries' ? 'bg-gray-100 text-gray-900' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-images mr-2 {{ request('type') == 'galleries' ? 'text-gray-800' : 'text-gray-500' }}"></i>
                Galeri
            </a>
            <a href="{{ route('admin.content.testimonials', request()->only(['business_id', 'search'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type') == 'testimonials' ? 'bg-gray-100 text-gray-900' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-quote-right mr-2 {{ request('type') == 'testimonials' ? 'text-gray-800' : 'text-gray-500' }}"></i>
                Testimonial
            </a>
            <a href="{{ route('admin.content.about', request()->only(['business_id'])) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('type') == 'about' ? 'bg-gray-100 text-gray-900' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-info-circle mr-2 {{ request('type') == 'about' ? 'text-gray-800' : 'text-gray-500' }}"></i>
                Tentang Usaha
            </a>
        </div>

        <!-- Business Filter Dropdown -->
        <div class="relative inline-block">
            <button type="button" 
                    id="filter-dropdown-button" 
                    class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fas fa-store mr-2 text-gray-400"></i>
                {{ $activeBusiness ? $activeBusiness->business_name : 'Semua Bisnis' }}
                <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
            </button>
            <div id="filter-dropdown" 
                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-10 hidden max-h-96 overflow-y-auto">
                <a href="{{ route('admin.content.' . request('type', 'products'), ['search' => request('search')]) }}" 
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ !$activeBusiness ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-globe mr-2 text-gray-400"></i>
                    Semua Bisnis
                </a>
                @foreach($businesses as $business)
                    <a href="{{ route('admin.content.' . request('type', 'products'), ['business_id' => $business->id, 'search' => request('search')]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ $activeBusiness && $activeBusiness->id == $business->id ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="fas fa-store mr-2 text-gray-400"></i>
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