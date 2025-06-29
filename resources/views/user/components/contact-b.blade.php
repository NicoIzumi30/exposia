<!-- SECTION: KONTAK -->
<section id="contact" class="bg-color-bg-mid">
    <div class="container mx-auto grid grid-cols-1 gap-12 px-6 py-16 xl:grid-cols-2">
        <!-- Contact Info -->
        <div>
            <h1 data-aos="fade-up" data-aos-delay="300" class="mb-6 text-3xl font-bold">
                Kontak Kami
            </h1>
            <div data-aos="fade-up" data-aos-delay="300" class="space-y-4 py-10">
                @foreach ($data['contacts'] as $contact)
                    <a href="{{ $contact['url'] }}" class="flex items-center gap-3 hover:underline">
                        <i class="{{ $contact['icon'] }} text-xl mb-2 text-color-accent w-5"></i>
                        <span>{{ $contact['title'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Contact Form -->
        <div data-aos="fade-up" data-aos-delay="300" class="bg-color-bg-light rounded-2xl p-16">
            <form action="#" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" placeholder="First Name"
                        class="text-text-secondary w-full rounded-md border bg-transparent px-4 py-2" />
                    <input type="text" placeholder="Last Name"
                        class="text-text-secondary w-full rounded-md border bg-transparent px-4 py-2" />
                </div>
                <input type="email" placeholder="Email"
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
