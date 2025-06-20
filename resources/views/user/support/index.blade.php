@extends('user.layouts.app')

@section('title', 'Bantuan & Support')

@section('page-title', 'Bantuan & Support')

@section('content')
<div class=" mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6 animate-fade-in text-center lg:text-left">
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Bantuan & Support
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 transition-colors duration-300">
            Dapatkan bantuan untuk penggunaan platform
        </p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Left Section - Video Tutorial (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-play-circle text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Video Tutorial</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pelajari cara menggunakan platform secara lengkap</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <!-- Video Player -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden mb-4">
                        <div class="w-full h-full">
                            <!-- Replace VIDEO_ID with your actual YouTube video ID -->
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/VIDEO_ID" title="Tutorial Penggunaan Platform" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>

                    <!-- Video Info -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tutorial Lengkap Penggunaan Platform</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Video tutorial ini mencakup seluruh fitur dan cara penggunaan platform website UMKM dari awal hingga akhir. Pelajari cara mengelola profil bisnis, produk, galeri, testimoni, template, hingga publikasi website Anda.
                        </p>
                        
                        <!-- Tutorial Points -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Setup Profil Bisnis</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Manajemen Produk</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Kustomisasi Template</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Publikasi Website</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section - Support Info (1/3 width) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- WhatsApp Support Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fab fa-whatsapp text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tim Support</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bantuan langsung</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-headset text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Customer Support</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                Tim kami siap membantu Anda 24/7 untuk pertanyaan dan masalah teknis.
                            </p>
                        </div>

                        <!-- Support Features -->
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                <span>Respon cepat</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-user-tie text-green-500 mr-2"></i>
                                <span>Tim profesional</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-tools text-green-500 mr-2"></i>
                                <span>Bantuan teknis</span>
                            </div>
                        </div>

                        <!-- Contact Button -->
                        <a href="{{ $whatsapp_cs_link ?? 'https://wa.me/62859126462972?text=Halo,%20saya%20butuh%20bantuan%20terkait%20website%20UMKM%20saya.' }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transform hover:scale-[1.02] transition-all duration-200">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Chat via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Links</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Akses cepat</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('user.business.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <i class="fas fa-building text-blue-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Data Usaha</span>
                        </a>
                        <a href="{{ route('user.products.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <i class="fas fa-box text-green-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Produk</span>
                        </a>
                        <a href="{{ route('user.templates.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <i class="fas fa-paint-brush text-purple-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Template</span>
                        </a>
                        <a href="{{ route('user.publish.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <i class="fas fa-globe text-indigo-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Publikasi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section - Full Width -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-question-circle text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pertanyaan Umum</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Jawaban untuk pertanyaan yang sering diajukan</p>
                </div>
            </div>
        </div>

        <!-- Card Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- FAQ Item 1 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset dark:focus:ring-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-target="faq-1">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">Bagaimana cara mempublikasikan website saya?</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2"></i>
                    </button>
                    <div id="faq-1" class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Untuk mempublikasikan website, pastikan profil bisnis Anda telah terisi minimal 80%. Kemudian kunjungi menu "Publikasi & Link Website", atur URL yang diinginkan, dan klik tombol "Publikasikan".
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset dark:focus:ring-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-target="faq-2">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">Bagaimana cara mengubah template website?</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2"></i>
                    </button>
                    <div id="faq-2" class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Kunjungi menu "Template & Tampilan", pilih template yang diinginkan dari galeri template yang tersedia, lalu klik "Pilih Template" dan simpan perubahan.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset dark:focus:ring-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-target="faq-3">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">Bagaimana cara menggunakan AI Content Generator?</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2"></i>
                    </button>
                    <div id="faq-3" class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Kunjungi menu "AI Konten Generator", pilih jenis konten yang ingin dibuat (deskripsi bisnis, deskripsi produk, atau headline), isi informasi yang diminta, lalu klik "Generate". Hasil akan muncul dan dapat langsung Anda simpan.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset dark:focus:ring-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-target="faq-4">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">Bagaimana cara melihat statistik pengunjung website?</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2"></i>
                    </button>
                    <div id="faq-4" class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Statistik pengunjung dapat dilihat di halaman "Beranda" dashboard Anda. Di sana akan ditampilkan grafik kunjungan 7 hari terakhir dan total pengunjung website Anda.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset dark:focus:ring-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-target="faq-5">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">Bagaimana cara menambahkan produk baru?</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2"></i>
                    </button>
                    <div id="faq-5" class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Kunjungi menu "Produk", klik tombol "Tambah Produk", isi informasi produk seperti nama, deskripsi, harga, dan unggah gambar produk, lalu klik "Simpan".
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset dark:focus:ring-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-target="faq-6">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">Bagaimana cara backup data website saya?</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2"></i>
                    </button>
                    <div id="faq-6" class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Sistem kami secara otomatis melakukan backup data Anda secara berkala. Namun, Anda juga dapat mengekspor data melalui menu pengaturan akun untuk backup manual.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Aspect ratio container for video */
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%;
        /* 16:9 Aspect Ratio */
    }

    .aspect-w-16>div {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ toggle functionality
        const faqToggles = document.querySelectorAll('.faq-toggle');

        faqToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const content = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    content.classList.add('hidden');
                    icon.style.transform = 'rotate(0)';
                }
            });
        });
    });
</script>
@endpush
@endsection