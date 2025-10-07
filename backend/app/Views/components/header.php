<nav class="fixed w-full top-0 z-50 bg-light-cream/90 backdrop-blur-md border-b border-cream-beige/50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="<?= site_url('/') ?>" class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                        <img src="/images/salogo.png" alt="Silver Atelier Logo" class="w-full h-full object-cover">
                    </div>
                    <span class="font-serif text-2xl font-bold text-warm-brown">Silver Atelier</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="<?= site_url('/') ?>#home" class="nav-link text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">Home</a>
                    <a href="<?= site_url('/') ?>#collections" class="nav-link text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">Collections</a>
                    <a href="<?= site_url('catalog') ?>" class="nav-link text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">Look Catalog</a>
                    <a href="<?= site_url('/') ?>#about" class="nav-link text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">About</a>
                    <a href="<?= site_url('moodboard') ?>" class="nav-link text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">Mood Board</a>
                    <a href="<?= site_url('roadmap') ?>" class="nav-link text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">Roadmap</a>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="hidden md:block">
                <?php if (session()->has('user')): ?>
                    <a href="<?= site_url('logout') ?>" class="bg-warm-brown text-light-cream px-6 py-2 rounded-full hover:bg-sage-green transition-all duration-300 transform hover:scale-105 font-medium inline-block text-center">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="bg-warm-brown text-light-cream px-6 py-2 rounded-full hover:bg-sage-green transition-all duration-300 transform hover:scale-105 font-medium inline-block text-center">
                        Login
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="text-warm-brown hover:text-sage-green transition-colors duration-300">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="md:hidden hidden bg-light-cream border-t border-cream-beige/50">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?= site_url('/') ?>#home" class="block px-3 py-2 text-warm-brown hover:text-sage-green transition-colors duration-300">Home</a>
            <a href="<?= site_url('/') ?>#collections" class="block px-3 py-2 text-warm-brown hover:text-sage-green transition-colors duration-300">Collections</a>
            <a href="<?= site_url('catalog') ?>" class="block px-3 py-2 text-warm-brown hover:text-sage-green transition-colors duration-300">Look Catalog</a>
            <a href="<?= site_url('/') ?>#about" class="block px-3 py-2 text-warm-brown hover:text-sage-green transition-colors duration-300">About</a>
            <a href="<?= site_url('moodboard') ?>" class="block px-3 py-2 text-warm-brown hover:text-sage-green transition-colors duration-300">Mood Board</a>
            <a href="<?= site_url('roadmap') ?>" class="block px-3 py-2 text-warm-brown hover:text-sage-green transition-colors duration-300">Roadmap</a>
            
            <?php if (session()->has('user')): ?>
                <a href="<?= site_url('logout') ?>" class="block w-full mt-4 bg-warm-brown text-light-cream px-6 py-2 rounded-full hover:bg-sage-green transition-all duration-300 text-center font-medium">
                    Logout
                </a>
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="block w-full mt-4 bg-warm-brown text-light-cream px-6 py-2 rounded-full hover:bg-sage-green transition-all duration-300 text-center font-medium">
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>