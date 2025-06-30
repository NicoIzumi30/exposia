@extends('user.layouts.app')

@section('title', 'Template & Tampilan')

@section('page-title', 'Template & Tampilan')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
        <div>
            <h1
                class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
                Template & Tampilan
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
                Pilih template dan atur tampilan website Anda
            </p>
        </div>
        <div class="flex items-center space-x-3 mt-4 sm:mt-0">
            <!-- Progress Completion Badge -->
            <div
                class="flex items-center space-x-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="w-3 h-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full"></div>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $business->calculateTemplateCompletion() }}% Template
                </span>
            </div>
            <button onclick="previewWebsite()"
                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 flex items-center space-x-2">
                <i class="fas fa-eye"></i>
                <span>Preview Website</span>
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Template Selection & Customization -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Template Selection -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-palette text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pilih Template</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pilih desain yang sesuai dengan bisnis Anda</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($templates as $template)
                        <div class="template-card group border-2 rounded-xl p-4 cursor-pointer transition-all duration-300 {{ $businessTemplate && $businessTemplate->template_id == $template->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600' }}"
                            data-template-id="{{ $template->id }}"
                            data-default-style="{{ $loop->iteration <= 3 ? chr(64 + $loop->iteration) : 'A' }}">

                            <div class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg mb-3 overflow-hidden">
                                <img src="{{ $template->getThumbnailUrl() }}" alt="{{ $template->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <h3 class="font-medium text-gray-900 dark:text-white mb-1">{{ $template->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $template->description }}</p>

                            @if ($businessTemplate && $businessTemplate->template_id == $template->id)
                                <div class="flex items-center text-blue-600 dark:text-blue-400">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="text-sm font-medium">Template Aktif</span>
                                </div>
                            @else
                                <div
                                    class="flex items-center text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    <i class="fas fa-mouse-pointer mr-2"></i>
                                    <span class="text-sm">Klik untuk pilih</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Color Customization -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-paint-brush text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Palet Warna</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sesuaikan warna sesuai brand Anda</p>
                    </div>
                </div>

                <!-- Color Palette Selection - Improved Responsive Design -->
                <div class="space-y-6 sm:space-y-4">
                    <!-- Warna Aksen -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300 sm:w-32">
                            <i class="fas fa-circle mr-2 text-yellow-500"></i>
                            Warna Aksen
                        </label>
                        <div class="flex items-center gap-3 flex-1">
                            <input type="color" id="accent-color"
                                value="{{ $businessTemplate ? $businessTemplate->getAccentColor() : '#012c4e' }}"
                                class="h-12 w-12 sm:w-16 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                            <input type="text" id="accent-color-text"
                                value="{{ $businessTemplate ? $businessTemplate->getAccentColor() : '#012c4e' }}"
                                class="flex-1 w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200">
                        </div>
                    </div>

                    <!-- Warna Utama -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300 sm:w-32">
                            <i class="fas fa-circle mr-2 text-blue-500"></i>
                            Background
                        </label>
                        <div class="flex items-center gap-3 flex-1">
                            <input type="color" id="primary-color"
                                value="{{ $businessTemplate ? $businessTemplate->getPrimaryColor() : '#EAEFEF' }}"
                                class="h-12 w-12 sm:w-16 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                            <input type="text" id="primary-color-text"
                                value="{{ $businessTemplate ? $businessTemplate->getPrimaryColor() : '#EAEFEF' }}"
                                class="flex-1 w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200">
                        </div>
                    </div>

                    <!-- Warna Sekunder -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300 sm:w-32">
                            <i class="fas fa-circle mr-2 text-gray-500"></i>
                            Background Gelap
                        </label>
                        <div class="flex items-center gap-3 flex-1">
                            <input type="color" id="secondary-color"
                                value="{{ $businessTemplate ? $businessTemplate->getSecondaryColor() : '#D9D9D9' }}"
                                class="h-12 w-12 sm:w-16 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                            <input type="text" id="secondary-color-text"
                                value="{{ $businessTemplate ? $businessTemplate->getSecondaryColor() : '#D9D9D9' }}"
                                class="flex-1 w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200">
                        </div>
                    </div>

                    <!-- Warna Highlight (Baru) -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-300 sm:w-32">
                            <i class="fas fa-circle mr-2 text-purple-500"></i>
                            Background Terang
                        </label>
                        <div class="flex items-center gap-3 flex-1">
                            <input type="color" id="highlight-color"
                                value="{{ $businessTemplate ? $businessTemplate->getHighlightColor() : '#FFFFFF' }}"
                                class="h-12 w-12 sm:w-16 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer">
                            <input type="text" id="highlight-color-text"
                                value="{{ $businessTemplate ? $businessTemplate->getHighlightColor() : '#FFFFFF' }}"
                                class="flex-1 w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200">
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button onclick="updateColors()"
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Warna</span>
                    </button>
                </div>
            </div>

            <!-- Section Configuration with Style Variants -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
                <div class="flex items-center mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Konfigurasi Section</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pilih section dan style untuk setiap bagian
                            website</p>
                    </div>
                </div>

                <div class="space-y-6">
                    @foreach ($availableSections as $sectionKey => $sectionConfig)
                        @php
                            $currentSection = $activeSections[$sectionKey] ?? null;
                            $isActive = $currentSection ? $currentSection->is_active : false;
                            $currentVariant = $currentSection ? $currentSection->style_variant : 'A';
                        @endphp

                        <div
                            class="section-config-item border-2 border-gray-200 dark:border-gray-700 rounded-xl p-5 {{ $isActive ? 'bg-blue-50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800' : '' }} transition-all duration-300">
                            <!-- Section Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center mr-3">
                                        <i
                                            class="fas fa-{{ getSectionIcon($sectionKey) }} text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $sectionConfig['name'] }}</h3>
                                        @if ($isActive && $currentVariant)
                                            <p class="text-sm text-blue-600 dark:text-blue-400">
                                                {{ $sectionConfig['variants'][$currentVariant] ?? 'Default' }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Toggle Switch -->
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer section-toggle"
                                        data-section="{{ $sectionKey }}" {{ $isActive ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>

                            <!-- Style Variants Selection -->
                            <div class="style-variants {{ $isActive ? '' : 'hidden' }}"
                                id="variants-{{ $sectionKey }}">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <i class="fas fa-palette mr-2"></i>Pilih Style:
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    @foreach ($sectionConfig['variants'] as $variantKey => $variantName)
                                        <div class="variant-option cursor-pointer border-2 rounded-lg p-3 transition-all duration-200 {{ $currentVariant == $variantKey ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600' }}"
                                            data-section="{{ $sectionKey }}" data-variant="{{ $variantKey }}"
                                            onclick="updateSectionStyle('{{ $sectionKey }}', '{{ $variantKey }}')">

                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-gray-900 dark:text-white">Style
                                                    {{ $variantKey }}</span>
                                                @if ($currentVariant == $variantKey)
                                                    <i class="fas fa-check-circle text-blue-500"></i>
                                                @endif
                                            </div>

                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $variantName }}</p>

                                            <!-- Style Preview Button -->
                                            <div class="mt-2">
                                                <button type="button"
                                                    class="preview-button w-full h-12 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded border flex items-center justify-center hover:from-blue-50 hover:to-blue-100 dark:hover:from-blue-900/20 dark:hover:to-blue-800/20 transition-colors duration-200"
                                                    data-section="{{ $sectionKey }}"
                                                    data-variant="{{ $variantKey }}" data-name="{{ $variantName }}"
                                                    onclick="showPreviewModal(event, '{{ $sectionKey }}', '{{ $variantKey }}', '{{ $variantName }}')">
                                                    <i class="fas fa-eye mr-2 text-gray-500 dark:text-gray-400"></i>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Preview</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column - Hero Image & Actions -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Hero Image Upload -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up sticky">
                <div class="flex items-center mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-image text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gambar Hero</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Gambar utama website</p>
                    </div>
                </div>

                <!-- Current Hero Image Display -->
                @if ($business->hero_image_url)
                    <div class="current-hero mb-6">
                        <div class="text-center">
                            <div
                                class="aspect-video bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                                <img src="{{ asset('storage/' . $business->hero_image_url) }}" alt="Current Hero"
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Gambar hero saat ini</p>
                        </div>
                    </div>
                @endif

                <!-- Hero Image Upload Area -->
                <div class="hero-upload-dropzone relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 cursor-pointer group"
                    id="hero-dropzone">
                    <input type="file" id="hero-image" accept="image/*" class="hidden">

                    <div class="upload-placeholder" id="hero-upload-placeholder">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors duration-300">
                            <i
                                class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                            {{ $business->hero_image_url ? 'Ganti gambar hero' : 'Upload gambar hero' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG max 2MB</p>
                    </div>

                    <!-- Upload Progress -->
                    <div class="upload-progress hidden" id="hero-upload-progress">
                        <div class="w-16 h-16 mx-auto mb-4">
                            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500"></div>
                        </div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Uploading...</p>
                    </div>
                </div>
                <!-- Secondary Hero Image Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up mt-6">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-500 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-images text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gambar Hero Kedua</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Gambar pendukung untuk hero section</p>
                        </div>
                    </div>

                    <!-- Current Secondary Hero Image Display -->
                    @if ($business->hero_image_secondary_url)
                        <div class="current-secondary-hero mb-6">
                            <div class="text-center">
                                <div
                                    class="aspect-video bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                                    <img src="{{ Storage::url($business->hero_image_secondary_url) }}"
                                        alt="Current Secondary Hero" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-4 flex justify-center space-x-3">
                                    <button type="button"
                                        onclick="document.getElementById('hero-image-secondary').click()"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-edit mr-2"></i>Ganti Foto
                                    </button>
                                    <button type="button" onclick="removeSecondaryHeroImage()"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash mr-2"></i>Hapus Foto
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Gambar hero kedua saat ini</p>
                            </div>
                        </div>
                    @endif

                    <!-- Secondary Hero Image Upload Area -->
                    <div class="secondary-hero-upload-dropzone relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-purple-500 dark:hover:border-purple-400 transition-all duration-300 cursor-pointer group {{ $business->hero_image_secondary_url ? 'hidden' : '' }}"
                        id="hero-secondary-dropzone">
                        <input type="file" id="hero-image-secondary" accept="image/*" class="hidden">

                        <div class="upload-placeholder" id="hero-secondary-upload-placeholder">
                            <div
                                class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-purple-50 dark:group-hover:bg-purple-900/20 transition-colors duration-300">
                                <i
                                    class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors duration-300"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                {{ $business->hero_image_secondary_url ? 'Ganti gambar hero kedua' : 'Upload gambar hero kedua' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG max 2MB</p>
                        </div>

                        <!-- Upload Progress -->
                        <div class="upload-progress hidden" id="hero-secondary-upload-progress">
                            <div class="w-16 h-16 mx-auto mb-4">
                                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-purple-500"></div>
                            </div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Uploading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col gap-4 animate-slide-up">
                <a href="{{ route('user.dashboard') }}"
                    class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-center flex items-center justify-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="preview-modal"
        class="fixed inset-0 z-50 hidden overflow-x-hidden bg-black bg-opacity-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
        <div
            class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-3xl w-full mx-auto transform scale-95 transition-all duration-300 overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="preview-modal-title">Preview Style
                </h3>
                <button type="button"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none"
                    onclick="closePreviewModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="p-6">
                <div class="max-h-96 overflow-y-auto bg-gray-100 dark:bg-gray-700 rounded-lg mb-4">
                    <img id="preview-modal-image" src="" alt="Style Preview"
                        class="w-full object-contain mx-auto" />
                </div>

                <p class="text-gray-600 dark:text-gray-400 text-sm">(scrollable)</p>
                <p id="preview-modal-description" class="text-gray-600 dark:text-gray-400 text-sm"></p>
            </div>
            <!-- Modal Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button type="button"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                    onclick="closePreviewModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initializeTemplateSelection();
                initializeColorPickers();
                initializeHeroImageUpload();
                initializeSecondaryHeroImageUpload();
                initializeSectionToggles();
            });

            function initializeTemplateSelection() {
                document.querySelectorAll('.template-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const templateId = this.dataset.templateId;
                        updateTemplate(templateId);
                    });
                });
            }

            function initializeColorPickers() {
                ['primary', 'secondary', 'accent', 'highlight'].forEach(type => { // Tambahkan 'highlight'
                    const colorInput = document.getElementById(`${type}-color`);
                    const textInput = document.getElementById(`${type}-color-text`);
                    if (!colorInput || !textInput) return;
                    colorInput.addEventListener('input', function() {
                        textInput.value = this.value;
                    });
                    textInput.addEventListener('input', function() {
                        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                            colorInput.value = this.value;
                        }
                    });
                });
            }

            function initializeHeroImageUpload() {
                const dropzone = document.getElementById('hero-dropzone');
                const fileInput = document.getElementById('hero-image');
                if (!dropzone || !fileInput) return;
                dropzone.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', function() {
                    if (this.files[0]) {
                        handleHeroImageUpload(this.files[0]);
                    }
                });
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                });
                dropzone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                });
                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleHeroImageUpload(files[0]);
                    }
                });
            }

            function initializeSectionToggles() {
                document.querySelectorAll('.section-toggle').forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const section = this.dataset.section;
                        const isChecked = this.checked;
                        toggleSection(section);
                        const variantsDiv = document.getElementById(`variants-${section}`);
                        const sectionItem = this.closest('.section-config-item');
                        if (isChecked) {
                            variantsDiv.classList.remove('hidden');
                            sectionItem.classList.add('bg-blue-50', 'dark:bg-blue-900/10', 'border-blue-200',
                                'dark:border-blue-800');
                            sectionItem.classList.remove('border-gray-200', 'dark:border-gray-700');
                        } else {
                            variantsDiv.classList.add('hidden');
                            sectionItem.classList.remove('bg-blue-50', 'dark:bg-blue-900/10', 'border-blue-200',
                                'dark:border-blue-800');
                            sectionItem.classList.add('border-gray-200', 'dark:border-gray-700');
                        }
                    });
                });
            }

            function updateTemplate(templateId) {
                const templateCard = document.querySelector(`[data-template-id="${templateId}"]`);
                const defaultStyle = templateCard.dataset.defaultStyle || 'A';
                showToast('Memperbarui template...', 'info', 0);
                fetch('{{ route('user.templates.update-template') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            template_id: templateId,
                            default_style: defaultStyle
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(() => {
                        showToast('Terjadi kesalahan saat memperbarui template.', 'error');
                        window.clearAllToasts();
                    });
            }

            function updateColors() {
                const colors = {
                    primary: document.getElementById('primary-color').value,
                    secondary: document.getElementById('secondary-color').value,
                    accent: document.getElementById('accent-color').value,
                    highlight: document.getElementById('highlight-color').value // Tambahkan highlight color
                };
                showToast('Menyimpan palet warna...', 'info');
                fetch('{{ route('user.templates.update-colors') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(colors)
                    })
                    .then(response => response.json())
                    .then(data => {
                        showToast(data.message, data.success ? 'success' : 'error');
                    })
                    .catch(() => showToast('Terjadi kesalahan saat menyimpan warna.', 'error'));
            }

            function handleHeroImageUpload(file) {
                const placeholder = document.getElementById('hero-upload-placeholder');
                const progress = document.getElementById('hero-upload-progress');

                if (!file.type.startsWith('image/')) {
                    showToast('File harus berupa gambar (JPG, PNG, WEBP)', 'error');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showToast('Ukuran file maksimal adalah 2MB', 'error');
                    return;
                }

                placeholder.classList.add('hidden');
                progress.classList.remove('hidden');

                const formData = new FormData();
                formData.append('hero_image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch('{{ route('user.templates.update-hero') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengupload, respons server tidak valid.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        progress.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        if (data.success) {
                            showToast(data.message, 'success');
                            if (data.image_url) {
                                let currentHero = document.querySelector('.current-hero');
                                if (!currentHero) {
                                    const heroUploadContainer = document.getElementById('hero-dropzone').parentElement;
                                    currentHero = document.createElement('div');
                                    currentHero.className = 'current-hero mb-6';
                                    currentHero.innerHTML = `
                        <div class="text-center">
                            <div class="aspect-video bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                                <img src="${data.image_url}" alt="Current Hero" class="w-full h-full object-cover">
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Gambar hero saat ini</p>
                        </div>
                    `;
                                    heroUploadContainer.insertBefore(currentHero, document.getElementById('hero-dropzone'));
                                } else {
                                    const img = currentHero.querySelector('img');
                                    if (img) img.src = data.image_url;
                                }
                            }
                            const uploadText = document.querySelector('#hero-upload-placeholder p.text-sm.font-medium');
                            if (uploadText) uploadText.textContent = 'Ganti gambar hero';
                        } else {
                            showToast(data.message || 'Terjadi kesalahan saat mengupload gambar.', 'error');
                        }
                    })
                    .catch((error) => {
                        progress.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        showToast('Gagal mengupload gambar. Periksa koneksi Anda.', 'error');
                    });
            }

            function toggleSection(section) {
                showToast('Mengubah status section...', 'info');
                fetch('{{ route('user.templates.toggle-section') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            section: section
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        showToast(data.message, data.success ? 'success' : 'error');
                    })
                    .catch(() => showToast('Terjadi kesalahan saat mengubah status section.', 'error'));
            }

            function updateSectionStyle(section, styleVariant) {
                document.querySelectorAll(`[data-section="${section}"].variant-option`).forEach(option => {
                    option.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    option.classList.add('border-gray-200', 'dark:border-gray-600');
                    const checkIcon = option.querySelector('.fas.fa-check-circle');
                    if (checkIcon) checkIcon.remove();
                });

                const selectedOption = document.querySelector(`[data-section="${section}"][data-variant="${styleVariant}"]`);
                if (selectedOption) {
                    selectedOption.classList.remove('border-gray-200', 'dark:border-gray-600');
                    selectedOption.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    const titleDiv = selectedOption.querySelector('.flex.items-center.justify-between');
                    if (titleDiv && !titleDiv.querySelector('.fa-check-circle')) {
                        const checkIcon = document.createElement('i');
                        checkIcon.className = 'fas fa-check-circle text-blue-500';
                        titleDiv.appendChild(checkIcon);
                    }
                }

                showToast('Memperbarui style...', 'info');
                fetch('{{ route('user.templates.update-section-style') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            section: section,
                            style_variant: styleVariant
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            const sectionItem = document.querySelector(`[data-section="${section}"].section-toggle`)
                                .closest('.section-config-item');
                            const descriptionP = sectionItem.querySelector('h3 + p');
                            if (descriptionP && data.display_name) {
                                const variantName = data.display_name.split(' - ')[1] || 'Default';
                                descriptionP.textContent = variantName;
                            }
                        } else {
                            showToast(data.message, 'error');
                            setTimeout(() => location.reload(), 1500);
                        }
                    })
                    .catch(() => {
                        showToast('Terjadi kesalahan saat mengubah style.', 'error');
                        setTimeout(() => location.reload(), 1500);
                    });
            }

            const sectionPreviews = {
                'navbar': {
                    'A': '/img/sections/navbar-a.png',
                    'B': '/img/sections/navbar-b.png',
                    'C': '/img/sections/navbar-c.png'
                },
                'hero': {
                    'A': '/img/sections/hero-a.png',
                    'B': '/img/sections/hero-b.png',
                    'C': '/img/sections/hero-c.png'
                },
                'about': {
                    'A': '/img/sections/about-a.png',
                    'B': '/img/sections/about-b.png',
                    'C': '/img/sections/about-c.png'
                },
                'branches': {
                    'A': '/img/sections/branches-a.png',
                    'B': '/img/sections/branches-b.png',
                    'C': '/img/sections/branches-c.png'
                },
                'produk': {
                    'A': '/img/sections/produk-a.png',
                    'B': '/img/sections/produk-b.png',
                    'C': '/img/sections/produk-c.png'
                },
                'galeri': {
                    'A': '/img/sections/galeri-a.png',
                    'B': '/img/sections/galeri-b.png',
                    'C': '/img/sections/galeri-c.png'
                },
                'testimoni': {
                    'A': '/img/sections/testimoni-a.png',
                    'B': '/img/sections/testimoni-b.png',
                    'C': '/img/sections/testimoni-c.png'
                },
                'footer': {
                    'A': '/img/sections/footer-a.png',
                    'B': '/img/sections/footer-b.png',
                    'C': '/img/sections/footer-c.png'
                }
            };

            function initializeSecondaryHeroImageUpload() {
                const dropzone = document.getElementById('hero-secondary-dropzone');
                const fileInput = document.getElementById('hero-image-secondary');
                if (!dropzone || !fileInput) return;
                dropzone.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', function() {
                    if (this.files[0]) {
                        handleSecondaryHeroImageUpload(this.files[0]);
                    }
                });
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
                });
                dropzone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
                });
                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleSecondaryHeroImageUpload(files[0]);
                    }
                });
            }

            function handleSecondaryHeroImageUpload(file) {
                const placeholder = document.getElementById('hero-secondary-upload-placeholder');
                const progress = document.getElementById('hero-secondary-upload-progress');

                if (!file.type.startsWith('image/')) {
                    showToast('File harus berupa gambar (JPG, PNG, WEBP)', 'error');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showToast('Ukuran file maksimal adalah 2MB', 'error');
                    return;
                }

                placeholder.classList.add('hidden');
                progress.classList.remove('hidden');

                const formData = new FormData();
                formData.append('hero_image_secondary', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch('{{ route('user.templates.update-secondary-hero') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengupload, respons server tidak valid.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        progress.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        if (data.success) {
                            showToast(data.message, 'success');
                            if (data.image_url) {
                                let currentSecondaryHero = document.querySelector('.current-secondary-hero');
                                if (!currentSecondaryHero) {
                                    const heroSecondaryUploadContainer = document.getElementById('hero-secondary-dropzone')
                                        .parentElement;
                                    currentSecondaryHero = document.createElement('div');
                                    currentSecondaryHero.className = 'current-secondary-hero mb-6';
                                    currentSecondaryHero.innerHTML = `
                            <div class="text-center">
                                <div class="aspect-video bg-white dark:bg-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden shadow-lg">
                                    <img src="${data.image_url}" alt="Current Secondary Hero" class="w-full h-full object-cover">
                                </div>
                                <div class="mt-4 flex justify-center space-x-3">
                                    <button type="button" onclick="document.getElementById('hero-image-secondary').click()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-edit mr-2"></i>Ganti Foto
                                    </button>
                                    <button type="button" onclick="removeSecondaryHeroImage()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash mr-2"></i>Hapus Foto
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Gambar hero kedua saat ini</p>
                            </div>
                        `;
                                    heroSecondaryUploadContainer.insertBefore(currentSecondaryHero, document.getElementById(
                                        'hero-secondary-dropzone'));
                                    document.getElementById('hero-secondary-dropzone').classList.add('hidden');
                                } else {
                                    const img = currentSecondaryHero.querySelector('img');
                                    if (img) img.src = data.image_url;
                                }
                            }
                            const uploadText = document.querySelector(
                                '#hero-secondary-upload-placeholder p.text-sm.font-medium');
                            if (uploadText) uploadText.textContent = 'Ganti gambar hero kedua';
                        } else {
                            showToast(data.message || 'Terjadi kesalahan saat mengupload gambar.', 'error');
                        }
                    })
                    .catch((error) => {
                        progress.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        showToast('Gagal mengupload gambar. Periksa koneksi Anda.', 'error');
                        console.error(error);
                    });
            }

            function removeSecondaryHeroImage() {
                showConfirmation({
                    title: 'Hapus Gambar Hero Kedua?',
                    text: 'Gambar hero kedua akan dihapus secara permanen.',
                    icon: 'warning',
                    confirmButtonText: 'Ya, Hapus!'
                }, () => {
                    showToast('Menghapus gambar...', 'info', 0);
                    fetch('{{ route('user.templates.remove-secondary-hero') }}', {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            window.clearAllToasts();
                            if (data.success) {
                                showToast(data.message, 'success');
                                const currentSecondaryHero = document.querySelector('.current-secondary-hero');
                                if (currentSecondaryHero) {
                                    currentSecondaryHero.remove();
                                }
                                document.getElementById('hero-secondary-dropzone').classList.remove('hidden');
                            } else {
                                showToast(data.message || 'Gagal menghapus gambar', 'error');
                            }
                        })
                        .catch(error => {
                            window.clearAllToasts();
                            showToast('Gagal terhubung ke server saat menghapus gambar.', 'error');
                        });
                });
            }

            function showPreviewModal(event, section, variant, variantName) {
                event.stopPropagation();
                const modal = document.getElementById('preview-modal');
                const modalTitle = document.getElementById('preview-modal-title');
                const modalImage = document.getElementById('preview-modal-image');
                const modalDescription = document.getElementById('preview-modal-description');
                const sectionName = document.querySelector(`.section-config-item [data-section="${section}"]`).closest(
                    '.section-config-item').querySelector('h3').textContent;

                modalTitle.textContent = `${sectionName} - Style ${variant}`;
                modalDescription.textContent = variantName;
                const imagePath = sectionPreviews[section]?.[variant] || '/images/sections/placeholder.jpg';
                modalImage.src = imagePath;
                modalImage.alt = `${sectionName} Style ${variant}`;
                modalImage.classList.add('opacity-50');
                modalImage.onload = () => modalImage.classList.remove('opacity-50');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.querySelector('.max-w-3xl').classList.remove('scale-95');
                    modal.querySelector('.max-w-3xl').classList.add('scale-100');
                }, 10);
                modal.addEventListener('click', (e) => e.target === modal && closePreviewModal());
                document.addEventListener('keydown', handleEscapeKey);
                document.body.classList.add('overflow-hidden');
            }

            function handleEscapeKey(e) {
                if (e.key === 'Escape') {
                    closePreviewModal();
                }
            }

            function closePreviewModal() {
                const modal = document.getElementById('preview-modal');
                modal.classList.add('opacity-0');
                modal.querySelector('.max-w-3xl').classList.remove('scale-100');
                modal.querySelector('.max-w-3xl').classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
                document.removeEventListener('keydown', handleEscapeKey);
                document.body.classList.remove('overflow-hidden');
            }

            function previewWebsite() {
                window.open('{{ route('user.templates.preview') }}', '_blank');
            }
        </script>
    @endpush

    @php
        function getSectionIcon($section)
        {
            return match ($section) {
                'navbar' => 'bars',
                'hero' => 'star',
                'about' => 'info-circle',
                'branches' => 'map-marker-alt',
                'produk' => 'box',
                'galeri' => 'images',
                'testimoni' => 'quote-left',
                'kontak' => 'address-card',
                'footer' => 'grip-lines',
                default => 'circle',
            };
        }
    @endphp
@endsection
