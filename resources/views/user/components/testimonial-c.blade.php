<!-- SECTION: TESTIMONI -->
<section id="testimony" class="container mx-auto px-6 py-16">
    <div data-aos="fade-up" data-aos-delay="300">
        <h1 class="text-center text-3xl font-bold">Testimoni Pelanggan</h1>
        <p class="mb-10 text-center">
            Kami sangat menghargai setiap feedback dari konsumen. Berikut adalah
            beberapa testimoni mereka tentang produk kami.
        </p>
    </div>

    <div class="relative">
        <!-- Background Box -->
        <div data-aos="fade-up" data-aos-delay="100" class="absolute -z-10 flex h-full w-full items-center justify-center">
            <div class="bg-color-accent h-full w-[800px] rounded-3xl"></div>
        </div>

        <!-- Testimony Grid -->
        <div>
            <!-- Left Gradient Overlay -->
            <div
                class="from-color-bg-dark pointer-events-none absolute top-0 left-0 z-10 h-full w-5 bg-gradient-to-r to-transparent">
            </div>

            <!-- Right Gradient Overlay -->
            <div
                class="from-color-bg-dark pointer-events-none absolute top-0 right-0 z-10 h-full w-5 bg-gradient-to-l to-transparent">
            </div>

            <div data-aos="fade-left" data-aos-delay="100"
                class="scrollbar-hide relative flex gap-5 overflow-x-auto px-5 py-16">
                @foreach ($data['testimonies'] as $testimony)
                    <!-- Testimony Card -->
                    <div>
                        <div class="bg-color-bg-light hover-scale h-full w-[400px] flex-shrink-0 rounded-xl p-8 shadow">
                            <div class="overflow-hidden rounded-3xl">
                                <img src="{{ $testimony['img'] }}" alt="Profile"
                                    class="h-16 w-16 rounded-full object-cover" />
                            </div>
                            <h2 class="mt-3 text-lg font-semibold">{{ $testimony['name'] }}</h2>
                            <p class="my-5 text-sm">
                                {{ $testimony['text'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- END-SECTION: TESTIMONI -->
