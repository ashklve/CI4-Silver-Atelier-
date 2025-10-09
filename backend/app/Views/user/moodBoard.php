<?php
?>
<!DOCTYPE html>
<html lang="en">
<?= view('components/head', ['title' => 'Mood Board - Silver Atelier']) ?>

<body class="font-sans text-warm-brown bg-light-cream">
    <?= view('components/header') ?>

    <div class="max-w-7xl mx-auto p-8">
        <!-- Header -->
        <div class="mb-12 text-center pt-16">
            <h1 class="font-serif text-5xl font-black text-warm-brown mb-3">Mood Board</h1>
            <p class="text-sage-green text-lg">Visual identity guide for Silver Atelier - Premium Fashion & Accessories</p>
        </div>

        <!-- Color System -->
        <section class="mb-16">
            <h2 class="font-serif text-3xl font-bold text-warm-brown mb-6">Color System</h2>
            <p class="text-sage-green mb-8">Our signature palette with three vibrance levels — earthy, sophisticated, and timeless.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Warm Brown -->
                <div class="group">
                    <div class="bg-warm-brown h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4"></div>
                    <div class="bg-warm-brown opacity-70 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4"></div>
                    <div class="bg-warm-brown opacity-40 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4"></div>
                    <div class="text-center">
                        <h3 class="font-semibold text-warm-brown mb-1">Warm Brown</h3>
                        <p class="text-sm text-sage-green">Main Accent</p>
                        <p class="text-xs text-sage-green font-mono mt-2">#57564F</p>
                    </div>
                </div>

                <!-- Sage Green -->
                <div class="group">
                    <div class="bg-sage-green h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4"></div>
                    <div class="bg-sage-green opacity-70 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4"></div>
                    <div class="bg-sage-green opacity-40 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4"></div>
                    <div class="text-center">
                        <h3 class="font-semibold text-warm-brown mb-1">Sage Green</h3>
                        <p class="text-sm text-sage-green">Secondary Tone</p>
                        <p class="text-xs text-sage-green font-mono mt-2">#7A7A73</p>
                    </div>
                </div>

                <!-- Cream Beige -->
                <div class="group">
                    <div class="bg-cream-beige h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4 border border-sage-green/20"></div>
                    <div class="bg-cream-beige opacity-70 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4 border border-sage-green/20"></div>
                    <div class="bg-cream-beige opacity-40 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4 border border-sage-green/20"></div>
                    <div class="text-center">
                        <h3 class="font-semibold text-warm-brown mb-1">Cream Beige</h3>
                        <p class="text-sm text-sage-green">Surface Background</p>
                        <p class="text-xs text-sage-green font-mono mt-2">#DDDAD0</p>
                    </div>
                </div>

                <!-- Light Cream -->
                <div class="group">
                    <div class="bg-light-cream h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4 border border-sage-green/20"></div>
                    <div class="bg-light-cream opacity-70 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4 border border-sage-green/20"></div>
                    <div class="bg-light-cream opacity-40 h-32 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 mb-4 border border-sage-green/20"></div>
                    <div class="text-center">
                        <h3 class="font-semibold text-warm-brown mb-1">Light Cream</h3>
                        <p class="text-sm text-sage-green">Primary Background</p>
                        <p class="text-xs text-sage-green font-mono mt-2">#F8F3CE</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Typography -->
        <section class="mb-16">
            <h2 class="font-serif text-3xl font-bold text-warm-brown mb-6">Typography</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Heading Font -->
                <div class="bg-cream-beige rounded-3xl p-8 shadow-lg">
                    <p class="text-sm text-sage-green mb-2 uppercase tracking-wider">Heading Font</p>
                    <h3 class="font-serif text-5xl font-black text-warm-brown mb-4">Playfair Display</h3>
                    <p class="text-sage-green text-sm mb-6">Elegant serif font for headlines and emphasis</p>
                    <div class="space-y-2">
                        <p class="font-serif text-3xl font-black text-warm-brown">The Quick Brown Fox</p>
                        <p class="font-serif text-2xl font-bold text-warm-brown">The Quick Brown Fox</p>
                        <p class="font-serif text-xl font-semibold text-warm-brown">The Quick Brown Fox</p>
                    </div>
                </div>

                <!-- Body Font -->
                <div class="bg-cream-beige rounded-3xl p-8 shadow-lg">
                    <p class="text-sm text-sage-green mb-2 uppercase tracking-wider">Body Font</p>
                    <h3 class="font-sans text-4xl font-bold text-warm-brown mb-4">Inter</h3>
                    <p class="text-sage-green text-sm mb-6">Clean sans-serif for readable body text and UI elements</p>
                    <div class="space-y-2">
                        <p class="font-sans text-lg font-semibold text-warm-brown">The Quick Brown Fox Jumps</p>
                        <p class="font-sans text-base font-medium text-warm-brown">The Quick Brown Fox Jumps</p>
                        <p class="font-sans text-sm text-sage-green">The Quick Brown Fox Jumps Over The Lazy Dog</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Buttons -->
        <section class="mb-16">
            <h2 class="font-serif text-3xl font-bold text-warm-brown mb-6">Button System</h2>
            
            <div class="bg-cream-beige rounded-3xl p-8 shadow-lg">
                <!-- Light Mode -->
                <div class="mb-12">
                    <h3 class="text-lg font-semibold text-warm-brown mb-6">Light Mode</h3>
                    <div class="flex flex-wrap gap-4">
                        <?= view('components/buttons') ?>
                    </div>
                </div>

                <!-- Dark Mode -->
                <div class="bg-warm-brown p-6 rounded-2xl">
                    <h3 class="text-lg font-semibold text-light-cream mb-6">Dark Mode</h3>
                    <div class="flex flex-wrap gap-4">
                        <button class="px-6 py-3 bg-light-cream text-warm-brown rounded-full font-semibold hover:bg-cream-beige transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Primary
                        </button>
                        <button class="px-6 py-3 bg-sage-green text-light-cream rounded-full font-semibold hover:bg-cream-beige hover:text-warm-brown transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Secondary
                        </button>
                        <button class="px-6 py-3 border-2 border-light-cream text-light-cream rounded-full font-semibold hover:bg-light-cream hover:text-warm-brown transition-all duration-300">
                            Border
                        </button>
                        <button class="px-6 py-3 bg-gray-600 text-gray-400 rounded-full font-semibold cursor-not-allowed opacity-50" disabled>
                            Disabled
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Card Samples -->
        <section class="mb-16">
            <h2 class="font-serif text-3xl font-bold text-warm-brown mb-6">Card Components</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Product Card -->
                <div class="bg-cream-beige rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="h-48 bg-gradient-to-br from-warm-brown to-sage-green flex items-center justify-center">
                        <i class="fas fa-tshirt text-6xl text-light-cream"></i>
                    </div>
                    <div class="p-6">
                        <h4 class="font-serif text-xl font-bold text-warm-brown mb-2">Product Card</h4>
                        <p class="text-sage-green mb-4">Clean design with hover effects and smooth transitions</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-warm-brown">₱2,999</span>
                            <button class="text-sage-green hover:text-warm-brown transition-colors duration-300">
                                <i class="fas fa-arrow-right text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <?= view('components/cards', [
                    'icon' => 'gem',
                    'iconBg' => 'warm-brown',
                    'title' => 'Feature Card',
                    'description' => 'Highlighting key benefits with elegant iconography and balanced spacing'
                ]) ?>

                <!-- Testimonial Card -->
                <div class="bg-cream-beige rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-warm-brown rounded-full flex items-center justify-center mr-4">
                            <span class="text-light-cream font-bold">SA</span>
                        </div>
                        <div>
                            <h5 class="font-semibold text-warm-brown">Customer Name</h5>
                            <div class="flex text-sage-green">
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                                <i class="fas fa-star text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-sage-green italic leading-relaxed">
                        "Premium quality and exceptional service. Silver Atelier never disappoints!"
                    </p>
                </div>
            </div>
        </section>

        <!-- Logos -->
        <section class="mb-16">
            <h2 class="font-serif text-3xl font-bold text-warm-brown mb-6">Logo Variations</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Circle Logo -->
                <div class="bg-cream-beige rounded-3xl p-12 shadow-lg">
                    <div class="flex flex-col items-center">
                        <div class="w-48 h-48 rounded-full overflow-hidden shadow-2xl">
                            <img src="<?= base_url('images/salogo.png'); ?>" 
                                alt="Silver Atelier Circle Logo"
                                class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-serif text-xl font-bold text-warm-brown mt-6">Circle Logo</h4>
                        <p class="text-sage-green text-center mt-2">For social media and app icons</p>
                    </div>
                </div>

                <!-- Square Logo -->
                <div class="bg-cream-beige rounded-3xl p-12 shadow-lg">
                    <div class="flex flex-col items-center">
                        <div class="w-48 h-48 rounded-3xl overflow-hidden shadow-2xl">
                            <img src="<?= base_url('images/salogo.png'); ?>" 
                                alt="Silver Atelier Square Logo"
                                class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-serif text-xl font-bold text-warm-brown mt-6">Square Logo</h4>
                        <p class="text-sage-green text-center mt-2">For favicons and badges</p>
                    </div>
                </div>

            </div>
        </section>

        <!-- Design Principles -->
        <section class="mb-16">
            <h2 class="font-serif text-3xl font-bold text-warm-brown mb-6">Design Principles</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-cream-beige rounded-2xl p-6">
                    <div class="w-12 h-12 bg-warm-brown rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-shapes text-xl text-light-cream"></i>
                    </div>
                    <h4 class="font-semibold text-warm-brown mb-2">Modern Minimalism</h4>
                    <p class="text-sage-green text-sm">Clean lines, generous whitespace, and purposeful design elements</p>
                </div>

                <div class="bg-cream-beige rounded-2xl p-6">
                    <div class="w-12 h-12 bg-sage-green rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-paint-brush text-xl text-light-cream"></i>
                    </div>
                    <h4 class="font-semibold text-warm-brown mb-2">Timeless Elegance</h4>
                    <p class="text-sage-green text-sm">Sophisticated color palette and refined typography choices</p>
                </div>

                <div class="bg-cream-beige rounded-2xl p-6">
                    <div class="w-12 h-12 bg-warm-brown rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-xl text-light-cream"></i>
                    </div>
                    <h4 class="font-semibold text-warm-brown mb-2">Mobile First</h4>
                    <p class="text-sage-green text-sm">Responsive design that works beautifully on all devices</p>
                </div>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <?= view('components/footer') ?>
    
</body>
</html>