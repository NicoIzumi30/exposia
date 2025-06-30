    <!-- SECTION: HERO -->
    <section id="hero" class="bg-color-bg-dark">
        <div
            class="container mx-auto flex min-h-screen flex-col items-center justify-center gap-20 px-6 py-16 lg:flex-row lg:justify-between">
            <!-- Left Text -->
            <div data-aos="fade-up" data-aos-duration="1000" class="max-w-xl text-center lg:text-left">
                <h1 class="mb-4 text-4xl font-bold lg:text-6xl">
                    {{ $data['title'] ?? 'No Data' }}
                </h1>
                <p class="mb-6 lg:text-3xl">
                    {{ $data['description'] ?? 'No Data' }}
                </p>
            </div>

            <!-- Image Stack (Wrapper to center on mobile) -->
            <div class="flex w-full justify-center lg:w-auto">
                <div class="relative z-10 h-[300px] w-[300px] md:h-[400px] md:w-[400px] lg:h-[600px] lg:w-[600px]">
                    <!-- SVG Blob Background -->
                    <svg data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="100" viewBox="0 0 621 545"
                        xmlns="http://www.w3.org/2000/svg"
                        class="text-color-accent absolute inset-0 left-3 z-0 h-full w-full">
                        <path
                            d="M152.444 11.8923C67.7889 19.1573 34.6753 -0.93676 9.35243 183.905C3.24771 200.974 -5.91849 243.708 6.25435 278.096C21.4704 321.08 -14.9055 403.781 39.101 480.854C93.1075 557.926 117.496 529.107 283.245 542.181C448.995 555.256 419.198 504.169 552.282 418.514C685.367 332.858 587.444 263.182 559.693 111.339C531.943 -40.5026 237.1 4.62729 152.444 11.8923Z"
                            fill="currentColor" />
                    </svg>

                    <!-- Foreground Image -->
                    <img data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="300" src="{{ $data['img-1'] }}"
                        alt="blob-masked image" class="masked-blob h-full w-full object-cover" />
                </div>
            </div>
        </div>
    </section>
    <!-- END-SECTION: HERO -->
