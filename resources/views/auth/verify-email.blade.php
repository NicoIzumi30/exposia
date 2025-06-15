<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - UMKM Platform</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
                    }
                }
            }
        }
    </script>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300 font-sans">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            
            <!-- Header -->
            <div class="text-center">
                <!-- Icon -->
                <div class="mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-envelope-open-text text-3xl text-white"></i>
                    </div>
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Verify Your Email
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    We've sent a verification link to your email address
                </p>
            </div>

            <!-- Email Verification Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 border border-gray-200 dark:border-gray-700">
                
                <!-- Flash Messages (Hidden - will be handled by toast) -->
                @if (session('success'))
                    <div data-flash-success="{{ session('success') }}" class="hidden"></div>
                @endif

                @if (session('info'))
                    <div data-flash-info="{{ session('info') }}" class="hidden"></div>
                @endif

                @if (session('error'))
                    <div data-flash-error="{{ session('error') }}" class="hidden"></div>
                @endif

                <!-- Email Info -->
                <div class="mb-6">
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                        <i class="fas fa-envelope text-primary-500 dark:text-accent-400 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Verification email sent to:</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="space-y-4 mb-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Next Steps:</h3>
                    <ol class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-primary-500 dark:bg-accent-400 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                            Check your email inbox (including spam/junk folder)
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-primary-500 dark:bg-accent-400 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                            Click the verification link in the email
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-primary-500 dark:bg-accent-400 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                            Return to this page to access your dashboard
                        </li>
                    </ol>
                </div>

                <!-- Resend Button -->
                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-paper-plane"></i>
                            <span>Resend Verification Email</span>
                        </button>
                    </form>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>

                <!-- Help Text -->
                <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-amber-500 mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-medium text-amber-900 dark:text-amber-400 mb-1">
                                Having trouble?
                            </h4>
                            <p class="text-sm text-amber-800 dark:text-amber-300">
                                If you don't receive the email within a few minutes, check your spam folder or try resending the verification email.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    © 2024 UMKM Platform. Building better businesses.
                </p>
            </div>
        </div>
    </div>

    <!-- Toast Notification System -->
    <script>
        /**
         * Toast Notification System
         */
        class ToastNotification {
            constructor() {
                this.container = null;
                this.notifications = [];
                this.init();
            }

            init() {
                if (!document.getElementById('toast-container')) {
                    this.createContainer();
                } else {
                    this.container = document.getElementById('toast-container');
                }
            }

            createContainer() {
                this.container = document.createElement('div');
                this.container.id = 'toast-container';
                this.container.className = 'fixed top-4 right-4 z-50 space-y-3 max-w-sm';
                document.body.appendChild(this.container);
            }

            show(message, type = 'info', duration = 5000) {
                const toast = this.createToast(message, type, duration);
                this.container.appendChild(toast);
                
                this.notifications.push(toast);
                
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 10);
                
                if (duration > 0) {
                    setTimeout(() => {
                        this.dismiss(toast);
                    }, duration);
                }
                
                return toast;
            }

            createToast(message, type, duration) {
                const toast = document.createElement('div');
                const config = this.getTypeConfig(type);
                
                toast.className = `
                    transform translate-x-full opacity-0 transition-all duration-300 ease-in-out
                    bg-white dark:bg-gray-800 border-l-4 rounded-lg shadow-xl overflow-hidden
                    max-w-sm w-full ${config.borderColor} backdrop-blur-sm
                `;
                
                toast.innerHTML = `
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center ${config.bgColor}">
                                    <i class="${config.icon} text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            ${config.title}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                            ${message}
                                        </p>
                                    </div>
                                    <button 
                                        type="button" 
                                        class="ml-4 inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none transition-colors duration-200"
                                        onclick="window.toast.dismiss(this.closest('.transform'))"
                                    >
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        ${duration > 0 ? this.createProgressBar(duration, config.bgColor) : ''}
                    </div>
                `;
                
                toast.id = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
                return toast;
            }

            createProgressBar(duration, bgColor) {
                return `
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700">
                        <div class="h-full ${bgColor} transition-all ease-linear" 
                             style="width: 100%; animation: progress ${duration}ms linear forwards;">
                        </div>
                    </div>
                `;
            }

            getTypeConfig(type) {
                const configs = {
                    success: {
                        title: 'Success!',
                        icon: 'fas fa-check',
                        bgColor: 'bg-green-500',
                        borderColor: 'border-green-500'
                    },
                    error: {
                        title: 'Error',
                        icon: 'fas fa-exclamation-triangle',
                        bgColor: 'bg-red-500',
                        borderColor: 'border-red-500'
                    },
                    warning: {
                        title: 'Warning',
                        icon: 'fas fa-exclamation',
                        bgColor: 'bg-yellow-500',
                        borderColor: 'border-yellow-500'
                    },
                    info: {
                        title: 'Info',
                        icon: 'fas fa-info',
                        bgColor: 'bg-blue-500',
                        borderColor: 'border-blue-500'
                    }
                };
                
                return configs[type] || configs.info;
            }

            dismiss(toast) {
                if (!toast || !toast.parentNode) return;
                
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');
                
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                    this.notifications = this.notifications.filter(n => n !== toast);
                }, 300);
            }

            success(message, duration = 5000) {
                return this.show(message, 'success', duration);
            }

            error(message, duration = 7000) {
                return this.show(message, 'error', duration);
            }

            warning(message, duration = 6000) {
                return this.show(message, 'warning', duration);
            }

            info(message, duration = 5000) {
                return this.show(message, 'info', duration);
            }
        }

        // Global toast instance
        window.toast = new ToastNotification();

        // Add CSS for progress bar animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes progress {
                from { width: 100%; }
                to { width: 0%; }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Auto-refresh script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Laravel flash messages
            const flashSuccess = document.querySelector('[data-flash-success]');
            const flashError = document.querySelector('[data-flash-error]');
            const flashInfo = document.querySelector('[data-flash-info]');
            
            if (flashSuccess) {
                window.toast.success(flashSuccess.getAttribute('data-flash-success'));
            }
            
            if (flashError) {
                window.toast.error(flashError.getAttribute('data-flash-error'));
            }
            
            if (flashInfo) {
                window.toast.info(flashInfo.getAttribute('data-flash-info'));
            }

            // Enhanced form submission handling
            const resendForm = document.querySelector('form[action*="verification-notification"]');
            const logoutForm = document.querySelector('form[action*="logout"]');
            
            if (resendForm) {
                resendForm.addEventListener('submit', function() {
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;
                    
                    button.disabled = true;
                    button.innerHTML = '<div class="inline-block w-4 h-4 border-2 border-white border-r-transparent rounded-full animate-spin mr-2"></div>Sending...';
                    
                    window.toast.info('Sending verification email...', 3000);
                    
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }, 5000);
                });
            }

            if (logoutForm) {
                logoutForm.addEventListener('submit', function() {
                    window.toast.info('Signing you out...', 2000);
                });
            }
        });

        // Auto refresh setiap 30 detik untuk cek verification status
        setInterval(function() {
            fetch('/email/verify/check')
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        window.toast.success('Email verified! Redirecting to dashboard...', 3000);
                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 2000);
                    }
                })
                .catch(error => {
                    // Ignore errors - user can manually refresh
                });
        }, 30000);
    </script>
</body>
</html>