<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full text-[12px] sm:text-[14px] lg:text-[16px]">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EXPOSIA - Buat Landing Page Tanpa Koding</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=gpp_maybe" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Local Styling -->
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            animation: scroll 20s linear infinite;
        }
    </style>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont',
                            'sans-serif'
                        ],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#012c4e',
                            600: '#011f37',
                            700: '#001529'
                        },
                        accent: {
                            400: '#fcab1b',
                            500: '#f59e0b',
                            600: '#d97706'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'card-entrance': 'cardSlideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'pulse-soft': 'pulseSoft 2s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            'from': {
                                opacity: '0'
                            },
                            'to': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            'from': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            'to': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        cardSlideUp: {
                            'from': {
                                transform: 'translateY(30px)',
                                opacity: '0'
                            },
                            'to': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        scaleIn: {
                            'from': {
                                transform: 'scale(0.95)',
                                opacity: '0'
                            },
                            'to': {
                                transform: 'scale(1)',
                                opacity: '1'
                            }
                        },
                        pulseSoft: {
                            '0%, 100%': {
                                opacity: '1'
                            },
                            '50%': {
                                opacity: '0.5'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Theme initialization -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') ||
                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            } else {
                document.documentElement.classList.add('light');
                document.body.classList.add('light');
            }

            console.log('Initial theme set:', theme);
        })();
    </script>

    <script>
        // Theme Manager
        class ThemeManager {
            constructor() {
                this.currentTheme = this.getStoredTheme();
                this.init();
            }

            getStoredTheme() {
                const stored = localStorage.getItem('theme');
                if (stored) return stored;

                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }

                return 'light';
            }

            init() {
                this.setTheme(this.currentTheme);

                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.addEventListener('click', () => this.toggleTheme());
                }
            }

            setTheme(theme) {
                this.currentTheme = theme;
                const html = document.documentElement;
                const body = document.body;
                const themeIcon = document.getElementById('theme-icon');

                // Remove existing theme classes
                html.classList.remove('dark', 'light');
                body.classList.remove('dark', 'light');

                // Add new theme class
                if (theme === 'dark') {
                    html.classList.add('dark');
                    body.classList.add('dark');

                    if (themeIcon) {
                        themeIcon.className = 'fas fa-sun text-yellow-400';
                    }
                } else {
                    html.classList.add('light');
                    body.classList.add('light');

                    if (themeIcon) {
                        themeIcon.className = 'fas fa-moon text-gray-600 dark:text-gray-300';
                    }
                }

                localStorage.setItem('theme', theme);
                window.dispatchEvent(new CustomEvent('themeChanged', {
                    detail: {
                        theme
                    }
                }));
            }

            toggleTheme() {
                const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
                this.showThemeChangeToast(newTheme);
            }

            showThemeChangeToast(theme) {
                const message = theme === 'dark' ? 'Mode gelap diaktifkan' : 'Mode terang diaktifkan';
                this.showToast(message, 'info', 2000);
            }

            showToast(message, type = 'info', duration = 3000) {
                const toast = document.createElement('div');
                toast.className =
                    `fixed bottom-4 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-y-full opacity-0 max-w-sm`;

                const bgColors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500',
                    info: 'bg-blue-500'
                };

                toast.classList.add(bgColors[type] || bgColors.info);

                toast.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-y-full', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 100);

                if (duration > 0) {
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.classList.add('translate-y-full', 'opacity-0');
                            setTimeout(() => {
                                if (toast.parentElement) {
                                    toast.remove();
                                }
                            }, 300);
                        }
                    }, duration);
                }
            }
        }
    </script>


</head>

