<footer class="bg-warm-brown text-light-cream py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand -->
            <div class="lg:col-span-2">
                <div class="flex items-center space-x-2 mb-6">
                    <div class="w-8 h-8 bg-light-cream rounded-full flex items-center justify-center">
                        <i class="fas fa-gem text-warm-brown text-sm"></i>
                    </div>
                    <span class="font-serif text-2xl font-bold">Silver Atelier</span>
                </div>
                <p class="text-cream-beige leading-relaxed mb-6 max-w-md">
                    Your premier destination for luxury fashion and contemporary style. Where elegance meets innovation in every piece.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-12 h-12 bg-sage-green rounded-full flex items-center justify-center hover:bg-light-cream hover:text-warm-brown transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-sage-green rounded-full flex items-center justify-center hover:bg-light-cream hover:text-warm-brown transition-all duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-sage-green rounded-full flex items-center justify-center hover:bg-light-cream hover:text-warm-brown transition-all duration-300">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-xl font-serif font-bold mb-6">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="<?= site_url('/') ?>#home" class="text-cream-beige hover:text-light-cream transition-colors duration-300">Home</a></li>
                    <li><a href="<?= site_url('/') ?>#collections" class="text-cream-beige hover:text-light-cream transition-colors duration-300">Collections</a></li>
                    <li><a href="<?= site_url('catalog') ?>" class="text-cream-beige hover:text-light-cream transition-colors duration-300">Look Catalog</a></li>
                    <li><a href="<?= site_url('/') ?>#about" class="text-cream-beige hover:text-light-cream transition-colors duration-300">About Us</a></li>
                    <li><a href="<?= site_url('moodboard') ?>" class="text-cream-beige hover:text-light-cream transition-colors duration-300">Mood Board</a></li>
                    <li><a href="<?= site_url('roadmap') ?>" class="text-cream-beige hover:text-light-cream transition-colors duration-300">Roadmap</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-xl font-serif font-bold mb-6">Contact</h4>
                <div class="space-y-3 text-cream-beige">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-sage-green"></i>
                        <span>Manila, Philippines</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-sage-green"></i>
                        <span>+63 912 345 6789</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-sage-green"></i>
                        <span>silveratelier@gmail.com</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-sage-green pt-8 text-center text-cream-beige">
            <p>&copy; 2025 Silver Atelier. All rights reserved. Crafted with love for fashion enthusiasts.</p>
        </div>
    </div>
</footer>