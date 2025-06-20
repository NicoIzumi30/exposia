@extends('user.layouts.app')

@section('title', 'Publikasi & Link Website')

@section('page-title', 'Publikasi & Link Website')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6 animate-fade-in">
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Publikasi & Link Website
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 transition-colors duration-300">
            Atur publikasi dan URL website bisnis Anda
        </p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        <!-- Left Section - Status & URL (3/4 width) -->
        <div class="xl:col-span-3 space-y-6">

            <!-- Status Website Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-globe text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Website</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status publikasi website Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Status Display -->
                        <div class="lg:col-span-2">
                            <div class="p-4 rounded-lg {{ $business->isPublished() ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600' }}">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 rounded-full {{ $business->isPublished() ? 'bg-green-100 dark:bg-green-800' : 'bg-gray-200 dark:bg-gray-600' }} flex items-center justify-center mr-3 mt-0.5">
                                        <i class="fas {{ $business->isPublished() ? 'fa-check text-green-600 dark:text-green-400' : 'fa-clock text-gray-500 dark:text-gray-400' }} text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold {{ $business->isPublished() ? 'text-green-800 dark:text-green-300' : 'text-gray-700 dark:text-gray-300' }} text-lg">
                                            {{ $business->isPublished() ? 'Aktif' : 'Draft' }}
                                        </h3>
                                        <p class="text-sm {{ $business->isPublished() ? 'text-green-700 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }} mt-1">
                                            {{ $business->isPublished() 
                                                ? 'Website dapat diakses publik' 
                                                : 'Website dalam mode draft' }}
                                        </p>

                                        <!-- Additional Info -->
                                        <div class="mt-3 space-y-1">
                                            @if($business->isReadyToPublish() || $business->isPublished())
                                            @if($business->isPublished())
                                            <div class="text-xs font-medium text-green-600 dark:text-green-400">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Dipublikasikan: {{ $business->updated_at->format('d M Y H:i') }}
                                            </div>
                                            @else
                                            <div class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Siap untuk dipublikasikan
                                            </div>
                                            @endif
                                            @else
                                            <div class="flex items-center text-yellow-600 dark:text-yellow-400 text-xs font-medium">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                <span>Lengkapi profil minimal 80% (saat ini: {{ $business->progress_completion }}%)</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="lg:col-span-1 flex items-center">
                            <form id="toggleStatusForm" action="{{ route('user.publish.toggle-status') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="publish_status" value="{{ $business->isPublished() ? '0' : '1' }}">
                                <button type="submit" {{ !$business->isReadyToPublish() && !$business->isPublished() ? 'disabled' : '' }} class="w-full px-6 py-3 rounded-lg font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200
                                    {{ $business->isPublished() 
                                        ? 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 focus:ring-gray-500' 
                                        : 'bg-green-500 hover:bg-green-600 text-white focus:ring-green-500' }}
                                    {{ !$business->isReadyToPublish() && !$business->isPublished() ? 'opacity-50 cursor-not-allowed' : 'transform hover:scale-[1.02]' }}">
                                    <i class="fas {{ $business->isPublished() ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                    {{ $business->isPublished() ? 'Nonaktifkan' : 'Publikasikan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- URL Website Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">URL Website</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Atur alamat website Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Current URL Display -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">URL Aktif:</label>
                            @if($business->public_url)
                            <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ $business->public_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-medium text-sm break-all">
                                            {{ $business->public_url }}
                                        </a>
                                    </div>
                                    @if($business->isPublished())
                                    <a href="{{ $business->public_url }}" target="_blank" class="ml-3 p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex-shrink-0">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-2"></i>
                                    <p class="text-yellow-700 dark:text-yellow-300 text-sm">URL website belum diatur</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Update URL Form -->
                        <div>
                            <form id="updateUrlForm" action="{{ route('user.publish.update-url') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="url_slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        Atur URL Website
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                            {{ url('/') }}/
                                        </span>
                                        <input type="text" name="url_slug" id="url_slug" value="{{ $business->public_url ? basename(parse_url($business->public_url, PHP_URL_PATH)) : '' }}" class="flex-1 min-w-0 block px-4 py-2.5 rounded-r-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 @error('url_slug') border-red-500 dark:border-red-500 @enderror" placeholder="nama-usaha-anda">
                                    </div>
                                    @error('url_slug')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Hanya huruf kecil, angka, dan tanda hubung (-)
                                    </p>
                                </div>
                                <button type="submit" class="w-full px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transform hover:scale-[1.02] transition-all duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan URL
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up sticky top-6">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-qrcode text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">QR Code</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Akses cepat website</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    @if($business->public_url)
                    <div class="space-y-4">
                        <div class="w-full aspect-square bg-white border border-gray-200 dark:border-gray-700 rounded-lg p-3 shadow-sm">
                            {{-- Arahkan src ke rute baru yang kita buat --}}
                            <img src="{{ route('user.publish.display-qr') }}" alt="QR Code" class="w-full h-full object-contain">
                        </div>

                        <p class="text-gray-700 dark:text-gray-300 text-sm text-center">
                            Scan untuk akses langsung ke website bisnis Anda
                        </p>

                        <div class="space-y-2">
                            <a href="{{ route('user.publish.download-qr') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-lg shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transform hover:scale-[1.02] transition-all duration-200">
                                <i class="fas fa-download mr-2"></i>
                                Download QR
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-20 h-20 mx-auto bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-qrcode text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            QR Code akan dibuat otomatis setelah URL website diatur
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleStatusForm = document.getElementById('toggleStatusForm');
    if (toggleStatusForm) {
        toggleStatusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const button = this.querySelector('button[type="submit"]');
            submitForm(this, button);
        });
    }

    const updateUrlForm = document.getElementById('updateUrlForm');
    if (updateUrlForm) {
        updateUrlForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            const button = this.querySelector('button[type="submit"]');
            submitForm(this, button);
        });
    }

    const regenerateQrForm = document.getElementById('regenerateQrForm');
    if (regenerateQrForm) {
        regenerateQrForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const button = this.querySelector('button[type="submit"]');
            submitForm(this, button, 'Sedang membuat ulang QR Code...');
        });
    }
});

