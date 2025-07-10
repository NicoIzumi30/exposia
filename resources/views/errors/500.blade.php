<!-- resources/views/errors/500.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terjadi Kesalahan Server - {{ config('app.name', 'UMKM Builder') }}</title>

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
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 1s ease-out',
                        'pulse-subtle': 'pulseSubtle 3s ease-in-out infinite',
                        'shake': 'shake 0.5s ease-in-out infinite',
                        'glitch': 'glitch 2s linear infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        },
                        fadeIn: {
                            'from': {
                                opacity: '0'
                            },
                            'to': {
                                opacity: '1'
                            }
                        },
                        pulseSubtle: {
                            '0%, 100%': {
                                opacity: '0.8'
                            },
                            '50%': {
                                opacity: '0.4'
                            }
                        },
                        shake: {
                            '0%, 100%': {
                                transform: 'translateX(0)'
                            },
                            '25%': {
                                transform: 'translateX(-2px)'
                            },
                            '75%': {
                                transform: 'translateX(2px)'
                            }
                        },
                        glitch: {
                            '0%': {
                                transform: 'translate(0)'
                            },
                            '20%': {
                                transform: 'translate(-1px, 1px)'
                            },
                            '40%': {
                                transform: 'translate(-1px, -1px)'
                            },
                            '60%': {
                                transform: 'translate(1px, 1px)'
                            },
                            '80%': {
                                transform: 'translate(1px, -1px)'
                            },
                            '100%': {
                                transform: 'translate(0)'
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

<body class="h-full bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 font-sans animate-fade-in">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">


        <!-- Main Content -->
        <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 text-center">
  
            <!-- Error Code -->
            <div class="mb-4">
                <span class="text-6xl font-black text-black dark:text-white opacity-20">500</span>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Server Error</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                {{ $message ?? 'Maaf, terjadi kesalahan pada server. Tim kami sedang memperbaiki masalah ini.' }}
            </p>



            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-black dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-black font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <i class="fas fa-redo mr-2"></i>
                    Coba Lagi
                </button>
                <button onclick="goBack()" class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </button>
            </div>

            <!-- Error ID (for support) -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Error ID: #{{ substr(md5(time()), 0, 8) }} | {{ date('Y-m-d H:i:s') }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'EXPOSIA') }}
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                Powered by Exposia Platform
            </p>
        </div>


        <!-- Auto refresh indicator -->
        <div id="refresh-indicator" class="fixed top-4 right-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-4 py-2 shadow-lg hidden">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-black dark:border-gray-600 dark:border-t-white"></div>
                <span class="text-sm text-gray-600 dark:text-gray-300">Auto refresh dalam <span id="countdown">30</span>s</span>
            </div>
        </div>
    </div>

    <script>
        // Go back function
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/';
            }
        }

        // Theme toggle
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                icon.className = 'fas fa-moon text-gray-700';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                icon.className = 'fas fa-sun text-gray-300';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Auto refresh functionality
        let autoRefreshCountdown = 30;
        let autoRefreshTimer;
        let countdownTimer;

        function startAutoRefresh() {
            const indicator = document.getElementById('refresh-indicator');
            const countdownSpan = document.getElementById('countdown');
            
            indicator.classList.remove('hidden');
            
            countdownTimer = setInterval(() => {
                autoRefreshCountdown--;
                countdownSpan.textContent = autoRefreshCountdown;
                
                if (autoRefreshCountdown <= 0) {
                    clearInterval(countdownTimer);
                    location.reload();
                }
            }, 1000);
        }

        function stopAutoRefresh() {
            if (countdownTimer) {
                clearInterval(countdownTimer);
            }
            document.getElementById('refresh-indicator').classList.add('hidden');
        }

        // Start auto refresh after 5 seconds
        setTimeout(() => {
            startAutoRefresh();
        }, 5000);

        // Stop auto refresh if user interacts with page
        ['click', 'keydown', 'mousemove', 'scroll'].forEach(event => {
            document.addEventListener(event, stopAutoRefresh, { once: true });
        });

        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click ripple effect to buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        left: ${x}px;
                        top: ${y}px;
                        width: ${size}px;
                        height: ${size}px;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>