<!-- SECTION: GALLERY -->
<section id="gallery" class="bg-color-bg-mid">
    @if (!empty($data['images']) && count($data['images']) > 0)
        <div data-aos="fade-up" class="container mx-auto px-6 py-16">
            <h1 class="mb-10 text-center text-3xl font-bold">Galeri Kami</h1>

            @php
                $imageCount = count($data['images'] ?? []);

                // Determine rows and height per layout
                $gridRows = match (true) {
                    $imageCount <= 4 => 1,
                    $imageCount <= 5 => 2,
                    $imageCount <= 8 => 3,
                    default => 4,
                };
            @endphp

            <div class="relative grid grid-cols-4 grid-rows-4 gap-4"
                style="grid-template-rows: repeat({{ $gridRows }}, 12rem)">
                @php
                    $imageCount = count($data['images'] ?? []);

                    $positionsIf4Img = [
                        0 => ['col' => 'col-start-1', 'row' => 'row-start-1', 'delay' => 100],
                        1 => ['col' => 'col-start-2', 'row' => 'row-start-1', 'delay' => 200],
                        2 => ['col' => 'col-start-3', 'row' => 'row-start-1', 'delay' => 300],
                        3 => ['col' => 'col-start-4', 'row' => 'row-start-1', 'delay' => 200],
                    ];

                    $positionsIf8Img = [
                        0 => ['col' => 'col-start-1 col-end-3', 'row' => 'row-start-1 row-end-3', 'delay' => 100],
                        1 => ['col' => 'col-start-3', 'row' => 'row-start-1', 'delay' => 200],
                        2 => ['col' => 'col-start-4', 'row' => 'row-start-1', 'delay' => 300],
                        3 => ['col' => 'col-start-3', 'row' => 'row-start-2', 'delay' => 200],
                        4 => ['col' => 'col-start-4', 'row' => 'row-start-2', 'delay' => 300],
                        5 => ['col' => 'col-start-1', 'row' => 'row-start-3', 'delay' => 100],
                        6 => ['col' => 'col-start-2', 'row' => 'row-start-3', 'delay' => 100],
                        7 => ['col' => 'col-start-3', 'row' => 'row-start-3', 'delay' => 100],
                        8 => ['col' => 'col-start-4', 'row' => 'row-start-3', 'delay' => 200],
                    ];

                    $positionsIf10Img = [
                        0 => ['col' => 'col-start-1 col-end-3', 'row' => 'row-start-1 row-end-3', 'delay' => 100],
                        1 => ['col' => 'col-start-3', 'row' => 'row-start-1', 'delay' => 200],
                        2 => ['col' => 'col-start-4', 'row' => 'row-start-1', 'delay' => 300],
                        3 => ['col' => 'col-start-3', 'row' => 'row-start-2', 'delay' => 200],
                        4 => ['col' => 'col-start-4', 'row' => 'row-start-2', 'delay' => 300],
                        5 => ['col' => 'col-start-1', 'row' => 'row-start-3', 'delay' => 100],
                        6 => ['col' => 'col-start-2', 'row' => 'row-start-3', 'delay' => 100],
                        7 => ['col' => 'col-start-1', 'row' => 'row-start-4', 'delay' => 100],
                        8 => ['col' => 'col-start-2', 'row' => 'row-start-4', 'delay' => 200],
                        9 => ['col' => 'col-start-3 col-end-5', 'row' => 'row-start-3 row-end-5', 'delay' => 100],
                    ];

                    // Determine which layout to use
                    $positions = match (true) {
                        $imageCount <= 4 => $positionsIf4Img,
                        $imageCount <= 8 => $positionsIf8Img,
                        default => $positionsIf10Img,
                    };
                @endphp


                @foreach ($positions as $i => $pos)
                    @if (!empty($data['images'][$i]))
                        <div data-aos="fade-up" data-aos-delay="{{ $pos['delay'] }}"
                            class="{{ $pos['col'] }} {{ $pos['row'] }} overflow-hidden rounded">
                            <button onclick="openModal({{ $i }})" class="hover-gallery block h-full w-full">
                                <img src="{{ $data['images'][$i] }}" alt="Image"
                                    class="hover-gallery-image h-full w-full object-cover" />
                            </button>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

</section>
<!-- END-SECTION: GALLERY -->

<!-- GALLERY MODAL -->
<div id="imageModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/80">
    <!-- Close button -->
    <button onclick="closeModal()" class="absolute top-4 right-4 text-3xl font-bold text-white hover:text-red-500">
        &times;
    </button>

    <!-- Prev -->
    <button onclick="prevImage()"
        class="absolute top-1/2 left-4 -translate-y-1/2 text-4xl font-bold text-white hover:text-gray-400">
        &#10094;
    </button>

    <!-- Image -->
    <div onclick="event.stopPropagation()" class="text-center">
        <img id="modalImage" class="mx-auto max-h-[80vh] max-w-[90vw] rounded-lg shadow-lg" />
        <div id="imageCounter" class="mt-4 text-sm font-semibold text-white"></div>
    </div>

    <!-- Next -->
    <button onclick="nextImage()"
        class="absolute top-1/2 right-4 -translate-y-1/2 text-4xl font-bold text-white hover:text-gray-400">
        &#10095;
    </button>
</div>
<!-- END-GALLERY MODAL -->


<script>
    // Gallery Modal
    const galleryImages = @json($data['images']);

    var currentIndex = 0;

    function openModal(index) {
        currentIndex = index;
        updateModalImage();
        document.getElementById("imageModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("imageModal").classList.add("hidden");
    }

    function updateModalImage() {
        const image = document.getElementById("modalImage");
        const counter = document.getElementById("imageCounter");

        image.src = galleryImages[currentIndex];
        counter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % galleryImages.length;
        updateModalImage();
    }

    function prevImage() {
        currentIndex =
            (currentIndex - 1 + galleryImages.length) % galleryImages.length;
        updateModalImage();
    }

    // Close on ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeModal();
        if (e.key === "ArrowRight") nextImage();
        if (e.key === "ArrowLeft") prevImage();
    });
</script>
