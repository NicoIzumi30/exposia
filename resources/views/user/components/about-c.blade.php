<!-- SECTION: ABOUT -->
<section id="about" class="bg-color-bg-mid px-6 py-16">
    <div class="container mx-auto">
        <h1 data-aos="fade-up" class="mb-10 text-center text-3xl font-bold">
            Tentang Kami
        </h1>
        <div class="mx-auto mb-12 flex max-w-5xl flex-col space-y-10 text-center lg:flex-row lg:space-y-0 lg:space-x-10">
            <div data-aos="zoom-in" data-aos-delay="300"
                class="mx-auto h-[250px] w-full shrink-0 overflow-hidden rounded-xl sm:h-[350px] sm:w-[500px]">
                <img src="{{ $data['img-1'] }}" alt="" class="hover-lift h-full w-full object-cover" />
            </div>
            <p data-aos="fade-up" data-aos-delay="100" class="text-center text-justify text-2xl lg:text-justify">
                {!! $data['description'] ?? 'No Data' !!}
            </p>
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
