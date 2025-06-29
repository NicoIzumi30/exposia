<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

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
                <button id="theme-toggle"
                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <i id="theme-icon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                </button>
                <li class=""><a href="{{ route('login') }}"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm font-medium rounded-lg transition-colors duration-200">Login</a>
                </li>
                <li class=""><a href="{{ route('register') }}"
                        class="inline-flex items-center px-3 py-2 bg-accent-500 hover:bg-accent-600 text-white text-sm font-medium rounded-lg  transition-colors duration-200">Register</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section
        class="min-h-screen bg-gradient-to-t from-accent-400 to-transparent flex items-center relative overflow-hidden text-center text-gray-900 dark:text-white">
        <!-- Floating Shapes (Optional if keeping) -->
        <div class="absolute inset-0 -z-10">
            <div
                class="absolute w-20 h-20 top-[20%] left-[10%] rounded-full bg-yellow-500/10 animate-[float_6s_ease-in-out_infinite]">
            </div>
            <div
                class="absolute w-32 h-32 top-[60%] right-[10%] rounded-full bg-yellow-500/10 animate-[float_6s_ease-in-out_infinite] delay-2000">
            </div>
            <div
                class="absolute w-16 h-16 bottom-[20%] left-[20%] rounded-full bg-yellow-500/10 animate-[float_6s_ease-in-out_infinite] delay-4000">
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 z-10">
            <div class="mt-36">
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-6 text-accent-400 animate-fade-in-up">
                    Buat Landing Page Profesional Tanpa Perlu Koding
                </h1>
                <p
                    class="text-lg md:text-xl text-gray-900 dark:text-white max-w-2xl mx-auto mb-10 animate-fade-in-up delay-200">
                    Buat website untuk bisnis anda dengan mudah, cepat, dan profesional
                </p>
                <a href="{{ route('login') }}"
                    class="inline-block px-8 py-3 rounded-lg font-semibold text-lg text-white bg-accent-400 transition hover:bg-accent-600">
                    Coba Gratis Sekarang
                </a>

            </div>

            <div class="mb-16 p-20">
                <img src="{{ asset('img/Hero-Image.png') }}" alt="Hero Image" class="rounded-2xl mx-auto" />
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section id="fitur" class="py-20 text-gray-900 dark:text-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl font-semibold mb-10 text-accent-400 text-center">
                Fitur yang Memudahkan Bisnis Kamu
            </h2>

            <div class="flex flex-wrap justify-center gap-6">
                <!-- Tanpa Koding -->
                <div
                    class="group w-full sm:w-[45%] lg:w-[30%] max-w-sm flex-shrink-0 bg-white dark:bg-gray-800 p-8 text-gray-900 dark:text-white rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-accent-400 hover:text-white dark:hover:bg-accent-400 dark:hover:text-gray-900 transition">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-icons-outlined text-accent-400 group-hover:text-white text-2xl">
                            code_off
                        </span>
                        <h3 class="text-xl font-semibold">Tanpa Koding</h3>
                    </div>
                    <p>Nggak perlu ngerti HTML, tinggal isi dan pilih.</p>
                </div>

                <!-- Template Variatif -->
                <div
                    class="group w-full sm:w-[45%] lg:w-[30%] max-w-sm flex-shrink-0 bg-white dark:bg-gray-800 p-8 text-gray-900 dark:text-white rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-accent-400 hover:text-white dark:hover:bg-accent-400 dark:hover:text-gray-900 transition">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-icons-outlined text-accent-400 group-hover:text-white text-2xl">
                            view_quilt
                        </span>
                        <h3 class="text-xl font-semibold">Template Variatif</h3>
                    </div>
                    <p>Pilih layout tiap section sesuai selera dan kebutuhan.</p>
                </div>

                <!-- Cepat & Efisien -->
                <div
                    class="group w-full sm:w-[45%] lg:w-[30%] max-w-sm flex-shrink-0 bg-white dark:bg-gray-800 p-8 text-gray-900 dark:text-white rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-accent-400 hover:text-white dark:hover:bg-accent-400 dark:hover:text-gray-900 transition">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-icons-outlined text-accent-400 group-hover:text-white text-2xl">
                            speed
                        </span>
                        <h3 class="text-xl font-semibold">Cepat & Efisien</h3>
                    </div>
                    <p>Buat landing page cuma dalam hitungan menit.</p>
                </div>

                <!-- Mobile Friendly -->
                <div
                    class="group w-full sm:w-[45%] lg:w-[30%] max-w-sm flex-shrink-0 bg-white dark:bg-gray-800 p-8 text-gray-900 dark:text-white rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-accent-400 hover:text-white dark:hover:bg-accent-400 dark:hover:text-gray-900 transition">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-icons-outlined text-accent-400 group-hover:text-white text-2xl">
                            smartphone
                        </span>
                        <h3 class="text-xl font-semibold">Mobile Friendly</h3>
                    </div>
                    <p>Desain otomatis menyesuaikan dengan HP.</p>
                </div>

                <!-- Fokus Jualan -->
                <div
                    class="group w-full sm:w-[45%] lg:w-[30%] max-w-sm flex-shrink-0 bg-white dark:bg-gray-800 p-8 text-gray-900 dark:text-white rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-accent-400 hover:text-white dark:hover:bg-accent-400 dark:hover:text-gray-900 transition">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-icons-outlined text-accent-400 group-hover:text-white text-2xl">
                            shopping_bag
                        </span>
                        <h3 class="text-xl font-semibold">Fokus Jualan</h3>
                    </div>
                    <p>Cocok buat promosi produk, jasa, atau event UMKM.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Variasi Template Page -->
    <section class="py-20 text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl font-semibold mb-10 text-accent-400 text-center">
                Variasi Template Sesuai Keinginan Anda
            </h2>
            <p class="w-full text-lg text-gray-900 dark:text-white mb-10 text-center">
                Jelajahi 100+ template website, dapat dikustomisasi sesuai keinginan anda.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
                <div class="">
                    <img src="{{ asset('img/Hero-1.png') }}" alt="Hero Image" class="rounded-2xl mx-auto" />
                </div>
                <div class="">
                    <img src="{{ asset('img/Hero-2.png') }}" alt="Hero Image" class="rounded-2xl mx-auto" />
                </div>
                <div class="">
                    <img src="{{ asset('img/Hero-3.png') }}" alt="Hero Image" class="rounded-2xl mx-auto" />
                </div>
                <div class="">
                    <img src="{{ asset('img/Hero-4.png') }}" alt="Hero Image" class="rounded-2xl mx-auto" />
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni -->
    <section class="py-20 text-gray-900 dark:text-white relative">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between">
                <h2 class="text-4xl font-semibold mb-10 text-accent-400 md:text-left">
                    Apa Kata UMKM yang Sudah Pakai?
                </h2>

                <!-- Arrows -->
                <div class="flex justify-end items-center gap-4 mb-4">
                    <button id="prevBtn"
                        class="w-12 h-12 flex items-center justify-center rounded-full bg-accent-400 text-white hover:bg-accent-500 transition">
                        <span class="material-icons">chevron_left</span>
                    </button>
                    <button id="nextBtn"
                        class="w-12 h-12 flex items-center justify-center rounded-full bg-accent-400 text-white hover:bg-accent-500 transition">
                        <span class="material-icons">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Horizontal Scroll Wrapper -->
            <div id="testimonialSlider"
                class="hide-scrollbar flex space-x-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4">

                <!-- Testimonial 1 -->
                <div
                    class="min-w-[90%] max-w-3xl sm:min-w-[70%] md:min-w-[45%] lg:min-w-[33%] shrink-0 snap-start bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 p-8 rounded-2xl backdrop-blur relative">
                    <span class="absolute text-5xl text-accent-400 top-0 left-4">"</span>
                    <p class="italic mb-4 text-gray-900 dark:text-white">
                        Awalnya bingung bikin web jualan, tapi EXPOSIA bikin semuanya simpel. Dalam 1 jam, landing page
                        jualan saya langsung jadi!
                    </p>
                    <p class="font-semibold text-accent-400">— Rina, Pemilik RinaSnack</p>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="min-w-[90%] max-w-3xl sm:min-w-[70%] md:min-w-[45%] lg:min-w-[33%] shrink-0 snap-start bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 p-8 rounded-2xl backdrop-blur relative">
                    <span class="absolute text-5xl text-accent-400 top-0 left-4">"</span>
                    <p class="italic mb-4 text-gray-900 dark:text-white">
                        Saya suka karena bisa pilih layout tiap bagian. Lebih fleksibel dan nggak monoton.
                    </p>
                    <p class="font-semibold text-accent-400">— Yusuf, Owner Kopi Kulo Kita</p>
                </div>

                <!-- Tambah testimoni lagi jika perlu -->
            </div>
        </div>
    </section>

    <!-- Cara Kerja -->
    <section class="py-20 text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl font-semibold text-accent-400 mb-6">
                Cara Membuat Website Gratis
            </h2>
            <p class="mb-12 text-base text-gray-700 dark:text-gray-300">4 langkah sederhana untuk membuat website di
                Exposia.</p>
        </div>
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row gap-10">
            <!-- Timeline -->
            <ul class="w-full md:w-1/2 pl-3 ml-6 relative border-l border-gray-300 dark:border-gray-600">
                <li class="mb-10 ml-6">
                    <div
                        class="absolute w-8 h-8 bg-accent-400 rounded-full -left-4 flex items-center justify-center font-bold text-white dark:text-gray-900 ring-4 ring-gray-100 dark:ring-gray-800">
                        1
                    </div>
                    <h3 class="text-lg font-semibold">Daftar ke Exposia</h3>
                    <p class="text-gray-700 dark:text-gray-300 mt-1">Buat akun dan login ke Exposia.</p>
                </li>
                <li class="mb-10 ml-6">
                    <div
                        class="absolute w-8 h-8 bg-accent-400 rounded-full -left-4 flex items-center justify-center font-bold text-white dark:text-gray-900 ring-4 ring-gray-100 dark:ring-gray-800">
                        2
                    </div>
                    <h3 class="text-lg font-semibold">Isi Informasi Bisnis</h3>
                    <p class="text-gray-700 dark:text-gray-300 mt-1">Masukkan nama bisnis, produk, kontak, dll.</p>
                </li>
                <li class="mb-10 ml-6">
                    <div
                        class="absolute w-8 h-8 bg-accent-400 rounded-full -left-4 flex items-center justify-center font-bold text-white dark:text-gray-900 ring-4 ring-gray-100 dark:ring-gray-800">
                        3
                    </div>
                    <h3 class="text-lg font-semibold">Pilih Template Tiap Section</h3>
                    <p class="text-gray-700 dark:text-gray-300 mt-1">Sesuaikan layout sesuai kebutuhan.</p>
                </li>
                <li class="ml-6">
                    <div
                        class="absolute w-8 h-8 bg-accent-400 rounded-full -left-4 flex items-center justify-center font-bold text-white dark:text-gray-900 ring-4 ring-gray-100 dark:ring-gray-800">
                        4
                    </div>
                    <h3 class="text-lg font-semibold">Landing Page Siap Online!</h3>
                    <p class="text-gray-700 dark:text-gray-300 mt-1">Bagikan link ke pelanggan dan mulai jualan.</p>
                </li>
            </ul>

            <!-- Image -->
            <div class="w-full hidden md:w-1/2 md:block">
                <img src="{{ asset('img/steps.svg') }}" alt="Hero Image"
                    class="w-full h-auto rounded-2xl h-auto max-h-80" />
            </div>
        </div>

    </section>



    <!-- FAQ -->
    <section id="faq" class="py-20 text-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl font-semibold mb-12 text-accent-400">
                Frequently Asked Questions
            </h2>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- FAQ List -->
                <div class="flex-1 space-y-6">
                    <!-- FAQ Card -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow group cursor-pointer transition border border-gray-200 dark:border-gray-700"
                        onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Apakah EXPOSIA benar-benar tidak butuh koding?
                            </h3>
                            <span
                                class="text-2xl text-accent-400 transition-transform duration-300 group-[.open]:rotate-45">+</span>
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
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Apakah gratis selamanya?
                            </h3>
                            <span
                                class="text-2xl text-accent-400 transition-transform duration-300 group-[.open]:rotate-45">+</span>
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
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Apakah halaman saya akan mobile friendly?
                            </h3>
                            <span
                                class="text-2xl text-accent-400 transition-transform duration-300 group-[.open]:rotate-45">+</span>
                        </div>
                        <div class="mt-4 text-gray-700 dark:text-gray-200 hidden group-[.open]:block">
                            Ya, semua template EXPOSIA sudah dirancang untuk tampil optimal di HP, tablet, dan desktop.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow group cursor-pointer transition border border-gray-200 dark:border-gray-700"
                        onclick="toggleFAQ(this)">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Berapa lama proses pembuatan landing page?
                            </h3>
                            <span
                                class="text-2xl text-accent-400 transition-transform duration-300 group-[.open]:rotate-45">+</span>
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
                    class="w-full h-fit lg:w-1/3 bg-white dark:bg-gray-800 p-6 text-gray-900 dark:text-white rounded-xl shadow group cursor-pointer transition p-6 rounded-xl shadow flex flex-col items-start gap-4 border border-gray-200 dark:border-gray-700">
                    <span class="material-icons-outlined text-accent-400 text-4xl">
                        chat
                    </span>
                    <h3 class="text-2xl font-semibold">Masih ada pertanyaan?</h3>
                    <p>Kami siap membantu kamu. Jangan ragu untuk menghubungi tim support kami kapan saja!</p>
                    <button
                        class="w-full mt-5 px-5 py-4 rounded-lg bg-accent-400 text-white font-semibold hover:bg-accent-600 transition">
                        Hubungi Kami
                    </button>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Final -->
    <section id="daftar" class="py-20 text-gray-900 bg-gray-100 dark:bg-gray-800 dark:text-white text-center">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-semibold mb-4 text-accent-400">Mulai Buat Landing Page Pertamamu Hari Ini!</h2>
            <p class="text-lg text-gray-900 dark:text-white mb-8">
                Daftar gratis dan rasakan kemudahan EXPOSIA. Waktunya UMKM naik kelas secara digital.
            </p>
            <a href="{{ route('login') }}"
                class="inline-block px-8 py-3 rounded-lg font-semibold text-lg text-white bg-accent-400 transition hover:bg-accent-600">
                Coba Gratis Sekarang
            </a>
        </div>
    </section>


    <!-- Footer -->
    <footer class=" text-gray-900 dark:text-white">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10 mb-12">
                <div>
                    <h3 class="text-2xl font-semibold text-accent-400 mb-4">
                        EXPOSIA</h3>
                    <p class="text-gray-900 dark:text-white mb-4">
                        Platform terbaik untuk UMKM membuat landing page profesional tanpa coding. Mudah, cepat, dan
                        tampil memukau!
                    </p>
                    <div class="flex gap-3">
                        <a href="#"
                            class="w-10 h-10 dark:bg-gray-800 flex items-center justify-center rounded-lg bg-white border border-gray-200 dark:border-gray-700 hover:bg-yellow-500 transition">📧</a>
                        <a href="#"
                            class="w-10 h-10 dark:bg-gray-800 flex items-center justify-center rounded-lg bg-white border border-gray-200 dark:border-gray-700 hover:bg-yellow-500 transition">📱</a>
                        <a href="#"
                            class="w-10 h-10 dark:bg-gray-800 flex items-center justify-center rounded-lg bg-white border border-gray-200 dark:border-gray-700 hover:bg-yellow-500 transition">🌐</a>
                        <a href="#"
                            class="w-10 h-10 dark:bg-gray-800 flex items-center justify-center rounded-lg bg-white border border-gray-200 dark:border-gray-700 hover:bg-yellow-500 transition">💬</a>
                    </div>
                </div>
                <div>
                    <h3 class="text-accent-400 font-semibold mb-4">Produk</h3>
                    <ul class="space-y-2 text-gray-900 dark:text-white">
                        <li><a href="#fitur" class="hover:text-accent-400 transition">Fitur Unggulan</a></li>
                        <li><a href="#" class="hover:text-accent-400 transition">Template Gallery</a></li>
                        <li><a href="#" class="hover:text-accent-400 transition">Tutorial</a></li>
                        <li><a href="#" class="hover:text-accent-400 transition">Demo</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-accent-400 font-semibold mb-4">Perusahaan</h3>
                    <ul class="space-y-2 text-gray-900 dark:text-white">
                        <li><a href="#tentang" class="hover:text-accent-400 transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-accent-400 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-accent-400 transition">Karir</a></li>
                        <li><a href="#" class="hover:text-accent-400 transition">Partner</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-accent-400 font-semibold mb-4">Hubungi Kami</h3>
                    <div class="text-gray-900 dark:text-white space-y-2">
                        <p>📧 hello@exposia.id</p>
                        <p>📱 +62 812-3456-7890</p>
                        <p>📍 Jakarta, Indonesia</p>
                        <p>🕐 Senin - Jumat, 9:00 - 18:00</p>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-white pt-6 flex flex-col md:flex-row items-center justify-between text-gray-900 dark:text-white text-sm">
                <p>&copy; 2025 EXPOSIA. Semua hak cipta dilindungi.</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="hover:text-accent-400 transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-accent-400 transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-accent-400 transition">Cookie Policy</a>
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
