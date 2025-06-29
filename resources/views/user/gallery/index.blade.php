@extends('user.layouts.app')

@section('title', 'Galeri')

@section('page-title', 'Galeri')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
        <div>
            <h1
                class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-purple-500 to-pink-600 bg-clip-text text-transparent">
                Galeri Foto
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
                Upload dan kelola foto produk atau layanan bisnis Anda
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="openGalleryModal()"
                class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Upload Foto</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Gallery Grid -->
        <div class="lg:col-span-3">
            @if ($galleries->count() > 0)
                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="galleryContainer">
                    @foreach ($galleries as $gallery)
                        <div class="gallery-item bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-200 animate-slide-up"
                            data-gallery-id="{{ $gallery->id }}">
                            <!-- Image Container -->
                            <div class="relative aspect-square bg-gray-100 dark:bg-gray-700 overflow-hidden group">
                                <img src="{{ $gallery->image_url }}" alt="Gallery Image"
                                    class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-105"
                                    onclick="openLightbox('{{ $gallery->image_url }}', '{{ $gallery->display_name }} - {{ $gallery->formatted_file_size }}')">


                                <!-- Quick View Overlay -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <button
                                        onclick="openLightbox('{{ $gallery->image_url }}', '{{ addslashes($gallery->display_name) }} - {{ $gallery->formatted_file_size }}')"
                                        class="p-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-full transition-all duration-200">
                                        <i class="fas fa-eye text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Image Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-1">
                                    {{ $gallery->display_name }}
                                </h3>

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $gallery->created_at->format('d M Y') }}</span>

                                    <button
                                        onclick="deleteGallery('{{ $gallery->id }}', '{{ addslashes($gallery->display_name) }}')"
                                        class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center duration-200">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($galleries->hasPages())
                    <div class="mt-8">
                        {{ $galleries->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center animate-slide-up">
                    <div
                        class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-images text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Foto</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        Upload foto-foto terbaik untuk menampilkan produk dan layanan bisnis Anda.
                    </p>
                    <button onclick="openGalleryModal()"
                        class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <i class="fas fa-plus mr-2"></i>
                        Upload Foto Pertama
                    </button>
                </div>
            @endif
        </div>

        <!-- Right Sidebar - Stats & Tips -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Gallery Stats -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-images text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Galeri</h3>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Foto</span>
                        <span
                            class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $galleryStats['total'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Sisa Slot</span>
                        <span
                            class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $galleryStats['remaining'] }}</span>
                    </div>
                    @if ($galleryStats['total'] > 0)
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full"
                                style="width: {{ ($galleryStats['total'] / 10) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                            {{ $galleryStats['total'] }} dari 10 foto
                        </p>
                    @endif
                </div>
            </div>

            <!-- Tips -->
            <div
                class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl border border-amber-200 dark:border-amber-800 p-6 animate-slide-up">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-white text-sm"></i>
                    </div>
                    <h4 class="font-semibold text-amber-800 dark:text-amber-200">Tips</h4>
                </div>
                <ul class="space-y-2 text-sm text-amber-700 dark:text-amber-300">
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                        <span>Upload foto berkualitas tinggi dengan pencahayaan yang baik</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                        <span>Maksimal 8 foto untuk galeri bisnis</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                        <span>Pilih foto yang menampilkan produk/layanan terbaik</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                        <span>Format yang didukung: JPG, PNG, WebP</span>
                    </li>
                </ul>
            </div>

            <!-- Back to Dashboard -->
            <a href="{{ route('user.dashboard') }}"
                class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-center flex items-center justify-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Gallery Upload Modal -->
    <div id="galleryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"
                aria-hidden="true" onclick="closeGalleryModal()"></div>

            <div
                class="inline-block w-full max-w-3xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Upload Foto Galeri
                    </h3>
                    <button onclick="closeGalleryModal()"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form id="galleryForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Image Upload Area -->
                    <div class="mb-6">
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            <i class="fas fa-images mr-2 text-purple-500"></i>
                            Pilih Foto <span class="text-red-500 ml-1">*</span>
                        </label>

                        <!-- Upload Dropzone -->
                        <div class="image-upload-dropzone relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-purple-500 dark:hover:border-purple-400 transition-all duration-300 cursor-pointer group"
                            id="imageDropzone">
                            <input type="file" id="galleryImages" name="gallery_images[]" accept="image/*" multiple
                                class="hidden">

                            <div class="upload-placeholder" id="imageUploadPlaceholder">
                                <div
                                    class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-purple-50 dark:group-hover:bg-purple-900/20 transition-colors duration-300">
                                    <i
                                        class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors duration-300"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Upload foto galeri
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, WebP • Max 5MB per foto • Max 8 foto total
                                </p>
                                <p class="text-xs text-purple-600 dark:text-purple-400 mt-2">
                                    Klik atau seret foto ke sini
                                </p>
                            </div>
                        </div>

                        <!-- Image Previews Container -->
                        <div id="imagePreviewsContainer" class="hidden mt-6">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Foto yang akan diupload:
                            </h4>
                            <div id="imagePreviews" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
                        </div>

                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" id="submitBtn"
                            class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2">
                            <i class="fas fa-upload"></i>
                            <span>Upload Foto</span>
                        </button>
                        <button type="button" onclick="closeGalleryModal()"
                            class="flex-1 sm:flex-none bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="fixed inset-0 z-[60] hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-90" onclick="closeLightbox()"></div>

            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeLightbox()"
                    class="absolute top-4 right-4 z-10 p-2 bg-black bg-opacity-50 text-white rounded-lg hover:bg-opacity-70 transition-all duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <img id="lightboxImage" src="" alt=""
                    class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">

                <div id="lightboxCaption"
                    class="absolute bottom-4 left-4 right-4 bg-black bg-opacity-50 text-white p-4 rounded-lg">
                    <p class="text-sm font-medium"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedFiles = [];

        function buildUrl(action, id = null) {
            const baseUrl = '{{ url('user/gallery') }}';
            switch (action) {
                case 'index':
                case 'store':
                    return baseUrl;
                case 'destroy':
                    return `${baseUrl}/${id}`;
                default:
                    return baseUrl;
            }
        }

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function openGalleryModal() {
            const modal = document.getElementById('galleryModal');
            const form = document.getElementById('galleryForm');
            form.reset();
            clearErrors();
            resetImageUpload();
            selectedFiles = [];
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            showToast('Siap untuk upload foto galeri', 'info', 2000);
        }

        function closeGalleryModal() {
            const modal = document.getElementById('galleryModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            selectedFiles = [];
        }

        function deleteGallery(galleryId, galleryName) {
            showConfirmation({
                title: 'Hapus Foto Ini?',
                text: `Foto "${galleryName}" akan dihapus secara permanen.`,
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus!'
            }, () => {
                showToast('Menghapus foto...', 'info', 0);
                fetch(buildUrl('destroy', galleryId), {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.clearAllToasts();
                        if (data.success) {
                            showToast(data.message || 'Foto berhasil dihapus', 'success');
                            const galleryItem = document.querySelector(`[data-gallery-id="${galleryId}"]`);
                            if (galleryItem) {
                                galleryItem.style.transform = 'scale(0)';
                                galleryItem.style.opacity = '0';
                                setTimeout(() => {
                                    galleryItem.remove();
                                    updateGalleryStats(data.stats);
                                }, 300);
                            }
                        } else {
                            showToast(data.message || 'Gagal menghapus foto', 'error');
                        }
                    })
                    .catch(error => {
                        window.clearAllToasts();
                        showToast('Terjadi kesalahan saat menghapus foto', 'error');
                    });
            });
        }

        document.getElementById('galleryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Mengupload...';
            submitBtn.disabled = true;
            clearErrors();

            const formData = new FormData();
            const csrfToken = getCsrfToken();
            formData.append('_token', csrfToken);

            const fileInput = document.getElementById('galleryImages');
            if (selectedFiles.length > 0) {
                selectedFiles.forEach((fileData, index) => {
                    formData.append(`gallery_images[${index}]`, fileData.file);
                });
            } else {
                showToast('Silakan pilih foto untuk diupload', 'error');
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
                return;
            }

            const url = buildUrl('store');
            showToast('Mengupload foto galeri...', 'info', 0);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    window.clearAllToasts();
                    if (data.success) {
                        showToast(data.message || 'Foto berhasil diupload', 'success');
                        closeGalleryModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                        if (data.errors) {
                            displayErrors(data.errors);
                        }
                    }
                })
                .catch(error => {
                    window.clearAllToasts();
                    showToast('Terjadi kesalahan saat mengupload foto', 'error');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                });
        });

        function initializeImageUpload() {
            const dropzone = document.getElementById('imageDropzone');
            const fileInput = document.getElementById('galleryImages');
            if (!dropzone || !fileInput) return;

            dropzone.addEventListener('click', (e) => {
                if (e.target !== fileInput) {
                    fileInput.click();
                }
            });

            fileInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                if (files.length > 0) {
                    handleMultipleImageUpload(files);
                }
            });

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
            });

            dropzone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                if (!dropzone.contains(e.relatedTarget)) {
                    dropzone.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
                }
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
                const files = Array.from(e.dataTransfer.files);
                if (files.length > 0) {
                    const validFiles = files.filter(file => file.type.startsWith('image/'));
                    if (validFiles.length > 0) {
                        handleMultipleImageUpload(validFiles);
                    } else {
                        showToast('Silakan pilih file gambar yang valid', 'error');
                    }
                }
            });
        }

        function handleMultipleImageUpload(files) {
            const maxFiles = 10;
            const currentGalleryCount = {{ $galleryStats['total'] ?? 0 }};
            const remainingSlots = maxFiles - currentGalleryCount;
            const maxSize = 5 * 1024 * 1024;

            if (files.length > remainingSlots) {
                showToast(`Anda hanya dapat menambahkan ${remainingSlots} foto lagi.`, 'error');
                return;
            }

            const validFiles = [];
            for (let file of files) {
                if (!file.type.startsWith('image/')) {
                    showToast(`File ${file.name} bukan gambar.`, 'error');
                    continue;
                }
                if (file.size > maxSize) {
                    showToast(`File ${file.name} terlalu besar (maks 5MB).`, 'error');
                    continue;
                }
                validFiles.push(file);
            }

            if (validFiles.length === 0) {
                return;
            }

            selectedFiles = validFiles.map(file => ({
                file: file
            }));
            showToast(`${validFiles.length} foto siap untuk diupload`, 'success', 3000);
            showImagePreviews();
        }

        function showImagePreviews() {
            const placeholder = document.getElementById('imageUploadPlaceholder');
            const previewsContainer = document.getElementById('imagePreviewsContainer');
            const previews = document.getElementById('imagePreviews');
            placeholder.classList.add('hidden');
            previewsContainer.classList.remove('hidden');
            previews.innerHTML = '';
            selectedFiles.forEach((fileData, index) => {
                const file = fileData.file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewItem = document.createElement('div');
                    previewItem.className =
                        'relative bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden';
                    previewItem.innerHTML = `
                <div class="aspect-square">
                    <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">${file.name}</p>
                </div>
                <button type="button" onclick="removeFilePreview(${index})" class="absolute top-2 right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-xs"></i>
                </button>`;
                    previews.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeFilePreview(index) {
            selectedFiles.splice(index, 1);
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(fileData => dataTransfer.items.add(fileData.file));
            document.getElementById('galleryImages').files = dataTransfer.files;

            if (selectedFiles.length === 0) {
                resetImageUpload();
                showToast('Semua foto dibatalkan', 'info', 2000);
            } else {
                showImagePreviews();
                showToast('Foto dihapus dari daftar upload', 'info', 2000);
            }
        }

        function resetImageUpload() {
            const fileInput = document.getElementById('galleryImages');
            const placeholder = document.getElementById('imageUploadPlaceholder');
            const previewsContainer = document.getElementById('imagePreviewsContainer');
            fileInput.value = '';
            placeholder.classList.remove('hidden');
            previewsContainer.classList.add('hidden');
            selectedFiles = [];
        }

        function openLightbox(imageUrl, caption) {
            const modal = document.getElementById('lightboxModal');
            const image = document.getElementById('lightboxImage');
            const captionElement = document.getElementById('lightboxCaption');
            image.src = imageUrl;
            captionElement.querySelector('p').textContent = caption;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            showToast('Tekan ESC untuk menutup', 'info', 2000);
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function updateGalleryStats(stats) {
            if (stats) {
                const totalElement = document.querySelector('.text-2xl.font-bold.text-purple-600');
                const remainingElement = document.querySelector('.text-lg.font-semibold.text-green-600');
                const progressElement = document.querySelector('.w-full.bg-gray-200 .bg-gradient-to-r');
                const progressText = document.querySelector('.text-xs.text-gray-500.text-center');

                if (totalElement) totalElement.textContent = stats.total;
                if (remainingElement) remainingElement.textContent = stats.remaining;
                if (progressElement) progressElement.style.width = `${stats.percentage}%`;
                if (progressText) progressText.textContent = `${stats.total} dari 10 foto`;

                showToast(`Sisa slot foto: ${stats.remaining}`, 'info', 3000);
            }
        }

        function clearErrors() {
            const errorMessages = document.querySelectorAll('#galleryModal .error-message');
            errorMessages.forEach(msg => {
                msg.classList.add('hidden');
                msg.textContent = '';
            });
            const inputs = document.querySelectorAll('#galleryModal input');
            inputs.forEach(input => {
                input.classList.remove('border-red-500');
            });
        }

        function displayErrors(errors) {
            const firstErrorKey = Object.keys(errors)[0];
            if (firstErrorKey) {
                showToast(errors[firstErrorKey][0], 'error');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('lightboxModal').classList.contains('hidden')) {
                    closeGalleryModal();
                } else {
                    closeLightbox();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            initializeImageUpload();
            const galleryItems = document.querySelectorAll('.gallery-item');
            if (galleryItems.length === 0) {
                showToast('Upload foto terbaik untuk galeri bisnis Anda', 'info', 5000);
            } else if (galleryItems.length >= 6) {
                showToast('Galeri Anda sudah cukup lengkap!', 'success', 3000, {
                    title: 'Bagus!'
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        #branchModal {
            backdrop-filter: blur(4px);
        }

        #branchModal>div>div {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .branch-card {
            transition: all 0.3s ease;
        }

        .branch-card:hover {
            transform: translateY(-2px);
        }

        .loading {
            pointer-events: none;
            opacity: 0.7;
        }

        input:focus,
        textarea:focus {
            transform: translateY(-1px);
        }

        #branchModal .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        #branchModal .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        #branchModal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .empty-state {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            #branchModal>div>div {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }
        }
    </style>
@endpush
