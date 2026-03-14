<nav class="bg-warm-brown text-light-cream shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-light-cream rounded-full flex items-center justify-center">
                    <i class="fas fa-gem text-warm-brown"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Silver Atelier</h1>
                    <p class="text-xs text-sage-green">Admin Panel</p>
                </div>
            </div>

            <!-- nav links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= site_url('admin/dashboard') ?>" class="hover:text-sage-green transition flex items-center">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="<?= site_url('admin/products') ?>" class="hover:text-sage-green transition flex items-center">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="<?= site_url('admin/orders') ?>" class="hover:text-sage-green transition flex items-center">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="<?= site_url('admin/accounts') ?>" class="hover:text-sage-green transition flex items-center">
                    <i class="fas fa-users mr-2"></i> Accounts
                </a>
            </div>

            <!-- user menu -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="relative hover:text-sage-green transition">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                </button>

                <!-- user dropdown -->
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold"><?= esc(session('user')['username']) ?></p>
                        <p class="text-xs text-sage-green">Administrator</p>
                    </div>
                    <div class="w-10 h-10 bg-sage-green rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <!-- logout -->
                <a href="<?= site_url('logout') ?>" class="bg-sage-green px-4 py-2 rounded-lg hover:bg-light-cream hover:text-warm-brown transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>

                <!-- mobile menu button -->
                <button class="md:hidden" id="admin-mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- mobile nav -->
    <div id="admin-mobile-menu" class="md:hidden hidden bg-warm-brown border-t border-sage-green">
        <div class="px-4 py-4 space-y-3">
            <a href="<?= site_url('admin/dashboard') ?>" class="block py-2 hover:text-sage-green transition">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="<?= site_url('admin/products') ?>" class="block py-2 hover:text-sage-green transition">
                <i class="fas fa-box mr-2"></i> Products
            </a>
            <a href="<?= site_url('admin/orders') ?>" class="block py-2 hover:text-sage-green transition">
                <i class="fas fa-shopping-cart mr-2"></i> Orders
            </a>
            <a href="<?= site_url('admin/accounts') ?>" class="block py-2 hover:text-sage-green transition">
                <i class="fas fa-users mr-2"></i> Accounts
            </a>
        </div>
    </div>
</nav>

<script>
    // mobile menu toggle
    document.getElementById('admin-mobile-menu-btn')?.addEventListener('click', function() {
        document.getElementById('admin-mobile-menu').classList.toggle('hidden');
    });
</script>