<body class="h-fit bg-gray-50 dark:bg-gray-900 transition-colors duration-300 font-sans">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
        <nav class="flex items-center justify-between h-16 px-4 md:px-6">
            <img src="{{ asset('img/logo-full-dark.svg') }}" alt="" class="h-10 block dark:hidden">
            <img src="{{ asset('img/logo-full-light.svg') }}" alt="" class="h-10 hidden dark:block">
            <ul class="flex space-x-2 sm:space-x-4">
                <a href="{{ route('report.create') }}"
                    class="hidden sm:inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-800 text-sm font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                    <span class="material-icons-outlined text-sm mr-2">report</span>
                    Laporkan Website
                </a>
                <button id="theme-toggle"
                    class="py-1 px-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <i id="theme-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                </button>
                <li class=""><a href="{{ route('login') }}"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-sm font-medium rounded-lg transition-colors duration-200">Login</a>
                </li>
                <li class=""><a href="{{ route('register') }}"
                        class="inline-flex items-center px-3 py-2 bg-gray-800 hover:bg-gray-600 text-white text-sm font-medium rounded-lg  transition-colors duration-200">Register</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="h-screen flex items-center relative text-center text-gray-800 dark:text-white p-5">
        <div class="rounded-2xl w-full bg-gray-400 h-full flex items-center">
            <div class="max-w-6xl mx-auto px-6">
                <div class="mt-24">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-6 text-gray-800 animate-fade-in-up">
                        Bangun brand kamu. Tampil profesional. Raih lebih banyak pelanggan.
                    </h1>
                    <p class="text-lg md:text-xl text-gray-800 max-w-2xl mx-auto mb-10 animate-fade-in-up delay-200">
                        Exposia bantu kamu bikin profil bisnis online dalam hitungan menit — tanpa perlu ngoding, tanpa
                        ribet.
                    </p>
                    <a href="{{ route('login') }}"
                        class="inline-block px-8 py-3 rounded-lg font-semibold text-lg text-white bg-gray-800 transition hover:bg-gray-600">
                        Buat Website Sekarang
                    </a>

                </div>

                <!-- Responsive Container with 3D on md+ -->
                <div
                    class="-mx-6 md:-mx-16 lg:-mx-32 xl:-mx-48 px-6 flex justify-center [perspective:1000px] mb-20 gap-4">
                    <!-- Left Image (hidden on mobile) -->
                    <div class="hidden md:block py-20 [transform:rotateY(-30deg)_scale(0.9)_translateX(20%)]">
                        <img src="{{ asset('img/Hero-Image.png') }}" alt="Hero Image" class="rounded-lg" />
                    </div>

                    <!-- Center Image (always visible) -->
                    <div class="py-20">
                        <img src="{{ asset('img/Hero-Image.png') }}" alt="Hero Image" class="rounded-lg" />
                    </div>

                    <!-- Right Image (hidden on mobile) -->
                    <div class="hidden md:block py-20 [transform:rotateY(30deg)_scale(0.9)_translateX(-20%)]">
                        <img src="{{ asset('img/Hero-Image.png') }}" alt="Hero Image" class="rounded-lg" />
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Fitur Unggulan -->
    <section class="py-20 text-gray-800 dark:text-white">
        <div
            class="bg-gray-400 w-fit rounded-2xl px-16 py-20 mx-auto flex  items-center relative flex-col md:flex-row gap-16">
            {{-- Market Absolute Icon --}}
            <div class="absolute top-5 left-5 bg-gray-800 p-2 rounded-full h-12 w-12 flex justify-center items-center">
                <i class="fa-solid fa-store text-white"></i>
            </div>
            {{-- Features Card --}}
            <div class="bg-gray-800 rounded-2xl p-5">
                <img src="{{ asset('img/logo-light.svg') }}" alt="" class="h-10 block">
                <h2 class="font-semibold text-white my-5">FEATURES</h2>
                <div class="bg-[#47929C] rounded-2xl flex items-center p-3 mb-3">
                    <div class="p-2 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                        <i class="fa-solid fa-store text-gray-800"></i>
                    </div>
                    <p class="text-white w-48">AI Konten Generatif</p>
                </div>
                <div class="bg-[#8361E9] rounded-2xl flex items-center p-3 mb-3">
                    <div class="p-2 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                        <i class="fa-solid fa-store text-gray-800"></i>
                    </div>
                    <p class="text-white w-48">Template Variatif</p>
                </div>
                <div class="bg-[#777777] rounded-2xl flex items-center p-3 mb-3">
                    <div class="p-2 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                        <i class="fa-solid fa-store text-gray-800"></i>
                    </div>
                    <p class="text-white w-48">Responsif</p>
                </div>
            </div>
            <div class="space-y-5 max-w-lg">
                <h2 class="text-4xl font-bold mb-5 text-gray-800">
                    Bikin Website UMKM Tanpa Ribet
                </h2>

                <div class="flex text-gray-800">
                    <i class="fa-solid fa-check mr-2 mt-1"></i>
                    <p>
                        Cukup klik, pilih template, dan isi konten. Cocok untuk pelaku usaha pemula sekalipun.
                    </p>
                </div>
                <div class="flex text-gray-800">
                    <i class="fa-solid fa-check mr-2 mt-1"></i>
                    <p>
                        Tak perlu bingung nulis deskripsi. Exposia bantu buatkan konten dari info sederhana tentang
                        usahamu.
                    </p>
                </div>
                <div class="flex text-gray-800">
                    <i class="fa-solid fa-check mr-2 mt-1"></i>
                    <p>Desain responsif, otomatis menyesuaikan layar HP pelangganmu siap dibagikan lewat link,
                        Instagram, atau QR code. </p>
                </div>
            </div>

        </div>
    </section>

    <!-- Variasi Template Page -->
    <section class="py-20 text-gray-800 dark:text-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl font-semibold mb-10 text-center">
                Variasi Template Sesuai Keinginan Anda
            </h2>
            <p class="w-full text-lg mb-10 text-center">
                Jelajahi 100+ template website, dapat dikustomisasi sesuai keinginan anda.
            </p>
            <!-- Slider Wrapper -->
            <div class="overflow-hidden relative space-y-6">
                <!-- Top Slider -->
                <div class="flex animate-scroll gap-6 w-max">
                    <img src="{{ asset('img/Hero-1.png') }}" alt="Hero 1" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-2.png') }}" alt="Hero 2" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-3.png') }}" alt="Hero 3" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-1.png') }}" alt="Hero 1" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-2.png') }}" alt="Hero 2" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-3.png') }}" alt="Hero 3" class="rounded-2xl h-64" />
                </div>
                <!-- Offset Bottom Slider -->
                <div class="flex animate-scroll gap-6 w-max -ml-60">
                    <img src="{{ asset('img/Hero-1.png') }}" alt="Hero 1" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-2.png') }}" alt="Hero 2" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-3.png') }}" alt="Hero 3" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-1.png') }}" alt="Hero 1" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-2.png') }}" alt="Hero 2" class="rounded-2xl h-64" />
                    <img src="{{ asset('img/Hero-3.png') }}" alt="Hero 3" class="rounded-2xl h-64" />
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja -->
    <section class="py-20 text-gray-800 dark:text-white">
        <div class="max-w-6xl mx-auto px-6 mb-10">
            <h2 class="text-4xl font-semibold mb-6 text-center">
                Cara Mudah Bikin Website UMKM-mu Sendiri </h2>
        </div>
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row gap-10">
            <!-- Phone Image -->
            <div class="w-full hidden lg:w-[350px] lg:block shrink-0">
                <img src="{{ asset('img/PhoneLanding.webp') }}" alt="Hero Image" class="w-auto h-auto rounded-2xl" />
            </div>
            <div class="mx-auto max-w-2xl lg:max-w-full flex flex-col justify-between">
                <ul class="">
                    <li class="flex border-b border-gray-500 py-5 items-center">
                        <div
                            class="text-2xl p-2 text-center border border-gray-500 rounded-full h-12 w-12 shrink-0 mr-4">
                            1
                        </div>
                        <div class="flex items-center w-full">
                            <div class="flex-1 text-lg font-semibold">Daftar Gratis</div>
                            <div class="flex-1">Buat akun Exposia hanya dengan email aktif. Tidak dipungut biaya!</div>
                        </div>
                    </li>
                    <li class="flex border-b border-gray-500 py-5 items-center">
                        <div
                            class="text-2xl p-2 text-center border border-gray-500 rounded-full h-12 w-12 shrink-0 mr-4">
                            2
                        </div>
                        <div class="flex items-center w-full">
                            <div class="flex-1 text-lg font-semibold">Isi Konten</div>
                            <div class="flex-1">Buat akun Exposia hanya dengan email aktif. Tidak dipungut biaya!</div>
                        </div>
                    </li>
                    <li class="flex border-b border-gray-500 py-5 items-center">
                        <div
                            class="text-2xl p-2 text-center border border-gray-500 rounded-full h-12 w-12 shrink-0 mr-4">
                            3
                        </div>
                        <div class="flex items-center w-full">
                            <div class="flex-1 text-lg font-semibold">Pilih Template & Warna</div>
                            <div class="flex-1">Buat akun Exposia hanya dengan email aktif. Tidak dipungut biaya!</div>
                        </div>
                    </li>
                    <li class="flex border-b border-gray-500 py-5 items-center">
                        <div
                            class="text-2xl p-2 text-center border border-gray-500 rounded-full h-12 w-12 shrink-0 mr-4">
                            4
                        </div>
                        <div class="flex items-center w-full">
                            <div class="flex-1 text-lg font-semibold">Publikasi & Bagikan</div>
                            <div class="flex-1">Buat akun Exposia hanya dengan email aktif. Tidak dipungut biaya!</div>
                        </div>
                    </li>
                </ul>
                <div class="flex items-center gap-10 mt-5">
                    <p>Platform ini dirancang untuk memudahkan siapa pun membuat website bisnis, tanpa pengalaman
                        teknis.
                        Cukup klik, atur, tayangkan.</p>
                    <div class="border border-gray-500 py-4 px-8 rounded-2xl">
                        <h1 class="font-bold text-[3rem]">75%</h1>
                        <p>UMKM yang menggunakan Exposia mengalami peningkatan kepercayaan pelanggan dalam 2 minggu
                            pertama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni -->
    <section class="flex items-center relative overflow-hidden text-white p-5">
        <div class="rounded-2xl w-full bg-gray-800 px-5 py-20">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex justify-between mb-5">
                    <h2 class="text-4xl font-semibold mb-10 md:text-left">
                        <p>Apa Kata UMKM</p>
                        <p>yang Sudah Pakai?</p>
                    </h2>

                    <!-- Arrows -->
                    <div class="flex justify-end items-center gap-4 mb-4">
                        <button id="prevBtn"
                            class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-600 text-white hover:bg-gray-500 transition">
                            <span class="material-icons">chevron_left</span>
                        </button>
                        <button id="nextBtn"
                            class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-600 text-white hover:bg-gray-500 transition">
                            <span class="material-icons">chevron_right</span>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Scroll Wrapper -->
                <div id="testimonialSlider"
                    class="hide-scrollbar flex space-x-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4">
                    <!-- Testimony Card -->
                    <div
                        class="w-[90%] sm:w-[60%] md:w-[40%] shrink-0 snap-start bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-white shadow-sm p-8 rounded-2xl backdrop-blur text-gray-800 flex flex-col justify-between">

                        <p class="mb-10 text-justify">
                            Awalnya bingung bikin web jualan, tapi EXPOSIA bikin semuanya simpel. Dalam 1 jam, landing
                            page
                            jualan saya langsung jadi!
                        </p>
                        <div>
                            <p class="font-semibold">Rina</p>
                            <p class="">Pemilik RinaSnack</p>
                        </div>
                    </div>

                    <!-- Testimony Card -->
                    <div
                        class="w-[90%] sm:w-[60%] md:w-[40%] shrink-0 snap-start bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-white shadow-sm p-8 rounded-2xl backdrop-blur text-gray-800 flex flex-col justify-between">

                        <p class="mb-10 text-justify">
                            Saya suka karena bisa pilih layout tiap bagian. Lebih fleksibel dan nggak monoton.
                        </p>
                        <div>
                            <p class="font-semibold">Yusuf</p>
                            <p class="">Owner Kopi Kulo Kita</p>
                        </div>
                    </div>

                    <!-- Testimony Card -->
                    <div
                        class="w-[90%] sm:w-[60%] md:w-[40%] shrink-0 snap-start bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-white shadow-sm p-8 rounded-2xl backdrop-blur text-gray-800 flex flex-col justify-between">

                        <p class="mb-10 text-justify">
                            Saya suka karena bisa pilih layout tiap bagian. Lebih fleksibel dan nggak monoton.
                        </p>
                        <div>
                            <p class="font-semibold">Yusuf</p>
                            <p class="">Owner Kopi Kulo Kita</p>
                        </div>
                    </div>

                    <!-- Testimony Card -->
                    <div
                        class="w-[90%] sm:w-[60%] md:w-[40%] shrink-0 snap-start bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-white shadow-sm p-8 rounded-2xl backdrop-blur text-gray-800 flex flex-col justify-between">

                        <p class="mb-10 text-justify">
                            Saya suka karena bisa pilih layout tiap bagian. Lebih fleksibel dan nggak monoton.
                        </p>
                        <div>
                            <p class="font-semibold">Yusuf</p>
                            <p class="">Owner Kopi Kulo Kita</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-20">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl dark:text-white text-gray-800 text-center font-semibold mb-12">
                Frequently Asked Questions
            </h2>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- FAQ List -->
                <div class="flex-1 space-y-6">
                    <!-- FAQ Card -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow group cursor-pointer transition border border-gray-200 dark:border-gray-700"
                        onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                Apakah EXPOSIA benar-benar tidak butuh koding?
                            </h3>
                            <span
                                class="text-2xl text-gray-800 transition-transform duration-300 group-[.open]:rotate-45 dark:text-white">+</span>
                        </div>
                        <div class="mt-4 text-gray-700 dark:text-gray-200 hidden group-[.open]:block">
                            Ya! Kamu tidak perlu menulis satu baris kode pun. Cukup isi data dan pilih template yang
                            diinginkan.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow group cursor-pointer transition border border-gray-200 dark:border-gray-700"
                        onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                Apakah gratis selamanya?
                            </h3>
                            <span
                                class="text-2xl text-gray-800 transition-transform duration-300 group-[.open]:rotate-45 dark:text-white">+</span>
                        </div>
                        <div class="mt-4 text-gray-700 dark:text-gray-200 hidden group-[.open]:block">
                            EXPOSIA menyediakan versi gratis dengan fitur dasar. Untuk fitur lanjutan dan domain custom,
                            tersedia paket premium.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow group cursor-pointer transition border border-gray-200 dark:border-gray-700"
                        onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                Apakah halaman saya akan mobile friendly?
                            </h3>
                            <span
                                class="text-2xl text-gray-800 transition-transform duration-300 group-[.open]:rotate-45 dark:text-white">+</span>
                        </div>
                        <div class="mt-4 text-gray-700 dark:text-gray-200 hidden group-[.open]:block">
                            Ya, semua template EXPOSIA sudah dirancang untuk tampil optimal di HP, tablet, dan desktop.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow group cursor-pointer transition border border-gray-200 dark:border-gray-700"
                        onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                Berapa lama proses pembuatan landing page?
                            </h3>
                            <span
                                class="text-2xl text-gray-800 transition-transform duration-300 group-[.open]:rotate-45 dark:text-white">+</span>
                        </div>
                        <div class="mt-4 text-gray-700 dark:text-gray-200 hidden group-[.open]:block">
                            Hanya butuh beberapa menit! Setelah mengisi informasi dan memilih layout, halaman kamu
                            langsung siap digunakan.
                        </div>
                    </div>

                    <!-- Add more FAQ items similarly -->
                </div>

                <!-- CTA Card -->
                <div
                    class="w-full h-fit lg:w-1/3 bg-white dark:bg-gray-800 p-6 text-gray-800 dark:text-white rounded-xl shadow group cursor-pointer transition p-6 rounded-xl shadow flex flex-col items-start gap-4 border border-gray-200 dark:border-gray-700">
                    <span class="material-icons-outlined text-gray-800 dark:text-white text-4xl">
                        chat
                    </span>
                    <h3 class="text-2xl font-semibold">Masih ada pertanyaan?</h3>
                    <p>Kami siap membantu kamu. Jangan ragu untuk menghubungi tim support kami kapan saja!</p>
                    <button
                        class="w-full mt-5 px-5 py-4 rounded-lg bg-gray-800 text-white font-semibold dark:bg-gray-700 hover:bg-gray-500 dark:hover:bg-gray-500 transition">
                        Hubungi Kami
                    </button>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Final -->
    <section id="daftar" class="text-gray-800 bg-gray-400 dark:bg-gray-800 dark:text-white">
        <div class="max-w-6xl mx-auto px-6 py-20 relative">
            <div class="max-w-xl text-center mx-auto md:text-left md:mx-0">
                <h2 class="text-4xl font-semibold mb-4">Mulai Buat Landing Page Pertamamu Hari Ini!</h2>
                <p class="text-lg text-gray-800 dark:text-white mb-8">
                    Daftar gratis dan rasakan kemudahan EXPOSIA. Waktunya UMKM naik kelas secara digital.
                </p>
                <a href="{{ route('login') }}"
                    class="inline-block px-8 py-3 rounded-lg font-semibold text-lg text-white bg-gray-800 transition dark:bg-gray-700 hover:bg-gray-500 dark:hover:bg-gray-500">
                    Coba Gratis Sekarang
                </a>
            </div>
            <img src="/img/PhoneLandingCrop.webp" alt="Phone Image"
                class="w-72 absolute right-10 bottom-0 hidden md:block">
        </div>
    </section>


    <!-- Footer -->
    <footer class=" text-gray-800 dark:text-white">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div
                class="pt-6 flex flex-col md:flex-row items-center justify-between text-gray-800 dark:text-white text-sm">
                <p>&copy; 2025 EXPOSIA. Semua hak cipta dilindungi.</p>

                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 flex items-center justify-center transition">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>


    <script>
        // Toggle FAQ
        function toggleFAQ(el) {
            el.classList.toggle("open");
        }

        // Scroll Navigation Script
        const container = document.getElementById("testimonialSlider");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        const scrollAmount = 300;

        nextBtn.addEventListener("click", () => {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        prevBtn.addEventListener("click", () => {
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });


        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            window.themeManager = new ThemeManager();
            window.sidebarManager = new SidebarManager();

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                const profileDropdown = document.getElementById('profile-dropdown');
                const profileButton = document.getElementById('user-profile-button');

                if (profileDropdown && profileButton) {
                    if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                        closeProfileDropdown();
                    }
                }
            });

            console.log('User dashboard initialized with CDN Tailwind');
        });
    </script>
</body>

</html>
