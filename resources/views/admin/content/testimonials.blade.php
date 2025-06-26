    @extends('admin.content.index')

@section('title', 'Monitoring Testimonial')

@section('page-title', 'Monitoring Testimonial')

@section('content-section')
<!-- Search Form -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
    <form action="{{ route('admin.content.testimonials') }}" method="GET" class="flex space-x-2">
        <input type="hidden" name="business_id" value="{{ request('business_id') }}">
        <div class="relative flex-1">
            <input type="text" 
                   name="search" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Cari nama, posisi, atau isi testimonial..." 
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

<!-- Testimonials Table Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Table Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Daftar Testimonial
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menampilkan {{ $testimonials->firstItem() ?? 0 }} - {{ $testimonials->lastItem() ?? 0 }} dari {{ $testimonials->total() }} testimonial
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
                        <a href="{{ route('admin.content.testimonials', array_merge(request()->only(['business_id', 'search']), ['sort' => 'testimonial_name', 'direction' => $sortField == 'testimonial_name' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}" 
                           class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-white">
                            <span>Nama</span>
                            @if($sortField == 'testimonial_name')
                                <i class="fas fa-chevron-{{ $sortDirection == 'asc' ? 'up' : 'down' }} text-xs"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Posisi
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Testimonial
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Bisnis
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($testimonials as $testimonial)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $testimonial->testimonial_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $testimonial->testimonial_position }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-white max-w-md truncate">
                            {{ $testimonial->testimonial_content }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $testimonial->business->business_name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- View Business Button -->
                            <a href="{{ route('admin.websites.show', $testimonial->business_id) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" 
                               title="Lihat Bisnis">
                                <i class="fas fa-store"></i>
                            </a>
                            
                            <!-- Delete Button -->
                            <button type="button"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                    title="Hapus"
                                    data-action="{{ route('admin.content.delete', ['type' => 'testimonials', 'id' => $testimonial->id]) }}"
                                    data-method="DELETE"
                                    data-confirm-type="delete"
                                    data-confirm-title="Konfirmasi Hapus Testimonial"
                                    data-confirm-text="Testimonial dari '{{ $testimonial->testimonial_name }}' akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!"
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
                            <i class="fas fa-quote-right text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-lg">Tidak ada testimonial ditemukan</p>
                            @if($search)
                            <p class="text-sm mt-1">Coba ubah kata kunci pencarian</p>
                            <a href="{{ route('admin.content.testimonials', ['business_id' => request('business_id')]) }}" class="mt-3 text-indigo-600 dark:text-indigo-400 hover:underline">
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
    @if($testimonials->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        {{ $testimonials->links() }}
    </div>
    @endif
</div>
@endsection