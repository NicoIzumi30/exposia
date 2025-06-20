<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Website Tidak Ditemukan - {{ config('app.name', 'UMKM Builder') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class'
            , theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'sans-serif']
                    , }
                    , animation: {
                        'float': 'float 6s ease-in-out infinite'
                        , 'fade-in': 'fadeIn 1s ease-out'
                    }
                    , keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            }
                            , '50%': {
                                transform: 'translateY(-20px)'
                            }
                        , }
                        , fadeIn: {
                            'from': {
                                opacity: '0'
                            }
                            , 'to': {
                                opacity: '1'
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
            } else {
                document.documentElement.classList.add('light');
            }
        })();

    </script>
</head>

<body class="h-full bg-gradient-to-b from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 font-sans animate-fade-in">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
        <!-- Logo -->
        <div class="mb-8">
            <div class="w-40 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                <img src="{{ asset('img/logo.svg') }}" alt="">
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
            <!-- Animated Illustration -->
            <div class="animate-float mx-auto my-6 relative">
                <div class="w-48 h-48 mx-auto relative">
                    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path fill="#3B82F6" d="M47.7,-58.7C62.3,-46.4,75.2,-31.4,79.6,-13.8C84,3.9,79.8,24.2,68.5,38.5C57.1,52.8,38.6,61,19.9,66.5C1.3,71.9,-17.5,74.5,-33.9,68.2C-50.3,61.9,-64.3,46.7,-71.5,28.2C-78.6,9.7,-78.8,-12.2,-70.7,-29.2C-62.6,-46.3,-46.1,-58.4,-29.8,-69.7C-13.5,-81,-2.3,-91.4,9.5,-102.3C21.2,-113.1,33.1,-71,47.7,-58.7Z" transform="translate(100 100)" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-search text-white text-5xl"></i>
                    </div>
                </div>

                <!-- Cloud decorations -->
                <div class="absolute -top-8 -left-8 w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full opacity-70"></div>
                <div class="absolute top-12 -right-4 w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full opacity-70"></div>
                <div class="absolute bottom-0 left-4 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full opacity-70"></div>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Oops! </h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Website yang Anda cari tidak tersedia atau belum dipublikasikan.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
                <button onclick="goBack()" class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'EXPOSIA') }}
            </p>
        </div>

        <!-- Theme Toggle Button -->
        <button id="theme-toggle" class="fixed bottom-4 right-4 p-3 rounded-full bg-white dark:bg-gray-700 shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none">
            <i id="theme-icon" class="fas fa-moon text-gray-700 dark:text-yellow-300"></i>
        </button>
    </div>

    <script>
        // Go back function
        function goBack() {
            window.history.back();
        }

        // Theme toggle
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                document.getElementById('theme-icon').className = 'fas fa-moon text-gray-700';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                document.getElementById('theme-icon').className = 'fas fa-sun text-yellow-300';
                localStorage.setItem('theme', 'dark');
            }
        });

    </script>
</body>
</html>
