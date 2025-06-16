@extends('user.layouts.app')

@section('title', 'Data Usaha')

@section('page-title', 'Data Usaha')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Data Usaha
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
            Kelola informasi lengkap tentang bisnis Anda
        </p>
    </div>
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <!-- Progress Completion Badge -->
        <div class="flex items-center space-x-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $business->progress_completion ?? 0 }}% Lengkap
            </span>
        </div>
        @if($business->public_url && $business->publish_status)
        <a href="{{ $business->public_url }}" target="_blank"
           class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 flex items-center space-x-2">
            <i class="fas fa-external-link-alt"></i>
            <span>Lihat Website</span>
        </a>
        @endif
    </div>
</div>

<!-- Main Form Container -->
<div class="mx-auto">
    <form id="business-form" action="{{ route('user.business.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')
        
        <!-- Left Column - Main Business Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Business Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Dasar</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Data utama tentang bisnis Anda</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Business Name -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">
                            <i class="fas fa-store mr-2 text-blue-500 dark:text-blue-400"></i>
                            Nama Usaha <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="business_name" value="{{ old('business_name', $business->business_name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('business_name') border-red-500 @enderror"
                               placeholder="Masukkan nama usaha Anda"
                               id="business-name">
                        @error('business_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500 dark:text-blue-400"></i>
                            Alamat Utama <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="main_address" rows="3" required
                                  class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('main_address') border-red-500 @enderror"
                                  placeholder="Masukkan alamat lengkap usaha Anda">{{ old('main_address', $business->main_address) }}</textarea>
                        @error('main_address')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Operational Hours -->
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">
                                <i class="fas fa-clock mr-2 text-blue-500 dark:text-blue-400"></i>
                                Jam Operasional <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="main_operational_hours" value="{{ old('main_operational_hours', $business->main_operational_hours) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('main_operational_hours') border-red-500 @enderror"
                                   placeholder="Contoh: Senin-Sabtu 08:00-17:00">
                            @error('main_operational_hours')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Maps Link -->
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">
                                <i class="fas fa-map mr-2 text-blue-500 dark:text-blue-400"></i>
                                Link Google Maps
                            </label>
                            <input type="url" name="google_maps_link" value="{{ old('google_maps_link', $business->google_maps_link) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('google_maps_link') border-red-500 @enderror"
                                   placeholder="https://maps.google.com/...">
                            @error('google_maps_link')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descriptions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-align-left text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Deskripsi Usaha</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ceritakan tentang bisnis Anda</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Short Description -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">
                            <i class="fas fa-quote-left mr-2 text-blue-500 dark:text-blue-400"></i>
                            Deskripsi Singkat <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="short_description" rows="2" maxlength="160" required
                                  class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('short_description') border-red-500 @enderror"
                                  placeholder="Deskripsi singkat tentang usaha Anda (maksimal 160 karakter)"
                                  id="short-description">{{ old('short_description', $business->short_description) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            @error('short_description')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                            <p class="text-xs text-gray-500 dark:text-gray-400">Akan ditampilkan sebagai snippet di search engine</p>
                            @enderror
                            <span class="text-xs text-gray-400" id="short-desc-count">0/160</span>
                        </div>
                    </div>

                    <!-- Full Description with Rich Text Editor -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">
                            <i class="fas fa-file-alt mr-2 text-blue-500 dark:text-blue-400"></i>
                            Deskripsi Lengkap <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="full_description" id="full-description-editor" class="hidden">{{ old('full_description', $business->full_description) }}</textarea>
                        @error('full_description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Logo Upload & Actions -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Logo Upload -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up sticky top-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-image text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Logo Usaha</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Upload logo bisnis Anda</p>
                    </div>
                </div>

                <!-- Current Logo Display -->
                @if($business->logo_url)
                <div class="current-logo mb-6">
                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                            <img src="{{ Storage::url($business->logo_url) }}" alt="Current Logo" class="w-full h-full object-contain">
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Logo saat ini</p>
                    </div>
                </div>
                @endif

                <!-- Logo Upload Area -->
                <div class="logo-upload-dropzone relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 cursor-pointer group" id="logo-dropzone">
                    <input type="file" id="business-logo" name="logo" accept="image/*" class="hidden">

                    <div class="upload-placeholder" id="logo-upload-placeholder">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors duration-300">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                            {{ $business->logo_url ? 'Ganti logo' : 'Upload logo' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG max 2MB</p>
                    </div>

                    <!-- Upload Progress -->
                    <div class="upload-progress hidden" id="logo-upload-progress">
                        <div class="w-16 h-16 mx-auto mb-4">
                            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500"></div>
                        </div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Uploading...</p>
                    </div>
                </div>

                <!-- Logo Preview -->
                <div class="logo-preview-container hidden mt-4" id="logo-preview-container">
                    <div class="relative">
                        <img id="logo-preview" src="" alt="Logo Preview" class="w-full h-32 object-contain rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700">
                        <button type="button" class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200" onclick="removeLogo()">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700 dark:text-green-400 font-medium">Logo ready to upload</span>
                        </div>
                    </div>
                </div>

                @error('logo')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website URL Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-globe text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">URL Website</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Atur alamat website Anda</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if($business->public_url && $business->publish_status)
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">Website URL:</p>
                                <a href="{{ $business->public_url }}" target="_blank" class="text-sm text-green-600 dark:text-green-400 hover:underline break-all">
                                    {{ $business->public_url }}
                                </a>
                            </div>
                            <button type="button" onclick="generateQrCode()" class="p-2 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            <i class="fas fa-info-circle mr-1"></i>
                            URL website akan dibuat otomatis setelah publikasi
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col gap-4 animate-slide-up">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
                
                <a href="{{ route('user.dashboard') }}" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-center flex items-center justify-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

<script>
let fullDescriptionEditor;

document.addEventListener('DOMContentLoaded', () => {
    // Initialize CKEditor for full description
    ClassicEditor
        .create(document.querySelector('#full-description-editor'), {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                'blockQuote', 'insertTable', 'undo', 'redo'
            ],
            placeholder: 'Ceritakan secara detail tentang bisnis Anda, produk/jasa yang ditawarkan, visi misi, dan keunggulan kompetitif...'
        })
        .then(editor => {
            fullDescriptionEditor = editor;
        })
        .catch(error => {
            console.error('CKEditor error:', error);
        });

    // Character counter for short description
    const shortDescInput = document.getElementById('short-description');
    const shortDescCounter = document.getElementById('short-desc-count');
    
    if (shortDescInput && shortDescCounter) {
        updateCharCount();
        shortDescInput.addEventListener('input', updateCharCount);
        
        function updateCharCount() {
            const count = shortDescInput.value.length;
            shortDescCounter.textContent = `${count}/160`;
            
            if (count > 160) {
                shortDescCounter.classList.add('text-red-500');
            } else {
                shortDescCounter.classList.remove('text-red-500');
            }
        }
    }

    // Logo upload handling
    initializeLogoUpload();

    // Auto-generate URL suggestion when business name changes
    const businessNameInput = document.getElementById('business-name');
    if (businessNameInput) {
        let timeoutId;
        businessNameInput.addEventListener('input', (e) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                if (e.target.value.length > 2) {
                    generateUrlSuggestion(e.target.value);
                }
            }, 500);
        });
    }
});

