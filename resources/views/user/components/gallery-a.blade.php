<!-- SECTION: GALLERY -->
<section id="gallery" class="bg-color-bg-mid">
    @if (!empty($data['images']) && count($data['images']) > 0)
        <div class="relative container mx-auto grid grid-cols-4 grid-rows-4 gap-4 px-6 py-16">
            <!-- Title Block -->
            <div data-aos="fade-right" data-aos-delay="300" class="col-start-1 col-end-3 row-start-1 row-end-3 h-48 p-6">
                <h1 class="mb-2 text-2xl font-bold">Galeri Kami</h1>
                <p class="">Kumpulan momen, proses kreatif, dan perusahaan</p>
            </div>

            <!-- Image Blocks -->
            @php
                $imageCount = count($data['images'] ?? []);

                $gridRows = match (true) {
                    $imageCount <= 1 => 1,
                    $imageCount <= 3 => 2,
                    $imageCount <= 7 => 3,
                    default => 4,
                };

                $positions = [
                    0 => ['col' => 4, 'row' => 1],
                    1 => ['col' => 4, 'row' => 2],
                    2 => ['col' => 3, 'row' => 2],
                    3 => ['col' => 4, 'row' => 3],
                    4 => ['col' => 3, 'row' => 3],
                    5 => ['col' => 2, 'row' => 3],
                    6 => ['col' => 4, 'row' => 4],
                    7 => ['col' => 3, 'row' => 4],
                    8 => ['col' => 2, 'row' => 4],
                    9 => ['col' => 1, 'row' => 4],
                ];
            @endphp

            @foreach ($positions as $i => $pos)
                @if (!empty($data['images'][$i]))
                    <div data-aos="fade-up" data-aos-delay="{{ 100 + $i * 100 }}"
                        class="col-start-{{ $pos['col'] }} row-start-{{ $pos['row'] }} h-48 overflow-hidden rounded">
                        <button onclick="openModal({{ $i }})" class="hover-gallery block h-full w-full">
                            <img src="{{ $data['images'][$i] }}" alt="Image"
                                class="hover-gallery-image h-full w-full object-cover" />
                        </button>
                    </div>
                @endif
            @endforeach
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
