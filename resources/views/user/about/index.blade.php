@extends('user.layouts.app')

@section('title', 'Tentang Usaha')

@section('page-title', 'Tentang Usaha')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Tentang Usaha
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
            Ceritakan kisah bisnis Anda dan highlight keunggulan yang dimiliki
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Content Area -->
    <div class="lg:col-span-3 space-y-8">

        <!-- Business Story Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-book-open text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Cerita Bisnis</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Bagikan kisah perjalanan dan visi bisnis Anda</p>
                    </div>
                </div>
            </div>

            <form id="storyForm" class="space-y-6">
                @csrf
                <!-- Story Content -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-pen mr-2 text-blue-500"></i>
                        Cerita Lengkap Bisnis
                    </label>
                    <div class="relative">
                        <textarea id="fullStory" name="full_story" rows="10" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Ceritakan kisah perjalanan bisnis Anda, visi, misi, dan nilai-nilai yang dijunjung tinggi...">{{ $business->full_story }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                            <span id="storyCounter">{{ strlen($business->full_story ?? '') }}</span>/5000
                        </div>
                    </div>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end">
                    <button type="submit" id="storySubmitBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Cerita</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- About Image Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-image text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Foto Tentang Bisnis</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Upload foto yang mewakili bisnis Anda</p>
                    </div>
                </div>
            </div>

            <form id="aboutImageForm" class="space-y-6">
                @csrf
                <!-- Current Image Display -->
                @if($business->about_image)
                <div class="text-center">
                    <div class="w-full max-w-md mx-auto bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                        <img id="currentAboutImage" src="{{ Storage::url($business->about_image) }}" alt="About Business Image" class="w-full h-64 object-cover">
                    </div>
                    <div class="mt-4 flex justify-center space-x-3">
                        <button type="button" onclick="document.getElementById('aboutImage').click()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Ganti Foto
                        </button>
                        <button type="button" onclick="removeAboutImage()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>Hapus Foto
                        </button>
                    </div>
                </div>
                @endif

                <!-- Image Upload Area -->
                <div class="image-upload-dropzone relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 cursor-pointer group {{ $business->about_image ? 'hidden' : '' }}" id="aboutImageDropzone">
                    <input type="file" id="aboutImage" name="about_image" accept="image/*" class="hidden">

                    <div class="upload-placeholder">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors duration-300">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Upload foto tentang bisnis
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG max 2MB</p>
                    </div>
                </div>

                <!-- Image Preview -->
                <div class="image-preview-container hidden" id="aboutImagePreviewContainer">
                    <div class="relative text-center">
                        <img id="aboutImagePreview" src="" alt="Preview" class="w-full max-w-md mx-auto h-64 object-cover rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700">
                        <button type="button" class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200" onclick="removeAboutImagePreview()">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" id="aboutImageSubmitBtn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Foto
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Business Highlights Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Keunggulan Bisnis</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Highlight nilai-nilai dan keunggulan bisnis Anda</p>
                    </div>
                </div>
                <button onclick="openHighlightModal()" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-[1.02] flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Highlight</span>
                </button>
            </div>

            @if($highlights->count() > 0)
            <!-- Highlights Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="highlightsContainer">
                @foreach($highlights as $highlight)
                <div class="highlight-card bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-200" data-highlight-id="{{ $highlight->id }}">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            {!! $highlight->icon_html !!}
                            <span class="text-white text-lg"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $highlight->title }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $highlight->description }}</p>
                        </div>
                        <div class="flex space-x-1 flex-shrink-0">
                            <button onclick="editHighlight('{{ $highlight->id }}')" class="p-2 text-gray-500 hover:text-blue-600 transition-colors duration-200 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteHighlight('{{ $highlight->id }}', '{{ $highlight->title }}')" class="p-2 text-gray-500 hover:text-red-600 transition-colors duration-200 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Highlight</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    Tambahkan highlight untuk menampilkan keunggulan dan nilai-nilai bisnis Anda.
                </p>
                <button onclick="openHighlightModal()" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02]">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Highlight Pertama
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Sidebar - Stats & Tips -->
    <div class="lg:col-span-1 space-y-6">
        <!-- About Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-pie text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Konten</h3>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Cerita Bisnis</span>
                    <span class="text-lg font-semibold {{ $business->full_story ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                        {{ $business->full_story ? 'Lengkap' : 'Kosong' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Foto Tentang</span>
                    <span class="text-lg font-semibold {{ $business->about_image ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                        {{ $business->about_image ? 'Ada' : 'Belum' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Highlights</span>
                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $highlightStats['total'] }}</span>
                </div>
                @if($highlightStats['total'] > 0)
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Highlights Lengkap</span>
                    <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $highlightStats['complete'] }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-lightbulb text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-blue-800 dark:text-blue-200">Tips Halaman About</h4>
            </div>
            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <span>Ceritakan perjalanan bisnis dengan jujur dan menarik</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <span>Gunakan foto berkualitas yang merepresentasikan bisnis</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <span>Highlight 3-6 keunggulan utama bisnis Anda</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <span>Fokus pada nilai yang diberikan kepada pelanggan</span>
                </li>
            </ul>
        </div>

        <!-- Back to Dashboard -->
        <a href="{{ route('user.dashboard') }}" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-center flex items-center justify-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
