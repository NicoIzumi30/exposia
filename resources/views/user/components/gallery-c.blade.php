<!-- SECTION: GALLERY -->
<section id="gallery" class="bg-color-bg-mid">
    @php
        $imageCount = count($data['images'] ?? []);

        $gridRows = match (true) {
            $imageCount <= 3 => 1,
            $imageCount <= 6 => 2,
            default => 3,
        };
    @endphp

    @if ($imageCount > 0)
        <div class="container mx-auto flex justify-between px-6 py-16">
            <div class="relative grid flex-1 grid-cols-3 grid-rows-3 gap-4"
                style="grid-template-rows: repeat({{ $gridRows }}, 12rem)">
                @for ($i = 0; $i < 9; $i++)
                    @if (!empty($data['images'][$i]))
                        <div data-aos="fade-up" data-aos-delay="{{ 100 + ($i % 3) * 100 }}"
                            class="col-start-{{ ($i % 3) + 1 }} row-start-{{ intdiv($i, 3) + 1 }} overflow-hidden rounded">
                            <button onclick="openModal({{ $i }})" class="hover-gallery block h-full w-full">
                                <img src="{{ $data['images'][$i] }}" alt="Image"
                                    class="hover-gallery-image h-full w-full object-cover" />
                            </button>
                        </div>
                    @endif
                @endfor
            </div>

            <div class="hidden items-center lg:flex">
                <h1 data-aos="fade-left" class="mb-10 h-fit w-96 text-center text-3xl break-words whitespace-normal">
                    Kumpulan Momen, Proses Kreatif, dan Perusahaan
                </h1>
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
