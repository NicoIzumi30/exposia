<!-- SECTION: ABOUT -->
<section id="about" class="bg-color-bg-mid px-6 py-16">
    <div class="container mx-auto">
        <h1 data-aos="fade-up" class="mb-10 text-center text-3xl font-bold">
            Tentang Kami
        </h1>
        <div class="mx-auto mb-12 flex max-w-5xl flex-col space-y-10 text-center lg:flex-row lg:space-y-0 lg:space-x-10">
            <!-- Image Stack -->
            <div class="relative mx-auto mb-10 flex h-[18rem] w-full max-w-xl justify-center lg:mb-0 lg:justify-start">
                <div class="relative mx-auto h-full w-[25rem]">
                    <!-- Bottom Image -->
                    <div data-aos="fade-up" data-aos-delay="200" class="absolute bottom-0 right-0 z-10">
                        <img src="{{ $data['img-2'] }}" alt="Image 2"
                            class="border-color-bg-mid hover-scale h-36 w-48 rounded-xl border-[10px] object-cover" />
                    </div>
                    <!-- Top Image -->
                    <div data-aos="fade-up" data-aos-delay="100" class="absolute top-0 right-10 z-0">
                        <img src="{{ $data['img-1'] }}" alt="Image 1"
                            class="hover-lift h-60 w-80 rounded-xl object-cover" />
                    </div>
                </div>
            </div>
            <div data-aos="fade-up" data-aos-delay="300" class="text-center text-justify text-2xl lg:text-justify">
                {!! $data['description'] ?? 'No Data' !!}
            </div>
        </div>

        <!-- Keunggulan -->
        <div class="grid gap-5 lg:grid-cols-3">
            @foreach ($data['highlights'] as $highlight)
                <div data-aos="fade-up" data-aos-delay="200" class="mx-auto rounded-xl py-4 text-center">
                    <i class="{{ $highlight['icon'] }} text-color-accent mt-2 mb-5 flex justify-center text-4xl"></i>
                    <h1 class="font-bold">{{ $highlight['title'] ?? 'No Data' }}</h1>
                    <p class="max-w-96 mx-auto">
                        {{ $highlight['description'] ?? 'No Data' }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</section>
<!-- END-SECTION: ABOUT -->
