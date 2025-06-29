<!-- SECTION: HERO -->
<section id="hero"
    class="container mx-auto flex min-h-screen flex-col items-center justify-center px-6 py-12 lg:flex-row lg:justify-between lg:space-x-10">
    <!-- Left Text -->
    <div data-aos="fade-up" data-aos-duration="1000" class="mb-10 max-w-xl text-center lg:mb-0 lg:text-left">
        <h1 class="mb-4 text-4xl font-bold">
            {{ $data['title'] ?? 'No Data' }}
        </h1>
        <p class="mb-6">
            {{ $data['description'] ?? 'No Data' }}
        </p>
        <a href="#product"
            class="bg-color-accent text-color-bg-light hover-scale mx-auto flex w-fit items-center rounded-xl px-5 py-3 lg:mx-0">
            <span class="material-icons mr-2">arrow_right_alt</span>
            Lihat Produk
        </a>
    </div>

    <!-- Image Stack -->
    <div class="relative h-[400px] w-full max-w-[320px] overflow-visible sm:h-[600px] sm:max-w-[650px]">
        <div data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="300" class="absolute top-36 left-0 z-10">
            <img src="{{ $data['img-1'] }}" alt="Image 2"
                class="hover-lift h-80 w-80 rounded-xl object-cover shadow-lg md:h-96 md:w-96" />
        </div>
        <div data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="100" class="absolute top-0 right-0 z-0">
            <img src="{{ $data['img-2'] }}" alt="Image 1"
                class="hover-lift h-80 w-80 rounded-xl object-cover shadow-xl md:h-96 md:w-96" />
        </div>
    </div>
</section>
<!-- END-SECTION: HERO -->