</div>

<!-- Highlight Modal -->
<div id="highlightModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true" onclick="closeHighlightModal()"></div>

        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="highlightModalTitle">
                    Tambah Highlight Baru
                </h3>
                <button onclick="closeHighlightModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="highlightForm" class="space-y-6">
                @csrf
                <input type="hidden" id="highlightId" name="highlight_id">

                <!-- Icon Selection -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-icons mr-2 text-blue-500"></i>
                        Pilih Icon <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="grid grid-cols-6 sm:grid-cols-8 gap-2 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                        @foreach($availableIcons as $iconClass => $iconName)
                        <button type="button" onclick="selectIcon('{{ $iconClass }}')" class="icon-option w-10 h-10 flex items-center justify-center border border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200" data-icon="{{ $iconClass }}" title="{{ $iconName }}">
                            <i class="{{ $iconClass }} text-gray-600 dark:text-gray-400"></i>
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedIcon" name="icon" required>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Title -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Judul Highlight <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" id="highlightTitle" name="title" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Masukkan judul highlight">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Description -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Deskripsi <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea id="highlightDescription" name="description" rows="4" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Jelaskan keunggulan atau nilai yang diberikan..."></textarea>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Minimal 10 karakter, maksimal 500 karakter
                    </div>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Modal Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" id="highlightSubmitBtn" class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Highlight</span>
                    </button>
                    <button type="button" onclick="closeHighlightModal()" class="flex-1 sm:flex-none bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    let isEditHighlightMode = false;
    let currentHighlightId = null;
    let storyEditor = null;

    // Build URLs dynamically
    function buildUrl(action, id = null) {
        const baseUrl = '{{ url("user/about") }}';

        switch (action) {
            case 'update-story':
                return `${baseUrl}/story`;
            case 'store-highlight':
                return `${baseUrl}/highlights`;
            case 'show-highlight':
            case 'update-highlight':
            case 'destroy-highlight':
                return `${baseUrl}/highlights/${id}`;
            case 'remove-about-image':
                return `${baseUrl}/remove-image`;
            default:
                return baseUrl;
        }
    }

    // Get CSRF token
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // Initialize CKEditor for story
    function initializeStoryEditor() {
        ClassicEditor
            .create(document.querySelector('#fullStory'), {
                toolbar: [
                    'heading', '|'
                    , 'bold', 'italic', 'underline', '|'
                    , 'bulletedList', 'numberedList', '|'
                    , 'indent', 'outdent', '|'
                    , 'blockQuote', 'insertTable', '|'
                    , 'undo', 'redo'
                ]
                , placeholder: 'Ceritakan kisah perjalanan bisnis Anda, visi, misi, dan nilai-nilai yang dijunjung tinggi...'
            })
            .then(editor => {
                storyEditor = editor;

                // Character counter
                const counter = document.getElementById('storyCounter');
                editor.model.document.on('change:data', () => {
                    const data = editor.getData();
                    const textLength = data.replace(/<[^>]*>/g, '').length;
                    counter.textContent = textLength;

                    if (textLength > 5000) {
                        counter.classList.add('text-red-500');
                    } else {
                        counter.classList.remove('text-red-500');
                    }
                });
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
            });
    }

    // Handle story form submission
    document.getElementById('storyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('storySubmitBtn');
        const originalContent = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
        submitBtn.disabled = true;

        clearErrors();

        const formData = new FormData();
        formData.append('_token', getCsrfToken());
        formData.append('full_story', storyEditor.getData());

        fetch(buildUrl('update-story'), {
                method: 'POST'
                , headers: {
                    'Accept': 'application/json'
                }
                , body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');

                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menyimpan cerita', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            });
    });

    // Initialize about image upload
    function initializeAboutImageUpload() {
        const dropzone = document.getElementById('aboutImageDropzone');
        const fileInput = document.getElementById('aboutImage');
        const previewContainer = document.getElementById('aboutImagePreviewContainer');
        const preview = document.getElementById('aboutImagePreview');

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
            if (file && validateImageFile(file)) {
                handleAboutImageUpload(file);
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
                if (validateImageFile(file)) {
                    fileInput.files = files;
                    handleAboutImageUpload(file);
                }
            }
        });
    }

    function validateImageFile(file) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (!allowedTypes.includes(file.type)) {
            showToast('Format file harus JPG, PNG, atau WEBP', 'error');
            return false;
        }

        if (file.size > maxSize) {
            showToast('Ukuran file maksimal 2MB', 'error');
            return false;
        }

        return true;
    }

    function handleAboutImageUpload(file) {
        const dropzone = document.getElementById('aboutImageDropzone');
        const previewContainer = document.getElementById('aboutImagePreviewContainer');
        const preview = document.getElementById('aboutImagePreview');

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            dropzone.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            showToast('Foto siap untuk disimpan!', 'success');
        };

        reader.readAsDataURL(file);
    }

    function removeAboutImagePreview() {
        const fileInput = document.getElementById('aboutImage');
        const dropzone = document.getElementById('aboutImageDropzone');
        const previewContainer = document.getElementById('aboutImagePreviewContainer');

        fileInput.value = '';
        dropzone.classList.remove('hidden');
        previewContainer.classList.add('hidden');
    }

    // Handle about image form submission
    document.getElementById('aboutImageForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('aboutImageSubmitBtn');
        const originalContent = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>Menyimpan...';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch(buildUrl('update-story'), {
                method: 'POST'
                , headers: {
                    'Accept': 'application/json'
                }
                , body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menyimpan foto', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            });
    });

    // Remove about image
    function removeAboutImage() {
        if (confirm('Apakah Anda yakin ingin menghapus foto tentang bisnis?')) {
            fetch(buildUrl('remove-about-image'), {
                    method: 'DELETE'
                    , headers: {
                        'Accept': 'application/json'
                        , 'X-CSRF-TOKEN': getCsrfToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Gagal menghapus foto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menghapus foto', 'error');
                });
        }
    }

    // Highlight management functions
    function openHighlightModal(highlightData = null) {
        const modal = document.getElementById('highlightModal');
        const modalTitle = document.getElementById('highlightModalTitle');
        const form = document.getElementById('highlightForm');

        // Reset form
        form.reset();
        clearErrors();
        clearIconSelection();

        if (highlightData) {
            // Edit mode
            isEditHighlightMode = true;
            currentHighlightId = highlightData.id;
            modalTitle.textContent = 'Edit Highlight';

            // Fill form with highlight data
            document.getElementById('highlightId').value = highlightData.id;
            document.getElementById('highlightTitle').value = highlightData.title;
            document.getElementById('highlightDescription').value = highlightData.description;
            selectIcon(highlightData.icon);
        } else {
            // Add mode
            isEditHighlightMode = false;
            currentHighlightId = null;
            modalTitle.textContent = 'Tambah Highlight Baru';
        }

        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Focus first input
        setTimeout(() => {
            document.getElementById('highlightTitle').focus();
        }, 100);
    }

    function closeHighlightModal() {
        const modal = document.getElementById('highlightModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        isEditHighlightMode = false;
        currentHighlightId = null;
    }

    function selectIcon(iconClass) {
        // Clear previous selection
        document.querySelectorAll('.icon-option').forEach(btn => {
            btn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        });

        // Select new icon
        const iconBtn = document.querySelector(`[data-icon="${iconClass}"]`);
        if (iconBtn) {
            iconBtn.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            document.getElementById('selectedIcon').value = iconClass;
        }
    }

    function clearIconSelection() {
        document.querySelectorAll('.icon-option').forEach(btn => {
            btn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        });
        document.getElementById('selectedIcon').value = '';
    }

    // Edit highlight
    function editHighlight(highlightId) {
        fetch(buildUrl('show-highlight', highlightId), {
                method: 'GET'
                , headers: {
                    'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    openHighlightModal(data.highlight);
                } else {
                    showToast(data.message || 'Gagal memuat data highlight', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memuat data highlight', 'error');
            });
    }

    // Delete highlight
    function deleteHighlight(highlightId, highlightTitle) {
        if (confirm(`Apakah Anda yakin ingin menghapus highlight "${highlightTitle}"?`)) {
            fetch(buildUrl('destroy-highlight', highlightId), {
                    method: 'DELETE'
                    , headers: {
                        'Accept': 'application/json'
                        , 'X-CSRF-TOKEN': getCsrfToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        // Remove highlight card from DOM
                        const highlightCard = document.querySelector(`[data-highlight-id="${highlightId}"]`);
                        if (highlightCard) {
                            highlightCard.style.opacity = '0';
                            highlightCard.style.transform = 'translateX(-100%)';
                            setTimeout(() => {
                                highlightCard.remove();
                                updateHighlightCount();
                            }, 300);
                        }
                    } else {
                        showToast(data.message || 'Gagal menghapus highlight', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menghapus highlight', 'error');
                });
        }
    }

    // Handle highlight form submission
    document.getElementById('highlightForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('highlightSubmitBtn');
        const originalContent = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
        submitBtn.disabled = true;

        clearErrors();

        const formData = new FormData(this);
        const url = isEditHighlightMode ?
            buildUrl('update-highlight', currentHighlightId) :
            buildUrl('store-highlight');

        // For PUT request, we need to add method override
        if (isEditHighlightMode) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
                method: 'POST'
                , headers: {
                    'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': getCsrfToken()
                }
                , body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeHighlightModal();

                    // Reload page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');

                    // Handle validation errors
                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menyimpan highlight', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            });
    });

    // Utility functions
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => {
            msg.classList.add('hidden');
            msg.textContent = '';
        });

        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
        });
    }

    function displayErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('border-red-500');
                const errorDiv = input.parentElement.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.textContent = errors[field][0];
                    errorDiv.classList.remove('hidden');
                }
            }
        });
    }

    function updateHighlightCount() {
        const highlightCards = document.querySelectorAll('[data-highlight-id]');
        const count = highlightCards.length;

        // Show empty state if no highlights
        if (count === 0) {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    }

    // Toast notification function
    function showToast(message, type = 'info', duration = 5000) {
        const container = document.getElementById('toast-container') || document.body;
        const toast = document.createElement('div');

        const bgColors = {
            success: 'bg-green-500'
            , error: 'bg-red-500'
            , warning: 'bg-yellow-500'
            , info: 'bg-blue-500'
        };

        const icons = {
            success: 'fa-check-circle'
            , error: 'fa-exclamation-circle'
            , warning: 'fa-exclamation-triangle'
            , info: 'fa-info-circle'
        };

        toast.className = `fixed bottom-4 right-4 z-[9999] p-4 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full opacity-0 max-w-sm ${bgColors[type] || bgColors.info}`;

        toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icons[type] || icons.info} mr-3"></i>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        if (duration > 0) {
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, duration);
        }
    }

    // Handle escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeHighlightModal();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeStoryEditor();
        initializeAboutImageUpload();
        console.log('About business page initialized');
    });

