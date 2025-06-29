<!-- SECTION: PRODUCTS -->
<section id="product" class="container mx-auto px-6 py-16">
    <!-- Section Header -->
    <h1 data-aos="fade-up" class="mb-10 text-center text-3xl font-bold">
        Produk Kami
    </h1>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 items-stretch gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($data['products'] as $product)
            <!-- Product Card -->
            <div data-aos="fade-up" data-aos-delay="100" class="h-full">
                <div
                    class="bg-color-bg-light hover-lift flex h-full flex-col overflow-hidden rounded-xl shadow transition hover:shadow-md">
                    <div class="m-4 overflow-hidden rounded-xl">
                        <img src="{{ $product['img'] }}" alt="Americano" class="h-48 w-full object-cover" />
                    </div>
                    <div class="flex flex-grow flex-col justify-between p-4">
                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <h2 class="text-lg font-semibold">{{ $product['title'] }}</h2>
                                <span class="font-bold">{{ $product['price'] }}</span>
                            </div>
                            <p class="mb-4 min-h-[64px] text-sm line-clamp-2">
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
                            class="bg-color-accent hover-scale text-color-bg-light mt-auto w-full rounded-xl p-2 text-center font-bold">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
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
