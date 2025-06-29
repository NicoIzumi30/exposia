<!-- SECTION: FOOTER -->
<footer class="bg-color-accent text-color-bg-light px-6 py-10">
    <!-- Top Row: 3 Columns -->
    <div class="container mx-auto mb-10 grid grid-cols-1 gap-12 md:grid-cols-3">
        <!-- Tentang UMKM -->
        <div>
            <h1 class="mb-3 text-xl font-bold">Tentang UMKM</h1>
            <p class="mb-4 flex items-center justify-between text-justify">
                {!! $data['description'] !!}
            </p>
        </div>
        <!-- Quick Link -->
        <div>
            <h1 class="mb-3 text-xl font-bold">Quick Link</h1>
            <ul class="space-y-2">
                <li><a href="#" class="hover:underline">Home</a></li>
                <li><a href="#" class="hover:underline">Produk</a></li>
                <li><a href="#" class="hover:underline">Galeri</a></li>
                <li><a href="#" class="hover:underline">Testimoni</a></li>
                <li><a href="#" class="hover:underline">Kontak</a></li>
            </ul>
        </div>

        <!-- Informasi Umum -->
        <div>
            <h1 class="mb-3 text-xl font-bold">Informasi Umum</h1>

            @foreach ($data['branches'] as $branch)
                <div class="mb-4 flex items-start space-x-2">
                    <span class="material-icons">location_on</span>
                    <div>
                        <a href="" class="font-semibold">{{ $branch['name'] }}</a>
                        <ul class="text-sm">
                            <li><a href="{{ $branch['address_link'] }}">{{ $branch['address'] }}</a></li>
                            <li>{{ $branch['opening_time'] }}</li>
                            <li>{{ $branch['phone_number'] }}</li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</footer>
<!-- END-SECTION: FOOTER -->
