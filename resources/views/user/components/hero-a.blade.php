<!-- SECTION: HERO -->
<section id="hero"
    class="relative flex max-h-screen min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-16 text-center lg:justify-start">
    <!-- Heading and Paragraph -->
    <div data-aos="fade-up" data-aos-duration="1000" class="z-10 mt-20 max-w-xl">
        <h1 class="mb-5 text-4xl font-bold sm:text-5xl">
            {{ $data['title'] ?? 'No Data' }}
        </h1>
        <p class="mb-10 text-base sm:text-lg">
            {{ $data['description'] ?? 'No Data' }}
        </p>
    </div>

    <!-- Decorative Image -->
    <div data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="300"
        class="container flex items-center overflow-hidden rounded-t-[150px] rounded-b-[50px]">
        <img src="{{ $data['img-1'] }}" alt="img-1" class="pointer-events-none h-full w-full object-cover" />
    </div>
</section>
<!-- END-SECTION: HERO -->
