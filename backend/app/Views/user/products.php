<?php

/**
 * View: user/products.php
 * Controller: Users::products()
 * Route: /products  OR  /catalog
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products — COCOIR Coconut Coir Co.</title>
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

        /* Grain overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.07'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }

        .nav-scrolled {
            background: rgba(250, 243, 232, 0.96) !important;
            backdrop-filter: blur(14px);
            box-shadow: 0 2px 24px rgba(59, 35, 20, 0.10);
        }

        .fiber-bg {
            background: repeating-linear-gradient(108deg, transparent, transparent 2px,
                    rgba(139, 94, 60, 0.035) 2px, rgba(139, 94, 60, 0.035) 4px), #FAF3E8;
        }

        /* Product card */
        .product-card {
            transition: transform 0.32s cubic-bezier(.34, 1.56, .64, 1), box-shadow 0.32s ease;
        }

        .product-card:hover {
            transform: translateY(-8px) rotate(0.3deg);
            box-shadow: 0 24px 48px rgba(59, 35, 20, 0.15);
        }

        .product-card:hover .card-img {
            transform: scale(1.06);
        }

        .card-img {
            transition: transform 0.5s ease;
        }

        /* Filter pill active */
        .filter-pill {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .filter-pill.active,
        .filter-pill:hover {
            background: #E87722;
            color: #fff;
            border-color: #E87722;
        }

        /* Sort dropdown */
        select {
            appearance: none;
            cursor: pointer;
        }

        /* Badges */
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

        .badge-sale {
            background: #F4A940;
            color: #3B2314;
        }

        /* Pagination */
        .page-btn {
            transition: all 0.2s ease;
        }

        .page-btn.active,
        .page-btn:hover {
            background: #E87722;
            color: #fff;
            border-color: #E87722;
        }

        /* Search focus */
        .search-input:focus {
            outline: none;
            border-color: #E87722;
            box-shadow: 0 0 0 3px rgba(232, 119, 34, 0.15);
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

        /* Fade-in cards */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-card {
            animation: fadeUp 0.5s ease forwards;
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

<body class="bg-coco-cream font-body text-coco-brown">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"

        <?= $this->include('components/header') ?>


        <!-- ══════════════════════ FLASH MESSAGES ══════════════════════ -->
    <?php $flashSuccess = session()->getFlashdata('success');
    $flashError = session()->getFlashdata('error'); ?>
    <?php if ($flashSuccess): ?>
        <div id="flash-toast" class="top-20 left-1/2 z-[9998] fixed flex items-center gap-3 bg-coco-green shadow-2xl px-6 py-3 rounded-2xl font-semibold text-white text-sm transition-all -translate-x-1/2 duration-500">
            <i class="text-coco-sage fas fa-check-circle"></i>
            <?= esc($flashSuccess) ?>
            <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 ml-2"><i class="text-xs fas fa-times"></i></button>
        </div>
    <?php elseif ($flashError): ?>
        <div id="flash-toast" class="top-20 left-1/2 z-[9998] fixed flex items-center gap-3 bg-red-500 shadow-2xl px-6 py-3 rounded-2xl font-semibold text-white text-sm -translate-x-1/2">
            <i class="fas fa-exclamation-circle"></i>
            <?= esc($flashError) ?>
            <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 ml-2"><i class="text-xs fas fa-times"></i></button>
        </div>
    <?php endif; ?>


    <!-- ══════════════════════ PAGE HERO BANNER ══════════════════════ -->
    <section class="relative bg-gradient-to-br from-coco-cream via-coco-sand to-coco-cream pt-28 pb-14 overflow-hidden">
        <!-- Decorative -->
        <div class="top-0 right-0 absolute bg-coco-orange/8 rounded-full w-72 h-72 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="bottom-0 left-0 absolute bg-coco-green/8 rounded-full w-48 h-48 -translate-x-1/3 translate-y-1/2 pointer-events-none"></div>

        <div class="z-10 relative mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 mb-6 font-body text-coco-mid text-xs">
                <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
                <i class="fa-chevron-right text-[9px] text-coco-tan fas"></i>
                <span class="font-semibold text-coco-orange">Products</span>
            </nav>

            <div class="flex lg:flex-row flex-col lg:justify-between lg:items-end gap-6">
                <div>
                    <p class="mb-2 font-bold text-coco-orange text-xs uppercase tracking-[0.3em]">Full Catalog</p>
                    <h1 class="font-display font-black text-coco-brown text-5xl sm:text-6xl leading-tight">
                        All Products
                    </h1>
                    <p class="mt-3 max-w-lg font-light text-coco-mid text-base">
                        Browse our full range of premium coconut coir products — from garden essentials to construction materials.
                    </p>
                </div>
                <!-- Search bar -->
                <div class="relative w-full lg:w-80">
                    <input id="search-input" type="text" placeholder="Search products..."
                        class="bg-white px-5 py-3 pr-12 border-2 border-coco-sand rounded-full w-full font-body text-coco-brown text-sm transition-all search-input placeholder-coco-tan/60">
                    <i class="top-1/2 right-4 absolute text-coco-tan text-sm -translate-y-1/2 pointer-events-none fas fa-search"></i>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════ FILTER + GRID ══════════════════════ -->
    <section class="bg-coco-cream py-10">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

            <!-- Filter bar -->
            <div class="flex sm:flex-row flex-col sm:justify-between sm:items-center gap-4 mb-8">

                <!-- Category pills -->
                <div class="flex flex-wrap gap-2" id="filter-pills">
                    <?php
                    $categories = ['All', 'Home & Living', 'Gardening', 'Construction', 'Craft & Utility', 'Agriculture'];
                    foreach ($categories as $i => $cat):
                        $active = $i === 0 ? 'active' : '';
                    ?>
                        <button class="filter-pill <?= $active ?> font-body text-xs font-semibold px-4 py-2 rounded-full border-2 border-coco-sand text-coco-dark tracking-wide"
                            data-category="<?= $cat ?>">
                            <?= $cat ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Sort + result count -->
                <div class="flex flex-shrink-0 items-center gap-3">
                    <span id="result-count" class="font-body text-coco-mid text-xs whitespace-nowrap">
                        Showing <strong class="text-coco-brown" id="count-num"><?= count($products) ?></strong> products
                    </span>
                    <div class="relative">
                        <select id="sort-select" class="py-2.5 pr-8 pl-4 border-2 border-coco-sand focus:border-coco-orange rounded-full focus:outline-none font-body font-semibold text-coco-dark text-xs transition-colors fiber-bg">
                            <option value="default">Sort: Featured</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                            <option value="name-asc">Name: A–Z</option>
                            <option value="newest">Newest First</option>
                        </select>
                        <i class="top-1/2 right-3 absolute text-[10px] text-coco-tan -translate-y-1/2 pointer-events-none fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div id="product-grid" class="gap-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                <?php
                /**
                 * Replace this static array with your model data:
                 * $products = $this->ProductModel->getAllProducts();
                 * Then pass it from the controller: 'products' => $products
                 * And use: foreach ($products as $p):
                 */
                $products = [
                    ['id' => 1, 'name' => 'Coir Door Mat',        'category' => 'Home & Living',   'price' => 350,  'image' => 'coirdoormat.png',        'badge' => 'New',        'desc' => 'Natural, durable, fully biodegradable entrance mat.'],
                    ['id' => 2, 'name' => 'Coco Grow Bag',         'category' => 'Gardening',       'price' => 180,  'image' => 'growbag.png',     'badge' => 'Trending',   'desc' => 'Perfect moisture retention for vegetables and herbs.'],
                    ['id' => 3, 'name' => 'Geotextile Mat',        'category' => 'Construction',    'price' => 1200, 'image' => 'geotextitle.png',  'badge' => 'Best Seller', 'desc' => 'Slope stabilization and erosion control, project-grade.'],
                    ['id' => 4, 'name' => 'Twisted Coir Rope',     'category' => 'Craft & Utility', 'price' => 220,  'image' => 'coirrope.jpg',        'badge' => '',           'desc' => 'Strong, salt-resistant rope for marine and garden use.'],
                    ['id' => 5, 'name' => 'Coir Mulch Mat',        'category' => 'Gardening',       'price' => 295,  'image' => 'coirmulch.jpg',       'badge' => 'New',        'desc' => 'Suppress weeds and retain soil moisture naturally.'],
                    ['id' => 6, 'name' => 'Erosion Control Net',   'category' => 'Construction',    'price' => 890,  'image' => 'coir-erosion.jpg',     'badge' => '',           'desc' => 'Biodegradable netting for slopes and waterway banks.'],
                    ['id' => 7, 'name' => 'Coco Peat Block',       'category' => 'Agriculture',     'price' => 150,  'image' => 'coir-peat.jpg',        'badge' => 'Sale',       'desc' => 'Compressed coco peat — expands 5–6x with water.'],
                    ['id' => 8, 'name' => 'Coir Hanging Basket',   'category' => 'Home & Living',   'price' => 420,  'image' => 'coir-basket.png',      'badge' => 'Trending',   'desc' => 'Rustic lined hanging baskets for indoor and outdoor plants.'],
                    ['id' => 9, 'name' => 'Coir Fibre Bale',       'category' => 'Agriculture',     'price' => 550,  'image' => 'coir-bale.jpg',        'badge' => 'Best Seller', 'desc' => 'Bulk coir fibre for soil amendment and composting.'],
                    ['id' => 10, 'name' => 'Coir Log',               'category' => 'Construction',    'price' => 1800, 'image' => 'coir_logs.jpg',         'badge' => '',           'desc' => 'Cylindrical rolls for streambank and shoreline erosion.'],
                    ['id' => 11, 'name' => 'Seedling Tray Liner',   'category' => 'Gardening',       'price' => 95,   'image' => 'coir-liner.png',       'badge' => 'New',        'desc' => 'Natural coir liners for nursery trays and planters.'],
                    ['id' => 12, 'name' => 'Coir Doormat Runner',   'category' => 'Home & Living',   'price' => 680,  'image' => 'coir-runner.jpg',      'badge' => '',           'desc' => 'Long hallway runner, natural woven coir design.'],
                ];

                $badgeClass = [
                    'New'        => 'badge-new',
                    'Trending'   => 'badge-trending',
                    'Best Seller' => 'badge-bestseller',
                    'Sale'       => 'badge-sale',
                ];

                foreach ($products as $idx => $p):
                    $catDisplay = $categoryLabels[$p['category']] ?? ucfirst(str_replace('_', ' ', $p['category']));
                    $badge      = $p['badge'] ?? '';
                    $delay      = ($idx % 4) * 80;
                ?>
                    <div class="flex flex-col bg-white shadow-sm border border-coco-sand/60 rounded-3xl overflow-hidden product-card fade-card"
                        data-category="<?= esc($catDisplay) ?>"
                        data-price="<?= $p['price'] ?>"
                        data-name="<?= strtolower(esc($p['name'])) ?>"
                        style="animation-delay: <?= $delay ?>ms;">

                        <!-- Image -->
                        <div class="relative bg-gradient-to-br from-coco-sand/60 to-coco-cream aspect-[4/3] overflow-hidden">
                            <img src="/images/<?= esc($p['image'] ?? 'default.png') ?>" alt="<?= esc($p['name']) ?>"
                                class="w-full h-full object-cover card-img">

                            <?php if (!empty($badge)): ?>
                                <span class="absolute top-3 left-3 <?= $badgeClass[$p['badge']] ?? '' ?> text-[10px] font-black tracking-widest uppercase rounded-full px-3 py-1">
                                    <?= $p['badge'] ?>
                                </span>
                            <?php endif; ?>

                            <!-- Quick action overlay -->
                            <div class="absolute inset-0 flex justify-center items-center gap-2 bg-coco-brown/0 group-hover:bg-coco-brown/10 opacity-0 hover:opacity-100 transition-colors transition-opacity">
                            </div>

                            <!-- Wishlist -->
                            <button class="top-3 right-3 absolute flex justify-center items-center bg-white/90 hover:bg-coco-orange shadow-sm backdrop-blur-sm rounded-full w-8 h-8 text-coco-tan hover:text-white transition-all duration-200 wishlist-btn"
                                data-id="<?= $p['id'] ?>">
                                <i class="text-xs fas fa-heart"></i>
                            </button>
                        </div>

                        <!-- Info -->
                        <div class="flex flex-col flex-1 p-5">
                            <p class="mb-1 font-bold text-[10px] text-coco-green uppercase tracking-widest"><?= $p['category'] ?></p>
                            <h3 class="mb-1 font-display font-black text-coco-brown text-lg leading-snug"><?= $p['name'] ?></h3>
                            <p class="flex-1 mb-4 text-coco-mid text-xs leading-relaxed"><?= $p['desc'] ?></p>

                            <div class="flex justify-between items-center mt-auto">
                                <span class="font-display font-black text-coco-orange text-xl">₱<?= number_format($p['price']) ?></span>
                                <div class="flex gap-2">
                                    <a href="<?= site_url('products/' . $p['id']) ?>"
                                        class="flex justify-center items-center border-2 border-coco-sand hover:border-coco-orange rounded-full w-9 h-9 text-coco-mid hover:text-coco-orange text-xs transition-all"
                                        title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="bg-coco-brown hover:bg-coco-orange add-to-cart px-4 py-2 rounded-full font-bold text-coco-cream text-xs whitespace-nowrap transition-colors duration-200"
                                        data-id="<?= $p['id'] ?>" data-name="<?= $p['name'] ?>" data-price="<?= $p['price'] ?>" data-image="<?= esc($p['image'] ?? 'default.png') ?>">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div><!-- /product-grid -->

            <!-- Empty state (hidden by default) -->
            <div id="empty-state" class="hidden py-24 text-center">
                <div class="mb-4 text-6xl">🥥</div>
                <h3 class="mb-2 font-display font-black text-coco-brown text-2xl">No products found</h3>
                <p class="text-coco-mid text-sm">Try a different category or search term.</p>
                <button onclick="resetFilters()" class="bg-coco-orange hover:bg-coco-dark mt-6 px-8 py-3 rounded-full font-bold text-white text-sm transition-colors">
                    Clear Filters
                </button>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mt-14" id="pagination">
                <button class="flex justify-center items-center border-2 border-coco-sand rounded-full w-10 h-10 font-bold text-coco-mid text-sm page-btn" title="Previous">
                    <i class="fa-chevron-left text-xs fas"></i>
                </button>
                <button class="flex justify-center items-center border-2 rounded-full w-10 h-10 font-bold text-sm page-btn active">1</button>
                <button class="flex justify-center items-center border-2 border-coco-sand rounded-full w-10 h-10 font-bold text-coco-mid text-sm page-btn">2</button>
                <button class="flex justify-center items-center border-2 border-coco-sand rounded-full w-10 h-10 font-bold text-coco-mid text-sm page-btn">3</button>
                <span class="px-1 text-coco-tan text-sm">…</span>
                <button class="flex justify-center items-center border-2 border-coco-sand rounded-full w-10 h-10 font-bold text-coco-mid text-sm page-btn" title="Next">
                    <i class="fa-chevron-right text-xs fas"></i>
                </button>
            </div>

        </div>
    </section>


    <?= $this->include('components/footer') ?>

    <!-- ══════════════════════ TOAST NOTIFICATION ══════════════════════ -->
    <div id="toast" class="right-6 bottom-6 z-[9998] fixed opacity-0 transition-all translate-y-24 duration-300">
        <div class="flex items-center gap-3 bg-coco-brown shadow-2xl px-6 py-3 rounded-2xl font-semibold text-coco-cream text-sm">
            <i class="text-coco-leaf fas fa-check-circle"></i>
            <span id="toast-msg">Added to cart!</span>
        </div>
    </div>


    <script>
        // Auto-dismiss flash toast
        const flashToast = document.getElementById('flash-toast');
        if (flashToast) {
            setTimeout(() => {
                flashToast.style.opacity = '0';
                flashToast.style.transform = 'translateX(-50%) translateY(-10px)';
                setTimeout(() => flashToast.remove(), 500);
            }, 3500);
        }

        // ── Nav scroll ──
        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => header.classList.toggle('nav-scrolled', window.scrollY > 50));

        // ── Mobile menu ──
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        hamburger.addEventListener('click', () => mobileMenu.classList.toggle('open'));
        mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.remove('open')));

        // ── Filter & Search Logic ──
        let activeCategory = 'All';

        function getVisibleCards() {
            const query = document.getElementById('search-input').value.toLowerCase().trim();
            const cards = [...document.querySelectorAll('#product-grid .product-card')];

            let visible = 0;
            cards.forEach(card => {
                const cat = card.dataset.category;
                const name = card.dataset.name;
                const catMatch = activeCategory === 'All' || cat === activeCategory;
                const nameMatch = !query || name.includes(query);

                if (catMatch && nameMatch) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('count-num').textContent = visible;
            document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
            document.getElementById('pagination').classList.toggle('hidden', visible === 0);
        }

        // Category pill clicks
        document.getElementById('filter-pills').addEventListener('click', e => {
            const btn = e.target.closest('.filter-pill');
            if (!btn) return;
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            activeCategory = btn.dataset.category;
            getVisibleCards();
        });

        // Live search
        document.getElementById('search-input').addEventListener('input', getVisibleCards);

        // Sort
        document.getElementById('sort-select').addEventListener('change', function() {
            const grid = document.getElementById('product-grid');
            const cards = [...grid.querySelectorAll('.product-card')];

            cards.sort((a, b) => {
                switch (this.value) {
                    case 'price-asc':
                        return +a.dataset.price - +b.dataset.price;
                    case 'price-desc':
                        return +b.dataset.price - +a.dataset.price;
                    case 'name-asc':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    default:
                        return 0;
                }
            });
            cards.forEach(c => grid.appendChild(c));
        });

        function resetFilters() {
            activeCategory = 'All';
            document.getElementById('search-input').value = '';
            document.querySelectorAll('.filter-pill').forEach((p, i) => p.classList.toggle('active', i === 0));
            getVisibleCards();
        }

        // ── Add to cart toast ──
        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-msg').textContent = msg;
            toast.classList.remove('translate-y-24', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 2800);
        }

        // ── Add to Cart ──
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');

        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
                const image = this.dataset.image ?? '';

                this.disabled = true;
                this.textContent = 'Adding…';

                const fd = new FormData();
                fd.append('id', id);
                fd.append('name', name);
                fd.append('price', price);
                fd.append('image', image);

                // Get CSRF from the hidden input in cart.php style, or from a meta tag
                const csrfInput = document.querySelector('input[name^="csrf"]');
                if (csrfInput) fd.append(csrfInput.name, csrfInput.value);

                try {
                    const res = await fetch('<?= site_url('cart/add') ?>', {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const result = await res.json();

                    if (result.success) {
                        showToast(result.message ?? `"${name}" added to cart!`);

                        // ── Update header badge ──
                        const badge = document.getElementById('cart-count');
                        if (badge && result.totalItems !== undefined) {
                            badge.textContent = result.totalItems;
                            badge.classList.toggle('hidden', result.totalItems === 0);
                        }

                        if (result.csrf_hash && csrfInput) {
                            csrfInput.value = result.csrf_hash;
                        }
                    } else {
                        showToast(result.message ?? 'Failed to add item.');
                    }
                } catch (err) {
                    console.error('Add to cart error:', err);
                    showToast('Something went wrong. Try again.');
                } finally {
                    this.disabled = false;
                    this.textContent = 'Add to Cart';
                }
            });
        });

        // ── Wishlist toggle ──
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isWished = icon.classList.contains('text-white');
                this.classList.toggle('bg-coco-orange', !isWished);
                this.classList.toggle('bg-white/90', isWished);
                icon.classList.toggle('text-white', !isWished);
                icon.classList.toggle('text-coco-tan', isWished);
                showToast(isWished ? 'Removed from wishlist' : 'Added to wishlist!');
            });
        });

        // ── Pagination buttons ──
        document.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                if (!this.querySelector('i')) this.classList.add('active');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>

</body>

</html>