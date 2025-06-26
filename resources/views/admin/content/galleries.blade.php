@extends('admin.content.index')

@section('title', 'Monitoring Galeri')

@section('page-title', 'Monitoring Galeri')

@section('content-section')
<!-- Galleries Grid Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Grid Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Galeri Foto
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menampilkan {{ $galleries->firstItem() ?? 0 }} - {{ $galleries->lastItem() ?? 0 }} dari {{ $galleries->total() }} foto
                </p>
            </div>
            @if($activeBusiness)
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Bisnis: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $activeBusiness->business_name }}</span>
            </p>
            @endif
        </div>
    </div>

    <!-- Grid Container -->
    <div class="p-6">
        @if($galleries->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($galleries as $gallery)
                <div class="group relative">
                    <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700">
                        @if($gallery->gallery_image)
                            <img src="{{ asset('storage/'.$gallery->gallery_image) }}" alt="Gallery image" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                                <i class="fas fa-image text-3xl"></i>
                            </div>
                        @endif
                        
                        <!-- Overlay with actions -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                            <div class="flex space-x-2">
                                <!-- View Business Button -->
                                <a href="{{ route('admin.websites.show', $gallery->business_id) }}" 
                                   class="p-2 rounded-full bg-indigo-500 bg-opacity-80 text-white hover:bg-opacity-100 transition-all duration-200"
                                   title="Lihat Bisnis">
                                    <i class="fas fa-store"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <button type="button"
                                        class="p-2 rounded-full bg-red-500 bg-opacity-80 text-white hover:bg-opacity-100 transition-all duration-200"
                                        title="Hapus"
                                        data-action="{{ route('admin.content.delete', ['type' => 'galleries', 'id' => $gallery->id]) }}"
                                        data-method="DELETE"
                                        data-confirm-type="delete"
                                        data-confirm-title="Konfirmasi Hapus Galeri"
                                        data-confirm-text="Foto ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!"
                                        onclick="handleActionWithConfirmation(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ $gallery->business->business_name ?? 'N/A' }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10">
                <i class="fas fa-images text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                <p class="text-lg text-gray-500 dark:text-gray-400">Tidak ada foto dalam galeri</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($galleries->hasPages())
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        {{ $galleries->links() }}
    </div>
    @endif
</div>
@endsection