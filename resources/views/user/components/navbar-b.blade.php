    <!-- SECTION: NAVBAR -->
    <nav class=" fixed top-4 left-1/2 z-50 container mt-4 -translate-x-1/2 transform px-4">
        <div class="bg-color-bg-light shadow-md px-4 py-3 rounded-xl">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="#">
                    <img src="{{ $data['logo'] }}" alt="logo" class="h-10" />
                </a>
                <!-- Desktop Menu -->
                <div class="hidden items-center space-x-4 md:flex">
                    <a href="#hero" class="font-bold">Home</a>
                    <a href="#about" class="font-bold">About</a>
                    <a href="#product" class="font-bold">Produk</a>
                    <a href="#gallery" class="font-bold">Galeri</a>
                    <a href="#testimony" class="font-bold">Testimoni</a>
                    <a href="#contact" class="font-bold">Kontak</a>
                </div>
                <a href="#product"
                    class="bg-color-accent text-color-bg-light hidden items-center rounded-xl px-4 py-2 lg:flex">
                    <span class="material-icons mr-2">arrow_right_alt</span>
                    Lihat Produk
                </a>
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn"
                    class="bg-color-accent text-color-bg-light rounded-xl px-4 py-2 font-bold md:hidden">
                    Menu
                </button>
                <button id="mobileMenuCloseBtn"
                    class="bg-color-accent text-color-bg-light hidden rounded-xl px-4 py-2 font-bold md:hidden">
                    Close
                </button>
            </div>
            <div id="mobileDropdown" class="z-40 hidden bg-white p-4 transition-all duration-300 md:hidden">
                <a href="#hero" class="block py-2 font-bold">Home</a>
                <a href="#about" class="block py-2 font-bold">About</a>
                <a href="#product" class="block py-2 font-bold">Produk</a>
                <a href="#gallery" class="block py-2 font-bold">Galeri</a>
                <a href="#testimony" class="block py-2 font-bold">Testimoni</a>
                <a href="#contact" class="block py-2 font-bold">Kontak</a>
            </div>
        </div>
    </nav>
    <!-- END-SECTION: NAVBAR -->
