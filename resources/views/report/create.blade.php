@extends('layouts.public')

@section('title', 'Laporkan Website')

@section('content')
<div class="relative min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-accent-400/5"></div>
    
    <div class="relative max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fade-in">
           
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Laporkan Website
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Bantu kami menjaga kualitas platform dengan melaporkan konten yang tidak sesuai
            </p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl border border-gray-200 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-white/95 dark:bg-gray-800/95 animate-card-entrance">
            
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-6">
                <div class="flex items-center">
                    <span class="material-icons-outlined text-white text-2xl mr-3">shield</span>
                    <div>
                        <h2 class="text-xl font-bold text-white">
                            Form Pelaporan
                        </h2>
                        <p class="text-white text-sm">
                            Isi formulir di bawah dengan lengkap dan akurat
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Form Content -->
            <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                
                <!-- URL Website -->
                <div class="space-y-2">
                    <label for="website_url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <span class="material-icons-outlined text-accent-500 text-lg mr-2">link</span>
                        URL Website <span class="text-accent-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400">public</span>
                        </div>
                        <input type="url" 
                               name="website_url" 
                               id="website_url" 
                               value="{{ old('website_url') }}" 
                               required 
                               class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-accent-400 focus:border-accent-400 rounded-2xl shadow-sm dark:bg-gray-700 dark:text-white transition-all duration-200 @error('website_url') border-red-400 dark:border-red-500 @enderror" 
                               placeholder="https://example.com/halaman">
                    </div>
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <span class="material-icons-outlined text-xs mr-1">info</span>
                        Masukkan URL lengkap website yang ingin dilaporkan
                    </div>
                    @error('website_url')
                        <div class="flex items-center text-red-500 mt-2">
                            <span class="material-icons-outlined text-sm mr-1">error</span>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Report Type -->
                <div class="space-y-2">
                    <label for="report_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <span class="material-icons-outlined text-accent-500 text-lg mr-2">category</span>
                        Jenis Laporan <span class="text-accent-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-400">label</span>
                        </div>
                        <select name="report_type" 
                                id="report_type" 
                                required 
                                class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-accent-400 focus:border-accent-400 rounded-2xl shadow-sm dark:bg-gray-700 dark:text-white transition-all duration-200 @error('report_type') border-red-400 dark:border-red-500 @enderror">
                            <option value="" disabled selected>Pilih jenis laporan</option>
                            <option value="inappropriate" {{ old('report_type') == 'inappropriate' ? 'selected' : '' }}>Konten Tidak Pantas</option>
                            <option value="spam" {{ old('report_type') == 'spam' ? 'selected' : '' }}> Spam atau Penipuan</option>
                            <option value="offensive" {{ old('report_type') == 'offensive' ? 'selected' : '' }}>Konten Menyinggung</option>
                            <option value="copyright" {{ old('report_type') == 'copyright' ? 'selected' : '' }}> Pelanggaran Hak Cipta</option>
                            <option value="illegal" {{ old('report_type') == 'illegal' ? 'selected' : '' }}>Aktivitas Ilegal</option>
                            <option value="other" {{ old('report_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    @error('report_type')
                        <div class="flex items-center text-red-500 mt-2">
                            <span class="material-icons-outlined text-sm mr-1">error</span>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Report Content -->
                <div class="space-y-2">
                    <label for="report_content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <span class="material-icons-outlined text-accent-500 text-lg mr-2">description</span>
                        Deskripsi Laporan <span class="text-accent-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <textarea name="report_content" 
                                  id="report_content" 
                                  rows="6" 
                                  required 
                                  class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-accent-400 focus:border-accent-400 rounded-2xl shadow-sm dark:bg-gray-700 dark:text-white transition-all duration-200 resize-none @error('report_content') border-red-400 dark:border-red-500 @enderror" 
                                  placeholder="Jelaskan secara detail alasan Anda melaporkan website ini...">{{ old('report_content') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500" id="char-counter">
                            0 karakter
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <span class="material-icons-outlined text-xs mr-1">info</span>
                        Minimal 10 karakter. Berikan informasi spesifik untuk membantu tim moderasi kami.
                    </div>
                    @error('report_content')
                        <div class="flex items-center text-red-500 mt-2">
                            <span class="material-icons-outlined text-sm mr-1">error</span>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
             
                
                <!-- Evidence Image -->
                <div class="space-y-2">
                    <label for="evidence_image" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <span class="material-icons-outlined text-accent-500 text-lg mr-2">image</span>
                        Bukti (Screenshot) 
                        <span class="ml-2 text-xs font-normal text-gray-500 dark:text-gray-400">(Opsional)</span>
                    </label>
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="hidden w-full h-64 border-2 border-dashed border-accent-300 dark:border-accent-600 rounded-2xl p-4 mb-4 bg-accent-50 dark:bg-accent-900/20">
                        <img id="preview-image" src="#" alt="Preview" class="w-full h-full object-contain rounded-xl">
                    </div>
                    
                    <!-- Upload Area -->
                    <div id="upload-container" class="w-full">
                        <label for="evidence_image" class="group w-full flex flex-col items-center px-6 py-8 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 text-accent-500 dark:text-accent-400 rounded-2xl tracking-wide border-2 border-dashed border-accent-300 dark:border-accent-600 cursor-pointer hover:bg-accent-50 dark:hover:bg-accent-900/20 transition-all duration-300 transform hover:scale-105">
                            <span class="material-icons-outlined text-4xl mb-3 group-hover:scale-110 transition-transform duration-200">cloud_upload</span>
                            <span class="text-lg font-semibold text-center">Klik atau seret gambar di sini</span>
                            <span class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Format: JPG, PNG, GIF • Maksimal 5MB
                            </span>
                        </label>
                        <input type="file" 
                               id="evidence_image" 
                               name="evidence_image" 
                               class="hidden" 
                               accept="image/*"
                               onchange="previewImage(this)">
                    </div>
                    
                    <!-- Remove Button -->
                    <div id="remove-button" class="mt-4 hidden">
                        <button type="button" 
                                onclick="removeImage()" 
                                class="inline-flex items-center px-4 py-2 border-2 border-red-300 dark:border-red-600 text-sm font-semibold rounded-xl text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition-all duration-200 transform hover:scale-105">
                            <span class="material-icons-outlined mr-2 text-sm">delete</span>
                            Hapus Gambar
                        </button>
                    </div>
                    @error('evidence_image')
                        <div class="flex items-center text-red-500 mt-2">
                            <span class="material-icons-outlined text-sm mr-1">error</span>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Terms and Policy -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start space-x-4">
                        <div class="flex items-center h-6 mt-1">
                            <input id="terms" 
                                   name="terms" 
                                   type="checkbox" 
                                   required
                                   class="h-5 w-5 text-accent-600 focus:ring-accent-500 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 transition-all duration-200 @error('terms') border-red-500 dark:border-red-500 @enderror">
                        </div>
                        <div class="text-sm">
                            <label for="terms" class="font-semibold text-gray-900 dark:text-white flex items-center cursor-pointer">
                                <span class="material-icons-outlined text-blue-500 mr-2">verified_user</span>
                                Pernyataan Pelapor
                            </label>
                            <p class="text-gray-700 dark:text-gray-300 mt-2 leading-relaxed">
                                Saya menyatakan bahwa laporan ini dibuat dengan <strong>itikad baik</strong> dan informasi yang diberikan adalah <strong>benar</strong>. 
                                Dengan mengirimkan laporan ini, Anda menyetujui 
                                <a href="#" class="text-accent-600 dark:text-accent-400 hover:text-accent-700 dark:hover:text-accent-300 font-semibold underline">Syarat dan Ketentuan</a> 
                                serta 
                                <a href="#" class="text-accent-600 dark:text-accent-400 hover:text-accent-700 dark:hover:text-accent-300 font-semibold underline">Kebijakan Privasi</a> 
                                kami.
                            </p>
                        </div>
                    </div>
                    @error('terms')
                        <div class="flex items-center text-red-500 mt-3 ml-9">
                            <span class="material-icons-outlined text-sm mr-1">error</span>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" 
                            class="group w-full bg-gradient-to-r from-accent-400 to-accent-600 hover:from-accent-500 hover:to-accent-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-accent-400/50 flex items-center justify-center">
                        <span class="material-icons-outlined mr-3 group-hover:scale-110 transition-transform duration-200">send</span>
                        <span class="text-lg">Kirim Laporan</span>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Help Section -->
        <div class="mt-12 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 animate-slide-up">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-icons-outlined text-white">help</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Butuh Bantuan?
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        Jika Anda memiliki pertanyaan tentang proses pelaporan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim support kami.
                    </p>
                    <a href="#" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-semibold">
                        <span class="material-icons-outlined mr-2 text-sm">contact_support</span>
                        Hubungi Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter for textarea
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('report_content');
        const counter = document.getElementById('char-counter');
        
        function updateCounter() {
            const length = textarea.value.length;
            counter.textContent = `${length} karakter`;
            
            if (length < 10) {
                counter.classList.add('text-red-400');
                counter.classList.remove('text-gray-400', 'text-green-400');
            } else if (length >= 10 && length <= 100) {
                counter.classList.add('text-green-400');
                counter.classList.remove('text-gray-400', 'text-red-400');
            } else {
                counter.classList.add('text-gray-400');
                counter.classList.remove('text-red-400', 'text-green-400');
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial call
    });
    
    // Image preview functionality
    function previewImage(input) {
        const preview = document.getElementById('preview-image');
        const previewContainer = document.getElementById('image-preview');
        const uploadContainer = document.getElementById('upload-container');
        const removeButton = document.getElementById('remove-button');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                window.showToast('Ukuran file terlalu besar. Maksimal 5MB.', 'error');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('animate-scale-in');
                uploadContainer.classList.add('hidden');
                removeButton.classList.remove('hidden');
                
                window.showToast('Gambar berhasil diupload!', 'success', 3000);
            };
            
            reader.readAsDataURL(file);
        }
    }
    
    function removeImage() {
        const input = document.getElementById('evidence_image');
        const preview = document.getElementById('preview-image');
        const previewContainer = document.getElementById('image-preview');
        const uploadContainer = document.getElementById('upload-container');
        const removeButton = document.getElementById('remove-button');
        
        input.value = '';
        preview.src = '#';
        previewContainer.classList.add('hidden');
        previewContainer.classList.remove('animate-scale-in');
        uploadContainer.classList.remove('hidden');
        removeButton.classList.add('hidden');
        
        window.showToast('Gambar berhasil dihapus.', 'info', 2000);
    }
    
    // Drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.querySelector('label[for="evidence_image"]');
        const input = document.getElementById('evidence_image');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropArea.classList.add('border-accent-400', 'bg-accent-100', 'dark:bg-accent-900/30', 'scale-105');
        }
        
        function unhighlight() {
            dropArea.classList.remove('border-accent-400', 'bg-accent-100', 'dark:bg-accent-900/30', 'scale-105');
        }
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                input.files = files;
                previewImage(input);
            }
        }
        
        // Form validation feedback
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('border-red-400', 'dark:border-red-500');
                } else {
                    this.classList.remove('border-red-400', 'dark:border-red-500');
                    this.classList.add('border-green-400', 'dark:border-green-500');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('border-red-400', 'dark:border-red-500');
                    this.classList.add('border-green-400', 'dark:border-green-500');
                }
            });
        });
        
        // Form submission handling
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = `
                <span class="material-icons-outlined mr-2 animate-spin">sync</span>
                <span>Mengirim...</span>
            `;
            submitButton.disabled = true;
            
            // Re-enable if there's an error (you might want to handle this differently)
            setTimeout(() => {
                if (submitButton.disabled) {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            }, 10000);
        });
    });
</script>
@endpush