function initializeLogoUpload() {
    const dropzone = document.getElementById('logo-dropzone');
    const fileInput = document.getElementById('business-logo');
    const placeholder = document.getElementById('logo-upload-placeholder');
    const progress = document.getElementById('logo-upload-progress');
    const previewContainer = document.getElementById('logo-preview-container');
    const preview = document.getElementById('logo-preview');

    if (!dropzone || !fileInput) return;

    // Click to upload
    dropzone.addEventListener('click', (e) => {
        if (e.target !== fileInput) {
            fileInput.click();
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            handleLogoUpload(file);
        }
    });

    // Drag and drop events
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        if (!dropzone.contains(e.relatedTarget)) {
            dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        }
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (validateLogoFile(file)) {
                fileInput.files = files;
                handleLogoUpload(file);
            }
        }
    });
}

function validateLogoFile(file) {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!allowedTypes.includes(file.type)) {
        showToast('Please select a valid image file (JPG, PNG)', 'error');
        return false;
    }

    if (file.size > maxSize) {
        showToast('File size must be less than 2MB', 'error');
        return false;
    }

    return true;
}

function handleLogoUpload(file) {
    const placeholder = document.getElementById('logo-upload-placeholder');
    const progress = document.getElementById('logo-upload-progress');
    const previewContainer = document.getElementById('logo-preview-container');
    const preview = document.getElementById('logo-preview');

    // Show progress
    placeholder.classList.add('hidden');
    progress.classList.remove('hidden');

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
        preview.src = e.target.result;
        
        // Show success state
        setTimeout(() => {
            progress.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            showToast('Logo siap untuk diupload!', 'success');
        }, 1000);
    };

    reader.readAsDataURL(file);
}

