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
                        'display': ['Barlow', 'sans-serif'],
                        'body':    ['Lato', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Lato', sans-serif; }

        /* Grain overlay */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.07'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 9999; opacity: 0.4;
        }

        .nav-scrolled {
            background: rgba(250, 243, 232, 0.96) !important;
            backdrop-filter: blur(14px);
            box-shadow: 0 2px 24px rgba(59,35,20,0.10);
        }

        .fiber-bg {
            background: repeating-linear-gradient(
                108deg, transparent, transparent 2px,
                rgba(139,94,60,0.035) 2px, rgba(139,94,60,0.035) 4px
            ), #FAF3E8;
        }

        /* Product card */
        .product-card {
            transition: transform 0.32s cubic-bezier(.34,1.56,.64,1), box-shadow 0.32s ease;
        }
        .product-card:hover {
            transform: translateY(-8px) rotate(0.3deg);
            box-shadow: 0 24px 48px rgba(59,35,20,0.15);
        }
        .product-card:hover .card-img { transform: scale(1.06); }
        .card-img { transition: transform 0.5s ease; }

        /* Filter pill active */
        .filter-pill { transition: all 0.2s ease; cursor: pointer; }
        .filter-pill.active, .filter-pill:hover {
            background: #E87722; color: #fff; border-color: #E87722;
        }

        /* Sort dropdown */
        select { appearance: none; cursor: pointer; }

        /* Badges */
        .badge-new        { background: #E87722; color: #fff; }
        .badge-trending   { background: #4A7C59; color: #fff; }
        .badge-bestseller { background: #3B2314; color: #FAF3E8; }
        .badge-sale       { background: #F4A940; color: #3B2314; }

        /* Pagination */
        .page-btn { transition: all 0.2s ease; }
        .page-btn.active, .page-btn:hover { background: #E87722; color: #fff; border-color: #E87722; }

        /* Search focus */
        .search-input:focus { outline: none; border-color: #E87722; box-shadow: 0 0 0 3px rgba(232,119,34,0.15); }

        /* Mobile menu */
        #mobile-menu { max-height: 0; opacity: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.4s ease; }
        #mobile-menu.open { max-height: 440px; opacity: 1; }

        /* Fade-in cards */
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-card { animation: fadeUp 0.5s ease forwards; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FAF3E8; }
        ::-webkit-scrollbar-thumb { background: #C8956C; border-radius: 3px; }
    </style>
</head>

<body class="font-body text-coco-brown bg-coco-cream">

<?= $this->include('components/header') ?>


<!-- ══════════════════════ FLASH MESSAGES ══════════════════════ -->
<?php $flashSuccess = session()->getFlashdata('success'); $flashError = session()->getFlashdata('error'); ?>
<?php if ($flashSuccess): ?>
<div id="flash-toast" class="fixed top-20 left-1/2 -translate-x-1/2 z-[9998] flex items-center gap-3 bg-coco-green text-white px-6 py-3 rounded-2xl shadow-2xl text-sm font-semibold transition-all duration-500">
    <i class="fas fa-check-circle text-coco-sage"></i>
    <?= esc($flashSuccess) ?>
    <button onclick="this.parentElement.remove()" class="ml-2 opacity-60 hover:opacity-100"><i class="fas fa-times text-xs"></i></button>
</div>
<?php elseif ($flashError): ?>
<div id="flash-toast" class="fixed top-20 left-1/2 -translate-x-1/2 z-[9998] flex items-center gap-3 bg-red-500 text-white px-6 py-3 rounded-2xl shadow-2xl text-sm font-semibold">
    <i class="fas fa-exclamation-circle"></i>
    <?= esc($flashError) ?>
    <button onclick="this.parentElement.remove()" class="ml-2 opacity-60 hover:opacity-100"><i class="fas fa-times text-xs"></i></button>
</div>
<?php endif; ?>


<!-- ══════════════════════ PAGE HERO BANNER ══════════════════════ -->
<section class="pt-28 pb-14 bg-gradient-to-br from-coco-cream via-coco-sand to-coco-cream relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-coco-orange/8 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full bg-coco-green/8 translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-coco-mid mb-6 font-body">
            <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[9px] text-coco-tan"></i>
            <span class="text-coco-orange font-semibold">Products</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <p class="text-coco-orange font-bold text-xs tracking-[0.3em] uppercase mb-2">Full Catalog</p>
                <h1 class="font-display text-5xl sm:text-6xl font-black text-coco-brown leading-tight">
                    All Products
                </h1>
                <p class="text-coco-mid text-base mt-3 font-light max-w-lg">
                    Browse our full range of premium coconut coir products — from garden essentials to construction materials.
                </p>
            </div>
            <!-- Search bar -->
            <div class="relative w-full lg:w-80">
                <input id="search-input" type="text" placeholder="Search products..."
                    class="search-input w-full bg-white border-2 border-coco-sand text-coco-brown placeholder-coco-tan/60 px-5 py-3 pr-12 rounded-full text-sm font-body transition-all">
                <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-coco-tan text-sm pointer-events-none"></i>
            </div>
        </div>
    </div>
</section>


<!-- ══════════════════════ FILTER + GRID ══════════════════════ -->
<section class="py-10 bg-coco-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Filter bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

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
            <div class="flex items-center gap-3 flex-shrink-0">
                <span id="result-count" class="text-coco-mid text-xs font-body whitespace-nowrap">
                    Showing <strong class="text-coco-brown" id="count-num"><?= count($products) ?></strong> products
                </span>
                <div class="relative">
                    <select id="sort-select" class="fiber-bg border-2 border-coco-sand text-coco-dark text-xs font-semibold pl-4 pr-8 py-2.5 rounded-full font-body focus:outline-none focus:border-coco-orange transition-colors">
                        <option value="default">Sort: Featured</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="name-asc">Name: A–Z</option>
                        <option value="newest">Newest First</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-coco-tan text-[10px] pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <?php
            /**
             * Replace this static array with your model data:
             * $products = $this->ProductModel->getAllProducts();
             * Then pass it from the controller: 'products' => $products
             * And use: foreach ($products as $p):
             */
            $products = [
                ['id'=>1, 'name'=>'Coir Door Mat',        'category'=>'Home & Living',   'price'=>350,  'image'=>'coirdoormat.png',        'badge'=>'New',        'desc'=>'Natural, durable, fully biodegradable entrance mat.'],
                ['id'=>2, 'name'=>'Coco Grow Bag',         'category'=>'Gardening',       'price'=>180,  'image'=>'growbag.png',     'badge'=>'Trending',   'desc'=>'Perfect moisture retention for vegetables and herbs.'],
                ['id'=>3, 'name'=>'Geotextile Mat',        'category'=>'Construction',    'price'=>1200, 'image'=>'geotextitle.png',  'badge'=>'Best Seller','desc'=>'Slope stabilization and erosion control, project-grade.'],
                ['id'=>4, 'name'=>'Twisted Coir Rope',     'category'=>'Craft & Utility', 'price'=>220,  'image'=>'coirrope.jpg',        'badge'=>'',           'desc'=>'Strong, salt-resistant rope for marine and garden use.'],
                ['id'=>5, 'name'=>'Coir Mulch Mat',        'category'=>'Gardening',       'price'=>295,  'image'=>'coirmulch.jpg',       'badge'=>'New',        'desc'=>'Suppress weeds and retain soil moisture naturally.'],
                ['id'=>6, 'name'=>'Erosion Control Net',   'category'=>'Construction',    'price'=>890,  'image'=>'coir-erosion.jpg',     'badge'=>'',           'desc'=>'Biodegradable netting for slopes and waterway banks.'],
                ['id'=>7, 'name'=>'Coco Peat Block',       'category'=>'Agriculture',     'price'=>150,  'image'=>'coir-peat.jpg',        'badge'=>'Sale',       'desc'=>'Compressed coco peat — expands 5–6x with water.'],
                ['id'=>8, 'name'=>'Coir Hanging Basket',   'category'=>'Home & Living',   'price'=>420,  'image'=>'coir-basket.png',      'badge'=>'Trending',   'desc'=>'Rustic lined hanging baskets for indoor and outdoor plants.'],
                ['id'=>9, 'name'=>'Coir Fibre Bale',       'category'=>'Agriculture',     'price'=>550,  'image'=>'coir-bale.jpg',        'badge'=>'Best Seller','desc'=>'Bulk coir fibre for soil amendment and composting.'],
                ['id'=>10,'name'=>'Coir Log',               'category'=>'Construction',    'price'=>1800, 'image'=>'coir_logs.jpg',         'badge'=>'',           'desc'=>'Cylindrical rolls for streambank and shoreline erosion.'],
                ['id'=>11,'name'=>'Seedling Tray Liner',   'category'=>'Gardening',       'price'=>95,   'image'=>'coir-liner.png',       'badge'=>'New',        'desc'=>'Natural coir liners for nursery trays and planters.'],
                ['id'=>12,'name'=>'Coir Doormat Runner',   'category'=>'Home & Living',   'price'=>680,  'image'=>'coir-runner.jpg',      'badge'=>'',           'desc'=>'Long hallway runner, natural woven coir design.'],
            ];

            $badgeClass = [
                'New'        => 'badge-new',
                'Trending'   => 'badge-trending',
                'Best Seller'=> 'badge-bestseller',
                'Sale'       => 'badge-sale',
            ];

           foreach ($products as $idx => $p):
                $catDisplay = $categoryLabels[$p['category']] ?? ucfirst(str_replace('_', ' ', $p['category']));
                $badge      = $p['badge'] ?? '';
                $delay      = ($idx % 4) * 80;
            ?>
            <div class="product-card fade-card bg-white rounded-3xl overflow-hidden shadow-sm border border-coco-sand/60 flex flex-col"
                 data-category="<?= esc($catDisplay) ?>"
                 data-price="<?= $p['price'] ?>"
                 data-name="<?= strtolower(esc($p['name'])) ?>"
                 style="animation-delay: <?= $delay ?>ms;">

                <!-- Image -->
                <div class="relative aspect-[4/3] bg-gradient-to-br from-coco-sand/60 to-coco-cream overflow-hidden">
                    <img src="/images/<?= esc($p['image'] ?? 'default.png') ?>" alt="<?= esc($p['name']) ?>"
                         class="card-img w-full h-full object-cover">

                    <?php if (!empty($badge)): ?>
                    <span class="absolute top-3 left-3 <?= $badgeClass[$p['badge']] ?? '' ?> text-[10px] font-black tracking-widest uppercase rounded-full px-3 py-1">
                        <?= $p['badge'] ?>
                    </span>
                    <?php endif; ?>

                    <!-- Quick action overlay -->
                    <div class="absolute inset-0 bg-coco-brown/0 group-hover:bg-coco-brown/10 transition-colors flex items-center justify-center gap-2 opacity-0 hover:opacity-100 transition-opacity">
                    </div>

                    <!-- Wishlist -->
                    <button class="wishlist-btn absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm hover:bg-coco-orange hover:text-white text-coco-tan transition-all duration-200"
                            data-id="<?= $p['id'] ?>">
                        <i class="fas fa-heart text-xs"></i>
                    </button>
                </div>

                <!-- Info -->
                <div class="p-5 flex flex-col flex-1">
                    <p class="text-coco-green text-[10px] font-bold tracking-widest uppercase mb-1"><?= $p['category'] ?></p>
                    <h3 class="font-display font-black text-coco-brown text-lg leading-snug mb-1"><?= $p['name'] ?></h3>
                    <p class="text-coco-mid text-xs leading-relaxed mb-4 flex-1"><?= $p['desc'] ?></p>

                    <div class="flex items-center justify-between mt-auto">
                        <span class="font-display font-black text-xl text-coco-orange">₱<?= number_format($p['price']) ?></span>
                        <div class="flex gap-2">
                            <a href="<?= site_url('products/' . $p['id']) ?>"
                               class="w-9 h-9 border-2 border-coco-sand rounded-full flex items-center justify-center text-coco-mid hover:border-coco-orange hover:text-coco-orange transition-all text-xs"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="add-to-cart bg-coco-brown text-coco-cream text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-orange transition-colors duration-200 whitespace-nowrap"
                                    data-id="<?= $p['id'] ?>" data-name="<?= $p['name'] ?>" data-price="<?= $p['price'] ?>">
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
            <div class="text-6xl mb-4">🥥</div>
            <h3 class="font-display font-black text-2xl text-coco-brown mb-2">No products found</h3>
            <p class="text-coco-mid text-sm">Try a different category or search term.</p>
            <button onclick="resetFilters()" class="mt-6 bg-coco-orange text-white px-8 py-3 rounded-full font-bold text-sm hover:bg-coco-dark transition-colors">
                Clear Filters
            </button>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center gap-2 mt-14" id="pagination">
            <button class="page-btn w-10 h-10 rounded-full border-2 border-coco-sand text-coco-mid text-sm font-bold flex items-center justify-center" title="Previous">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <button class="page-btn active w-10 h-10 rounded-full border-2 text-sm font-bold flex items-center justify-center">1</button>
            <button class="page-btn w-10 h-10 rounded-full border-2 border-coco-sand text-coco-mid text-sm font-bold flex items-center justify-center">2</button>
            <button class="page-btn w-10 h-10 rounded-full border-2 border-coco-sand text-coco-mid text-sm font-bold flex items-center justify-center">3</button>
            <span class="text-coco-tan text-sm px-1">…</span>
            <button class="page-btn w-10 h-10 rounded-full border-2 border-coco-sand text-coco-mid text-sm font-bold flex items-center justify-center" title="Next">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>

    </div>
</section>


 <?= $this->include('components/footer') ?>

<!-- ══════════════════════ TOAST NOTIFICATION ══════════════════════ -->
<div id="toast" class="fixed bottom-6 right-6 z-[9998] translate-y-24 opacity-0 transition-all duration-300">
    <div class="bg-coco-brown text-coco-cream px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-semibold">
        <i class="fas fa-check-circle text-coco-leaf"></i>
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
    const cards  = [...document.querySelectorAll('#product-grid .product-card')];

    let visible = 0;
    cards.forEach(card => {
        const cat    = card.dataset.category;
        const name   = card.dataset.name;
        const catMatch  = activeCategory === 'All' || cat === activeCategory;
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
    const grid  = document.getElementById('product-grid');
    const cards = [...grid.querySelectorAll('.product-card')];

    cards.sort((a, b) => {
        switch (this.value) {
            case 'price-asc':  return +a.dataset.price - +b.dataset.price;
            case 'price-desc': return +b.dataset.price - +a.dataset.price;
            case 'name-asc':   return a.dataset.name.localeCompare(b.dataset.name);
            default:           return 0;
        }
    });
    cards.forEach(c => grid.appendChild(c));
});

function resetFilters() {
    activeCategory = 'All';
    document.getElementById('search-input').value = '';
    document.querySelectorAll('.filter-pill').forEach((p,i) => p.classList.toggle('active', i===0));
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

document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const name = this.dataset.name;
        showToast(`"${name}" added to cart!`);
        // TODO: wire up to your cart controller via fetch/AJAX
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>

</body>
</html>