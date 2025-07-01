<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $business->business_name }} - {{ $business->short_description ?? 'Solusi Terbaik untuk Kebutuhan Anda' }}</title>
    <meta name="description" content="{{ $business->short_description ?? $business->full_description ?? 'Temukan produk dan layanan terbaik dari ' . $business->business_name . '. Hubungi kami sekarang!' }}">
    <meta name="keywords" content="{{ $business->business_name }}, {{ strtolower(str_replace(' ', ', ', $business->business_name)) }}, bisnis, produk, layanan, {{ $business->main_address ? explode(',', $business->main_address)[0] : 'Indonesia' }}">
    <meta name="author" content="{{ $business->business_name }}">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $business->business_name }} - {{ $business->short_description ?? 'Solusi Terbaik untuk Kebutuhan Anda' }}">
    <meta property="og:description" content="{{ $business->short_description ?? $business->full_description ?? 'Temukan produk dan layanan terbaik dari ' . $business->business_name . '. Hubungi kami sekarang!' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $business->business_name }}">
    <meta property="og:image" content="{{ asset('storage/'.$business->logo_url) ?? asset('storage/'.$business->hero_image_url) ?? asset('img/default-og.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $business->business_name }} - Logo">
    <meta property="og:locale" content="id_ID">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $business->business_name }} - {{ $business->short_description ?? 'Solusi Terbaik untuk Kebutuhan Anda' }}">
    <meta name="twitter:description" content="{{ $business->short_description ?? $business->full_description ?? 'Temukan produk dan layanan terbaik dari ' . $business->business_name . '. Hubungi kami sekarang!' }}">
    <meta name="twitter:image" content="{{ asset('storage/'.$business->logo_url) ?? asset('storage/'.$business->hero_image_url) ?? asset('img/default-og.jpg') }}">
    <meta name="twitter:image:alt" content="{{ $business->business_name }} - Logo">
    
    @if(asset('storage/'.$business->logo_url))
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/'.$business->logo_url) }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/'.$business->logo_url) }}">
    @endif
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "{{ $business->business_name }}",
        "description": "{{ $business->short_description ?? $business->full_description ?? 'Bisnis terpercaya yang menyediakan produk dan layanan berkualitas' }}",
        @if(asset('storage/'.$business->logo_url))
        "logo": "{{ asset('storage/'.$business->logo_url) }}",
        "image": "{{ asset('storage/'.$business->logo_url) }}",
        @endif
        @if($business->main_address)
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $business->main_address }}"
        },
        @endif
        @if($business->user->phone)
        "telephone": "{{ $business->user->phone }}",
        @endif
        "url": "{{ url()->current() }}",
        @if($business->main_operational_hours)
        "openingHours": "{{ $business->main_operational_hours }}",
        @endif
        "priceRange": "$",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "reviewCount": "50"
        }
    }
    </script>

    <!-- External Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
    
    <meta name="theme-color" content="{{ $colorPalette['accent'] ?? '#F59E0B' }}">
    <meta name="msapplication-TileColor" content="{{ $colorPalette['accent'] ?? '#F59E0B' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">
    
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">
    <meta name="google-site-verification" content="">
    <meta name="yandex-verification" content="">
    <meta name="msvalidate.01" content="">
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ $business->business_name }}",
        "url": "{{ url()->current() }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/search') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "color-accent": "{{ $colorPalette['accent'] ?? '#F59E0B' }}",
                        "color-bg-light": "{{ $colorPalette['highlight'] ?? '#8B5CF6' }}",
                        "color-bg-mid": "{{ $colorPalette['primary'] ?? '#3B82F6' }}",
                        "color-bg-dark": "{{ $colorPalette['secondary'] ?? '#64748B' }}",
                        "text-primary": "#0C0C20",
                        "text-secondary": "#828282",
                    },
                    fontFamily: {
                        sans: ["Poppins", "ui-sans-serif", "system-ui"],
                    },
                    boxShadow: {
                        soft: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        medium: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                        large: "0 25px 50px -12px rgba(0, 0, 0, 0.25)",
                    },
                },
            },
        };
    </script>

    <!-- Custom Styles -->
    <style>
        /* ===== MODAL STYLES ===== */
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

        /* ===== MASK STYLES ===== */
        .masked-blob {
            -webkit-mask-image: url({{ asset('img/blob-img.svg') }});
            mask-image: url({{ asset('img/blob-img.svg') }});
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: contain;
            mask-size: contain;
            -webkit-mask-position: center;
            mask-position: center;
        }

        /* ===== SCROLLBAR STYLES ===== */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ===== HOVER EFFECTS ===== */
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
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .hover-gallery {
            position: relative;
        }

        .hover-gallery-image {
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .hover-gallery:hover .hover-gallery-image {
            transform: scale(1.1);
            filter: brightness(0.7);
        }
    </style>

    @stack('styles')
    
    <!-- Google Analytics (Replace GA_MEASUREMENT_ID with your actual ID) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
    </script> -->
</head>

<body class="text-text-primary bg-color-bg-dark font-sans">

    <!-- NAVBAR -->
    @if (isset($sectionVariants['navbar']))
        @includeIf('user.components.navbar-' . strtolower($sectionVariants['navbar']), [
            'data' => $navbarData,
        ])
    @endif

    <!-- HERO SECTION -->
    @if (isset($sectionVariants['hero']))
        @includeIf('user.components.hero-' . strtolower($sectionVariants['hero']), [
            'data' => $heroData
        ])
    @endif

    <!-- ABOUT SECTION -->
    @if (isset($sectionVariants['about']))
        @includeIf('user.components.about-' . strtolower($sectionVariants['about']), [
            'data' => $aboutData
        ])
    @endif

    <!-- PRODUCT SECTION -->
    @if (isset($sectionVariants['produk']))
        @includeIf('user.components.product-' . strtolower($sectionVariants['produk']), [
            'data' => $productData,
        ])
    @endif

    <!-- GALLERY SECTION -->
    @if (isset($sectionVariants['galeri']))
        @includeIf('user.components.gallery-' . strtolower($sectionVariants['galeri']), [
            'data' => $galleryData,
        ])
    @endif

    <!-- TESTIMONIAL SECTION -->
    @if (isset($sectionVariants['testimoni']))
        @includeIf('user.components.testimonial-' . strtolower($sectionVariants['testimoni']), [
            'data' => $testimonialData,
        ])
    @endif

    <!-- CONTACT SECTION -->
    @if (isset($sectionVariants['kontak']))
        @includeIf('user.components.contact-' . strtolower($sectionVariants['kontak']), [
            'data' => $contactData,
        ])
    @endif

    <!-- FOOTER -->
    @includeIf('user.components.footer-a', ['data' => $footerData])

    <!-- FLOATING ELEMENTS -->
    <div class="fixed bottom-6 left-6 z-30">
        <div class="rounded-full bg-white/60 px-3 py-1 text-xs font-medium text-gray-600 backdrop-blur-sm">
            Powered by Exposia
        </div>
    </div>

    <!-- WhatsApp Floating Button -->
    @php
        $rawNumber = $business->user->phone;
        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $rawNumber));
    @endphp

    @if ($waNumber)
        <a href="https://wa.me/{{ $waNumber }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="fixed right-6 bottom-6 z-30 flex h-12 w-12 transform items-center justify-center rounded-full bg-green-500 p-4 text-white shadow-lg transition-all duration-300 hover:scale-110"
           aria-label="Hubungi {{ $business->business_name }} via WhatsApp"
           title="Chat WhatsApp - {{ $business->business_name }}">
            <i class="fab fa-whatsapp text-2xl" aria-hidden="true"></i>
        </a>
    @endif

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPoint",
        "telephone": "+{{ $waNumber }}",
        "contactType": "customer service",
        "availableLanguage": "Indonesian"
    }
    </script>

    <!-- JAVASCRIPT -->
    <script>
        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 100,
            });
        });

        // ===== NAVBAR FUNCTIONS =====
        function toggleMenu() {
            const menu = document.getElementById("mobileMenu");
            const burger = document.getElementById("burger");
            
            if (menu && burger) {
                menu.classList.toggle("hidden");
                burger.classList.toggle("rotate-90");
            }
        }

        function toggleMenuC() {
            const menu = document.getElementById("mobileMenu");
            const line1 = document.getElementById("line1");
            const line2 = document.getElementById("line2");

            if (menu && line1 && line2) {
                menu.classList.toggle("hidden");
                
                // Toggle colors
                line1.classList.toggle("bg-black");
                line2.classList.toggle("bg-black");
                line1.classList.toggle("bg-white");
                line2.classList.toggle("bg-white");
                
                // Animate into X
                line1.classList.toggle("rotate-45");
                line1.classList.toggle("top-2");
                line1.classList.toggle("top-3.5");
                
                line2.classList.toggle("-rotate-45");
                line2.classList.toggle("bottom-2");
                line2.classList.toggle("bottom-3.5");
            }
        }

        // Navbar B Toggle
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

        // ===== PRODUCT MODAL FUNCTIONS =====
        function openProductModal(title, description, img, price) {
            const modal = document.getElementById("productModal");
            const modalImage = document.getElementById("productModalImage");
            const modalTitle = document.getElementById("productModalTitle");
            const modalPrice = document.getElementById("productModalPrice");
            const modalDescription = document.getElementById("productModalDescription");

            if (modal && modalImage && modalTitle && modalPrice && modalDescription) {
                modalImage.src = img;
                modalTitle.textContent = title;
                modalPrice.textContent = `Rp${Number(price).toLocaleString('id-ID')}`;
                modalDescription.textContent = description;
                
                modal.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            }
        }

        function closeProductModal() {
            const modal = document.getElementById("productModal");
            if (modal) {
                modal.classList.add("hidden");
                document.body.style.overflow = "auto";
            }
        }

        function orderNow() {
            const productTitle = document.getElementById("productModalTitle")?.textContent;
            const productPrice = document.getElementById("productModalPrice")?.textContent;
            
            if (productTitle && productPrice) {
                const message = `Halo! Saya ingin memesan:\n\n${productTitle}\n${productPrice}\n\nMohon info lebih lanjut. Terima kasih!`;
                window.open(`https://wa.me/{{ $waNumber }}?text=${encodeURIComponent(message)}`, "_blank");
                closeProductModal();
            }
        }

        // ===== PRODUCT SCROLL FUNCTIONS =====
        function scrollProductLeft() {
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
        }

        function scrollProductRight() {
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
        }

        // ===== TESTIMONIAL FUNCTIONS =====
        function selectTestimonial(index, text, name) {
            const testimonialText = document.getElementById("testimonialText");
            const testimonialName = document.getElementById("testimonialName");
            
            if (testimonialText && testimonialName) {
                testimonialText.textContent = text;
                testimonialName.textContent = name;

                // Update image highlighting
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
        }

        // ===== INITIALIZE ON DOM LOAD =====
        document.addEventListener('DOMContentLoaded', function() {
            initializeNavbarB();
        });
    </script>

    @stack('scripts')
</body>

</html>