@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('page-title', 'Manajemen Pengguna')

@section('content')
<!-- Filter & Search Header -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <!-- Search Form -->
        <div class="w-full md:w-1/2 lg:w-2/5">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex space-x-2">
                <div class="relative flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           placeholder="Cari nama, email, atau nomor telepon..." 
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

        <!-- Filter & Add User -->
        <div class="flex items-center space-x-3">
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
                    <a href="{{ route('admin.users.index', ['search' => $search]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ !$status ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['all'] }}</span>
                        <span>Semua Pengguna</span>
                    </a>
                    <a href="{{ route('admin.users.index', ['status' => 'active', 'search' => $search]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status == 'active' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['active'] }}</span>
                        <span class="flex items-center">
                            <span class="w-2 h-2 inline-block bg-green-500 rounded-full mr-2"></span>
                            Aktif
                        </span>
                    </a>
                    <a href="{{ route('admin.users.index', ['status' => 'inactive', 'search' => $search]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status == 'inactive' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['inactive'] }}</span>
                        <span class="flex items-center">
                            <span class="w-2 h-2 inline-block bg-gray-500 rounded-full mr-2"></span>
                            Belum Verifikasi
                        </span>
                    </a>
                    <a href="{{ route('admin.users.index', ['status' => 'suspended', 'search' => $search]) }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white {{ $status == 'suspended' ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                        <span class="w-8 inline-block">{{ $counts['suspended'] }}</span>
                        <span class="flex items-center">
                            <span class="w-2 h-2 inline-block bg-red-500 rounded-full mr-2"></span>
                            Dinonaktifkan
                        </span>
                    </a>
                </div>
            </div>

            <!-- Add User Button -->
            <a href="{{ route('admin.users.create') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                <i class="fas fa-user-plus mr-2"></i>
                Tambah Pengguna
            </a>
        </div>
    </div>
</div>

<!-- Users Table Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Daftar Pengguna
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </p>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.users.index', ['sort' => 'name', 'direction' => $sortField == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => $search, 'status' => $status]) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Nama</span>
                            @if($sortField == 'name')
                                <i class="fas fa-chevron-{{ $sortDirection == 'asc' ? 'up' : 'down' }} text-xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.users.index', ['sort' => 'email', 'direction' => $sortField == 'email' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => $search, 'status' => $status]) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Email</span>
                            @if($sortField == 'email')
                                <i class="fas fa-chevron-{{ $sortDirection == 'asc' ? 'up' : 'down' }} text-xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nomor Telepon
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.users.index', ['sort' => 'created_at', 'direction' => $sortField == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => $search, 'status' => $status]) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Terdaftar</span>
                            @if($sortField == 'created_at')
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
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $user->businesses_count ?? 0 }} website
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $user->email }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum verifikasi' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $user->phone }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $user->created_at->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->is_suspended)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                Dinonaktifkan
                            </span>
                        @elseif(!$user->is_active || !$user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                Belum Verifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- View Button -->
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- Toggle Status Button -->
                            @if($user->is_suspended)
                                <button type="button"
                                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300" 
                                        title="Aktifkan"
                                        data-action="{{ route('admin.users.activate', $user) }}"
                                        data-method="POST"
                                        data-confirm-type="toggle"
                                        data-confirm-title="Konfirmasi Aktivasi"
                                        data-confirm-text="Apakah Anda yakin ingin mengaktifkan pengguna {{ $user->name }}?"
                                        data-item-name="pengguna"
                                        data-is-activating="true"
                                        onclick="handleActionWithConfirmation(this)">
                                    <i class="fas fa-toggle-on"></i>
                                </button>
                            @else
                                <button type="button"
                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300" 
                                        title="Nonaktifkan"
                                        data-action="{{ route('admin.users.suspend', $user) }}"
                                        data-method="POST"
                                        data-confirm-type="toggle"
                                        data-confirm-title="Konfirmasi Nonaktivasi"
                                        data-confirm-text="Pengguna {{ $user->name }} akan dinonaktifkan dan tidak bisa login. Lanjutkan?"
                                        data-item-name="pengguna"
                                        data-is-activating="false"
                                        onclick="handleActionWithConfirmation(this)">
                                    <i class="fas fa-toggle-off"></i>
                                </button>
                            @endif
                            
                            <!-- Delete Button -->
                            <button type="button"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                    title="Hapus"
                                    data-action="{{ route('admin.users.destroy', $user) }}"
                                    data-method="DELETE"
                                    data-confirm-type="delete"
                                    data-confirm-title="Konfirmasi Hapus Pengguna"
                                    data-confirm-text="Pengguna {{ $user->name }} beserta semua data terkait akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!"
                                    onclick="handleActionWithConfirmation(this)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-lg">Tidak ada pengguna ditemukan</p>
                            @if($search)
                            <p class="text-sm mt-1">Coba ubah kata kunci pencarian</p>
                            <a href="{{ route('admin.users.index') }}" class="mt-3 text-indigo-600 dark:text-indigo-400 hover:underline">
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
    @if($users->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        {{ $users->links() }}
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