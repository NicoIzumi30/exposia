<!-- SECTION: NAVBAR -->
<nav class=" fixed top-0 left-1/2 z-50 container mx-auto -translate-x-1/2 transform px-4">
    <div class="flex items-center justify-between bg-color-accent text-color-bg-light rounded-b-[50px] px-8 py-3">
        <!-- Burger (mobile only) -->
        <button id="burger" class="text-3xl transition-transform duration-300 lg:hidden" onclick="toggleMenu()">
            &#9776;
        </button>

        <!-- Menu (desktop) -->
        <div class="hidden items-center space-x-4 lg:flex">
            <a href="#hero" class="font-bold">Home</a>
            <a href="#about" class="font-bold">About</a>
            <a href="#product" class="font-bold">Product</a>
        </div>

        <div class="flex flex-1 justify-center">
            <a href="#">
                <img src="{{ $data['logo'] }}" alt="logo" class="h-10" />
            </a>
        </div>

        <div class="hidden items-center space-x-4 lg:flex">
            <a href="#gallery" class="font-bold">Galeri</a>
            <a href="#testimony" class="font-bold">Testimoni</a>
            <a href="#contact" class="font-bold">Kontak</a>
        </div>
    </div>
</nav>
<!-- END-SECTION: NAVBAR -->

<!-- Mobile Menu Overlay -->
<div id="mobileMenu"
    class="bg-color-accent text-color-bg-light fixed inset-0 z-40 flex hidden flex-col items-start text-xl duration-300 lg:hidden">
    <div class="flex flex-col items-start container mx-auto">
        <a href="#hero" onclick="toggleMenu()" class="mt-32 w-full p-4 px-10 shadow">Home</a>
        <a href="#about" onclick="toggleMenu()" class="w-full p-4 px-10 shadow">About</a>
        <a href="#product" onclick="toggleMenu()" class="w-full p-4 px-10 shadow">Product</a>
        <a href="#gallery" onclick="toggleMenu()" class="w-full p-4 px-10 shadow">Galeri</a>
        <a href="#testimony" onclick="toggleMenu()" class="w-full p-4 px-10 shadow">Testimoni</a>
        <a href="#contact" onclick="toggleMenu()" class="w-full p-4 px-10 shadow">Kontak</a>
    </div>
</div>
<!-- END-Mobile Menu Overlay -->
