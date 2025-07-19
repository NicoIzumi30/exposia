@extends('user.layouts.app')

@section('title', 'Data Usaha')

@section('page-title', 'Data Usaha')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-900 bg-clip-text text-transparent">
            Data Usaha
        </h1>
        <p class="text-gray-600 mt-2 transition-colors duration-300">
            Kelola informasi lengkap tentang bisnis Anda
        </p>
    </div>
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <!-- Progress Completion Badge -->
        <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-xl border border-gray-200">
            <div class="w-3 h-3 bg-gray-800 rounded-full"></div>
            <span class="text-sm font-medium text-gray-900">
                {{ $business->progress_completion ?? 0 }}% Lengkap
            </span>
        </div>
        @if($business->public_url && $business->publish_status)
        <a href="{{ $business->public_url }}" target="_blank"
           class="bg-gray-800 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center space-x-2">
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
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                        <p class="text-sm text-gray-600">Data utama tentang bisnis Anda</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Business Name -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-2 transition-colors duration-300">
                            <i class="fas fa-store mr-2 text-gray-600"></i>
                            Nama Usaha <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="business_name" value="{{ old('business_name', $business->business_name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('business_name') border-red-500 @enderror"
                               placeholder="Masukkan nama usaha Anda"
                               id="business-name">
                        @error('business_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-2 transition-colors duration-300">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-600"></i>
                            Alamat Utama <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="main_address" rows="3" required
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('main_address') border-red-500 @enderror"
                                  placeholder="Masukkan alamat lengkap usaha Anda">{{ old('main_address', $business->main_address) }}</textarea>
                        @error('main_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Operational Hours -->
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-2 transition-colors duration-300">
                                <i class="fas fa-clock mr-2 text-gray-600"></i>
                                Jam Operasional <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="main_operational_hours" value="{{ old('main_operational_hours', $business->main_operational_hours) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('main_operational_hours') border-red-500 @enderror"
                                   placeholder="Contoh: Senin-Sabtu 08:00-17:00">
                            @error('main_operational_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Maps Link -->
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-2 transition-colors duration-300">
                                <i class="fas fa-map mr-2 text-gray-600"></i>
                                Link Google Maps
                            </label>
                            <input type="url" name="google_maps_link" value="{{ old('google_maps_link', $business->google_maps_link) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('google_maps_link') border-red-500 @enderror"
                                   placeholder="https://maps.google.com/...">
                            @error('google_maps_link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descriptions -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-align-left text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Deskripsi Usaha</h2>
                        <p class="text-sm text-gray-600">Ceritakan tentang bisnis Anda</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Short Description -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-2 transition-colors duration-300">
                            <i class="fas fa-quote-left mr-2 text-gray-600"></i>
                            Deskripsi Singkat <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="short_description" rows="2" maxlength="160" required
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:border-gray-800 focus:ring-2 focus:ring-gray-800/20 focus:outline-none transition-all duration-200 transform focus:-translate-y-0.5 @error('short_description') border-red-500 @enderror"
                                  placeholder="Deskripsi singkat tentang usaha Anda (maksimal 160 karakter)"
                                  id="short-description">{{ old('short_description', $business->short_description) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            @error('short_description')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @else
                            <p class="text-xs text-gray-500">Akan ditampilkan sebagai snippet di search engine</p>
                            @enderror
                            <span class="text-xs text-gray-400" id="short-desc-count">0/160</span>
                        </div>
                    </div>

                    <!-- Full Description with Rich Text Editor -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-2 transition-colors duration-300">
                            <i class="fas fa-file-alt mr-2 text-gray-600"></i>
                            Deskripsi Lengkap <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="full_description" id="full-description-editor" class="hidden">{{ old('full_description', $business->full_description) }}</textarea>
                        @error('full_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Logo Upload & Actions -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Logo Upload -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 p-6 animate-slide-up sticky">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-image text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Logo Usaha</h2>
                        <p class="text-sm text-gray-600">Upload logo bisnis Anda</p>
                    </div>
                </div>

                <!-- Current Logo Display -->
                @if($business->logo_url)
                <div class="current-logo mb-6">
                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto bg-white rounded-xl border-2 border-gray-200 overflow-hidden shadow-lg">
                            <img src="{{ Storage::url($business->logo_url) }}" alt="Current Logo" class="w-full h-full object-contain">
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Logo saat ini</p>
                    </div>
                </div>
                @endif

                <!-- Logo Upload Area -->
                <div class="logo-upload-dropzone relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-gray-500 transition-all duration-300 cursor-pointer group" id="logo-dropzone">
                    <input type="file" id="business-logo" name="logo" accept="image/*" class="hidden">

                    <div class="upload-placeholder" id="logo-upload-placeholder">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-gray-200 transition-colors duration-300">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-gray-600 transition-colors duration-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-2">
                            {{ $business->logo_url ? 'Ganti logo' : 'Upload logo' }}
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG max 2MB</p>
                    </div>

                    <!-- Upload Progress -->
                    <div class="upload-progress hidden" id="logo-upload-progress">
                        <div class="w-16 h-16 mx-auto mb-4">
                            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-gray-800"></div>
                        </div>
                        <p class="text-sm font-medium text-gray-800">Uploading...</p>
                    </div>
                </div>

                <!-- Logo Preview -->
                <div class="logo-preview-container hidden mt-4" id="logo-preview-container">
                    <div class="relative">
                        <img id="logo-preview" src="" alt="Logo Preview" class="w-full h-32 object-contain rounded-xl border-2 border-gray-200 bg-white">
                        <button type="button" class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200" onclick="removeLogo()">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700 font-medium">Logo ready to upload</span>
                        </div>
                    </div>
                </div>

                @error('logo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website URL Section -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-globe text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">URL Website</h2>
                        <p class="text-sm text-gray-600">Atur alamat website Anda</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if($business->public_url && $business->publish_status)
                    <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-800">Website URL:</p>
                                <a href="{{ $business->public_url }}" target="_blank" class="text-sm text-green-600 hover:underline break-all">
                                    {{ $business->public_url }}
                                </a>
                            </div>
                            <button type="button" onclick="generateQrCode()" class="p-2 text-green-600 hover:text-green-800">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            URL website akan dibuat otomatis setelah publikasi
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col gap-4 animate-slide-up">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
                
                <a href="{{ route('user.dashboard') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center flex items-center justify-center space-x-2">
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
            showToast('Editor siap digunakan', 'success', 2000);
        })
        .catch(error => {
            console.error('CKEditor error:', error);
            showToast('Gagal memuat editor teks', 'error');
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
        dropzone.classList.add('border-gray-500', 'bg-gray-50');
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        if (!dropzone.contains(e.relatedTarget)) {
            dropzone.classList.remove('border-gray-500', 'bg-gray-50');
        }
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-gray-500', 'bg-gray-50');

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
        showToast('Format file harus JPG atau PNG', 'error');
        return false;
    }

    if (file.size > maxSize) {
        showToast('Ukuran file maksimal 2MB', 'error');
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
            showToast('Logo siap untuk diupload!', 'success', 3000);
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
    
    showToast('Logo dihapus', 'info', 2000);
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
    
    // Show saving notification
    showToast('Menyimpan perubahan...', 'info', 0);
});
</script>
@endpush

@push('styles')
<style>
.drag-over {
    @apply border-gray-500 bg-gray-50;
}

.upload-success {
    animation: successPulse 0.6s ease-out;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* CKEditor adjustments */
.ck-editor__editable_inline {
    min-height: 200px;
}

.ck.ck-editor {
    border-color: rgb(209 213 219);
}

.ck.ck-editor__editable_inline {
    background-color: rgb(255 255 255);
    color: rgb(17 24 39);
    border-color: rgb(209 213 219);
}

.ck.ck-toolbar {
    background-color: rgb(249 250 251);
    border-color: rgb(209 213 219);
}

.ck.ck-button {
    color: rgb(75 85 99);
}

.ck.ck-button:hover {
    background-color: rgb(243 244 246);
}
</style>
@endpush