function removeLogo() {
    const fileInput = document.getElementById('business-logo');
    const placeholder = document.getElementById('logo-upload-placeholder');
    const previewContainer = document.getElementById('logo-preview-container');

    fileInput.value = '';
    placeholder.classList.remove('hidden');
    previewContainer.classList.add('hidden');
    
    showToast('Logo dihapus', 'info');
}

function generateUrlSuggestion(businessName) {
    fetch('{{ route("user.business.generate-url") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            business_name: businessName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Suggested URL:', data.suggested_url);
            // You can show this as a preview if needed
        }
    })
    .catch(error => {
        console.error('Error generating URL:', error);
    });
}

function generateQrCode() {
    fetch('{{ route("user.business.generate-qr") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('QR Code berhasil dibuat!', 'success');
            // You can show QR code in a modal here
            window.open(data.qr_code_url, '_blank');
        } else {
            showToast(data.message || 'Gagal membuat QR Code', 'error');
        }
    })
    .catch(error => {
        console.error('Error generating QR code:', error);
        showToast('Gagal membuat QR Code', 'error');
    });
}

// Enhanced form submission with loading state
document.getElementById('business-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
    submitButton.disabled = true;
    
    // If using CKEditor, sync the content
    if (fullDescriptionEditor) {
        const textareaElement = document.querySelector('#full-description-editor');
        textareaElement.value = fullDescriptionEditor.getData();
    }
});

// Toast notification function
function showToast(message, type = 'info', duration = 5000) {
    const toast = document.createElement('div');
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    toast.className = `fixed bottom-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-y-full opacity-0 ${bgColors[type] || bgColors.info}`;
    
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icons[type] || icons.info} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-y-full', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 100);
    
    if (duration > 0) {
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
    
    return toast;
}
</script>
@endpush

@push('styles')
<style>
.drag-over {
    @apply border-blue-500 bg-blue-50 dark:bg-blue-900/20;
}

.upload-success {
    animation: successPulse 0.6s ease-out;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* CKEditor dark mode adjustments */
.ck-editor__editable_inline {
    min-height: 200px;
}

.dark .ck.ck-editor {
    border-color: rgb(75 85 99);
}

.dark .ck.ck-editor__editable_inline {
    background-color: rgb(55 65 81);
    color: rgb(243 244 246);
    border-color: rgb(75 85 99);
}

.dark .ck.ck-toolbar {
    background-color: rgb(75 85 99);
    border-color: rgb(75 85 99);
}

.dark .ck.ck-button {
    color: rgb(209 213 219);
}

.dark .ck.ck-button:hover {
    background-color: rgb(55 65 81);
}
</style>
@endpush