@extends('admin.layouts.app')

@section('title', 'Edit Website')

@section('page-title', 'Edit Website')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit Website
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Perbarui informasi website: {{ $website->business_name }}
                </p>
            </div>
            <div class="flex-shrink-0">
                @if($website->logo_url)
                    <img src="{{ asset('storage/'.$website->logo_url) }}" alt="{{ $website->business_name }}" class="h-12 w-12 rounded-lg object-cover">
                @else
                    <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-store"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <form id="edit-website-form" action="{{ route('admin.websites.update', $website) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Business Name Field -->
            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Bisnis <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="business_name" 
                       id="business_name" 
                       value="{{ old('business_name', $website->business_name) }}" 
                       required 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('business_name') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="Masukkan nama bisnis">
                @error('business_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Short Description Field -->
            <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi Singkat
                </label>
                <input type="text" 
                       name="short_description" 
                       id="short_description" 
                       value="{{ old('short_description', $website->short_description) }}" 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('short_description') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="Deskripsi singkat bisnis">
                @error('short_description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Main Address Field -->
            <div>
                <label for="main_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Alamat Utama
                </label>
                <input type="text" 
                       name="main_address" 
                       id="main_address" 
                       value="{{ old('main_address', $website->main_address) }}" 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('main_address') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="Alamat bisnis">
                @error('main_address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Operational Hours Field -->
            <div>
                <label for="main_operational_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Jam Operasional
                </label>
                <input type="text" 
                       name="main_operational_hours" 
                       id="main_operational_hours" 
                       value="{{ old('main_operational_hours', $website->main_operational_hours) }}" 
                       class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('main_operational_hours') border-red-500 dark:border-red-500 @enderror" 
                       placeholder="Contoh: Senin-Jumat, 08:00 - 17:00">
                @error('main_operational_hours')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Publication Status -->
        <div class="mt-6">
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status Publikasi
                </h3>
                <div class="flex items-center">
                    <span class="mr-3 text-sm text-gray-700 dark:text-gray-300">Status saat ini:</span>
                    @if($website->publish_status)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                            Dipublikasikan
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                            Draft
                        </span>
                    @endif
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Untuk mengubah status publikasi, gunakan tombol publikasi/nonaktifkan di halaman detail website.
                </p>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.websites.show', $website) }}" 
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Batal
            </a>
            <button type="button" 
                    id="submit-button"
                    onclick="handleFormSubmissionWithConfirmation()"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function handleFormSubmissionWithConfirmation() {
        let confirmOptions = {
            title: 'Konfirmasi Perubahan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan data website?',
            icon: 'question',
            confirmButtonText: 'Ya, Simpan Perubahan',
            cancelButtonText: 'Batal'
        };
        
        window.submitFormWithConfirmation('#edit-website-form', confirmOptions);
    }
</script>
@endpush