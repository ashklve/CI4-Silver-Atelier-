<?php
?>
<!DOCTYPE html>
<html lang="en">
<?= view('components/head') ?>

<body class="font-sans text-warm-brown bg-light-cream">
    <?= view('components/header') ?>

    <!-- Hero Section -->
    <section id="home" class="pt-16 min-h-screen bg-gradient-to-br from-light-cream via-cream-beige to-light-cream flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="space-y-8 animate-fade-in-up">
                    <div class="space-y-4">
                        <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl font-black text-warm-brown leading-tight">
                            Welcome to
                            <span class="block text-sage-green italic">Silver Atelier</span>
                        </h1>
                        <p class="text-xl sm:text-2xl text-sage-green font-light max-w-2xl leading-relaxed">
                            Where we meet your standard when it comes to latest trends. Discover timeless elegance with contemporary flair.
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                    <a href="<?= site_url('/') ?>#collections" class="bg-warm-brown text-light-cream px-8 py-4 rounded-full text-lg font-semibold hover:bg-sage-green transform hover:scale-105 transition-all duration-300 shadow-lg inline-block">
                        Collections
                    </a>
                    <a href="<?= site_url('catalog') ?>" class="bg-sage-green text-light-cream px-8 py-4 rounded-full text-lg font-semibold hover:bg-warm-brown transform hover:scale-105 transition-all duration-300 shadow-lg inline-block">
                        Explore Catalog
                    </a>
                </div>
                    
                    <!-- Stats -->
                    <div class="flex space-x-8 pt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-warm-brown">500+</div>
                            <div class="text-sage-green">Happy Clients</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-warm-brown">1000+</div>
                            <div class="text-sage-green">Premium Items</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-warm-brown">4.9★</div>
                            <div class="text-sage-green">Reviews</div>
                        </div>
                    </div>
                </div>
                
                <!-- Image/Visual Element -->
                <div class="relative">
                    <div class="aspect-square bg-gradient-to-br from-sage-green to-warm-brown rounded-3xl shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-500 overflow-hidden">
                        <img src="/images/product1.jpg" alt="Premium Fashion - Silver Atelier" class="w-full h-full object-cover opacity-80">
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-light-cream rounded-full shadow-lg flex items-center justify-center animate-bounce">
                        <i class="fas fa-star text-warm-brown text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-warm-brown rounded-full shadow-lg flex items-center justify-center animate-pulse">
                        <i class="fas fa-heart text-light-cream text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Features Section -->
<section class="py-20 bg-cream-beige">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl sm:text-5xl font-bold text-warm-brown mb-6">Why Choose Silver Atelier?</h2>
            <p class="text-xl text-sage-green max-w-3xl mx-auto">Experience fashion excellence with our commitment to quality, style, and customer satisfaction.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?= view('components/cards', [
                'icon' => 'gem',
                'iconBg' => 'warm-brown',
                'title' => 'Premium Quality',
                'description' => 'Handpicked materials and expert craftsmanship ensure every piece meets the highest standards of luxury fashion.'
            ]) ?>
            
            <?= view('components/cards', [
                'icon' => 'truck',
                'iconBg' => 'sage-green',
                'title' => 'Fast Delivery',
                'description' => 'Swift and secure shipping across Metro Manila and nationwide. Your style arrives at your doorstep on time.'
            ]) ?>
            
            <?= view('components/cards', [
                'icon' => 'shield-alt',
                'iconBg' => 'warm-brown',
                'title' => 'Secure Shopping',
                'description' => 'Shop with confidence knowing your transactions are protected with industry-leading security measures.'
            ]) ?>
            
            <?= view('components/cards', [
                'icon' => 'heart',
                'iconBg' => 'sage-green',
                'title' => 'Curated Collections',
                'description' => 'Carefully selected pieces that blend timeless elegance with contemporary trends for the modern fashionista.'
            ]) ?>
            
            <?= view('components/cards', [
                'icon' => 'headset',
                'iconBg' => 'warm-brown',
                'title' => '24/7 Support',
                'description' => 'Our dedicated team is always ready to assist you with styling advice, orders, and any questions you may have.'
            ]) ?>
            
            <?= view('components/cards', [
                'icon' => 'undo',
                'iconBg' => 'sage-green',
                'title' => 'Easy Returns',
                'description' => 'Not satisfied? No worries. Hassle-free returns within 30 days to ensure you love every purchase.'
            ]) ?>
        </div>
        </div>
    </section>

    <!-- Collections Preview -->
    <section id="collections" class="py-20 bg-light-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-serif text-4xl sm:text-5xl font-bold text-warm-brown mb-6">Featured Collections</h2>
                <p class="text-xl text-sage-green max-w-3xl mx-auto">Discover our handpicked selection of premium fashion pieces that define modern elegance.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?= view('components/collections', [
                    'image' => 'images/womens-wear.jpg',
                    'title' => "Women's Wear",
                    'description' => 'Elegant and sophisticated styles',
                    'price' => 'From ₱2,999',
                    'badge' => 'New'
                ]) ?>
                
                <?= view('components/collections', [
                    'image' => 'images/mens-collection.jpg',
                    'title' => "Men's Collection",
                    'description' => 'Contemporary and classic designs',
                    'price' => 'From ₱3,499',
                    'badge' => 'Trending'
                ]) ?>
                
                <?= view('components/collections', [
                    'image' => 'images/accessories.jpg',
                    'title' => 'Accessories',
                    'description' => 'Perfect finishing touches',
                    'price' => 'From ₱899'
                ]) ?>
                
                <?= view('components/collections', [
                    'image' => 'images/premium-bags.jpg',
                    'title' => 'Premium Bags',
                    'description' => 'Luxury meets functionality',
                    'price' => 'From ₱4,999',
                    'badge' => 'Best Seller'
                ]) ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="<?= site_url('catalog') ?>" class="bg-warm-brown text-light-cream px-8 py-4 rounded-full text-lg font-semibold hover:bg-sage-green transform hover:scale-105 transition-all duration-300 shadow-lg inline-block">
                    View All Collections
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <?= view('components/cta') ?>

    <!-- Footer -->
    <?= view('components/footer') ?>
    
    <script src="<?= base_url('js/main.js') ?>"></script>
</body>
</html>