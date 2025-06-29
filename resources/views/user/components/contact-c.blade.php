<!-- SECTION: KONTAK -->
<section id="contact" class="bg-color-bg-mid">
    <div class="container mx-auto grid grid-cols-1 gap-12 px-6 py-16 xl:grid-cols-2">
        <!-- Contact Info -->
        <div class="flex items-center">
            <div class="grid w-full grid-cols-1 gap-6 sm:grid-cols-2">
                @foreach ($data['contacts'] as $contact)
                    <!-- Contact Card -->
                    <div data-aos="fade-up" data-aos-delay="200"
                        class="bg-color-bg-light flex flex-col items-center rounded-xl p-6 shadow">
                        <i class="{{ $contact['icon'] }} text-4xl mb-2 text-color-accent"></i>
                        <p class="mb-2 mb-4 font-semibold">{{ $contact['name'] }}</p>
                        <a href="{{ $contact['url'] }}"
                            class="bg-color-accent text-color-bg-light hover-scale mt-auto rounded-lg px-4 py-2 text-sm font-semibold transition hover:brightness-110">
                            {{ $contact['title'] }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Contact Form -->
        <div data-aos="fade-up" data-aos-delay="300" class="bg-color-bg-light rounded-2xl p-16">
            <h1 class="mb-6 text-center text-3xl font-bold">Kontak Kami</h1>
            <p class="mb-6 text-center">
                Hubungi kami untuk mengetahui lebih lanjut
            </p>
            <form action="#" class="space-y-6">
                <input type="text" placeholder="Your Name"
                    class="text-text-secondary w-full rounded-md border bg-transparent px-4 py-2" />

                <input type="email" placeholder="Email"
                    class="text-text-secondary w-full rounded-md border bg-transparent px-4 py-2" />
                <input type="tel" placeholder="Phone Number"
                    class="text-text-secondary w-full rounded-md border bg-transparent px-4 py-2" />
                <textarea rows="5" placeholder="Your Message"
                    class="text-text-secondary w-full rounded-md border bg-transparent px-4 py-2"></textarea>
                <button type="submit"
                    class="bg-color-accent text-color-bg-light w-full rounded-full px-6 py-2 transition hover:bg-gray-800">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>
<!-- END-SECTION: KONTAK -->
