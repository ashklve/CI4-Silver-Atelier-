<!-- ══════════════════════════ HEADER ══════════════════════════ -->
<header id="main-header" class="top-0 right-0 left-0 z-50 fixed px-4 lg:px-8 py-3 transition-all duration-400">
    <?php $isLanding = uri_string() === '' || uri_string() === '/'; ?>
    <div class="flex justify-between items-center mx-auto max-w-7xl">

        <!-- Logo -->
        <a href="<?= $isLanding ? '#home' : site_url('/') ?>" class="group flex items-center gap-3">
            <svg class="drop-shadow-md w-12 h-12" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Sun -->
                <circle cx="19" cy="13" r="8" fill="#E87722" opacity="0.92" />
                <path d="M13 13 Q16 11 19 13" stroke="#F4A940" stroke-width="1.5" fill="none" stroke-linecap="round" />
                <!-- Palm trunk -->
                <path d="M33 45 Q31 33 35 21" stroke="#5C3317" stroke-width="3" stroke-linecap="round" />
                <!-- Palm leaves -->
                <path d="M35 21 Q44 15 48 11" stroke="#4A7C59" stroke-width="2.5" stroke-linecap="round" />
                <path d="M35 21 Q27 13 25 8" stroke="#6BAF78" stroke-width="2" stroke-linecap="round" />
                <path d="M35 21 Q42 20 46 18" stroke="#4A7C59" stroke-width="2" stroke-linecap="round" />
                <!-- Ground -->
                <ellipse cx="30" cy="46" rx="16" ry="4" fill="#6BAF78" opacity="0.45" />
                <!-- Coconuts -->
                <circle cx="21" cy="44" r="5.2" fill="#3B2314" />
                <circle cx="30" cy="44" r="5.2" fill="#5C3317" />
                <circle cx="21" cy="44" r="2.2" fill="#8B5E3C" opacity="0.55" />
                <circle cx="30" cy="44" r="2.2" fill="#8B5E3C" opacity="0.55" />
            </svg>
            <div>
                <span class="block font-display font-black text-coco-brown text-2xl leading-none tracking-wide">COCOIR</span>
                <span class="font-body text-[10px] text-coco-green uppercase leading-none tracking-[0.22em]">Coconut Coir Co.</span>
            </div>
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-8">
            <a href="<?= $isLanding ? '#home' : site_url('/') ?>" class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Home</a>
            <a href="<?= $isLanding ? '#about' : site_url('/') . '#about' ?>" class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">About</a>
            <a href="<?= site_url('products') ?>" class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Products</a>
            <a href="<?= $isLanding ? '#why-us' : site_url('/') . '#why-us' ?>" class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Why Us</a>
            <a href="<?= $isLanding ? '#contact' : site_url('/') . '#contact' ?>" class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Contact</a>
        </nav>

        <!-- CTA -->
        <div class="hidden lg:flex items-center gap-3">
            <a href="<?= site_url('cart') ?>" class="group relative mr-2 p-2 text-coco-dark hover:text-coco-orange transition-colors" title="View Cart">
                <i class="text-lg fas fa-shopping-bag"></i>
                <?php
                $cartModel = new \App\Models\CartItemModel();
                $userId = session()->get('user')['id'] ?? null;
                $conditions = $userId ? ['user_id' => $userId] : ['session_id' => session_id()];
                $result = $cartModel->selectSum('quantity')->where($conditions)->first();
                $cartCount = (int)($result['quantity'] ?? 0);
                ?>
                <span id="global-cart-count" class="top-0 right-0 absolute flex justify-center items-center bg-coco-orange <?= $cartCount > 0 ? '' : 'opacity-0' ?> -mt-1 -mr-1 rounded-full w-4 h-4 font-bold text-[10px] text-white transition-opacity"><?= $cartCount ?></span>
            </a>
            <a href="<?= site_url('login') ?>" class="px-5 py-2 border-2 border-coco-tan hover:border-coco-orange rounded-full font-semibold text-coco-dark hover:text-coco-orange text-sm transition-all">
                Sign In
            </a>
        </div>

        <!-- Hamburger -->
        <button id="hamburger" class="lg:hidden flex flex-col gap-1.5 p-2" aria-label="Menu">
            <span class="block bg-coco-brown w-6 h-0.5"></span>
            <span class="block bg-coco-brown w-6 h-0.5"></span>
            <span class="block bg-coco-brown w-4 h-0.5"></span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden bg-coco-cream px-4 border-coco-sand border-t">
        <nav class="flex flex-col gap-3 py-4">
            <a href="<?= $isLanding ? '#home' : site_url('/') ?>" class="py-2 border-coco-sand/60 border-b font-semibold text-coco-dark text-xs uppercase tracking-widest">Home</a>
            <a href="<?= $isLanding ? '#about' : site_url('/') . '#about' ?>" class="py-2 border-coco-sand/60 border-b font-semibold text-coco-dark text-xs uppercase tracking-widest">About</a>
            <a href="<?= site_url('products') ?>" class="py-2 border-coco-sand/60 border-b font-semibold text-coco-dark text-xs uppercase tracking-widest">Products</a>
            <a href="<?= $isLanding ? '#why-us' : site_url('/') . '#why-us' ?>" class="py-2 border-coco-sand/60 border-b font-semibold text-coco-dark text-xs uppercase tracking-widest">Why Us</a>
            <a href="<?= $isLanding ? '#contact' : site_url('/') . '#contact' ?>" class="py-2 font-semibold text-coco-dark text-xs uppercase tracking-widest">Contact</a>
            <a href="<?= site_url('cart') ?>" class="bg-coco-orange mt-2 py-3 rounded-full font-bold text-white text-sm text-center">View Cart</a>
        </nav>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('cartUpdated', (event) => {
                const badge = document.getElementById('global-cart-count');
                if (badge) {
                    const count = event.detail.count;
                    badge.textContent = count;
                    badge.classList.toggle('opacity-0', count === 0);
                }
            });
        });
    </script>
</header>