@extends('user.layouts.app')

@section('title', 'AI Konten Generator')

@section('page-title', 'AI Konten Generator')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            AI Konten Generator
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
            Buat deskripsi profesional, headline menarik, dan konten SEO-friendly dengan bantuan AI
        </p>
    </div>
</div>

<!-- Tabs Container -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex">
            <button id="tab-business-description" class="tab-button active py-4 px-6 text-sm font-medium border-b-2 border-transparent hover:border-blue-500 focus:outline-none">
                Deskripsi Bisnis
            </button>
            <button id="tab-product-description" class="tab-button py-4 px-6 text-sm font-medium border-b-2 border-transparent hover:border-blue-500 focus:outline-none">
                Deskripsi Produk
            </button>
            <button id="tab-headline" class="tab-button py-4 px-6 text-sm font-medium border-b-2 border-transparent hover:border-blue-500 focus:outline-none">
                Headline & Tagline
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="p-6 space-y-6">
        <!-- Business Description Tab -->
        <div id="content-business-description" class="tab-content active">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Input Form -->
                <div class="space-y-6 animate-slide-up">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Bisnis</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan detail untuk membuat deskripsi</p>
                        </div>
                    </div>

                    <form id="business-description-form" class="space-y-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-store mr-2 text-blue-500 dark:text-blue-400"></i>
                                Nama Bisnis <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="business_name" value="{{ $business->business_name ?? '' }}" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Batik Nusantara">
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tags mr-2 text-blue-500 dark:text-blue-400"></i>
                                Jenis Bisnis <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="business_type" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Kerajinan Batik / Katering Makanan">
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500 dark:text-blue-400"></i>
                                Lokasi <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="location" value="Yogyakarta" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Yogyakarta / Jakarta Selatan">
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-shopping-bag mr-2 text-blue-500 dark:text-blue-400"></i>
                                Produk/Layanan Utama <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="main_products" rows="2" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Batik cap tradisional, Batik tulis premium, Kain batik meteran"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-award mr-2 text-blue-500 dark:text-blue-400"></i>
                                Keunggulan Bisnis <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="strengths" rows="2" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Menggunakan pewarna alami, Motif eksklusif, Pengalaman 15 tahun"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-users mr-2 text-blue-500 dark:text-blue-400"></i>
                                Target Pasar <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="target_market" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Pecinta batik, Kolektor, Wisatawan domestik">
                        </div>

                        <div class="flex items-center mt-2 mb-4">
                            <input type="checkbox" id="use-structured-format-business" name="use_structured_format" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="use-structured-format-business" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Gunakan format terstruktur (JSON)
                            </label>
                        </div>

                        <div class="pt-4">
                            <button type="submit" id="generate-business-description-btn" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-robot"></i>
                                <span>Buat Deskripsi dengan AI</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Result Area -->
                <div class="space-y-6 animate-slide-up">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hasil Deskripsi</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Deskripsi yang dihasilkan oleh AI</p>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="business-description-loading" class="hidden">
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="w-16 h-16 border-4 border-t-blue-500 border-r-transparent border-b-blue-500 border-l-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-gray-600 dark:text-gray-400 text-center">
                                Sedang membuat deskripsi bisnis Anda...<br>
                                <span class="text-sm">Mohon tunggu beberapa saat</span>
                            </p>
                        </div>
                    </div>

                    <!-- Result Content -->
                    <div id="business-description-result" class="hidden space-y-6">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-quote-left mr-2 text-blue-500 dark:text-blue-400"></i>
                                Deskripsi Singkat <span class="text-xs text-gray-500 ml-2">(Meta Description)</span>
                            </label>
                            <div class="relative">
                                <textarea id="short-description-result" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"></textarea>
                                <button onclick="copyToClipboard('short-description-result')" class="absolute top-2 right-2 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ideal untuk SEO dan preview di Google</p>
                                <span class="text-xs text-gray-400" id="short-desc-count">0/160</span>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-file-alt mr-2 text-blue-500 dark:text-blue-400"></i>
                                Deskripsi Lengkap <span class="text-xs text-gray-500 ml-2">(About Us)</span>
                            </label>
                            <div class="relative">
                                <textarea id="full-description-result" rows="10" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"></textarea>
                                <button onclick="copyToClipboard('full-description-result')" class="absolute top-2 right-2 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <button id="save-business-description-btn" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save"></i>
                                <span>Simpan ke Profil Bisnis</span>
                            </button>
                            <button id="regenerate-business-description-btn" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Initial State -->
                    <div id="business-description-initial" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Buat Deskripsi Bisnis dengan AI</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Isi informasi bisnis dan klik tombol "Buat Deskripsi dengan AI"
                            untuk membuat deskripsi profesional secara otomatis.
                        </p>
                        <div class="text-xs text-gray-500 dark:text-gray-500 flex items-center justify-center">
                            <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                            <span>Tips: Semakin detail informasi yang dimasukkan, semakin baik hasilnya</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description Tab -->
        <div id="content-product-description" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Input Form -->
                <div class="space-y-6 animate-slide-up">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Produk</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan detail untuk membuat deskripsi produk</p>
                        </div>
                    </div>

                    <form id="product-description-form" class="space-y-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500 dark:text-blue-400"></i>
                                Pilih Produk <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="product_id" name="product_id" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->product_name }}" data-price="{{ format_currency($product->product_price) }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Info Display -->
                        <div id="product-info-display" class="hidden mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center">
                                <div class="mr-4 w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-blue-500 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white" id="product-name-display"></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" id="product-price-display"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields to store product data -->
                        <input type="hidden" name="product_name" id="product_name">
                        <input type="hidden" name="price" id="product_price">

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-layer-group mr-2 text-blue-500 dark:text-blue-400"></i>
                                Kategori <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="category" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Fashion / Kerajinan / Makanan">
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-list-ul mr-2 text-blue-500 dark:text-blue-400"></i>
                                Fitur Utama <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="features" rows="2" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Kain katun premium, Pewarna alami, Ukuran 2x1 meter"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-heart mr-2 text-blue-500 dark:text-blue-400"></i>
                                Manfaat Produk <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="benefits" rows="2" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Nyaman dipakai, Tahan lama, Eksklusif"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user-tag mr-2 text-blue-500 dark:text-blue-400"></i>
                                Target Pengguna <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="target_user" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Pria dewasa / Pecinta fashion / Kolektor">
                        </div>

                        <div class="flex items-center mt-2 mb-4">
                            <input type="checkbox" id="use-structured-format-product" name="use_structured_format" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="use-structured-format-product" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Gunakan format terstruktur (JSON)
                            </label>
                        </div>

                        <div class="pt-4">
                            <button type="submit" id="generate-product-description-btn" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-robot"></i>
                                <span>Buat Deskripsi Produk dengan AI</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Result Area -->
                <div class="space-y-6 animate-slide-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Deskripsi Produk</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Hasil dari AI Generator</p>
                            </div>
                        </div>

                        <!-- Product Selector for Saving -->
                        <div class="relative hidden">
                            <select id="product-selector" class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="product-description-loading" class="hidden">
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="w-16 h-16 border-4 border-t-blue-500 border-r-transparent border-b-blue-500 border-l-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-gray-600 dark:text-gray-400 text-center">
                                Sedang membuat deskripsi produk...<br>
                                <span class="text-sm">Mohon tunggu beberapa saat</span>
                            </p>
                        </div>
                    </div>

                    <!-- Result Content -->
                    <div id="product-description-result" class="hidden space-y-6">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-file-alt mr-2 text-blue-500 dark:text-blue-400"></i>
                                Deskripsi Produk <span class="text-xs text-gray-500 ml-2">(Untuk halaman produk)</span>
                            </label>
                            <div class="relative">
                                <textarea id="product-description-result-text" rows="12" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"></textarea>
                                <button onclick="copyToClipboard('product-description-result-text')" class="absolute top-2 right-2 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Key Features Section (for structured format) -->
                        <div id="product-key-features-section" class="hidden">
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-list mr-2 text-blue-500 dark:text-blue-400"></i>
                                Fitur Utama
                            </label>
                            <div id="product-key-features" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <!-- Key features will be inserted here -->
                            </div>
                        </div>

                        <!-- Meta Description (for structured format) -->
                        <div id="product-meta-description-section" class="hidden">
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-2 text-blue-500 dark:text-blue-400"></i>
                                Meta Description <span class="text-xs text-gray-500 ml-2">(Untuk SEO)</span>
                            </label>
                            <div class="relative">
                                <textarea id="product-meta-description" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"></textarea>
                                <button onclick="copyToClipboard('product-meta-description')" class="absolute top-2 right-2 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <button id="save-product-description-btn" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save"></i>
                                <span>Simpan ke Produk</span>
                            </button>
                            <button id="regenerate-product-description-btn" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Initial State -->
                    <div id="product-description-initial" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Buat Deskripsi Produk dengan AI</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Pilih produk yang sudah ada dan isi informasi tambahan untuk membuat deskripsi produk yang menarik dan persuasif.
                        </p>
                        <div class="text-xs text-gray-500 dark:text-gray-500 flex items-center justify-center">
                            <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                            <span>Tips: Sebutkan manfaat utama dan keunikan produk Anda</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Headline Tab -->
        <div id="content-headline" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Input Form -->
                <div class="space-y-6 animate-slide-up">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-heading text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi untuk Headline</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tambahan informasi untuk membuat headline menarik</p>
                        </div>
                    </div>

                    <!-- Info box showing that business data is being used -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-1">Headline dibuat berdasarkan data bisnis Anda</h4>
                                <p class="text-xs text-blue-600 dark:text-blue-300">
                                    Sistem akan menggunakan data bisnis yang sudah Anda isi di profil bisnis Anda, seperti nama bisnis, deskripsi singkat, dan deskripsi lengkap.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Business data summary box -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Data Bisnis yang Digunakan:</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-start">
                                <span class="text-gray-600 dark:text-gray-400 min-w-28">Nama Bisnis:</span>
                                <span class="text-gray-900 dark:text-white ml-2 font-medium">{{ $business->business_name ?? 'Belum diisi' }}</span>
                            </div>
                            @if($business && $business->short_description)
                            <div class="flex items-start">
                                <span class="text-gray-600 dark:text-gray-400 min-w-28">Deskripsi Singkat:</span>
                                <span class="text-gray-900 dark:text-white ml-2">{{ \Illuminate\Support\Str::limit($business->short_description, 50) }}</span>
                            </div>
                            @endif
                            @if($business && $business->full_description)
                            <div class="flex items-start">
                                <span class="text-gray-600 dark:text-gray-400 min-w-28">Deskripsi Lengkap:</span>
                                <span class="text-gray-900 dark:text-white ml-2">{{ \Illuminate\Support\Str::limit($business->full_description, 100) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <form id="headline-form" class="space-y-4">
                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tags mr-2 text-blue-500 dark:text-blue-400"></i>
                                Jenis Bisnis <span class="text-xs text-gray-500 ml-1">(Opsional)</span>
                            </label>
                            <input type="text" name="business_type" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Kerajinan Batik / Katering Makanan">
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-users mr-2 text-blue-500 dark:text-blue-400"></i>
                                Target Pasar <span class="text-xs text-gray-500 ml-1">(Opsional)</span>
                            </label>
                            <input type="text" name="target_market" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Pecinta batik, Kolektor, Wisatawan domestik">
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-heart mr-2 text-blue-500 dark:text-blue-400"></i>
                                Nilai Utama Bisnis <span class="text-xs text-gray-500 ml-1">(Opsional)</span>
                            </label>
                            <textarea name="core_values" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Kualitas premium, Pelayanan ramah, Keberlanjutan"></textarea>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-award mr-2 text-blue-500 dark:text-blue-400"></i>
                                Keunggulan Bisnis <span class="text-xs text-gray-500 ml-1">(Opsional)</span>
                            </label>
                            <textarea name="strengths" rows="2" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Menggunakan pewarna alami, Motif eksklusif, Pengalaman 15 tahun"></textarea>
                        </div>


                        <div class="pt-4">
                            <button type="submit" id="generate-headline-btn" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-robot"></i>
                                <span>Buat Headline dengan AI</span>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Result Area -->
                <div class="space-y-6 animate-slide-up">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-bullhorn text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hasil Headline</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Headline & tagline yang dihasilkan oleh AI</p>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="headline-loading" class="hidden">
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="w-16 h-16 border-4 border-t-blue-500 border-r-transparent border-b-blue-500 border-l-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-gray-600 dark:text-gray-400 text-center">
                                Sedang membuat headline menarik...<br>
                                <span class="text-sm">Mohon tunggu beberapa saat</span>
                            </p>
                        </div>
                    </div>

                    <!-- Result Content -->
                    <div id="headline-result" class="hidden space-y-6">
                        <div class="space-y-4">
                            <div id="headline-options">
                                <!-- Headline options will be inserted here by JavaScript -->
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <button id="regenerate-headline-btn" class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-sync-alt"></i>
                                <span>Buat Headline Baru</span>
                            </button>
                        </div>
                    </div>

                    <!-- Initial State -->
                    <div id="headline-initial" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-heading text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Buat Headline & Tagline dengan AI</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Isi informasi bisnis dan klik tombol "Buat Headline dengan AI"
                            untuk membuat headline dan tagline yang menarik dan mudah diingat.
                        </p>
                        <div class="text-xs text-gray-500 dark:text-gray-500 flex items-center justify-center">
                            <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                            <span>Tips: Headline yang baik menunjukkan nilai utama bisnis Anda</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Switching
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Reset all tabs
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('text-blue-600', 'dark:text-blue-400', 'border-blue-500');
                });

                tabContents.forEach(content => {
                    content.classList.remove('active');
                    content.classList.add('hidden');
                });

                // Activate selected tab
                button.classList.add('active');
                button.classList.add('text-blue-600', 'dark:text-blue-400', 'border-blue-500');

                const contentId = 'content-' + button.id.replace('tab-', '');
                const content = document.getElementById(contentId);
                content.classList.add('active');
                content.classList.remove('hidden');
            });
        });

        // Short Description Character Counter
        const shortDescResult = document.getElementById('short-description-result');
        const shortDescCount = document.getElementById('short-desc-count');

        if (shortDescResult && shortDescCount) {
            shortDescResult.addEventListener('input', updateCharCount);

            function updateCharCount() {
                const count = shortDescResult.value.length;
                shortDescCount.textContent = `${count}/160`;

                if (count > 160) {
                    shortDescCount.classList.add('text-red-500');
                } else {
                    shortDescCount.classList.remove('text-red-500');
                }
            }
        }

        // Business Description Form
        const businessDescForm = document.getElementById('business-description-form');
        if (businessDescForm) {
            businessDescForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                document.getElementById('business-description-initial').classList.add('hidden');
                document.getElementById('business-description-result').classList.add('hidden');
                document.getElementById('business-description-loading').classList.remove('hidden');

                // Get form data
                const formData = new FormData(businessDescForm);
                const data = Object.fromEntries(formData.entries());

                // Use JSON format for clean results
                data.format = 'json';

                // Send API request
                fetch('{{ route("user.ai-content.generate-business-description") }}', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide loading
                            document.getElementById('business-description-loading').classList.add('hidden');
                            document.getElementById('business-description-result').classList.remove('hidden');

                            // Tampilkan nilai ke dalam form
                            document.getElementById('short-description-result').value = data.content.short_description || '';
                            document.getElementById('full-description-result').value = data.content.full_description || '';

                            // Update character count
                            updateCharCount();

                            // Show success toast
                            showToast('Deskripsi bisnis berhasil dibuat!', 'success');
                        } else {
                            // Show error
                            document.getElementById('business-description-loading').classList.add('hidden');
                            document.getElementById('business-description-initial').classList.remove('hidden');

                            showToast(data.message || 'Gagal membuat deskripsi bisnis.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('business-description-loading').classList.add('hidden');
                        document.getElementById('business-description-initial').classList.remove('hidden');

                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    });
            });
        }

        // Save Business Description
        const saveBizDescBtn = document.getElementById('save-business-description-btn');
        if (saveBizDescBtn) {
            saveBizDescBtn.addEventListener('click', function() {
                const shortDesc = document.getElementById('short-description-result').value;
                const fullDesc = document.getElementById('full-description-result').value;

                if (!shortDesc || !fullDesc) {
                    showToast('Harap buat deskripsi terlebih dahulu.', 'warning');
                    return;
                }

                // Show loading state
                saveBizDescBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
                saveBizDescBtn.disabled = true;

                // Send API request
                fetch('{{ route("user.ai-content.save-business-description") }}', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify({
                            short_description: shortDesc
                            , full_description: fullDesc
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message || 'Deskripsi bisnis berhasil disimpan!', 'success');
                        } else {
                            showToast(data.message || 'Gagal menyimpan deskripsi bisnis.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    })
                    .finally(() => {
                        // Reset button
                        saveBizDescBtn.innerHTML = '<i class="fas fa-save"></i><span>Simpan ke Profil Bisnis</span>';
                        saveBizDescBtn.disabled = false;
                    });
            });
        }

        // Regenerate Business Description
        const regenerateBizDescBtn = document.getElementById('regenerate-business-description-btn');
        if (regenerateBizDescBtn) {
            regenerateBizDescBtn.addEventListener('click', function() {
                // Trigger form submission to regenerate
                businessDescForm.dispatchEvent(new Event('submit'));
            });
        }

        // Product selector change handler
        const productSelector = document.getElementById('product_id');
        if (productSelector) {
            productSelector.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                if (this.value) {
                    // Set hidden field values from data attributes
                    document.getElementById('product_name').value = selectedOption.dataset.name || '';
                    document.getElementById('product_price').value = selectedOption.dataset.price || '';

                    // Update the product info display
                    document.getElementById('product-name-display').textContent = selectedOption.dataset.name || '';
                    document.getElementById('product-price-display').textContent = selectedOption.dataset.price || '';

                    // Show the product info display
                    document.getElementById('product-info-display').classList.remove('hidden');
                } else {
                    // Hide the product info display if no product selected
                    document.getElementById('product-info-display').classList.add('hidden');
                }
            });
        }

        // Product Description Form
        const productDescForm = document.getElementById('product-description-form');
        if (productDescForm) {
            productDescForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Check if product is selected
                const productId = document.getElementById('product_id').value;
                if (!productId) {
                    showToast('Harap pilih produk terlebih dahulu.', 'warning');
                    return;
                }

                // Show loading state
                document.getElementById('product-description-initial').classList.add('hidden');
                document.getElementById('product-description-result').classList.add('hidden');
                document.getElementById('product-description-loading').classList.remove('hidden');

                // Get form data
                const formData = new FormData(productDescForm);
                const data = Object.fromEntries(formData.entries());

                // Check if structured format is requested
                const useStructuredFormat = document.getElementById('use-structured-format-product').checked;
                if (useStructuredFormat) {
                    data.format = 'json';
                }

                // Send API request
                fetch('{{ route("user.ai-content.generate-product-description") }}', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Store the product ID for saving later
                            document.getElementById('product-selector').value = data.product_id || '';

                            // Hide loading
                            document.getElementById('product-description-loading').classList.add('hidden');
                            document.getElementById('product-description-result').classList.remove('hidden');

                            // Hide structured sections by default
                            document.getElementById('product-key-features-section').classList.add('hidden');
                            document.getElementById('product-meta-description-section').classList.add('hidden');

                            if (useStructuredFormat && typeof data.content === 'object') {
                                // Structured format (JSON)
                                const content = data.content;

                                // Set main description
                                document.getElementById('product-description-result-text').value = content.product_description || '';

                                // Show and populate key features if available
                                if (content.key_features && Array.isArray(content.key_features) && content.key_features.length > 0) {
                                    const keyFeaturesSection = document.getElementById('product-key-features-section');
                                    const keyFeaturesContainer = document.getElementById('product-key-features');

                                    keyFeaturesSection.classList.remove('hidden');
                                    keyFeaturesContainer.innerHTML = '';

                                    const featuresList = document.createElement('ul');
                                    featuresList.className = 'list-disc pl-5 space-y-2';

                                    content.key_features.forEach(feature => {
                                        const listItem = document.createElement('li');
                                        listItem.className = 'text-gray-800 dark:text-gray-200';
                                        listItem.textContent = feature;
                                        featuresList.appendChild(listItem);
                                    });

                                    keyFeaturesContainer.appendChild(featuresList);
                                }

                                // Show and populate meta description if available
                                if (content.meta_description) {
                                    const metaDescSection = document.getElementById('product-meta-description-section');
                                    const metaDescTextarea = document.getElementById('product-meta-description');

                                    metaDescSection.classList.remove('hidden');
                                    metaDescTextarea.value = content.meta_description;
                                }
                            } else {
                                // Text format - use as is
                                document.getElementById('product-description-result-text').value = data.content.product_description || data.content;
                            }

                            // Show success toast
                            showToast('Deskripsi produk berhasil dibuat!', 'success');
                        } else {
                            // Show error
                            document.getElementById('product-description-loading').classList.add('hidden');
                            document.getElementById('product-description-initial').classList.remove('hidden');

                            showToast(data.message || 'Gagal membuat deskripsi produk.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('product-description-loading').classList.add('hidden');
                        document.getElementById('product-description-initial').classList.remove('hidden');

                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    });
            });
        }

        // Save Product Description
        const saveProductDescBtn = document.getElementById('save-product-description-btn');
        if (saveProductDescBtn) {
            saveProductDescBtn.addEventListener('click', function() {
                const productDesc = document.getElementById('product-description-result-text').value;
                const productId = document.getElementById('product_id').value;

                if (!productDesc) {
                    showToast('Harap buat deskripsi terlebih dahulu.', 'warning');
                    return;
                }

                if (!productId) {
                    showToast('Harap pilih produk untuk menyimpan deskripsi.', 'warning');
                    return;
                }

                // Show loading state
                saveProductDescBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
                saveProductDescBtn.disabled = true;

                // Send API request
                fetch('{{ route("user.ai-content.save-product-description") }}', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify({
                            product_id: productId
                            , product_description: productDesc
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message || 'Deskripsi produk berhasil disimpan!', 'success');
                        } else {
                            showToast(data.message || 'Gagal menyimpan deskripsi produk.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    })
                    .finally(() => {
                        // Reset button
                        saveProductDescBtn.innerHTML = '<i class="fas fa-save"></i><span>Simpan ke Produk</span>';
                        saveProductDescBtn.disabled = false;
                    });
            });
        }

        // Regenerate Product Description
        const regenerateProductDescBtn = document.getElementById('regenerate-product-description-btn');
        if (regenerateProductDescBtn) {
            regenerateProductDescBtn.addEventListener('click', function() {
                // Trigger form submission to regenerate
                productDescForm.dispatchEvent(new Event('submit'));
            });
        }

        // Headline Form
        const headlineForm = document.getElementById('headline-form');
        if (headlineForm) {
            headlineForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                document.getElementById('headline-initial').classList.add('hidden');
                document.getElementById('headline-result').classList.add('hidden');
                document.getElementById('headline-loading').classList.remove('hidden');

                // Get form data
                const formData = new FormData(headlineForm);
                const data = Object.fromEntries(formData.entries());

                

                // Send API request
                fetch('{{ route("user.ai-content.generate-headline") }}', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide loading
                            document.getElementById('headline-loading').classList.add('hidden');
                            document.getElementById('headline-result').classList.remove('hidden');

                            // Generate headline options
                            const headlineOptions = document.getElementById('headline-options');
                            headlineOptions.innerHTML = '';

                            if (typeof data.content === 'object' && data.content.headlines) {
                                // Structured format (JSON)
                                const headlines = data.content.headlines;

                                headlines.forEach((headline, index) => {
                                    const optionDiv = document.createElement('div');
                                    optionDiv.className = 'relative p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl hover:shadow-md transition-all duration-200';

                                    let styleTag = '';
                                    if (headline.style) {
                                        const styleColorClass = getStyleColorClass(headline.style);
                                        styleTag = `<span class="text-xs ${styleColorClass} ml-2 px-2 py-1 rounded-full bg-opacity-10 border border-current">${headline.style}</span>`;
                                    }

                                    optionDiv.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 mr-3">
                                            <span>${index + 1}</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">${headline.text || headline}</p>
                                        ${styleTag}
                                    </div>
                                    <button onclick="copyHeadline(this)" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            `;

                                    headlineOptions.appendChild(optionDiv);
                                });
                            } else {
                                // Process the text content
                                const content = data.content;

                                // Extract headlines (assuming they are numbered or in a list)
                                const headlines = content.split('\n')
                                    .filter(line => line.trim().length > 0)
                                    .filter(line => /^\d+[\.\)\-]|^\-|\*/.test(line.trim()))
                                    .map(line => line.replace(/^\d+[\.\)\-]|\-|\*/, '').trim());

                                headlines.forEach((headline, index) => {
                                    const optionDiv = document.createElement('div');
                                    optionDiv.className = 'relative p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl hover:shadow-md transition-all duration-200';

                                    optionDiv.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 mr-3">
                                            <span>${index + 1}</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium">${headline}</p>
                                    </div>
                                    <button onclick="copyHeadline(this)" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            `;

                                    headlineOptions.appendChild(optionDiv);
                                });
                            }

                            // Show success toast
                            showToast('Headline berhasil dibuat!', 'success');
                        } else {
                            // Show error
                            document.getElementById('headline-loading').classList.add('hidden');
                            document.getElementById('headline-initial').classList.remove('hidden');

                            showToast(data.message || 'Gagal membuat headline.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('headline-loading').classList.add('hidden');
                        document.getElementById('headline-initial').classList.remove('hidden');

                        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    });
            });
        }

        // Regenerate Headline
        const regenerateHeadlineBtn = document.getElementById('regenerate-headline-btn');
        if (regenerateHeadlineBtn) {
            regenerateHeadlineBtn.addEventListener('click', function() {
                // Trigger form submission to regenerate
                headlineForm.dispatchEvent(new Event('submit'));
            });
        }

        // Helper function to get style color class
        function getStyleColorClass(style) {
            style = style.toLowerCase();
            if (style.includes('profesional')) return 'text-blue-500 dark:text-blue-400 bg-blue-500';
            if (style.includes('catchy') || style.includes('menarik')) return 'text-purple-500 dark:text-purple-400 bg-purple-500';
            if (style.includes('motivasi') || style.includes('inspiratif')) return 'text-yellow-500 dark:text-yellow-400 bg-yellow-500';
            return 'text-gray-500 dark:text-gray-400 bg-gray-500';
        }
    });

    // Copy text to clipboard
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        document.execCommand('copy');
        showToast('Teks berhasil disalin!', 'success', 2000);
    }

    // Copy headline to clipboard
    function copyHeadline(button) {
        const headline = button.parentElement.querySelector('p').textContent;
        navigator.clipboard.writeText(headline);
        showToast('Headline berhasil disalin!', 'success', 2000);
    }

    // Toast Notification Function
    function showToast(message, type = 'info', duration = 5000) {
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
    /* Tab Styles */
    .tab-button.active {
        @apply text-blue-600 dark: text-blue-400 border-blue-500;
    }

    /* Copy button hover effect */
    .copy-btn:hover {
        @apply text-blue-600 dark: text-blue-400;
    }

    /* Headline options hover effect */
    .headline-option:hover {
        @apply shadow-md;
    }

</style>
@endpush
