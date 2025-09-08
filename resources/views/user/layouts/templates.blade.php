<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Exposia') }}</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "color-accent": '@yield('color-accent')', // '#012C4E' 
                        "color-bg-light": '@yield('color-highlight')', // '#FFFFFF'
                        "color-bg-mid": '@yield('color-primary')', // '#EAEFEF'
                        "color-bg-dark": '@yield('color-secondary')', // '#D9D9D9'

                        "text-primary": "#0C0C20",
                        "text-secondary": "#828282",
                    },

                    fontFamily: {
                        sans: ["Poppins", "ui-sans-serif", "system-ui"],
                    },
                    boxShadow: {
                        soft: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        medium: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                        large: "0 25px 50px -12px rgba(0, 0, 0, 0.25)",
                    },
                },
            },
        };

    </script>

    <style>
        /* Modal styles */
        .modal-overlay {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(226, 232, 240, 0.6);
        }

        /* Hero Blob Mask */
        .masked-blob {
            -webkit-mask-image: url({{ asset('img/blob-img.svg') }});
            mask-image: url({{ asset('img/blob-img.svg') }});
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: contain;
            mask-size: contain;
            -webkit-mask-position: center;
            mask-position: center;
        }

        /* Hide Scrollbar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        /* Hover scale */
        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* Hover lift */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Hover gallery scale */
        .hover-gallery {
            position: relative;
        }

        .hover-gallery-image {
            transition:
                transform 0.3s ease,
                filter 0.3s ease;
        }

        .hover-gallery:hover .hover-gallery-image {
            transform: scale(1.1);
            filter: brightness(0.7);
        }

        @media (max-width: 639px) {
            #aiChatWidget {
                bottom: 6rem;
                right: 1rem;
                left: 1rem;
            }

            #chatToggleBtn {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                z-index: 50;
            }
        }

        /* Scrollbar hiding utility */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Enhanced focus states */
        .chat-input-container:focus-within {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Smooth transitions */
        * {
            scroll-behavior: smooth;
        }

        /* Loading states */
        .loading-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        /* Message animations */
        .message-enter {
            animation: messageSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes messageSlideIn {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Enhanced hover effects */
        .quick-action-btn:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #000;
        }

        .user-message:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .ai-message:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Status indicators */
        .status-online {
            background: linear-gradient(45deg, #10B981, #059669);
            box-shadow: 0 0 0 2px white, 0 0 8px rgba(16, 185, 129, 0.5);
        }

        /* Custom tooltip */
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 4px;
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* High contrast mode */
        @media (prefers-contrast: high) {

            .user-message,
            .ai-message,
            .quick-action-btn {
                border: 2px solid #000;
            }

            #chatToggleBtn {
                border: 3px solid #fff;
            }
        }

        /* Print styles */
        @media print {
            #aiChatWidget {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="text-text-primary bg-color-bg-dark font-sans">
    @yield('content')
</body>

</html>
