<!-- SECTION: PRODUCTS -->
<section id="product" class="container mx-auto px-6 py-16">
    <!-- Header: Title + Buttons -->
    <div class="mb-6 flex items-center justify-between">
        <h2 data-aos="fade-up" data-aos-delay="300" class="text-3xl font-bold">
            Produk Kami
        </h2>
        <div data-aos="fade-up" data-aos-delay="300" class="flex space-x-2">
            <button id="btnLeft"
                class="bg-color-accent text-color-bg-light flex h-12 w-12 items-center justify-center rounded-full">
                <span class="material-icons" onclick="scrollProductLeft()">chevron_left</span>
            </button>
            <button id="btnRight"
                class="bg-color-accent text-color-bg-light flex h-12 w-12 items-center justify-center rounded-full">
                <span class="material-icons" onclick="scrollProductRight()">chevron_right</span>
            </button>
        </div>
    </div>

    <div class="relative">
        <!-- Left Gradient Overlay -->
        <div
            class="from-color-bg-dark pointer-events-none absolute top-0 -left-1 z-10 h-full w-8 bg-gradient-to-r to-transparent">
        </div>

        <!-- Right Gradient Overlay -->
        <div
            class="from-color-bg-dark pointer-events-none absolute top-0 -right-1 z-10 h-full w-8 bg-gradient-to-l to-transparent">
        </div>

        <!-- Scrollable Card List -->
        <div data-aos="fade-up" data-aos-delay="300" id="productScroll"
            class="scrollbar-hide snap-x snap-mandatory overflow-x-auto scroll-smooth px-8">
            <div class="flex min-w-full space-x-4">
                @foreach ($data['products'] as $product)
                    <!-- Product Card -->
                    <div class="flex w-60 flex-shrink-0 snap-start flex-col justify-between p-4">
                        <div class="relative flex items-center justify-center">
                            <div
                                class="bg-color-accent absolute inset-x-0 -z-10 mx-auto h-full w-full scale-y-[0.8] rounded-full">
                            </div>
                            <img src="{{ $product['img'] }}" alt="Product"
                                class="hover-scale z-10 mb-3 h-48 w-full rounded-full object-cover p-4" />
                        </div>
                        <div class="flex flex-grow flex-col justify-start">
                            <h3 class="mb-2 text-lg font-semibold">{{ $product['title'] }}</h3>
                            <p class="mb-2 text-sm line-clamp-2">
                                {{ $product['description'] }}
                            </p>
                        </div>
                        <button
                            onclick="openProductModal(
                                {{ json_encode($product['title']) }},
                                {{ json_encode($product['description']) }},
                                {{ json_encode($product['img']) }},
                                {{ json_encode($product['price']) }}
                             )"
                            class="bg-color-accent
                            hover-scale text-color-bg-light w-full rounded-xl p-2 text-center font-bold">
                            Lihat Detail
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- END-SECTION: PRODUCTS -->

<!-- PRODUCT MODAL -->
<div id="productModal" class="modal-overlay fixed inset-0 z-50 flex hidden items-center justify-center p-4">
    <div class="bg-color-bg-light modal-content max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-3xl p-8">
        <!-- Close button -->
        <button onclick="closeProductModal()"
            class="text-text-secondary hover:text-text-primary absolute top-4 right-4 text-4xl font-bold transition-colors">
            &times;
        </button>

        <!-- Modal Content -->
        <div class="grid grid-cols-1 items-center gap-8 md:grid-cols-2">
            <!-- Product Image -->
            <div class="text-center">
                <img id="productModalImage" class="shadow-large mx-auto h-80 w-80 rounded-full object-cover"
                    alt="Product" />
            </div>

            <!-- Product Details -->
            <div class="text-center md:text-left">
                <h2 id="productModalTitle" class="mb-4 text-4xl font-bold"></h2>
                <div id="productModalPrice" class="text-color-accent mb-8 text-4xl font-bold"></div>

                <div class="mb-8">
                    <p id="productModalDescription" class="text-text-secondary text-lg leading-relaxed"></p>
                </div>

                <!-- Action Button -->
                <div>
                    <button onclick="orderNow()"
                        class="hover-lift bg-color-accent text-color-bg-light w-full rounded-xl py-4 text-xl font-semibold">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END-PRODUCT MODAL -->