</script>
@endpush

@push('styles')
<style>
    /* CKEditor customization */
    .ck-editor__editable {
        min-height: 200px;
    }

    .ck-editor__editable_inline {
        border: 2px solid #d1d5db;
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .dark .ck-editor__editable_inline {
        border-color: #4b5563;
        background-color: #374151;
        color: #ffffff;
    }

    .ck-editor__editable:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    /* Highlight cards */
    .highlight-card {
        transition: all 0.3s ease;
    }

    .highlight-card:hover {
        transform: translateY(-2px);
    }

    /* Icon selection */
    .icon-option {
        transition: all 0.2s ease;
    }

    .icon-option:hover {
        transform: scale(1.1);
    }

    .icon-option.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .dark .icon-option.selected {
        background-color: rgba(59, 130, 246, 0.2);
    }

    /* Image upload styles */
    .image-upload-dropzone {
        transition: all 0.3s ease;
    }

    .image-upload-dropzone:hover {
        transform: translateY(-2px);
    }

    .image-upload-dropzone.drag-over {
        border-color: rgb(59 130 246);
        background-color: rgb(239 246 255);
    }

    .dark .image-upload-dropzone.drag-over {
        background-color: rgb(30 58 138 / 0.2);
    }

    /* Modal animations */
    #highlightModal {
        backdrop-filter: blur(4px);
    }

    #highlightModal>div>div {
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

    /* Form field focus styles */
    input:focus,
    textarea:focus {
        transform: translateY(-1px);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .highlight-card {
            margin-bottom: 1rem;
        }

        .icon-option {
            width: 2.5rem;
            height: 2.5rem;
        }
    }

    /* Custom scrollbar for icon grid */
    .grid.overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .grid.overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .grid.overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }

</style>
@endpush