function submitForm(form, button, loadingMessage = null) {
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<div class="loading-spinner mr-2"></div>${loadingMessage ? 'Memproses...' : ''}`;
    
    if(loadingMessage) {
        showToast(loadingMessage, 'info', 0);
    }

    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw data;
        }
        return data;
    })
    .then(data => {
        if(loadingMessage) window.clearAllToasts();
        showToast(data.message, 'success');
        setTimeout(() => window.location.reload(), 1000);
    })
    .catch(error => {
        if(loadingMessage) window.clearAllToasts();
        const errorMessage = error.message || 'Terjadi kesalahan, silakan coba lagi.';
        showToast(errorMessage, 'error');
        if (error.errors) {
            displayErrors(error.errors);
        }
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

function clearErrors() {
    const errorMsg = document.querySelector('#updateUrlForm .error-message');
    if (errorMsg) errorMsg.remove();
    const input = document.getElementById('url_slug');
    if (input) {
        input.classList.remove('border-red-500', 'dark:border-red-500');
    }
}

function displayErrors(errors) {
    if (errors.url_slug) {
        const input = document.getElementById('url_slug');
        input.classList.add('border-red-500', 'dark:border-red-500');
        const errorElement = document.createElement('p');
        errorElement.className = 'error-message mt-1 text-sm text-red-600 dark:text-red-400';
        errorElement.textContent = errors.url_slug[0];
        const container = input.closest('div');
        if (container) {
             container.insertAdjacentElement('afterend', errorElement);
        }
    }
}
</script>
@endpush