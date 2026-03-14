<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COCOIR — Eco-Friendly Coconut Coir Products</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'coco-brown':  '#3B2314',
                        'coco-dark':   '#5C3317',
                        'coco-mid':    '#8B5E3C',
                        'coco-tan':    '#C8956C',
                        'coco-orange': '#E87722',
                        'coco-amber':  '#F4A940',
                        'coco-green':  '#4A7C59',
                        'coco-leaf':   '#6BAF78',
                        'coco-sage':   '#A8C5A0',
                        'coco-cream':  '#FAF3E8',
                        'coco-sand':   '#EDE0CC',
                        'coco-white':  '#FFFDF8',
                    },
                    fontFamily: {
                        'display': ['"Playfair Display"', 'serif'],
                        'body':    ['Lato', 'sans-serif'],
                    },
                    keyframes: {
                        'fade-up':   { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        'fade-in':   { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        'float':     { '0%,100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-14px)' } },
                        'spin-slow': { '0%': { transform: 'rotate(0deg)' }, '100%': { transform: 'rotate(360deg)' } },
                        'sway':      { '0%,100%': { transform: 'rotate(-3deg)' }, '50%': { transform: 'rotate(3deg)' } },
                    },
                    animation: {
                        'fade-up':   'fade-up 0.8s ease forwards',
                        'fade-in':   'fade-in 1s ease forwards',
                        'float':     'float 4s ease-in-out infinite',
                        'spin-slow': 'spin-slow 22s linear infinite',
                        'sway':      'sway 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Lato', sans-serif; }

        /* Subtle grain overlay */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.07'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 9999; opacity: 0.4;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-700 { animation-delay: 0.7s; }
        .anim-hidden { opacity: 0; }

        /* Sticky nav */
        .nav-scrolled {
            background: rgba(250, 243, 232, 0.96) !important;
            backdrop-filter: blur(14px);
            box-shadow: 0 2px 24px rgba(59,35,20,0.10);
        }

        /* Organic blobs */
        .blob-1 { border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%; }
        .blob-2 { border-radius: 45% 55% 40% 60% / 60% 40% 55% 45%; }

        /* Fiber texture background */
        .fiber-bg {
            background: repeating-linear-gradient(
                108deg,
                transparent,
                transparent 2px,
                rgba(139,94,60,0.035) 2px,
                rgba(139,94,60,0.035) 4px
            ), #FAF3E8;
        }

        /* Product card hover lift */
        .product-card { transition: transform 0.35s cubic-bezier(.34,1.56,.64,1), box-shadow 0.35s ease; }
        .product-card:hover { transform: translateY(-9px) rotate(0.4deg); box-shadow: 0 28px 52px rgba(59,35,20,0.16); }

        .badge-new        { background: #E87722; color: #fff; }
        .badge-trending   { background: #4A7C59; color: #fff; }
        .badge-bestseller { background: #3B2314; color: #FAF3E8; }

        /* Mobile menu */
        #mobile-menu { max-height: 0; opacity: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.4s ease; }
        #mobile-menu.open { max-height: 440px; opacity: 1; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FAF3E8; }
        ::-webkit-scrollbar-thumb { background: #C8956C; border-radius: 3px; }
    </style>
</head>

<body class="font-body text-coco-brown bg-coco-cream">


<!-- ══════════════════════════ HEADER ══════════════════════════ -->
<header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-400 py-3 px-4 lg:px-8">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        <!-- Logo -->
        <a href="#home" class="flex items-center gap-3 group">
            <svg class="w-12 h-12 drop-shadow-md" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Sun -->
                <circle cx="19" cy="13" r="8" fill="#E87722" opacity="0.92"/>
                <path d="M13 13 Q16 11 19 13" stroke="#F4A940" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                <!-- Palm trunk -->
                <path d="M33 45 Q31 33 35 21" stroke="#5C3317" stroke-width="3" stroke-linecap="round"/>
                <!-- Palm leaves -->
                <path d="M35 21 Q44 15 48 11" stroke="#4A7C59" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M35 21 Q27 13 25 8"  stroke="#6BAF78" stroke-width="2"   stroke-linecap="round"/>
                <path d="M35 21 Q42 20 46 18" stroke="#4A7C59" stroke-width="2"   stroke-linecap="round"/>
                <!-- Ground -->
                <ellipse cx="30" cy="46" rx="16" ry="4" fill="#6BAF78" opacity="0.45"/>
                <!-- Coconuts -->
                <circle cx="21" cy="44" r="5.2" fill="#3B2314"/>
                <circle cx="30" cy="44" r="5.2" fill="#5C3317"/>
                <circle cx="21" cy="44" r="2.2" fill="#8B5E3C" opacity="0.55"/>
                <circle cx="30" cy="44" r="2.2" fill="#8B5E3C" opacity="0.55"/>
            </svg>
            <div>
                <span class="font-display text-2xl font-black text-coco-brown tracking-wide leading-none block">COCOIR</span>
                <span class="text-[10px] font-body text-coco-green tracking-[0.22em] uppercase leading-none">Coconut Coir Co.</span>
            </div>
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-8">
            <a href="#home"     class="text-coco-dark font-semibold hover:text-coco-orange transition-colors text-xs tracking-widest uppercase">Home</a>
            <a href="#about"    class="text-coco-dark font-semibold hover:text-coco-orange transition-colors text-xs tracking-widest uppercase">About</a>
            <a href="#products" class="text-coco-dark font-semibold hover:text-coco-orange transition-colors text-xs tracking-widest uppercase">Products</a>
            <a href="#why-us"   class="text-coco-dark font-semibold hover:text-coco-orange transition-colors text-xs tracking-widest uppercase">Why Us</a>
            <a href="#contact"  class="text-coco-dark font-semibold hover:text-coco-orange transition-colors text-xs tracking-widest uppercase">Contact</a>
        </nav>

        <!-- CTA -->
        <div class="hidden lg:flex items-center gap-3">
            <a href="<?= site_url('login') ?>" class="text-coco-dark font-semibold px-5 py-2 rounded-full border-2 border-coco-tan hover:border-coco-orange hover:text-coco-orange transition-all text-sm">
                Sign In
            </a>
            <a href="<?= site_url('catalog') ?>" class="bg-coco-orange text-white px-6 py-2.5 rounded-full font-bold text-sm hover:bg-coco-dark transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 transform">
                Shop Now
            </a>
        </div>

        <!-- Hamburger -->
        <button id="hamburger" class="lg:hidden p-2 flex flex-col gap-1.5" aria-label="Menu">
            <span class="w-6 h-0.5 bg-coco-brown block"></span>
            <span class="w-6 h-0.5 bg-coco-brown block"></span>
            <span class="w-4 h-0.5 bg-coco-brown block"></span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden bg-coco-cream border-t border-coco-sand px-4">
        <nav class="py-4 flex flex-col gap-3">
            <a href="#home"     class="text-coco-dark font-semibold py-2 border-b border-coco-sand/60 text-xs tracking-widest uppercase">Home</a>
            <a href="#about"    class="text-coco-dark font-semibold py-2 border-b border-coco-sand/60 text-xs tracking-widest uppercase">About</a>
            <a href="#products" class="text-coco-dark font-semibold py-2 border-b border-coco-sand/60 text-xs tracking-widest uppercase">Products</a>
            <a href="#why-us"   class="text-coco-dark font-semibold py-2 border-b border-coco-sand/60 text-xs tracking-widest uppercase">Why Us</a>
            <a href="#contact"  class="text-coco-dark font-semibold py-2 text-xs tracking-widest uppercase">Contact</a>
            <a href="<?= site_url('catalog') ?>" class="bg-coco-orange text-white text-center py-3 rounded-full font-bold mt-2 text-sm">Shop Now</a>
        </nav>
    </div>
</header>


<!-- ══════════════════════════ HERO ══════════════════════════ -->
<section id="home" class="relative min-h-screen overflow-hidden bg-gradient-to-br from-coco-cream via-coco-sand to-coco-cream flex items-center pt-20">

    <!-- Decorative blobs -->
    <div class="absolute top-10 right-0 w-[480px] h-[480px] blob-1 bg-coco-orange/10 -z-0 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[320px] h-[320px] blob-2 bg-coco-green/10 -z-0 pointer-events-none"></div>
    <div class="absolute top-1/2 right-20 w-60 h-60 rounded-full border-[3px] border-dashed border-coco-tan/25 animate-spin-slow -z-0 pointer-events-none hidden lg:block"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <!-- Text Content -->
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 bg-coco-green/10 border border-coco-leaf/30 rounded-full px-4 py-1.5 anim-hidden animate-fade-up">
                    <span class="w-2 h-2 bg-coco-leaf rounded-full animate-pulse"></span>
                    <span class="text-coco-green text-xs font-bold tracking-[0.15em] uppercase">100% Natural · Philippine-Made</span>
                </div>

                <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-black text-coco-brown leading-[1.05] anim-hidden animate-fade-up delay-200">
                    From Husk<br>
                    <span class="text-coco-orange italic">to Craft.</span>
                </h1>

                <p class="text-lg text-coco-mid font-light max-w-lg leading-relaxed anim-hidden animate-fade-up delay-300">
                    COCOIR transforms discarded coconut husks into premium eco-friendly products — for construction, gardening, and everyday living. Rooted in the Philippines, growing worldwide.
                </p>

                <div class="flex flex-wrap gap-4 anim-hidden animate-fade-up delay-500">
                    <a href="<?= site_url('catalog') ?>" class="bg-coco-orange text-white px-8 py-4 rounded-full font-bold hover:bg-coco-dark transform hover:scale-105 transition-all duration-300 shadow-lg shadow-coco-orange/30 inline-flex items-center gap-2">
                        <i class="fas fa-leaf text-sm"></i> Shop Products
                    </a>
                    <a href="#about" class="border-2 border-coco-brown text-coco-brown px-8 py-4 rounded-full font-bold hover:bg-coco-brown hover:text-coco-cream transition-all duration-300 inline-flex items-center gap-2">
                        Our Story <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex gap-8 pt-4 anim-hidden animate-fade-up delay-700">
                    <div>
                        <div class="font-display text-3xl font-black text-coco-brown">200+</div>
                        <div class="text-coco-mid text-sm font-light">Products</div>
                    </div>
                    <div class="w-px bg-coco-sand self-stretch"></div>
                    <div>
                        <div class="font-display text-3xl font-black text-coco-brown">1,500+</div>
                        <div class="text-coco-mid text-sm font-light">Customers</div>
                    </div>
                    <div class="w-px bg-coco-sand self-stretch"></div>
                    <div>
                        <div class="font-display text-3xl font-black text-coco-brown">4.8★</div>
                        <div class="text-coco-mid text-sm font-light">Rating</div>
                    </div>
                </div>
            </div>

            <!-- Visual -->
            <div class="relative flex justify-center items-center">
                <!-- Main blob frame -->
                <div class="relative w-80 h-80 lg:w-[420px] lg:h-[420px] blob-1 bg-gradient-to-br from-coco-orange to-coco-dark shadow-2xl overflow-hidden animate-float">
                    <img src="/images/coir-hero.jpg" alt="Coconut Coir Products" class="w-full h-full object-cover mix-blend-multiply opacity-65">
                    <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,transparent,transparent 8px,rgba(255,255,255,0.04) 8px,rgba(255,255,255,0.04) 9px);"></div>
                    <!-- Centered logo watermark -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <span class="font-display text-6xl font-black text-coco-cream">🥥</span>
                    </div>
                </div>

                <!-- Badge: Eco-Friendly -->
                <div class="absolute -top-4 -left-2 lg:-left-10 bg-coco-green text-white rounded-2xl px-4 py-3 shadow-xl animate-sway">
                    <div class="text-[10px] font-bold tracking-widest uppercase opacity-80">Eco-Friendly</div>
                    <div class="font-display text-xl font-black leading-tight">100%<br>Natural</div>
                </div>

                <!-- Badge: PH Made -->
                <div class="absolute -bottom-4 -right-2 lg:-right-8 bg-coco-cream border-2 border-coco-sand rounded-2xl px-4 py-3 shadow-xl">
                    <div class="text-[10px] font-bold text-coco-mid tracking-widest">🇵🇭 Made in the</div>
                    <div class="font-display text-xl font-black text-coco-brown">Philippines</div>
                </div>

                <!-- Bouncing coconut -->
                <div class="absolute top-6 right-0 lg:-right-8 w-14 h-14 bg-coco-amber rounded-full flex items-center justify-center shadow-lg animate-bounce">
                    <span class="text-2xl">🥥</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave divider -->
    <div class="absolute bottom-0 left-0 right-0 pointer-events-none">
        <svg viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-14 lg:h-20">
            <path d="M0 36 Q360 0 720 36 Q1080 72 1440 36 L1440 72 L0 72 Z" fill="#EDE0CC"/>
        </svg>
    </div>
</section>


<!-- ══════════════════════════ ABOUT ══════════════════════════ -->
<section id="about" class="py-24 bg-coco-sand">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <!-- Story -->
            <div class="space-y-6">
                <p class="text-coco-orange font-bold text-xs tracking-[0.3em] uppercase">Our Story</p>
                <h2 class="font-display text-4xl sm:text-5xl font-black text-coco-brown leading-tight">
                    The Fiber That<br><em>Changes Everything</em>
                </h2>
                <p class="text-coco-dark text-lg leading-relaxed font-light">
                    Coconut coir — the tough fibrous material between the hard inner shell and outer husk of the coconut — has long been discarded as waste. COCOIR is changing that story.
                </p>
                <p class="text-coco-mid leading-relaxed text-sm">
                    In the Philippines, where coconuts are abundant and coir utilization is still in its infancy, we lead the charge: turning agricultural byproduct into durable mats, grow bags, erosion control nets, and construction liners. Every product supports local farmers, reduces waste, and brings world-class sustainable materials to your doorstep.
                </p>
                <div class="flex gap-6 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-coco-green/15 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-seedling text-coco-green text-sm"></i>
                        </div>
                        <div>
                            <div class="font-bold text-coco-brown text-sm">Zero Waste</div>
                            <div class="text-coco-mid text-xs">Every husk has purpose</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-coco-orange/15 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-hands-helping text-coco-orange text-sm"></i>
                        </div>
                        <div>
                            <div class="font-bold text-coco-brown text-sm">Farmer First</div>
                            <div class="text-coco-mid text-xs">Fair trade, local sourcing</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 gap-5">
                <div class="fiber-bg rounded-3xl p-7 border border-coco-tan/20 shadow-sm">
                    <div class="font-display text-5xl font-black text-coco-orange mb-1">15+</div>
                    <div class="font-bold text-coco-brown text-sm mb-1">Product Categories</div>
                    <div class="text-coco-mid text-xs leading-relaxed">Gardening, construction, craft, and more</div>
                </div>
                <div class="bg-coco-green rounded-3xl p-7 shadow-sm">
                    <div class="font-display text-5xl font-black text-coco-cream mb-1">5+</div>
                    <div class="font-bold text-coco-cream text-sm mb-1">Years Expertise</div>
                    <div class="text-coco-sage text-xs leading-relaxed">Pioneering coir craft in the Philippines</div>
                </div>
                <div class="bg-coco-brown rounded-3xl p-7 shadow-sm">
                    <div class="font-display text-5xl font-black text-coco-amber mb-1">100%</div>
                    <div class="font-bold text-coco-cream text-sm mb-1">Biodegradable</div>
                    <div class="text-coco-tan text-xs leading-relaxed">No synthetics, no compromise</div>
                </div>
                <div class="fiber-bg rounded-3xl p-7 border border-coco-tan/20 shadow-sm text-center flex flex-col items-center justify-center">
                    <div class="text-5xl mb-2">🌴</div>
                    <div class="font-bold text-coco-brown text-sm">PH Sourced</div>
                    <div class="text-coco-mid text-xs">Proudly Philippine farmers</div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ══════════════════════════ FEATURED PRODUCTS ══════════════════════════ -->
<section id="products" class="py-24 bg-coco-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <p class="text-coco-orange font-bold text-xs tracking-[0.3em] uppercase">Our Range</p>
            <h2 class="font-display text-4xl sm:text-5xl font-black text-coco-brown">Featured Products</h2>
            <p class="text-coco-mid text-lg max-w-2xl mx-auto font-light">Eco-friendly coir products handcrafted for gardening, construction, and sustainable living.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">

            <!-- Card 1 -->
            <div class="product-card bg-white rounded-3xl overflow-hidden shadow-md border border-coco-sand/60 group">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-coco-green/20 to-coco-sage/20 overflow-hidden">
                    <img src="/images/coir-mat.jpg" alt="Coir Door Mat" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3 left-3 badge-new text-[10px] font-black tracking-widest uppercase rounded-full px-3 py-1">New</span>
                    <button class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-coco-orange hover:text-white text-coco-brown">
                        <i class="fas fa-heart text-xs"></i>
                    </button>
                </div>
                <div class="p-5">
                    <p class="text-coco-green text-[10px] font-bold tracking-widest uppercase mb-1">Home & Living</p>
                    <h3 class="font-display font-bold text-coco-brown text-lg leading-snug mb-1">Coir Door Mat</h3>
                    <p class="text-coco-mid text-xs mb-4 leading-relaxed">Natural, durable, fully biodegradable entrance mats.</p>
                    <div class="flex items-center justify-between">
                        <span class="font-display font-black text-xl text-coco-orange">₱350</span>
                        <a href="<?= site_url('catalog') ?>" class="bg-coco-brown text-coco-cream text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-orange transition-colors">Add to Cart</a>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="product-card bg-white rounded-3xl overflow-hidden shadow-md border border-coco-sand/60 group">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-coco-amber/20 to-coco-orange/10 overflow-hidden">
                    <img src="/images/coir-growbag.jpg" alt="Coco Grow Bag" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3 left-3 badge-trending text-[10px] font-black tracking-widest uppercase rounded-full px-3 py-1">Trending</span>
                </div>
                <div class="p-5">
                    <p class="text-coco-green text-[10px] font-bold tracking-widest uppercase mb-1">Gardening</p>
                    <h3 class="font-display font-bold text-coco-brown text-lg leading-snug mb-1">Coco Grow Bag</h3>
                    <p class="text-coco-mid text-xs mb-4 leading-relaxed">Perfect moisture retention for vegetables and herbs.</p>
                    <div class="flex items-center justify-between">
                        <span class="font-display font-black text-xl text-coco-orange">₱180</span>
                        <a href="<?= site_url('catalog') ?>" class="bg-coco-brown text-coco-cream text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-orange transition-colors">Add to Cart</a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="product-card bg-white rounded-3xl overflow-hidden shadow-md border border-coco-sand/60 group">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-coco-brown/10 to-coco-tan/20 overflow-hidden">
                    <img src="/images/coir-geotextile.jpg" alt="Geotextile Erosion Mat" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3 left-3 badge-bestseller text-[10px] font-black tracking-widest uppercase rounded-full px-3 py-1">Best Seller</span>
                </div>
                <div class="p-5">
                    <p class="text-coco-green text-[10px] font-bold tracking-widest uppercase mb-1">Construction</p>
                    <h3 class="font-display font-bold text-coco-brown text-lg leading-snug mb-1">Geotextile Mat</h3>
                    <p class="text-coco-mid text-xs mb-4 leading-relaxed">Slope stabilization and erosion control, project-grade.</p>
                    <div class="flex items-center justify-between">
                        <span class="font-display font-black text-xl text-coco-orange">₱1,200</span>
                        <a href="<?= site_url('catalog') ?>" class="bg-coco-brown text-coco-cream text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-orange transition-colors">Add to Cart</a>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="product-card bg-white rounded-3xl overflow-hidden shadow-md border border-coco-sand/60 group">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-coco-leaf/20 to-coco-green/10 overflow-hidden">
                    <img src="/images/coir-rope.jpg" alt="Twisted Coir Rope" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <p class="text-coco-green text-[10px] font-bold tracking-widest uppercase mb-1">Craft & Utility</p>
                    <h3 class="font-display font-bold text-coco-brown text-lg leading-snug mb-1">Twisted Coir Rope</h3>
                    <p class="text-coco-mid text-xs mb-4 leading-relaxed">Strong, salt-resistant rope for marine and garden use.</p>
                    <div class="flex items-center justify-between">
                        <span class="font-display font-black text-xl text-coco-orange">₱220</span>
                        <a href="<?= site_url('catalog') ?>" class="bg-coco-brown text-coco-cream text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-orange transition-colors">Add to Cart</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="<?= site_url('catalog') ?>" class="inline-flex items-center gap-2 bg-coco-brown text-coco-cream px-10 py-4 rounded-full font-bold hover:bg-coco-orange transition-all duration-300 shadow-md hover:shadow-xl hover:scale-105 transform">
                View All Products <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════ WHY COCOIR ══════════════════════════ -->
<section id="why-us" class="py-24 bg-coco-sand">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <p class="text-coco-orange font-bold text-xs tracking-[0.3em] uppercase">Why COCOIR</p>
            <h2 class="font-display text-4xl sm:text-5xl font-black text-coco-brown">Rooted in Nature,<br><em>Built for You</em></h2>
            <p class="text-coco-mid text-lg max-w-2xl mx-auto font-light">Experience eco-excellence with our commitment to quality, sustainability, and Filipino craftsmanship.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            <?php
            $features = [
                ['icon'=>'leaf',               'color'=>'coco-green',  'title'=>'Fully Biodegradable',     'desc'=>'Every COCOIR product breaks down naturally — no synthetic residue in your soil, water, or air.'],
                ['icon'=>'truck',              'color'=>'coco-orange', 'title'=>'Nationwide Delivery',     'desc'=>'Fast, secure shipping across Metro Manila and all major Philippine provinces.'],
                ['icon'=>'shield-alt',         'color'=>'coco-brown',  'title'=>'Quality Assured',         'desc'=>'Each batch is tested for tensile strength, moisture resistance, and durability before shipping.'],
                ['icon'=>'hand-holding-heart', 'color'=>'coco-orange', 'title'=>'Farmer-Sourced',          'desc'=>'We source directly from Philippine coconut farmers, ensuring fair prices and sustainable livelihoods.'],
                ['icon'=>'headset',            'color'=>'coco-green',  'title'=>'Expert Support',          'desc'=>'Free consultations for construction and gardening projects. Our team guides you every step.'],
                ['icon'=>'recycle',            'color'=>'coco-brown',  'title'=>'Circular Economy',        'desc'=>'From husk to product to compost — our supply chain creates zero waste at every stage.'],
            ];
            foreach ($features as $f): ?>
            <div class="fiber-bg rounded-3xl p-8 border border-coco-tan/20 hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                <div class="w-14 h-14 bg-<?= $f['color'] ?>/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="fas fa-<?= $f['icon'] ?> text-<?= $f['color'] ?> text-xl"></i>
                </div>
                <h3 class="font-display font-bold text-coco-brown text-xl mb-2"><?= $f['title'] ?></h3>
                <p class="text-coco-mid text-sm leading-relaxed"><?= $f['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ══════════════════════════ CTA / NEWSLETTER ══════════════════════════ -->
<section id="contact" class="py-24 bg-coco-brown relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute top-0 right-0 w-80 h-80 blob-1 bg-coco-orange/10 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 blob-2 bg-coco-leaf/10 pointer-events-none"></div>
    <div class="absolute inset-0 pointer-events-none" style="background:repeating-linear-gradient(90deg,transparent,transparent 60px,rgba(255,255,255,0.015) 60px,rgba(255,255,255,0.015) 61px);"></div>

    <div class="max-w-4xl mx-auto px-4 text-center relative z-10 space-y-8">
        <p class="text-coco-amber font-bold text-xs tracking-[0.3em] uppercase">Stay Connected</p>
        <h2 class="font-display text-4xl sm:text-5xl font-black text-coco-cream leading-tight">
            Join the Coir Revolution.<br>
            <span class="text-coco-amber italic">Get 10% Off Your First Order.</span>
        </h2>
        <p class="text-coco-tan text-lg font-light max-w-xl mx-auto">Subscribe for exclusive deals, new product launches, and tips on sustainable living with coconut coir.</p>

        <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto" onsubmit="return false;">
            <input type="email" placeholder="your@email.com"
                class="flex-1 bg-white/10 border border-coco-tan/30 text-coco-cream placeholder-coco-tan/50 px-6 py-4 rounded-full focus:outline-none focus:border-coco-amber transition-colors text-sm">
            <button type="submit" class="bg-coco-orange text-white px-8 py-4 rounded-full font-bold hover:bg-coco-amber hover:text-coco-brown transition-all duration-300 shadow-lg whitespace-nowrap">
                Subscribe
            </button>
        </form>
        <p class="text-coco-tan/40 text-xs">No spam. Unsubscribe anytime. 🥥</p>

        <!-- Contact strip -->
        <div class="flex flex-wrap justify-center gap-8 pt-8 border-t border-white/10">
            <a href="mailto:hello@cocoir.ph" class="flex items-center gap-2 text-coco-tan hover:text-coco-amber transition-colors text-sm">
                <i class="fas fa-envelope text-coco-orange"></i> hello@cocoir.ph
            </a>
            <a href="tel:+639XXXXXXXXX" class="flex items-center gap-2 text-coco-tan hover:text-coco-amber transition-colors text-sm">
                <i class="fas fa-phone text-coco-orange"></i> +63 9XX XXX XXXX
            </a>
            <span class="flex items-center gap-2 text-coco-tan text-sm">
                <i class="fas fa-map-marker-alt text-coco-orange"></i> Philippines
            </span>
        </div>
    </div>
</section>


<!-- ══════════════════════════ FOOTER ══════════════════════════ -->
<footer class="bg-coco-dark py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            <!-- Brand -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="font-display text-2xl font-black text-coco-cream">COCOIR</span>
                    <span class="text-coco-tan text-xs tracking-widest uppercase">Co.</span>
                </div>
                <p class="text-coco-tan text-sm leading-relaxed font-light">Transforming coconut husks into premium eco-products. Proudly made in the Philippines.</p>
                <div class="flex gap-3 pt-2">
                    <a href="#" class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center text-coco-tan hover:bg-coco-orange hover:text-white transition-all"><i class="fab fa-facebook-f text-xs"></i></a>
                    <a href="#" class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center text-coco-tan hover:bg-coco-orange hover:text-white transition-all"><i class="fab fa-instagram text-xs"></i></a>
                    <a href="#" class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center text-coco-tan hover:bg-coco-orange hover:text-white transition-all"><i class="fab fa-tiktok text-xs"></i></a>
                </div>
            </div>

            <!-- Products -->
            <div>
                <h4 class="font-bold text-coco-cream text-xs tracking-widest uppercase mb-4">Products</h4>
                <ul class="space-y-2">
                    <?php foreach(['Door Mats','Grow Bags','Geotextile Mats','Coir Rope','Mulch Mat','Erosion Netting'] as $item): ?>
                    <li><a href="<?= site_url('catalog') ?>" class="text-coco-tan text-sm hover:text-coco-amber transition-colors"><?= $item ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="font-bold text-coco-cream text-xs tracking-widest uppercase mb-4">Company</h4>
                <ul class="space-y-2">
                    <?php foreach(['About Us'=>'#about','Our Farmers'=>'#','Sustainability'=>'#','Blog'=>'#','Careers'=>'#'] as $label=>$link): ?>
                    <li><a href="<?= $link ?>" class="text-coco-tan text-sm hover:text-coco-amber transition-colors"><?= $label ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-bold text-coco-cream text-xs tracking-widest uppercase mb-4">Support</h4>
                <ul class="space-y-2">
                    <?php foreach(['My Orders'=>site_url('orders'),'Track Delivery'=>'#','Returns & Refunds'=>'#','FAQs'=>'#','Contact Us'=>'#contact','Seller Portal'=>site_url('seller')] as $label=>$link): ?>
                    <li><a href="<?= $link ?>" class="text-coco-tan text-sm hover:text-coco-amber transition-colors"><?= $label ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-coco-tan/50 text-xs">© <?= date('Y') ?> COCOIR — Coconut Coir Co. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="text-coco-tan/50 text-xs hover:text-coco-amber transition-colors">Privacy Policy</a>
                <a href="#" class="text-coco-tan/50 text-xs hover:text-coco-amber transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>


<script src="<?= base_url('js/main.js') ?>"></script>
<script>
// Nav scroll
const header = document.getElementById('main-header');
window.addEventListener('scroll', () => {
    header.classList.toggle('nav-scrolled', window.scrollY > 50);
});

// Mobile menu
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');
hamburger.addEventListener('click', () => mobileMenu.classList.toggle('open'));
mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.remove('open')));

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});

// Scroll-triggered fade-in
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.product-card, .fiber-bg').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});
</script>

</body>
</html>