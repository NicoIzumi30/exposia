@extends('admin.layouts.app')

@section('title', 'Laporan & Moderasi')

@section('page-title', 'Laporan & Moderasi')

@section('content')
<!-- Filter & Search Header -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Search Form -->
            <div class="w-full md:w-1/2 lg:w-2/5">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="flex space-x-2">
                    <div class="relative flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}" 
                               placeholder="Cari kode laporan atau URL..." 
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
                    {{ $status ? ucfirst($status) : 'Semua Status' }}
                    <i class="fas fa-chevron-down ml-2 text-gray-400 dark:text-gray-500"></i>
                </button>
                <div id="filter-dropdown" 
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10 hidden">
                    <a href="{{ route('admin.reports.index', ['search' => $search, 'type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status === 'all' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['all'] }}</span>
                        <span>Semua Laporan</span>
                    </a>
                    <a href="{{ route('admin.reports.index', ['status' => 'pending', 'search' => $search, 'type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status === 'pending' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['pending'] }}</span>
                        <span class="flex items-center">
                            <span class="w-2 h-2 inline-block bg-yellow-500 rounded-full mr-2"></span>
                            Pending
                        </span>
                    </a>
                    <a href="{{ route('admin.reports.index', ['status' => 'resolved', 'search' => $search, 'type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status === 'resolved' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['resolved'] }}</span>
                        <span class="flex items-center">
                            <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-2"></span>
                            Diselesaikan
                        </span>
                    </a>
                    <a href="{{ route('admin.reports.index', ['status' => 'rejected', 'search' => $search, 'type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status === 'rejected' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['rejected'] }}</span>
                        <span class="flex items-center">
                            <span class="w-2 h-2 inline-block bg-red-500 rounded-full mr-2"></span>
                            Ditolak
                        </span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Advanced Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Report Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Jenis Laporan
                </label>
                <form action="{{ route('admin.reports.index') }}" method="GET" id="type-form">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <select id="type" 
                            name="type" 
                            onchange="document.getElementById('type-form').submit()"
                            class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Jenis</option>
                        @foreach($reportTypes as $reportType)
                            <option value="{{ $reportType }}" {{ $type == $reportType ? 'selected' : '' }}>
                                {{ ucfirst($reportType) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            
            <!-- Date Range Filter -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tanggal Mulai
                </label>
                <form action="{{ route('admin.reports.index') }}" method="GET" id="date-form">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ $startDate }}" 
                           onchange="document.getElementById('date-form').submit()"
                           class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </form>
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tanggal Akhir
                </label>
                <form action="{{ route('admin.reports.index') }}" method="GET" id="end-date-form">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ $endDate }}" 
                           onchange="document.getElementById('end-date-form').submit()"
                           class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reports Table Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Daftar Laporan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Menampilkan {{ $reports->firstItem() ?? 0 }} - {{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() }} laporan
        </p>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Kode Laporan
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Jenis
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Pelapor
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        URL Website
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tanggal
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
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $report->report_code }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->report_type === 'inappropriate' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                            {{ ucfirst($report->report_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                {{ $report->user ? strtoupper(substr($report->user->name, 0, 1)) : '?' }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $report->user ? $report->user->name : 'Pengunjung' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $report->user ? $report->user->email : 'Pengguna Anonim' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white truncate max-w-xs">
                            {{ $report->website_url }}
                        </div>
                        @if($report->business)
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $report->business->business_name }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $report->created_at->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $report->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($report->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <span class="w-1.5 h-1.5 inline-block bg-yellow-500 rounded-full mr-1.5"></span>
                                Pending
                            </span>
                        @elseif($report->status === 'resolved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                Diselesaikan
                            </span>
                        @elseif($report->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.reports.show', $report) }}" 
                           class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-flag text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-lg">Tidak ada laporan ditemukan</p>
                            @if($search || $status !== 'pending' || $type || $startDate || $endDate)
                            <p class="text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                            <a href="{{ route('admin.reports.index') }}" class="mt-3 text-indigo-600 dark:text-indigo-400 hover:underline">
                                <i class="fas fa-redo-alt mr-1"></i> Reset semua filter
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
    @if($reports->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        {{ $reports->links() }}
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