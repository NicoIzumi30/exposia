
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
        $rawNumber = $phoneNumber;
        // Convert leading 0 to 62
        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $rawNumber));
    @endphp


    @if ($waNumber)
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer"
            class="fixed right-6 bottom-6 z-30 flex h-12 w-12 transform items-center justify-center rounded-full bg-green-500 p-4 text-white shadow-lg transition-all duration-300 hover:scale-110">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
    @endif

    <!-- END-Floating Button -->


    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
        });

        // NAVBAR B TOGGLE
        const menuBtn = document.getElementById("mobileMenuBtn");
        const closeBtn = document.getElementById("mobileMenuCloseBtn");
        const menu = document.getElementById("mobileDropdown");

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

        // NAVBAR TOGGLE
        function toggleMenu() {
            const menu = document.getElementById("mobileMenu");
            const burger = document.getElementById("burger");

            menu.classList.toggle("hidden");
            burger.classList.toggle("rotate-90");
        }

        // NAVBAR TOGGLE
        function toggleMenuC() {
            const menu = document.getElementById("mobileMenu");
            const btn = document.getElementById("burgerBtn");
            const line1 = document.getElementById("line1");
            const line2 = document.getElementById("line2");

            menu.classList.toggle("hidden");

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

        // PRODUCT MODAL FUNCTIONS
        function openProductModal(title, description, img, price) {
            document.getElementById("productModalImage").src = img;
            document.getElementById("productModalTitle").textContent = title;
            document.getElementById("productModalPrice").textContent = `Rp${Number(price).toLocaleString('id-ID')}`;
            document.getElementById("productModalDescription").textContent = description;

            document.getElementById("productModal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        function closeProductModal() {
            document.getElementById("productModal").classList.add("hidden");
            document.body.style.overflow = "auto";
        }

        function orderNow() {
            // Redirect to WhatsApp with product info
            const productTitle =
                document.getElementById("productModalTitle").textContent;
            const productPrice =
                document.getElementById("productModalPrice").textContent;
            const message =
                `Halo! Saya ingin memesan:\n\n${productTitle}\n${productPrice}\n\nMohon info lebih lanjut. Terima kasih!`;

            window.open(
                `https://wa.me/6274123456?text=${encodeURIComponent(message)}`,
                "_blank",
            );
            closeProductModal();
        }

        // PRODUCT A SCROLL FUNCTIONS
        function scrollProductLeft() {
            const scrollContainer = document.getElementById("productScroll");
            const card = scrollContainer.querySelector(".snap-start");
            const scrollAmount = card.offsetWidth + parseInt(getComputedStyle(card).marginRight || 0);

            scrollContainer.scrollBy({
                left: -scrollAmount,
                behavior: "smooth"
            });
        }

        function scrollProductRight() {
            const scrollContainer = document.getElementById("productScroll");
            const card = scrollContainer.querySelector(".snap-start");
            const scrollAmount = card.offsetWidth + parseInt(getComputedStyle(card).marginRight || 0);

            scrollContainer.scrollBy({
                left: scrollAmount,
                behavior: "smooth"
            });
        }


        // TESTIMONY B SELECT
        function selectTestimonial(index, text, name) {
            // Update text and name
            document.getElementById("testimonialText").textContent = text;
            document.getElementById("testimonialName").textContent = name;

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
    </script>
@endsection
