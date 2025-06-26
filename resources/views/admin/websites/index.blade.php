@extends('admin.layouts.app')

@section('title', 'Manajemen Website')

@section('page-title', 'Manajemen Website')

@section('content')
<!-- Filter & Search Header -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <!-- Search Form -->
        <div class="w-full md:w-1/2 lg:w-2/5">
            <form action="{{ route('admin.websites.index') }}" method="GET" class="flex space-x-2">
                <div class="relative flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           placeholder="Cari nama bisnis, alamat..." 
                           class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                    </span>
                </div>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    Cari
                </button>
            </form>
        </div>

        <!-- Status Filter Dropdown -->
        <div class="relative inline-block">
            <button type="button" 
                    id="filter-dropdown-button" 
                    class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                <i class="fas fa-filter mr-2 text-gray-400 dark:text-gray-500"></i>
                {{ $status ? ($status == 'published' ? 'Dipublikasikan' : 'Draft') : 'Semua Status' }}
                <i class="fas fa-chevron-down ml-2 text-gray-400 dark:text-gray-500"></i>
            </button>
            <div id="filter-dropdown" 
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10 hidden">
                <a href="{{ route('admin.websites.index', ['search' => $search]) }}" 
                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ !$status ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                    <span class="w-8 inline-block">{{ $counts['all'] }}</span>
                    <span>Semua Website</span>
                </a>
                <a href="{{ route('admin.websites.index', ['status' => 'published', 'search' => $search]) }}" 
                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status == 'published' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                    <span class="w-8 inline-block">{{ $counts['published'] }}</span>
                    <span class="flex items-center">
                        <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-2"></span>
                        Dipublikasikan
                    </span>
                </a>
                <a href="{{ route('admin.websites.index', ['status' => 'draft', 'search' => $search]) }}" 
                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status == 'draft' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                    <span class="w-8 inline-block">{{ $counts['draft'] }}</span>
                    <span class="flex items-center">
                        <span class="w-2 h-2 inline-block bg-gray-500 rounded-full mr-2"></span>
                        Draft
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Websites Table Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Daftar Website
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Menampilkan {{ $websites->firstItem() ?? 0 }} - {{ $websites->lastItem() ?? 0 }} dari {{ $websites->total() }} website
        </p>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.websites.index', ['sort' => 'business_name', 'direction' => $sortField == 'business_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => $search, 'status' => $status]) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Nama Bisnis</span>
                            @if($sortField == 'business_name')
                                <i class="fas fa-chevron-{{ $sortDirection == 'asc' ? 'up' : 'down' }} text-xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Pemilik
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        URL
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.websites.index', ['sort' => 'progress_completion', 'direction' => $sortField == 'progress_completion' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => $search, 'status' => $status]) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Kelengkapan</span>
                            @if($sortField == 'progress_completion')
                                <i class="fas fa-chevron-{{ $sortDirection == 'asc' ? 'up' : 'down' }} text-xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($websites as $website)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($website->logo_url)
                                <img src="{{ asset('storage/'.$website->logo_url) }}" alt="{{ $website->business_name }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $website->business_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $website->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $website->user->name ?? 'N/A' }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $website->user->email ?? '' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white truncate max-w-xs">
                            {{ $website->public_url }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2 w-24">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $website->progress_completion }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-900 dark:text-white">
                                {{ $website->progress_completion }}%
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($website->publish_status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                Dipublikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- View Button -->
                            <a href="{{ route('admin.websites.show', $website) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('admin.websites.edit', $website) }}" 
                               class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- Preview Button -->
                            <a href="{{ route('admin.websites.preview', $website) }}" 
                               class="text-amber-600 dark:text-amber-400 hover:text-amber-900 dark:hover:text-amber-300" 
                               title="Preview" 
                               target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            
                            <!-- Publish/Unpublish Button -->
                            @if($website->publish_status)
                                <button type="button"
                                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300" 
                                        title="Unpublish"
                                        data-action="{{ route('admin.websites.unpublish', $website) }}"
                                        data-method="POST"
                                        data-confirm-type="toggle"
                                        data-confirm-title="Konfirmasi Nonaktifkan Website"
                                        data-confirm-text="Website {{ $website->business_name }} akan dinonaktifkan dan tidak dapat diakses publik. Lanjutkan?"
                                        data-item-name="website"
                                        data-is-activating="false"
                                        onclick="handleActionWithConfirmation(this)">
                                    <i class="fas fa-toggle-off"></i>
                                </button>
                            @else
                                <button type="button"
                                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300" 
                                        title="Publish"
                                        data-action="{{ route('admin.websites.publish', $website) }}"
                                        data-method="POST"
                                        data-confirm-type="toggle"
                                        data-confirm-title="Konfirmasi Publikasi Website"
                                        data-confirm-text="Website {{ $website->business_name }} akan dipublikasikan dan dapat diakses publik. Lanjutkan?"
                                        data-item-name="website"
                                        data-is-activating="true"
                                        onclick="handleActionWithConfirmation(this)">
                                    <i class="fas fa-toggle-on"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-store text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-lg">Tidak ada website ditemukan</p>
                            @if($search)
                            <p class="text-sm mt-1">Coba ubah kata kunci pencarian</p>
                            <a href="{{ route('admin.websites.index') }}" class="mt-3 text-indigo-600 dark:text-indigo-400 hover:underline">
                                <i class="fas fa-redo-alt mr-1"></i> Reset pencarian
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($websites->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        {{ $websites->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter dropdown
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