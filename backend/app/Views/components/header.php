<!-- ══════════════════════════ HEADER ══════════════════════════ -->
<header id="main-header" class="top-0 right-0 left-0 z-50 fixed px-4 lg:px-8 py-3 transition-all duration-400">
    <?php
    $isLanding   = uri_string() === '' || uri_string() === '/';
    $sessionUser = session()->get('user');

    // Cart count
    $cartModel  = new \App\Models\CartItemModel();
    $userId     = $sessionUser['id'] ?? null;
    $conditions = $userId ? ['user_id' => $userId] : ['session_id' => session_id()];
    $result     = $cartModel->selectSum('quantity')->where($conditions)->first();
    $cartCount  = (int)($result['quantity'] ?? 0);
    ?>

    <style>
        .user-dropdown {
            pointer-events: none;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .user-menu:hover .user-dropdown,
        .user-menu:focus-within .user-dropdown {
            pointer-events: auto;
            opacity: 1;
            transform: translateY(0);
        }
        #mobile-menu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, opacity 0.3s ease;
        }
        #mobile-menu.open { max-height: 600px; opacity: 1; }
        .nav-scrolled {
            background: rgba(250, 243, 232, 0.96) !important;
            backdrop-filter: blur(14px);
            box-shadow: 0 2px 24px rgba(59,35,20,0.10);
        }
    </style>

    <div class="flex justify-between items-center mx-auto max-w-7xl">

        <!-- ── Logo ── -->
        <a href="<?= $isLanding ? '#home' : site_url('/') ?>" class="group flex items-center gap-3">
            <svg class="drop-shadow-md w-12 h-12" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="19" cy="13" r="8" fill="#E87722" opacity="0.92"/>
                <path d="M13 13 Q16 11 19 13" stroke="#F4A940" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                <path d="M33 45 Q31 33 35 21" stroke="#5C3317" stroke-width="3" stroke-linecap="round"/>
                <path d="M35 21 Q44 15 48 11" stroke="#4A7C59" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M35 21 Q27 13 25 8"  stroke="#6BAF78" stroke-width="2"   stroke-linecap="round"/>
                <path d="M35 21 Q42 20 46 18" stroke="#4A7C59" stroke-width="2"   stroke-linecap="round"/>
                <ellipse cx="30" cy="46" rx="16" ry="4" fill="#6BAF78" opacity="0.45"/>
                <circle cx="21" cy="44" r="5.2" fill="#3B2314"/>
                <circle cx="30" cy="44" r="5.2" fill="#5C3317"/>
                <circle cx="21" cy="44" r="2.2" fill="#8B5E3C" opacity="0.55"/>
                <circle cx="30" cy="44" r="2.2" fill="#8B5E3C" opacity="0.55"/>
            </svg>
            <div>
                <span class="block font-display font-black text-coco-brown text-2xl leading-none tracking-wide">COCOIR</span>
                <span class="font-body text-[10px] text-coco-green uppercase leading-none tracking-[0.22em]">Coconut Coir Co.</span>
            </div>
        </a>

        <!-- ── Desktop Nav ── -->
        <nav class="hidden lg:flex items-center gap-8">
            <a href="<?= $isLanding ? '#home'    : site_url('/') ?>"
               class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Home</a>
            <a href="<?= site_url('about') ?>"
                class="font-semibold hover:text-coco-orange text-xs uppercase tracking-widest transition-colors <?= uri_string() === 'about' ? 'text-coco-orange border-b-2 border-coco-orange pb-0.5' : 'text-coco-dark' ?>">About</a>
            <a href="<?= site_url('products') ?>"
               class="font-semibold hover:text-coco-orange text-xs uppercase tracking-widest transition-colors <?= uri_string() === 'products' ? 'text-coco-orange border-b-2 border-coco-orange pb-0.5' : 'text-coco-dark' ?>">Products</a>
            <a href="<?= $isLanding ? '#why-us'  : site_url('/') . '#why-us' ?>"
               class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Why Us</a>
            <a href="<?= $isLanding ? '#contact' : site_url('/') . '#contact' ?>"
               class="font-semibold text-coco-dark hover:text-coco-orange text-xs uppercase tracking-widest transition-colors">Contact</a>
        </nav>

        <!-- ── Desktop Right: Cart + Auth ── -->
        <div class="hidden lg:flex items-center gap-3">

            <!-- Cart icon with badge -->
            <a href="<?= site_url('cart') ?>" class="relative p-2 text-coco-dark hover:text-coco-orange transition-colors" title="My Cart">
                <i class="text-xl fas fa-shopping-bag"></i>
                <span id="global-cart-count"
                      class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 bg-coco-orange rounded-full text-white text-[10px] font-bold transition-opacity <?= $cartCount > 0 ? '' : 'opacity-0' ?>">
                    <?= $cartCount ?>
                </span>
            </a>

            <?php if ($sessionUser): ?>
            <!-- ════ LOGGED IN ════ -->
            <div class="user-menu relative">

                <!-- Trigger button -->
                <button class="flex items-center gap-2 pl-2 pr-4 py-1.5 rounded-full border-2 border-coco-sand hover:border-coco-orange transition-all text-sm font-semibold text-coco-dark bg-white/60 backdrop-blur-sm">
                    <!-- Avatar circle with initial -->
                    <span class="flex items-center justify-center w-8 h-8 bg-coco-orange rounded-full text-white font-black text-sm flex-shrink-0">
                        <?= strtoupper(substr($sessionUser['first_name'] ?? $sessionUser['username'], 0, 1)) ?>
                    </span>
                    <span class="max-w-[100px] truncate">
                        Hi, <?= esc($sessionUser['first_name'] ?? $sessionUser['username']) ?>
                    </span>
                    <i class="fas fa-chevron-down text-[10px] text-coco-tan ml-0.5"></i>
                </button>

                <!-- Dropdown -->
                <div class="user-dropdown absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-coco-sand/60 overflow-hidden">

                    <!-- User info strip -->
                    <div class="px-4 py-3 bg-gradient-to-r from-coco-cream to-coco-sand/40 border-b border-coco-sand/50">
                        <div class="font-black text-coco-brown text-sm truncate">
                            <?= esc(trim(($sessionUser['first_name'] ?? '') . ' ' . ($sessionUser['last_name'] ?? ''))) ?: esc($sessionUser['username']) ?>
                        </div>
                        <div class="text-coco-mid text-xs truncate mt-0.5"><?= esc($sessionUser['email'] ?? '') ?></div>
                    </div>

                    <!-- Nav items -->
                    <a href="<?= site_url('profile') ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors border-b border-coco-sand/30 group">
                        <span class="w-7 h-7 bg-coco-sand/50 group-hover:bg-coco-orange/10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-user text-[11px] text-coco-tan group-hover:text-coco-orange transition-colors"></i>
                        </span>
                        My Profile
                    </a>

                    <a href="<?= site_url('orders') ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors border-b border-coco-sand/30 group">
                        <span class="w-7 h-7 bg-coco-sand/50 group-hover:bg-coco-orange/10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-box text-[11px] text-coco-tan group-hover:text-coco-orange transition-colors"></i>
                        </span>
                        My Orders
                    </a>

                    <a href="<?= site_url('cart') ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors border-b border-coco-sand/30 group">
                        <span class="w-7 h-7 bg-coco-sand/50 group-hover:bg-coco-orange/10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-shopping-bag text-[11px] text-coco-tan group-hover:text-coco-orange transition-colors"></i>
                        </span>
                        My Cart
                        <?php if ($cartCount > 0): ?>
                        <span class="ml-auto bg-coco-orange text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="<?= site_url('checkout') ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors border-b border-coco-sand/30 group">
                        <span class="w-7 h-7 bg-coco-sand/50 group-hover:bg-coco-orange/10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-credit-card text-[11px] text-coco-tan group-hover:text-coco-orange transition-colors"></i>
                        </span>
                        Checkout
                    </a>

                    <?php if (($sessionUser['type'] ?? '') === 'admin'): ?>
                    <a href="<?= site_url('admin/dashboard') ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors border-b border-coco-sand/30 group">
                        <span class="w-7 h-7 bg-coco-sand/50 group-hover:bg-coco-orange/10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-tachometer-alt text-[11px] text-coco-tan group-hover:text-coco-orange transition-colors"></i>
                        </span>
                        Admin Panel
                    </a>
                    <?php endif; ?>

                    <!-- Logout -->
                    <a href="<?= site_url('logout') ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-colors font-semibold group">
                        <span class="w-7 h-7 bg-red-50 group-hover:bg-red-100 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-sign-out-alt text-[11px] text-red-400"></i>
                        </span>
                        Log Out
                    </a>
                </div>
            </div>

            <!-- Shop Now pill -->
            <a href="<?= site_url('products') ?>"
               class="bg-coco-orange text-white px-5 py-2.5 rounded-full font-bold text-sm hover:bg-coco-dark transition-all duration-300 shadow-md hover:scale-105 transform">
                Shop Now
            </a>

            <?php else: ?>
            <!-- ════ GUEST ════ -->
            <a href="<?= site_url('login') ?>"
               class="px-5 py-2 border-2 border-coco-tan hover:border-coco-orange rounded-full font-semibold text-coco-dark hover:text-coco-orange text-sm transition-all">
                Sign In
            </a>
            <a href="<?= site_url('products') ?>"
               class="bg-coco-orange text-white px-5 py-2.5 rounded-full font-bold text-sm hover:bg-coco-dark transition-all duration-300 shadow-md hover:scale-105 transform">
                Shop Now
            </a>
            <?php endif; ?>
        </div>

        <!-- ── Hamburger ── -->
        <button id="hamburger" class="lg:hidden flex flex-col gap-1.5 p-2" aria-label="Open menu">
            <span class="block bg-coco-brown w-6 h-0.5 transition-all duration-300" id="ham-1"></span>
            <span class="block bg-coco-brown w-6 h-0.5 transition-all duration-300" id="ham-2"></span>
            <span class="block bg-coco-brown w-4 h-0.5 transition-all duration-300" id="ham-3"></span>
        </button>
    </div>

    <!-- ── Mobile Menu ── -->
    <div id="mobile-menu" class="lg:hidden bg-coco-cream border-t border-coco-sand px-4">
        <nav class="flex flex-col gap-1 py-4">
            <!-- Nav links -->
            <a href="<?= $isLanding ? '#home'    : site_url('/') ?>"             class="py-2.5 border-b border-coco-sand/50 font-semibold text-coco-dark text-xs uppercase tracking-widest hover:text-coco-orange transition-colors">Home</a>
            <a href="<?= site_url('about') ?>" class="py-2.5 border-b border-coco-sand/50 font-semibold text-coco-dark text-xs uppercase tracking-widest hover:text-coco-orange transition-colors">About</a>
            <a href="<?= site_url('products') ?>"                                 class="py-2.5 border-b border-coco-sand/50 font-semibold text-coco-dark text-xs uppercase tracking-widest hover:text-coco-orange transition-colors">Products</a>
            <a href="<?= $isLanding ? '#why-us'  : site_url('/') . '#why-us' ?>"  class="py-2.5 border-b border-coco-sand/50 font-semibold text-coco-dark text-xs uppercase tracking-widest hover:text-coco-orange transition-colors">Why Us</a>
            <a href="<?= $isLanding ? '#contact' : site_url('/') . '#contact' ?>" class="py-2.5 font-semibold text-coco-dark text-xs uppercase tracking-widest hover:text-coco-orange transition-colors">Contact</a>

            <?php if ($sessionUser): ?>
            <!-- Logged-in mobile -->
            <div class="mt-4 pt-4 border-t border-coco-sand/60 space-y-1">
                <!-- User info -->
                <div class="flex items-center gap-3 mb-3 px-1 pb-3 border-b border-coco-sand/40">
                    <span class="flex items-center justify-center w-10 h-10 bg-coco-orange rounded-full text-white font-black text-base flex-shrink-0">
                        <?= strtoupper(substr($sessionUser['first_name'] ?? $sessionUser['username'], 0, 1)) ?>
                    </span>
                    <div class="min-w-0">
                        <div class="font-black text-coco-brown text-sm truncate">
                            <?= esc(trim(($sessionUser['first_name'] ?? '') . ' ' . ($sessionUser['last_name'] ?? ''))) ?: esc($sessionUser['username']) ?>
                        </div>
                        <div class="text-coco-mid text-xs truncate"><?= esc($sessionUser['email'] ?? '') ?></div>
                    </div>
                </div>

                <a href="<?= site_url('profile') ?>"   class="flex items-center gap-3 py-2.5 border-b border-coco-sand/30 text-sm text-coco-dark hover:text-coco-orange transition-colors">
                    <i class="fas fa-user w-4 text-coco-tan text-xs"></i> My Profile
                </a>
                <a href="<?= site_url('orders') ?>"    class="flex items-center gap-3 py-2.5 border-b border-coco-sand/30 text-sm text-coco-dark hover:text-coco-orange transition-colors">
                    <i class="fas fa-box w-4 text-coco-tan text-xs"></i> My Orders
                </a>
                <a href="<?= site_url('cart') ?>"      class="flex items-center gap-3 py-2.5 border-b border-coco-sand/30 text-sm text-coco-dark hover:text-coco-orange transition-colors">
                    <i class="fas fa-shopping-bag w-4 text-coco-tan text-xs"></i> My Cart
                    <?php if ($cartCount > 0): ?>
                    <span class="ml-auto bg-coco-orange text-white text-[10px] font-black px-2 py-0.5 rounded-full"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= site_url('checkout') ?>"  class="flex items-center gap-3 py-2.5 border-b border-coco-sand/30 text-sm text-coco-dark hover:text-coco-orange transition-colors">
                    <i class="fas fa-credit-card w-4 text-coco-tan text-xs"></i> Checkout
                </a>
                <?php if (($sessionUser['type'] ?? '') === 'admin'): ?>
                <a href="<?= site_url('admin/dashboard') ?>" class="flex items-center gap-3 py-2.5 border-b border-coco-sand/30 text-sm text-coco-dark hover:text-coco-orange transition-colors">
                    <i class="fas fa-tachometer-alt w-4 text-coco-tan text-xs"></i> Admin Panel
                </a>
                <?php endif; ?>

                <a href="<?= site_url('logout') ?>"
                   class="flex items-center justify-center gap-2 mt-3 py-3 border-2 border-red-200 rounded-full text-red-500 text-sm font-bold hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt text-xs"></i> Log Out
                </a>
            </div>

            <?php else: ?>
            <!-- Guest mobile -->
            <div class="flex flex-col gap-2 mt-4 pt-4 border-t border-coco-sand/50">
                <a href="<?= site_url('login') ?>"
                   class="text-center py-3 border-2 border-coco-tan rounded-full font-bold text-coco-dark text-sm hover:border-coco-orange hover:text-coco-orange transition-all">Sign In</a>
                <a href="<?= site_url('signup') ?>"
                   class="text-center py-3 border-2 border-coco-sand rounded-full font-bold text-coco-mid text-sm hover:border-coco-orange hover:text-coco-orange transition-all">Create Account</a>
                <a href="<?= site_url('products') ?>"
                   class="text-center bg-coco-orange py-3 rounded-full font-bold text-white text-sm hover:bg-coco-dark transition-colors">Shop Now</a>
            </div>
            <?php endif; ?>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ── Hamburger toggle with X animation ──
            const hamburger  = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobile-menu');
            const ham1 = document.getElementById('ham-1');
            const ham3 = document.getElementById('ham-3');

            hamburger.addEventListener('click', () => {
                const isOpen = mobileMenu.classList.toggle('open');
                ham1.style.transform = isOpen ? 'translateY(8px) rotate(45deg)'   : '';
                ham3.style.transform = isOpen ? 'translateY(-8px) rotate(-45deg)' : '';
                ham3.style.width     = isOpen ? '24px' : '';
            });

            mobileMenu.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', () => {
                    mobileMenu.classList.remove('open');
                    ham1.style.transform = '';
                    ham3.style.transform = '';
                    ham3.style.width     = '';
                });
            });

            // ── Scroll blur effect ──
            const header = document.getElementById('main-header');
            window.addEventListener('scroll', () => {
                header.classList.toggle('nav-scrolled', window.scrollY > 50);
            });

            // ── Cart badge live update (fired by addToCart JS) ──
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