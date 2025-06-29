<!-- SECTION: ABOUT -->
<section id="about" class="bg-color-bg-mid w-full">
    <div class="container mx-auto px-6 py-16">
        <h1 data-aos="fade-up" class="mb-16 text-center text-3xl font-bold">
            Tentang Kami
        </h1>
        <div class="flex flex-col items-start space-x-0 lg:flex-row lg:space-x-10">
            <div
                class="flex flex-col items-center justify-between gap-12 xl:items-start w-full {{ empty($data['highlights']) ? '' : 'lg:max-w-[50%]' }}">
                <!-- Text Section -->
                <div data-aos="fade-up" data-aos-delay="300" class="text-center text-base sm:text-lg xl:text-left">
                    {!! $data['description'] ?? 'No Data' !!}
                </div>
                <!-- Image Stack -->
                <div
                    class="relative mx-auto mb-10 flex h-[18rem] w-full max-w-xl justify-center lg:mb-0 lg:justify-start">
                    <div class="relative mx-auto h-full w-[25rem]">
                        <!-- Bottom Image -->
                        <div data-aos="fade-up" data-aos-delay="200" class="absolute bottom-0 left-0 z-10">
                            <img src="{{ $data['img-2'] }}" alt="Image 2"
                                class="border-color-bg-mid hover-scale h-36 w-48 rounded-xl border-[10px] object-cover" />
                        </div>
                        <!-- Top Image -->
                        <div data-aos="fade-up" data-aos-delay="100" class="absolute top-0 left-10 z-0">
                            <img src="{{ $data['img-1'] }}" alt="Image 1"
                                class="hover-lift h-60 w-80 rounded-xl object-cover" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keunggulan -->
            <div data-aos="fade-up" data-aos-delay="100" class="flex flex-1 flex-col justify-between gap-5 w-full">
                @foreach ($data['highlights'] as $highlight)
                    <div data-aos="fade-up" data-aos-delay="100" class="flex space-x-5 rounded-xl py-4">
                        <i
                            class="{{ $highlight['icon'] }} text-color-accent mt-2 mb-5 flex text-4xl shrink-0 justify-center"></i>
                        <div>
                            <h1 class="font-bold">{{ $highlight['title'] }}</h1>
                            <p>
                                {{ $highlight['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- END-SECTION: ABOUT -->
