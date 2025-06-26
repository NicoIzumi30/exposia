@extends('admin.content.index')

@section('title', 'Monitoring Produk')

@section('page-title', 'Monitoring Produk')

@section('content-section')
<!-- Search Form -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
    <form action="{{ route('admin.content.products') }}" method="GET" class="flex space-x-2">
        <input type="hidden" name="business_id" value="{{ request('business_id') }}">
        <div class="relative flex-1">
            <input type="text" 
                   name="search" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Cari nama produk atau deskripsi..." 
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

<!-- Products Table Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Daftar Produk
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                </p>
            </div>
            @if($activeBusiness)
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Bisnis: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $activeBusiness->business_name }}</span>
            </p>
            @endif
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.content.products', array_merge(request()->only(['business_id', 'search']), ['sort' => 'product_name', 'direction' => $sortField == 'product_name' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Produk</span>
                            @if($sortField == 'product_name')
                                <i class="fas fa-chevron-{{ $sortDirection == 'asc' ? 'up' : 'down' }} text-xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Bisnis
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.content.products', array_merge(request()->only(['business_id', 'search']), ['sort' => 'product_price', 'direction' => $sortField == 'product_price' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Harga</span>
                            @if($sortField == 'product_price')
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
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($product->product_image)
                                <img src="{{ asset('storage/'.$product->product_image) }}" alt="{{ $product->product_name }}" class="h-12 w-12 rounded-lg object-cover">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-box"></i>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $product->product_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ $product->product_description }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $product->business->business_name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ number_format($product->product_price, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->is_pinned)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                <i class="fas fa-thumbtack mr-1.5"></i>
                                Dipinned
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                Normal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- View Business Button -->
                            <a href="{{ route('admin.websites.show', $product->business_id) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" 
                               title="Lihat Bisnis">
                                <i class="fas fa-store"></i>
                            </a>
                            
                            <!-- Delete Button -->
                            <button type="button"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                    title="Hapus"
                                    data-action="{{ route('admin.content.delete', ['type' => 'products', 'id' => $product->id]) }}"
                                    data-method="DELETE"
                                    data-confirm-type="delete"
                                    data-confirm-title="Konfirmasi Hapus Produk"
                                    data-confirm-text="Produk '{{ $product->product_name }}' akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!"
                                    onclick="handleActionWithConfirmation(this)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-lg">Tidak ada produk ditemukan</p>
                            @if($search)
                            <p class="text-sm mt-1">Coba ubah kata kunci pencarian</p>
                            <a href="{{ route('admin.content.products', ['business_id' => request('business_id')]) }}" class="mt-3 text-indigo-600 dark:text-indigo-400 hover:underline">
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
    @if($products->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection