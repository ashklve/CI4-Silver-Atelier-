<?php
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Products — COCOIR Coconut Coir Co.']) ?>
<style>
    .product-card:hover .card-img {
        transform: scale(1.06);
    }

    .card-img {
        transition: transform 0.5s ease;
    }

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

    select {
        appearance: none;
        cursor: pointer;
    }

    .badge-sale {
        background: #F4A940;
        color: #3B2314;
    }

    .page-btn {
        transition: all 0.2s ease;
    }

    .page-btn.active,
    .page-btn:hover {
        background: #E87722;
        color: #fff;
        border-color: #E87722;
    }

    .search-input:focus {
        outline: none;
        border-color: #E87722;
        box-shadow: 0 0 0 3px rgba(232, 119, 34, 0.15);
    }

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
</style>

<body class="bg-coco-cream font-body text-coco-brown">

    <?= $this->include('components/header') ?>
    <input type="hidden" class="csrf-token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

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
                <div class="flex-grow">
                    <p class="mb-2 font-bold text-coco-orange text-xs uppercase tracking-[0.3em]">Full Catalog</p>
                    <h1 class="font-display font-black text-coco-brown text-5xl sm:text-6xl leading-tight">
                        All Products
                    </h1>
                    <p class="mt-3 max-w-lg font-light text-coco-mid text-base">
                        Browse our full range of premium coconut coir products — from garden essentials to construction materials.
                    </p>
                </div>
                <!-- Search bar -->
                <div class="relative flex-shrink-0 w-full lg:w-80">
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
                        Showing <strong class="text-coco-brown" id="count-num">12</strong> products
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
                    $delay = ($idx % 4) * 80;
                ?>
                    <div class="flex flex-col bg-white shadow-sm border border-coco-sand/60 rounded-3xl overflow-hidden product-card fade-card"
                        data-category="<?= $p['category'] ?>"
                        data-price="<?= $p['price'] ?>"
                        data-name="<?= strtolower($p['name']) ?>"
                        style="animation-delay: <?= $delay ?>ms;">

                        <!-- Image -->
                        <div class="relative bg-gradient-to-br from-coco-sand/60 to-coco-cream aspect-[4/3] overflow-hidden">
                            <img src="/images/<?= $p['image'] ?>" alt="<?= $p['name'] ?>"
                                class="w-full h-full object-cover card-img">

                            <?php if ($p['badge']): ?>
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
                                        data-id="<?= $p['id'] ?>" data-name="<?= $p['name'] ?>" data-price="<?= $p['price'] ?>" data-image="<?= $p['image'] ?>">
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

        // ── Cart Logic ──
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = parseFloat(this.dataset.price);
                const image = this.dataset.image;
                const csrfToken = document.querySelector('.csrf-token');

                const formData = new FormData();
                formData.append('id', id);
                formData.append('name', name);
                formData.append('price', price);
                formData.append('image', image);
                formData.append(csrfToken.name, csrfToken.value);

                try {
                    const response = await fetch('<?= site_url('cart/add') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const result = await response.json();

                    if (result.success) {
                        window.dispatchEvent(new CustomEvent('cartUpdated', {
                            detail: {
                                count: result.totalItems
                            }
                        }));
                        showToast(`"${name}" added to cart!`);
                        csrfToken.value = result.csrf_hash; // Update CSRF token
                    } else {
                        showToast(result.message || 'Could not add to cart.', 'error');
                    }
                } catch (error) {
                    showToast('An error occurred while adding to cart.', 'error');
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