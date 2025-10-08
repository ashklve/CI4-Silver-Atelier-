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
                    
                    <?= view('components/buttons') ?>
                    
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
            
            <?= view('components/cards') ?>
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
                <!-- Collection 1 -->
                <div class="group bg-cream-beige rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="aspect-square bg-gradient-to-br from-warm-brown to-sage-green flex items-center justify-center relative overflow-hidden">
                        <i class="fas fa-female text-6xl text-light-cream group-hover:scale-110 transition-transform duration-300"></i>
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-serif font-bold text-warm-brown mb-2">Women's Wear</h4>
                        <p class="text-sage-green mb-4">Elegant and sophisticated styles</p>
                        <div class="text-2xl font-bold text-warm-brown">From ₱2,999</div>
                    </div>
                </div>
                
                <!-- Collection 2 -->
                <div class="group bg-cream-beige rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="aspect-square bg-gradient-to-br from-sage-green to-warm-brown flex items-center justify-center relative overflow-hidden">
                        <i class="fas fa-male text-6xl text-light-cream group-hover:scale-110 transition-transform duration-300"></i>
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-serif font-bold text-warm-brown mb-2">Men's Collection</h4>
                        <p class="text-sage-green mb-4">Contemporary and classic designs</p>
                        <div class="text-2xl font-bold text-warm-brown">From ₱3,499</div>
                    </div>
                </div>
                
                <!-- Collection 3 -->
                <div class="group bg-cream-beige rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="aspect-square bg-gradient-to-br from-warm-brown via-sage-green to-warm-brown flex items-center justify-center relative overflow-hidden">
                        <i class="fas fa-ring text-6xl text-light-cream group-hover:scale-110 transition-transform duration-300"></i>
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-serif font-bold text-warm-brown mb-2">Accessories</h4>
                        <p class="text-sage-green mb-4">Perfect finishing touches</p>
                        <div class="text-2xl font-bold text-warm-brown">From ₱899</div>
                    </div>
                </div>  
                
                <!-- Collection 4 -->
                <div class="group bg-cream-beige rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="aspect-square bg-gradient-to-br from-sage-green via-warm-brown to-sage-green flex items-center justify-center relative overflow-hidden">
                        <i class="fas fa-shopping-bag text-6xl text-light-cream group-hover:scale-110 transition-transform duration-300"></i>
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-serif font-bold text-warm-brown mb-2">Premium Bags</h4>
                        <p class="text-sage-green mb-4">Luxury meets functionality</p>
                        <div class="text-2xl font-bold text-warm-brown">From ₱4,999</div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <button class="bg-warm-brown text-light-cream px-8 py-4 rounded-full text-lg font-semibold hover:bg-sage-green transform hover:scale-105 transition-all duration-300 shadow-lg">
                    View All Collections
                </button>
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