@extends('user.layouts.templates')

{{-- Color Variants --}}
@section('color-accent', $colorPalette['accent'] ?? '#F59E0B')
@section('color-primary', $colorPalette['primary'] ?? '#3B82F6')
@section('color-highlight', $colorPalette['highlight'] ?? '#8B5CF6')
@section('color-secondary', $colorPalette['secondary'] ?? '#64748B')

@section('title', 'Preview Web')

@section('page-title', 'Preview Web')



@section('content')
    {{-- NAVBAR --}}
    @if (isset($sectionVariants['navbar']))
        @includeIf('user.components.navbar-' . strtolower($sectionVariants['navbar']), [
            'data' => $navbarData,
        ])
    @endif

    {{-- HERO --}}
    @if (isset($sectionVariants['hero']))
        @includeIf('user.components.hero-' . strtolower($sectionVariants['hero']), ['data' => $heroData])
    @endif

    {{-- ABOUT --}}
    @if (isset($sectionVariants['about']))
        @includeIf('user.components.about-' . strtolower($sectionVariants['about']), ['data' => $aboutData])
    @endif

    {{-- PRODUCT --}}
    @if (isset($sectionVariants['produk']))
        @includeIf('user.components.product-' . strtolower($sectionVariants['produk']), [
            'data' => $productData,
        ])
    @endif

    {{-- GALLERY --}}
    @if (isset($sectionVariants['galeri']))
        @includeIf('user.components.gallery-' . strtolower($sectionVariants['galeri']), [
            'data' => $galleryData,
        ])
    @endif

    {{-- TESTIMONIAL --}}
    @if (isset($sectionVariants['testimoni']))
        @includeIf('user.components.testimonial-' . strtolower($sectionVariants['testimoni']), [
            'data' => $testimonialData,
        ])
    @endif

    {{-- CONTACT --}}
    @if (isset($sectionVariants['kontak']))
        @includeIf('user.components.contact-' . strtolower($sectionVariants['kontak']), [
            'data' => $contactData,
        ])
    @endif

    {{-- FOOTER --}}
    @includeIf('user.components.footer-a', ['data' => $footerData])


    <!-- Floating Button -->
    <div class="fixed bottom-6 left-6 z-30">
        <div class="rounded-full bg-white/60 px-3 py-1 text-xs font-medium text-gray-600 backdrop-blur-sm">
            Powered by Exposia
        </div>
    </div>

    @php
        $rawNumber = auth()->user()->phone;

        // Convert leading 0 to 62
        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $rawNumber));
    @endphp
       <div id="aiChatWidget" class="fixed bottom-7 right-6 z-40">
        <!-- Chat Toggle Button -->
        <button id="chatToggleBtn" class="group flex h-14 w-14 items-center justify-center rounded-full shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-black/20 lg:h-16 lg:w-16" aria-label="Chat dengan AI Customer Service" title="Chat dengan AI - {{ $business->business_name }}" style="background:white">
            <i id="chatIcon" class="fas fa-comments text-xl lg:text-2xl transition-transform duration-300 group-hover:scale-110" aria-hidden="true"></i>
        </button>

        <!-- Enhanced Chat Interface -->
        <div id="chatInterface" class="hidden absolute bottom-25 right-0 w-80  bg-white rounded-2xl shadow-2xl border border-gray-200">

            <!-- Enhanced Chat Header -->
            <div class="chat-header text-white relative">
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center space-x-3">
                        @if($business->logo_url)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $business->logo_url) }}" alt="{{ $business->business_name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/30 shadow-md" loading="lazy">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                        </div>
                        @else
                        <div class="ai-avatar w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-lg"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-base truncate">AI Customer Service</h3>
                            <p class="text-sm opacity-90 truncate">{{ $business->business_name }}</p>
                        </div>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center space-x-2">
                        <button onclick="window.chatWidget?.clearHistory()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" title="Hapus riwayat chat" aria-label="Hapus riwayat chat">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                        <button id="chatCloseBtn" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Tutup chat">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Chat Messages Container -->
            <div id="chatMessages" class="flex-1 h-64 lg:h-96 overflow-y-auto space-y-4 relative">
                <!-- Welcome Message Container -->
                <div class="message-container opacity-0 animate-fade-in">
                    <div class="flex items-start space-x-3">
                        <div class="ai-avatar w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white text-xs"></i>
                        </div>
                        <div class="ai-message p-4 shadow-sm max-w-xs lg:max-w-sm">
                            <p class="text-sm text-gray-800" id="welcomeMessage">
                                <span class="inline-block animate-pulse">Memuat...</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Chat Input Section -->
            <div class="chat-input-section">
                <!-- Quick Action Buttons -->
                <div id="quickActions" class="mb-4 flex flex-wrap gap-2">
                    <!-- Populated by JavaScript -->
                </div>

                <!-- Input Container -->
                <div class="chat-input-container">
                    <textarea id="chatInput" placeholder="Ketik pesan Anda..." class="flex-1 border-0 resize-none max-h-20 scrollbar-hide" maxlength="1000" rows="1" autocomplete="off" autocorrect="off" spellcheck="false"></textarea>

                    <button id="sendBtn" class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/20" title="Kirim pesan" aria-label="Kirim pesan" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Enhanced Error Toast -->
        <div id="chatError" class="hidden absolute bottom-18 right-0 p-4 rounded-xl shadow-2xl max-w-xs z-50">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium" id="chatErrorMessage"></p>
                    <button onclick="document.getElementById('chatError').classList.add('hidden')" class="mt-2 text-xs opacity-75 hover:opacity-100 transition-opacity">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- END-Floating Button -->

    <!-- ===== CHAT WIDGET STYLES ===== -->
    <link rel="stylesheet" href="{{ asset('css/chat-widget.css') }}">

    <!-- ===== CUSTOM STYLES ===== -->
    <style>
        /* ===== CORE LAYOUT STYLES ===== */
        .modal-overlay {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(226, 232, 240, 0.6);
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ===== INTERACTION EFFECTS ===== */
        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .hover-gallery {
            position: relative;
            overflow: hidden;
        }

        .hover-gallery-image {
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .hover-gallery:hover .hover-gallery-image {
            transform: scale(1.1);
            filter: brightness(0.8);
        }

        /* Enhanced focus states */
        .chat-input-container:focus-within {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Smooth transitions */
        * {
            scroll-behavior: smooth;
        }

        /* Loading states */
        .loading-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        /* Message animations */
        .message-enter {
            animation: messageSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes messageSlideIn {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Enhanced hover effects */
        .quick-action-btn:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #000;
        }

        .user-message:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .ai-message:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Status indicators */
        .status-online {
            background: linear-gradient(45deg, #10B981, #059669);
            box-shadow: 0 0 0 2px white, 0 0 8px rgba(16, 185, 129, 0.5);
        }

        /* Custom tooltip */
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 4px;
        }

        /* Mobile responsiveness for chat widget */
        @media (max-width: 639px) {
            #aiChatWidget {
                bottom: 6rem;
                right: 1rem;
                left: 1rem;
            }

            #chatToggleBtn {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                z-index: 50;
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* High contrast mode */
        @media (prefers-contrast: high) {
            .user-message,
            .ai-message,
            .quick-action-btn {
                border: 2px solid #000;
            }

            #chatToggleBtn {
                border: 3px solid #fff;
            }
        }

        /* Print styles */
        @media print {
            #aiChatWidget {
                display: none !important;
            }
        }
    </style>

    <script>
        window.AppConfig = {
            businessSlug: '{{ $businessSlug ?? basename(parse_url($business->public_url, PHP_URL_PATH)) }}'
            , csrfToken: '{{ csrf_token() }}'
            , whatsappNumber: '{{ preg_replace("/^0/", "62", preg_replace("/[^0-9]/", "", $business->user->phone ?? "")) }}'
            , businessName: '{{ $business->business_name }}'
            , apiEndpoints: {
                chatInfo: '/api/chat/{{ $businessSlug ?? basename(parse_url($business->public_url, PHP_URL_PATH)) }}/info'
                , chatSend: '/api/chat/{{ $businessSlug ?? basename(parse_url($business->public_url, PHP_URL_PATH)) }}'
                , chatClear: '/api/chat/{{ $businessSlug ?? basename(parse_url($business->public_url, PHP_URL_PATH)) }}/history'
            }
            , debug: {{ config('app.debug') ? 'true' : 'false' }}
        };

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 100,
                    easing: 'ease-out-cubic'
                });
            }

            // Initialize existing components
            initializeExistingComponents();
        });

        // ===== EXISTING COMPONENT FUNCTIONS =====
        function initializeExistingComponents() {
            initializeNavbar();
            initializeProductModals();
            initializeTestimonials();
            initializeProductScroll();
        }

        // ===== NAVBAR FUNCTIONS =====
        function initializeNavbar() {
            window.toggleMenu = function() {
                const menu = document.getElementById("mobileMenu");
                const burger = document.getElementById("burger");

                if (menu && burger) {
                    menu.classList.toggle("hidden");
                    burger.classList.toggle("rotate-90");
                }
            };

            window.toggleMenuC = function() {
                const menu = document.getElementById("mobileMenu");
                const line1 = document.getElementById("line1");
                const line2 = document.getElementById("line2");

                if (menu && line1 && line2) {
                    menu.classList.toggle("hidden");

                    line1.classList.toggle("bg-black");
                    line2.classList.toggle("bg-black");
                    line1.classList.toggle("bg-white");
                    line2.classList.toggle("bg-white");

                    line1.classList.toggle("rotate-45");
                    line1.classList.toggle("top-2");
                    line1.classList.toggle("top-3.5");

                    line2.classList.toggle("-rotate-45");
                    line2.classList.toggle("bottom-2");
                    line2.classList.toggle("bottom-3.5");
                }
            };

            // Navbar B initialization
            const initializeNavbarB = () => {
                const menuBtn = document.getElementById("mobileMenuBtn");
                const closeBtn = document.getElementById("mobileMenuCloseBtn");
                const menu = document.getElementById("mobileDropdown");

                if (menuBtn && closeBtn && menu) {
                    menuBtn.addEventListener("click", () => {
                        menu.classList.remove("hidden");
                        menuBtn.classList.add("hidden");
                        closeBtn.classList.remove("hidden");
                    });

                    closeBtn.addEventListener("click", () => {
                        menu.classList.add("hidden");
                        closeBtn.classList.add("hidden");
                        menuBtn.classList.remove("hidden");
                    });
                }
            };

            initializeNavbarB();
        }

        // ===== PRODUCT MODAL FUNCTIONS =====
        function initializeProductModals() {
            window.openProductModal = function(title, description, img, price) {
                const modal = document.getElementById("productModal");
                const modalImage = document.getElementById("productModalImage");
                const modalTitle = document.getElementById("productModalTitle");
                const modalPrice = document.getElementById("productModalPrice");
                const modalDescription = document.getElementById("productModalDescription");

                if (modal && modalImage && modalTitle && modalPrice && modalDescription) {
                    modalImage.src = img;
                    modalImage.alt = title;
                    modalTitle.textContent = title;
                    modalPrice.textContent = `Rp${Number(price).toLocaleString('id-ID')}`;
                    modalDescription.textContent = description;

                    modal.classList.remove("hidden");
                    document.body.style.overflow = "hidden";
                }
            };

            window.closeProductModal = function() {
                const modal = document.getElementById("productModal");
                if (modal) {
                    modal.classList.add("hidden");
                    document.body.style.overflow = "auto";
                }
            };

            window.orderNow = function() {
                const productTitle = document.getElementById("productModalTitle")?.textContent;
                const productPrice = document.getElementById("productModalPrice")?.textContent;

                if (productTitle && productPrice && window.AppConfig.whatsappNumber) {
                    const message = `Halo! Saya ingin memesan:\n\n${productTitle}\n${productPrice}\n\nMohon info lebih lanjut. Terima kasih!`;
                    window.open(`https://wa.me/${window.AppConfig.whatsappNumber}?text=${encodeURIComponent(message)}`, "_blank");
                    closeProductModal();
                }
            };

            // Close modal on escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeProductModal();
                }
            });

            // Close modal on backdrop click
            document.addEventListener('click', function(event) {
                const modal = document.getElementById("productModal");
                if (event.target === modal) {
                    closeProductModal();
                }
            });
        }

        // ===== PRODUCT SCROLL FUNCTIONS =====
        function initializeProductScroll() {
            window.scrollProductLeft = function() {
                const scrollContainer = document.getElementById("productScroll");
                if (scrollContainer) {
                    const card = scrollContainer.querySelector(".snap-start");
                    if (card) {
                        const scrollAmount = card.offsetWidth + parseInt(getComputedStyle(card).marginRight || 0);
                        scrollContainer.scrollBy({
                            left: -scrollAmount,
                            behavior: "smooth"
                        });
                    }
                }
            };

            window.scrollProductRight = function() {
                const scrollContainer = document.getElementById("productScroll");
                if (scrollContainer) {
                    const card = scrollContainer.querySelector(".snap-start");
                    if (card) {
                        const scrollAmount = card.offsetWidth + parseInt(getComputedStyle(card).marginRight || 0);
                        scrollContainer.scrollBy({
                            left: scrollAmount,
                            behavior: "smooth"
                        });
                    }
                }
            };
        }

        // ===== TESTIMONIAL FUNCTIONS =====
        function initializeTestimonials() {
            window.selectTestimonial = function(index, text, name) {
                const testimonialText = document.getElementById("testimonialText");
                const testimonialName = document.getElementById("testimonialName");

                if (testimonialText && testimonialName) {
                    testimonialText.textContent = text;
                    testimonialName.textContent = name;

                    const images = document.querySelectorAll(".testimonial-img");
                    images.forEach((img, i) => {
                        if (i === index) {
                            img.classList.remove("opacity-70", "h-20", "w-20");
                            img.classList.add("h-24", "w-24");
                        } else {
                            img.classList.add("opacity-70");
                            img.classList.remove("h-24", "w-24");
                            img.classList.add("h-20", "w-20");
                        }
                    });
                }
            };
        }

        // ===== ERROR HANDLING =====
        window.addEventListener('error', function(event) {
            console.error('Global error:', event.error);
        });

        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
        });
    </script>

    <!-- ===== CHAT WIDGET SCRIPT ===== -->
    <script src="{{ asset('js/chat-widget.js') }}" defer></script>

@endsection
