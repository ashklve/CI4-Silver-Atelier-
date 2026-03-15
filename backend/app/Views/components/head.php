<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COCOIR — Eco-Friendly Coconut Coir Products</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,400;0,600;0,700;0,900;1,700;1,900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'coco-brown': '#3B2314',
                        'coco-dark': '#5C3317',
                        'coco-mid': '#8B5E3C',
                        'coco-tan': '#C8956C',
                        'coco-orange': '#E87722',
                        'coco-amber': '#F4A940',
                        'coco-green': '#4A7C59',
                        'coco-leaf': '#6BAF78',
                        'coco-sage': '#A8C5A0',
                        'coco-cream': '#FAF3E8',
                        'coco-sand': '#EDE0CC',
                        'coco-white': '#FFFDF8',
                    },
                    fontFamily: {
                        'display': ['Barlow', 'sans-serif'],
                        'body': ['Lato', 'sans-serif'],
                    },
                    keyframes: {
                        'fade-up': {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        'fade-in': {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        'float': {
                            '0%,100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-14px)'
                            }
                        },
                        'spin-slow': {
                            '0%': {
                                transform: 'rotate(0deg)'
                            },
                            '100%': {
                                transform: 'rotate(360deg)'
                            }
                        },
                        'sway': {
                            '0%,100%': {
                                transform: 'rotate(-3deg)'
                            },
                            '50%': {
                                transform: 'rotate(3deg)'
                            }
                        },
                    },
                    animation: {
                        'fade-up': 'fade-up 0.8s ease forwards',
                        'fade-in': 'fade-in 1s ease forwards',
                        'float': 'float 4s ease-in-out infinite',
                        'spin-slow': 'spin-slow 22s linear infinite',
                        'sway': 'sway 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
        }

        /* Subtle grain overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.07'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .anim-hidden {
            opacity: 0;
        }

        /* Sticky nav */
        .nav-scrolled {
            background: rgba(250, 243, 232, 0.96) !important;
            backdrop-filter: blur(14px);
            box-shadow: 0 2px 24px rgba(59, 35, 20, 0.10);
        }

        /* Organic blobs */
        .blob-1 {
            border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
        }

        .blob-2 {
            border-radius: 45% 55% 40% 60% / 60% 40% 55% 45%;
        }

        /* Fiber texture background */
        .fiber-bg {
            background: repeating-linear-gradient(108deg,
                    transparent,
                    transparent 2px,
                    rgba(139, 94, 60, 0.035) 2px,
                    rgba(139, 94, 60, 0.035) 4px), #FAF3E8;
        }

        /* Product card hover lift */
        .product-card {
            transition: transform 0.35s cubic-bezier(.34, 1.56, .64, 1), box-shadow 0.35s ease;
        }

        .product-card:hover {
            transform: translateY(-9px) rotate(0.4deg);
            box-shadow: 0 28px 52px rgba(59, 35, 20, 0.16);
        }

        .badge-new {
            background: #E87722;
            color: #fff;
        }

        .badge-trending {
            background: #4A7C59;
            color: #fff;
        }

        .badge-bestseller {
            background: #3B2314;
            color: #FAF3E8;
        }

        /* Mobile menu */
        #mobile-menu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, opacity 0.4s ease;
        }

        #mobile-menu.open {
            max-height: 440px;
            opacity: 1;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #FAF3E8;
        }

        ::-webkit-scrollbar-thumb {
            background: #C8956C;
            border-radius: 3px;
        }
    </style>
</head>