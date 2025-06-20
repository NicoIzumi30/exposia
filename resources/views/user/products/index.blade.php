@extends('user.layouts.app')

@section('title', 'Produk')

@section('page-title', 'Produk')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Produk & Layanan
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
            Kelola produk dan layanan yang Anda tawarkan
        </p>
    </div>
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <!-- Search -->
        <div class="relative hidden sm:block">
            <input type="text" id="searchProducts" placeholder="Cari produk..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xs text-gray-400 hidden" id="search-hint">
                ESC untuk reset
            </div>
        </div>

        <!-- Add Product Button -->
        <button onclick="openProductModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Produk</span>
        </button>
    </div>
</div>

<!-- Bulk Actions (Hidden by default) -->
<div id="bulkActions" class="hidden bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center space-x-3 mb-3 sm:mb-0">
            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                <span id="selectedCount">0</span> produk dipilih
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="bulkAction('pin')" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-thumbtack mr-1"></i> Pin
            </button>
            <button onclick="bulkAction('unpin')" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-times mr-1"></i> Unpin
            </button>
            <button onclick="bulkAction('delete')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-trash mr-1"></i> Hapus
            </button>
            <button onclick="clearSelection()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors duration-200">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- Mobile Search -->
<div class="sm:hidden mb-6">
    <div class="relative">
        <input type="text" id="searchProductsMobile" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Products Grid -->
    <div class="lg:col-span-3">
        @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="productsContainer">
            @foreach($products as $product)
            <div class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-200 animate-slide-up" data-product-id="{{ $product->id }}">
                <!-- Product Image -->
                <div class="relative aspect-square bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    @if($product->product_image)
                    <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                    @endif

                    <!-- Pin Badge -->
                    @if($product->is_pinned)
                    <div class="absolute top-3 left-3">
                        <span class="bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-medium flex items-center">
                            <i class="fas fa-thumbtack mr-1"></i>
                            Pinned
                        </span>
                    </div>
                    @endif

                    <!-- Selection Checkbox -->
                    <div class="absolute top-3 right-3">
                        <input type="checkbox" class="product-checkbox w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="{{ $product->id }}" onchange="handleSelection()">
                    </div>

                    <!-- Quick Actions Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                        <div class="flex space-x-2">
                            <button onclick="editProduct('{{ $product->id }}')" class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="togglePin('{{ $product->id }}')" class="p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-thumbtack"></i>
                            </button>
                            @if($product->product_wa_link)
                            <button onclick="generateWhatsAppOrder('{{ $product->id }}')" class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                        {{ $product->product_name }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                        {{ $product->product_description }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                            {{ $product->product_price ? format_currency($product->product_price) : 'Hubungi Kami' }}
                        </span>
                        <div class="flex space-x-1">
                            <button onclick="editProduct('{{ $product->id }}')" class="p-2 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProduct('{{ $product->id }}', '{{ $product->product_name }}')" class="p-2 text-gray-500 hover:text-red-600 transition-colors duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center animate-slide-up">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-box text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Produk</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                Tambahkan produk atau layanan pertama Anda untuk mulai menjual online.
            </p>
            <button onclick="openProductModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk Pertama
            </button>
        </div>
        @endif
    </div>

    <!-- Right Sidebar - Stats & Tips -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Product Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Produk</h3>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Produk</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $productStats['total'] }}</span>
                </div>
                @if($productStats['total'] > 0)
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Produk Unggulan</span>
                    <span class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $productStats['pinned'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Dengan Gambar</span>
                    <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $productStats['with_images'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Link WhatsApp</span>
                    <span class="text-lg font-semibold text-purple-600 dark:text-purple-400">{{ $productStats['with_wa_links'] }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl border border-amber-200 dark:border-amber-800 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-lightbulb text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-amber-800 dark:text-amber-200">Tips Produk</h4>
            </div>
            <ul class="space-y-2 text-sm text-amber-700 dark:text-amber-300">
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Gunakan foto produk berkualitas tinggi</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Tulis deskripsi yang menarik dan informatif</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Pin produk terbaik di bagian atas</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Tambahkan link WhatsApp untuk order mudah</span>
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

<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true" onclick="closeProductModal()"></div>

        <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">
                    Tambah Produk Baru
                </h3>
                <button onclick="closeProductModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="productForm" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @csrf
                <input type="hidden" id="productId" name="product_id">

                <!-- Left Column - Product Info -->
                <div class="space-y-6">
                    <!-- Product Name -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>
                            Nama Produk <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="productName" name="product_name" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Masukkan nama produk">
                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>

                    <!-- Product Description -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-500"></i>
                            Deskripsi Produk <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea id="productDescription" name="product_description" rows="4" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Jelaskan detail produk, manfaat, dan keunggulannya"></textarea>
                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>

                    <!-- Product Price -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-blue-500"></i>
                            Harga <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" id="productPrice" name="product_price" required min="0" step="1000" class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="0">
                        </div>
                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>

                    <!-- WhatsApp Link -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                            Nomor WhatsApp
                        </label>
                        <input type="text" id="productWaLink" name="product_wa_link" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="08123456789">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor untuk order langsung via WhatsApp</p>
                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>

                    <!-- Pin Product -->
                    <div class="flex items-center">
                        <input type="checkbox" id="isPinned" name="is_pinned" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="isPinned" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-thumbtack mr-2 text-red-500"></i>
                            Jadikan produk unggulan (akan ditampilkan di atas)
                        </label>
                    </div>
                </div>

                <!-- Right Column - Image Upload -->
                <div class="space-y-6">
                    <!-- Product Image Upload -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-image mr-2 text-blue-500"></i>
                            Foto Produk
                        </label>

                        <!-- Current Image Display -->
                        <div id="currentImageContainer" class="hidden mb-4">
                            <div class="text-center">
                                <div class="w-48 h-48 mx-auto bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                                    <img id="currentImage" src="" alt="Current Product Image" class="w-full h-full object-cover">
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Foto saat ini</p>
                            </div>
                        </div>

                        <!-- Image Upload Area -->
                        <div class="image-upload-dropzone relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 cursor-pointer group" id="imageDropzone">
                            <input type="file" id="productImage" name="product_image" accept="image/*" class="hidden">

                            <div class="upload-placeholder" id="imageUploadPlaceholder">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors duration-300">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Upload foto produk
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG max 2MB</p>
                            </div>

                            <!-- Upload Progress -->
                            <div class="upload-progress hidden" id="imageUploadProgress">
                                <div class="w-16 h-16 mx-auto mb-4">
                                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Uploading...</p>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div class="image-preview-container hidden mt-4" id="imagePreviewContainer">
                            <div class="relative">
                                <img id="imagePreview" src="" alt="Product Preview" class="w-full h-48 object-cover rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <button type="button" class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200" onclick="removeImage()">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-sm text-green-700 dark:text-green-400 font-medium">Foto siap diupload</span>
                                </div>
                            </div>
                        </div>

                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="lg:col-span-2 flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" id="submitBtn" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Produk</span>
                    </button>
                    <button type="button" onclick="closeProductModal()" class="flex-1 sm:flex-none bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let isEditMode = false;
    let currentProductId = null;
    let searchTimeout;
    let isSearchActive = false;
    let originalProductsHTML = null;
    let hasSearched = false;
    let originalPageState = null;

    function buildUrl(action, id = null) {
        const baseUrl = '{{ url("user/products") }}';
        switch (action) {
            case 'index':
            case 'store':
                return baseUrl;
            case 'show':
            case 'update':
            case 'destroy':
                return `${baseUrl}/${id}`;
            case 'toggle-pin':
                return `${baseUrl}/${id}/toggle-pin`;
            case 'bulk-action':
                return `${baseUrl}/bulk-action`;
            case 'search':
                return `${baseUrl}/search`;
            case 'generate-whatsapp-order':
                return `${baseUrl}/${id}/generate-whatsapp-order`;
            default:
                return baseUrl;
        }
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function setupSearch() {
        const searchInputs = ['searchProducts', 'searchProductsMobile'];
        storeOriginalProducts();
        searchInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const query = e.target.value.trim();
                        if (query.length === 0) {
                            resetSearch();
                        } else if (query.length >= 2) {
                            searchProducts(query);
                        }
                    }, 500);
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        input.value = '';
                        resetSearch();
                    }
                });
                input.addEventListener('focus', () => {
                    const hint = document.getElementById('search-hint');
                    if (hint) hint.classList.remove('hidden');
                });
                input.addEventListener('blur', () => {
                    const hint = document.getElementById('search-hint');
                    if (hint) hint.classList.add('hidden');
                });
                input.addEventListener('input', (e) => {
                    syncSearchInputs(inputId, e.target.value);
                });
            }
        });
    }

    function syncSearchInputs(currentInputId, value) {
        const searchInputs = ['searchProducts', 'searchProductsMobile'];
        searchInputs.forEach(inputId => {
            if (inputId !== currentInputId) {
                const input = document.getElementById(inputId);
                if (input && input.value !== value) {
                    input.value = value;
                }
            }
        });
    }

    function storeOriginalProducts() {
        const container = document.getElementById('productsContainer');
        if (container && !originalProductsHTML) {
            originalProductsHTML = container.innerHTML;
            const pagination = document.querySelector('.mt-8');
            originalPageState = {
                productsHTML: originalProductsHTML,
                paginationHTML: pagination ? pagination.outerHTML : null
            };
        }
    }

    function searchProducts(query) {
        if (!hasSearched) {
            storeOriginalProducts();
            hasSearched = true;
        }
        showSearchLoading(true);
        const searchUrl = buildUrl('search') + '?q=' + encodeURIComponent(query);
        fetch(searchUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isSearchActive = true;
                    updateProductsDisplay(data.products, query);
                    showSearchResults(data.products.length, query);
                    hidePagination();
                    showToast(`Ditemukan ${data.products.length} produk`, 'success', 2000);
                } else {
                    showToast('Gagal melakukan pencarian', 'error');
                }
            })
            .catch(error => {
                showToast('Terjadi kesalahan saat pencarian', 'error');
            })
            .finally(() => {
                showSearchLoading(false);
            });
    }

    function updateProductsDisplay(products, query = '') {
        const container = document.getElementById('productsContainer');
        if (!container) return;
        if (products.length === 0) {
            container.innerHTML = getEmptySearchHTML(query);
            return;
        }
        let productsHTML = '';
        products.forEach(product => {
            productsHTML += generateProductCardHTML(product);
        });
        container.innerHTML = productsHTML;
    }

    function resetSearch() {
        const searchInputs = ['searchProducts', 'searchProductsMobile'];
        searchInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.value = '';
            }
        });
        isSearchActive = false;
        const searchInfo = document.getElementById('search-results-info');
        if (searchInfo) {
            searchInfo.remove();
        }
        const container = document.getElementById('productsContainer');
        if (container) {
            if (originalProductsHTML) {
                container.innerHTML = originalProductsHTML;
                restorePagination();
                showToast('Kembali ke semua produk', 'info', 2000);
            } else {
                window.location.reload();
            }
        }
    }

    function hidePagination() {
        const pagination = document.querySelector('.mt-8');
        if (pagination) {
            pagination.style.display = 'none';
        }
    }

    function restorePagination() {
        const pagination = document.querySelector('.mt-8');
        if (pagination) {
            pagination.style.display = 'block';
        }
    }

    function showSearchResults(count, query) {
        const existingInfo = document.getElementById('search-results-info');
        if (existingInfo) {
            existingInfo.remove();
        }
        const container = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-4');
        if (container) {
            const searchInfo = document.createElement('div');
            searchInfo.id = 'search-results-info';
            searchInfo.className = 'lg:col-span-4 mb-6';
            searchInfo.innerHTML = `
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-3 mb-3 sm:mb-0">
                        <i class="fas fa-search text-blue-600 dark:text-blue-400"></i>
                        <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Ditemukan ${count} produk untuk "${query}"
                        </span>
                    </div>
                    <button onclick="resetSearch()" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 font-medium">
                        <i class="fas fa-times mr-1"></i>
                        Hapus Pencarian
                    </button>
                </div>
            </div>
        `;
            container.insertBefore(searchInfo, container.firstChild);
        }
    }

    function getEmptySearchHTML(query) {
        return `
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Tidak ditemukan produk yang sesuai dengan pencarian "<strong>${query}</strong>"
                </p>
                <button onclick="resetSearch()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Semua Produk
                </button>
            </div>
        </div>
    `;
    }

    function generateProductCardHTML(product) {
        const imageUrl = product.product_image ? `{{ Storage::url('') }}${product.product_image}` : '';
        const pinnedBadge = product.is_pinned ? `<div class="absolute top-3 left-3"><span class="bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-medium flex items-center"><i class="fas fa-thumbtack mr-1"></i>Pinned</span></div>` : '';
        const waButton = product.product_wa_link ? `<button onclick="generateWhatsAppOrder('${product.id}')" class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200"><i class="fab fa-whatsapp"></i></button>` : '';
        return `
        <div class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-200 animate-slide-up" data-product-id="${product.id}">
            <div class="relative aspect-square bg-gray-100 dark:bg-gray-700 overflow-hidden">
                ${imageUrl ? `<img src="${imageUrl}" alt="${product.product_name}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-4xl text-gray-400"></i></div>`}
                ${pinnedBadge}
                <div class="absolute top-3 right-3"><input type="checkbox" class="product-checkbox w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="${product.id}" onchange="handleSelection()"></div>
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center"><div class="flex space-x-2"><button onclick="editProduct('${product.id}')" class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200"><i class="fas fa-edit"></i></button><button onclick="togglePin('${product.id}')" class="p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors duration-200"><i class="fas fa-thumbtack"></i></button>${waButton}</div></div>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">${product.product_name}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">${product.product_description}</p>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">${product.product_price ? formatCurrency(product.product_price) : 'Hubungi Kami'}</span>
                    <div class="flex space-x-1"><button onclick="editProduct('${product.id}')" class="p-2 text-gray-500 hover:text-blue-600 transition-colors duration-200"><i class="fas fa-edit"></i></button><button onclick="deleteProduct('${product.id}', '${product.product_name}')" class="p-2 text-gray-500 hover:text-red-600 transition-colors duration-200"><i class="fas fa-trash"></i></button></div>
                </div>
            </div>
        </div>`;
    }

    function showSearchLoading(show) {
        const searchInputs = ['searchProducts', 'searchProductsMobile'];
        searchInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                const parent = input.parentElement;
                const loader = parent.querySelector('.search-loader');
                if (show && !loader) {
                    const loaderElement = document.createElement('div');
                    loaderElement.className = 'search-loader absolute right-3 top-1/2 transform -translate-y-1/2';
                    loaderElement.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>';
                    parent.appendChild(loaderElement);
                } else if (!show && loader) {
                    loader.remove();
                }
            }
        });
    }

    function formatCurrency(amount) {
        if (!amount) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    function openProductModal(productData = null) {
        const modal = document.getElementById('productModal');
        const modalTitle = document.getElementById('modalTitle');
        const form = document.getElementById('productForm');
        form.reset();
        clearErrors();
        resetImageUpload();
        if (productData) {
            isEditMode = true;
            currentProductId = productData.id;
            modalTitle.textContent = 'Edit Produk';
            document.getElementById('productId').value = productData.id;
            document.getElementById('productName').value = productData.product_name;
            document.getElementById('productDescription').value = productData.product_description;
            document.getElementById('productPrice').value = productData.product_price;
            document.getElementById('productWaLink').value = productData.product_wa_link || '';
            document.getElementById('isPinned').checked = productData.is_pinned;
            if (productData.product_image) {
                showCurrentImage(productData.product_image);
            }
            showToast('Data produk dimuat', 'info', 2000);
        } else {
            isEditMode = false;
            currentProductId = null;
            modalTitle.textContent = 'Tambah Produk Baru';
        }
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            document.getElementById('productName').focus();
        }, 100);
    }

    function closeProductModal() {
        const modal = document.getElementById('productModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        isEditMode = false;
        currentProductId = null;
    }

    function editProduct(productId) {
        showToast('Memuat data produk...', 'info', 1000);
        fetch(buildUrl('show', productId), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    openProductModal(data.product);
                } else {
                    showToast(data.message || 'Gagal memuat data produk', 'error');
                }
            })
            .catch(error => {
                showToast('Terjadi kesalahan saat memuat data produk', 'error');
            });
    }

    function deleteProduct(productId, productName) {
        showConfirmation({
            title: 'Hapus Produk Ini?',
            text: `Produk "${productName}" akan dihapus secara permanen.`,
            icon: 'warning',
            confirmButtonText: 'Ya, Hapus!'
        }, () => {
            showToast('Menghapus produk...', 'info', 0);
            fetch(buildUrl('destroy', productId), {
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
                        showToast(data.message || 'Produk berhasil dihapus', 'success');
                        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
                        if (productCard) {
                            productCard.style.transform = 'scale(0.9)';
                            productCard.style.opacity = '0';
                            setTimeout(() => {
                                productCard.remove();
                                updateProductCount();
                            }, 300);
                        }
                    } else {
                        showToast(data.message || 'Gagal menghapus produk', 'error');
                    }
                })
                .catch(error => {
                    window.clearAllToasts();
                    showToast('Terjadi kesalahan saat menghapus produk', 'error');
                });
        });
    }

    function togglePin(productId) {
        showToast('Mengubah status pin...', 'info', 1000);
        fetch(buildUrl('toggle-pin', productId), {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Status pin berhasil diubah', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Gagal mengubah status pin', 'error');
                }
            })
            .catch(error => {
                showToast('Terjadi kesalahan saat mengubah status pin', 'error');
            });
    }

    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
        submitBtn.disabled = true;
        clearErrors();
        const formData = new FormData(this);
        const url = isEditMode ? buildUrl('update', currentProductId) : buildUrl('store');
        if (isEditMode) {
            formData.append('_method', 'PUT');
        }
        showToast('Menyimpan produk...', 'info', 0);
        fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                window.clearAllToasts();
                if (data.success) {
                    showToast(data.message || 'Produk berhasil disimpan', 'success');
                    closeProductModal();
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
                showToast('Terjadi kesalahan saat menyimpan produk', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            });
    });

    function initializeImageUpload() {
        const dropzone = document.getElementById('imageDropzone');
        const fileInput = document.getElementById('productImage');
        const placeholder = document.getElementById('imageUploadPlaceholder');
        const progress = document.getElementById('imageUploadProgress');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        if (!dropzone || !fileInput) return;
        dropzone.addEventListener('click', (e) => {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                handleImageUpload(file);
            }
        });
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
                    handleImageUpload(file);
                }
            }
        });
    }

    function validateImageFile(file) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const maxSize = 2 * 1024 * 1024;
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

    function handleImageUpload(file) {
        const placeholder = document.getElementById('imageUploadPlaceholder');
        const progress = document.getElementById('imageUploadProgress');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        const currentImageContainer = document.getElementById('currentImageContainer');
        currentImageContainer.classList.add('hidden');
        placeholder.classList.add('hidden');
        progress.classList.remove('hidden');
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            setTimeout(() => {
                progress.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                showToast('Foto siap untuk diupload!', 'success', 3000);
            }, 1000);
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        const fileInput = document.getElementById('productImage');
        const placeholder = document.getElementById('imageUploadPlaceholder');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const currentImageContainer = document.getElementById('currentImageContainer');
        fileInput.value = '';
        placeholder.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        if (isEditMode) {
            currentImageContainer.classList.remove('hidden');
        }
        showToast('Foto dihapus', 'info', 2000);
    }

    function showCurrentImage(imagePath) {
        const currentImageContainer = document.getElementById('currentImageContainer');
        const currentImage = document.getElementById('currentImage');
        currentImage.src = `{{ Storage::url('') }}${imagePath}`;
        currentImageContainer.classList.remove('hidden');
    }

    function resetImageUpload() {
        const fileInput = document.getElementById('productImage');
        const placeholder = document.getElementById('imageUploadPlaceholder');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const currentImageContainer = document.getElementById('currentImageContainer');
        fileInput.value = '';
        placeholder.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        currentImageContainer.classList.add('hidden');
    }

    function handleSelection() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        selectedCount.textContent = checkboxes.length;
        if (checkboxes.length > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        handleSelection();
        showToast('Pilihan dibatalkan', 'info', 2000);
    }

    function bulkAction(action) {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const productIds = Array.from(checkboxes).map(cb => cb.value);

        if (productIds.length === 0) {
            showToast('Silakan pilih produk terlebih dahulu', 'warning');
            return;
        }

        const options = {
            'delete': {
                title: 'Hapus Produk Terpilih?',
                text: `Anda akan menghapus ${productIds.length} produk secara permanen.`,
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus!'
            },
            'pin': {
                title: 'Pin Produk Terpilih?',
                text: `Anda akan menjadikan ${productIds.length} produk sebagai unggulan.`,
                icon: 'question',
                confirmButtonText: 'Ya, Pin!'
            },
            'unpin': {
                title: 'Unpin Produk Terpilih?',
                text: `Anda akan menghapus status unggulan dari ${productIds.length} produk.`,
                icon: 'question',
                confirmButtonText: 'Ya, Unpin!'
            }
        };

        showConfirmation(options[action], () => {
            showToast('Memproses aksi...', 'info', 0);
            fetch(buildUrl('bulk-action'), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        action: action,
                        product_ids: productIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    window.clearAllToasts();
                    if (data.success) {
                        showToast(data.message || 'Aksi berhasil dilakukan', 'success');
                        clearSelection();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Gagal melakukan aksi', 'error');
                    }
                })
                .catch(error => {
                    window.clearAllToasts();
                    showToast('Terjadi kesalahan saat melakukan aksi', 'error');
                });
        });
    }

    function generateWhatsAppOrder(productId) {
        showToast('Membuat link WhatsApp...', 'info', 2000);
        fetch(buildUrl('generate-whatsapp-order', productId), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Mengarahkan ke WhatsApp...', 'success', 2000);
                    window.open(data.whatsapp_link, '_blank');
                } else {
                    showToast(data.message || 'Gagal generate link WhatsApp', 'error');
                }
            })
            .catch(error => {
                showToast('Terjadi kesalahan saat generate link WhatsApp', 'error');
            });
    }

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
        showToast('Periksa kembali form Anda', 'warning');
    }

    function updateProductCount() {
        const productCards = document.querySelectorAll('.product-card');
        const count = productCards.length;
        if (count === 0) {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        initializeImageUpload();
        setupSearch();
        const productCards = document.querySelectorAll('.product-card');
        if (productCards.length === 0) {
            showToast('Tambahkan produk pertama Anda untuk memulai', 'info', 5000);
        } else if (productCards.length >= 5 && !document.querySelector('.bg-red-500')) {
            showToast('Tips: Pin produk terbaik Anda agar tampil di atas', 'info', 5000, {
                title: 'Tips'
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    #productModal {
        backdrop-filter: blur(4px);
    }

    #productModal>div>div {
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

    .loading {
        pointer-events: none;
        opacity: 0.7;
    }

    input:focus,
    textarea:focus {
        transform: translateY(-1px);
    }

    #bulkActions {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 640px) {
        .product-card {
            margin-bottom: 1rem;
        }
    }

    #productModal .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    #productModal .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    #productModal .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pin-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }
</style>
@endpush
