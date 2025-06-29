<!-- SECTION: TESTIMONI -->
<section id="testimony" class="container mx-auto px-6 py-16">
    <div data-aos="fade-up" data-aos-delay="300" class="grid grid-cols-1 items-center gap-12 md:grid-cols-2">
        <!-- Left: Title + Description -->
        <div class="h-full lg:pt-20">
            <h1 class="mb-4 text-3xl font-bold">TESTIMONI KONSUMEN</h1>
            <p class="">
                Kami sangat menghargai setiap feedback dari konsumen. Berikut adalah
                beberapa testimoni mereka tentang produk kami.
            </p>
        </div>

        <!-- Right: Testimonial Cards -->
        <div data-aos="fade-up" data-aos-delay="300" class="relative">
            <!-- Left Gradient Overlay -->
            <div
                class="from-color-bg-dark pointer-events-none absolute -top-1 left-0 z-10 h-5 w-full bg-gradient-to-b to-transparent">
            </div>

            <!-- Right Gradient Overlay -->
            <div
                class="from-color-bg-dark pointer-events-none absolute -bottom-0 left-0 z-10 h-5 w-full bg-gradient-to-t to-transparent">
            </div>

            <div class="scrollbar-hide max-h-[600px] space-y-6 overflow-x-visible overflow-y-auto py-5">
                @foreach ($data['testimonies'] as $testimony)
                    <!-- Testimonial Card -->
                    <div>
                        <div class="bg-color-bg-light w-full rounded-xl p-6 shadow">
                            <p class="mb-4 text-center">
                                {{ $testimony['text'] }}
                            </p>
                            <div class="flex items-center justify-center space-x-4">
                                <img src="{{ $testimony['img'] }}" alt="Profile" class="h-10 w-10 rounded-full" />
                                <span class="font-semibold">{{ $testimony['name'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- END-SECTION: TESTIMONI -->
