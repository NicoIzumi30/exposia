@extends('admin.content.index')

@section('title', 'Monitoring Tentang Usaha')

@section('page-title', 'Monitoring Tentang Usaha')

@section('content-section')
<!-- About Content Card -->
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Tentang Usaha
                </h2>
            </div>
            @if($activeBusiness)
            <p class="text-sm text-gray-500">
                Bisnis: <span class="font-medium text-gray-700">{{ $activeBusiness->business_name }}</span>
            </p>
            @endif
        </div>
    </div>
    
    <div class="p-6">
        @if($activeBusiness)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Business Story -->
                <div>
                    <h3 class="text-md font-medium text-gray-800 mb-3">
                        Cerita Bisnis
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 h-64 overflow-y-auto">
                        @if($activeBusiness->full_story)
                            <div class="prose max-w-none">
                                {!! $activeBusiness->full_story !!}
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                                <i class="fas fa-book text-4xl mb-4 text-gray-300"></i>
                                <p class="text-center">Belum ada cerita bisnis</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- About Image -->
                    <h3 class="text-md font-medium text-gray-800 mb-3 mt-6">
                        Gambar Tentang Usaha
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                        @if($activeBusiness->about_image)
                            <img src="{{ asset('storage/'.$activeBusiness->about_image) }}" alt="About image" class="max-h-48 rounded-lg">
                        @else
                            <div class="flex flex-col items-center justify-center h-32 text-gray-500">
                                <i class="fas fa-image text-4xl mb-4 text-gray-300"></i>
                                <p class="text-center">Belum ada gambar</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Right Column: Business Highlights -->
                <div>
                    <h3 class="text-md font-medium text-gray-800 mb-3">
                        Fitur Unggulan Bisnis
                    </h3>
                    
                    @if(count($highlights) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($highlights as $highlight)
                                <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                        <i class="{{ $highlight->icon }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ $highlight->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $highlight->description }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                                class="text-red-600 hover:text-red-900 p-1" 
                                                title="Hapus"
                                                data-action="{{ route('admin.content.delete', ['type' => 'highlights', 'id' => $highlight->id]) }}"
                                                data-method="DELETE"
                                                data-confirm-type="delete"
                                                data-confirm-title="Konfirmasi Hapus Highlight"
                                                data-confirm-text="Highlight '{{ $highlight->title }}' akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!"
                                                onclick="handleActionWithConfirmation(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-64 bg-gray-50 rounded-lg text-gray-500">
                            <i class="fas fa-star text-4xl mb-4 text-gray-300"></i>
                            <p class="text-center">Belum ada fitur unggulan</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- View Business Button -->
            <div class="mt-6 flex justify-center">
                <a href="{{ route('admin.websites.show', $activeBusiness->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-store mr-2"></i>
                    Lihat Detail Bisnis
                </a>
            </div>
        @else
            <!-- Business Not Selected -->
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <i class="fas fa-store text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg text-center mb-4">Silakan pilih bisnis untuk melihat informasi tentang usaha</p>
                <button type="button" 
                        id="select-business-button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-store mr-2"></i>
                    Pilih Bisnis
                </button>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handler for select business button
        const selectBusinessButton = document.getElementById('select-business-button');
        if (selectBusinessButton) {
            selectBusinessButton.addEventListener('click', function() {
                // Trigger click on filter dropdown button
                const filterButton = document.getElementById('filter-dropdown-button');
                if (filterButton) {
                    filterButton.click();
                }
            });
        }
    });
</script>
@endpush