<!-- SECTION: NAVBAR -->
<nav class="bg-color-bg-dark/10 fixed top-0 left-1/2 z-50 w-full -translate-x-1/2 transform px-4 py-4 backdrop-blur-md">
    <div class="container mx-auto flex items-center justify-between">
        <div class="">
            <a href="#">
                <img src="{{ $data['logo'] }}" alt="logo" class="h-16" />
            </a>
        </div>

        <div class="hidden items-center space-x-4 lg:flex">
            <a href="#hero" class="font-bold">Home</a>
            <a href="#about" class="font-bold">About</a>
            <a href="#product" class="font-bold">Produk</a>
            <a href="#gallery" class="font-bold">Galeri</a>
            <a href="#testimony" class="font-bold">Testimoni</a>
            <a href="#contact" class="font-bold">Kontak</a>
        </div>

        <a href="#product"
            class="bg-color-accent text-color-bg-light hidden items-center rounded-full px-4 py-2 lg:flex">
            Lihat Produk
        </a>

        <!-- Burger (mobile only) -->
        <button id="burgerBtn" class="relative h-8 w-8 transition-transform duration-300 lg:hidden"
            onclick="toggleMenuC()">
            <span id="line1" class="absolute top-2 left-0 h-1 w-full bg-black transition-all duration-300"></span>
            <span id="line2"
                class="absolute bottom-2 left-0 h-1 w-full bg-black transition-all duration-300"></span>
        </button>
    </div>
</nav>
<!-- END-SECTION: NAVBAR -->

<!-- Mobile Menu Overlay -->
<div id="mobileMenu"
    class="bg-color-accent text-color-bg-light fixed inset-0 z-40  hidden text-xl transition-opacity duration-300 lg:hidden">
    <div class="flex flex-col items-start container mx-auto">
        <a href="#hero" onclick="toggleMenuC()" class="mt-32 w-full py-4 px-8 shadow">Home</a>
        <a href="#about" onclick="toggleMenuC()" class="w-full py-4 px-8 shadow">About</a>
        <a href="#product" onclick="toggleMenuC()" class="w-full py-4 px-8 shadow">Product</a>
        <a href="#gallery" onclick="toggleMenuC()" class="w-full py-4 px-8 shadow">Galeri</a>
        <a href="#testimony" onclick="toggleMenuC()" class="w-full py-4 px-8 shadow">Testimoni</a>
        <a href="#contact" onclick="toggleMenuC()" class="w-full py-4 px-8 shadow">Kontak</a>
    </div>
</div>
<!-- END-Mobile Menu Overlay -->
