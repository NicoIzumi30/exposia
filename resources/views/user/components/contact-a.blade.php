<!-- SECTION: KONTAK -->
<section id="contact" class="bg-color-bg-mid">
    <div class="container mx-auto p-6 py-16">
        <div data-aos="fade-up" data-aos-delay="300" class="mb-10 text-center">
            <h2 class="mb-2 text-3xl font-bold">Kontak Tim Kami</h2>
            <p class="">Beritahu kami bagaimana kita bisa membantu</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($data['contacts'] as $contact)
                <!-- Card -->
                <div data-aos="fade-up" data-aos-delay="100"
                    class="bg-color-bg-light flex flex-col rounded-xl p-6 shadow-md">
                    <i class="{{ $contact['icon'] }} text-4xl mb-2 text-color-accent"></i>
                    <div class="flex-1">
                        <p class="text-lg font-bold">{{ $contact['title'] }}</p>
                        <p class="text-sm">{{ $contact['description'] }}</p>
                    </div>
                    <div class="pt-5 font-medium hover:underline">
                        <a href="{{ $contact['url'] }}">{{ $contact['name'] }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- END-SECTION: KONTAK -->
