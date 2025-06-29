<!-- SECTION: TESTIMONI -->
<section id="testimony" class="container mx-auto px-6 py-16">
    <h1 data-aos="fade-up" class="text-center text-3xl font-bold">
        Testimoni Pelanggan
    </h1>
    <div data-aos="fade-up" class="bg-color-accent mx-auto my-5 h-2 w-24 rounded-full"></div>

    <!-- Testimony Text -->
    <div data-aos="fade-up" data-aos-delay="100">
        <p id="testimonialText" class="mx-auto max-w-2xl text-center italic">
            Testimony TEXT
        </p>
        <p id="testimonialName" class="text-color-accent my-5 text-center font-bold">
            Testimony user
        </p>
    </div>

    <div class="relative mx-auto max-w-96">
        <!-- Left Gradient Overlay -->
        <div
            class="from-color-bg-dark pointer-events-none absolute top-0 left-0 z-10 h-full w-5 bg-gradient-to-r to-transparent">
        </div>

        <!-- Right Gradient Overlay -->
        <div
            class="from-color-bg-dark pointer-events-none absolute top-0 right-0 z-10 h-full w-5 bg-gradient-to-l to-transparent">
        </div>

        <!-- Profile Selector -->
        <div id="testimonialProfiles"
            class="scrollbar-hide flex items-center gap-5 overflow-x-auto overflow-y-hidden px-5 py-5">
            <!-- These buttons will control the content -->
            @foreach ($data['testimonies'] as $testimony)
                <button data-aos="fade-up" data-aos-delay="100"
                    onclick="selectTestimonial({{ $loop->index }}, {{ json_encode($testimony['text']) }}, {{ json_encode($testimony['name']) }})"
                    class="shrink-0">
                    <img src="{{ $testimony['img'] }}" alt="Profile"
                        class="hover-scale testimonial-img border-color-bg-light h-20 w-20 rounded-full border-4 object-cover opacity-70 transition-all" />
                </button>
            @endforeach

        </div>
    </div>
</section>
<!-- END-SECTION: TESTIMONI -->

<script>
    // Auto-select first testimony on load
    window.addEventListener("DOMContentLoaded", () => {
        const testimonies = @json($data['testimonies']);
        selectTestimonial(0, testimonies[0].text, testimonies[0].name);
    });
</script>
