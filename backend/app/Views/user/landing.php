<?php
?>
<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<body class="bg-coco-cream font-body text-coco-brown">


    <?= $this->include('components/header') ?>


    <!-- ══════════════════════════ HERO ══════════════════════════ -->
    <section id="home" class="relative flex items-center bg-gradient-to-br from-coco-cream via-coco-sand to-coco-cream pt-20 min-h-screen overflow-hidden">

        <!-- Decorative blobs -->
        <div class="top-10 right-0 -z-0 absolute bg-coco-orange/10 w-[480px] h-[480px] pointer-events-none blob-1"></div>
        <div class="bottom-0 left-0 -z-0 absolute bg-coco-green/10 w-[320px] h-[320px] pointer-events-none blob-2"></div>
        <div class="hidden lg:block top-1/2 right-20 -z-0 absolute border-[3px] border-coco-tan/25 border-dashed rounded-full w-60 h-60 animate-spin-slow pointer-events-none"></div>

        <div class="z-10 relative mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full max-w-7xl">
            <div class="items-center gap-16 grid grid-cols-1 lg:grid-cols-2">

                <!-- Text Content -->
                <div class="space-y-8">
                    <div class="anim-hidden inline-flex items-center gap-2 bg-coco-green/10 px-4 py-1.5 border border-coco-leaf/30 rounded-full animate-fade-up">
                        <span class="bg-coco-leaf rounded-full w-2 h-2 animate-pulse"></span>
                        <span class="font-bold text-coco-green text-xs uppercase tracking-[0.15em]">100% Natural · Philippine-Made</span>
                    </div>

                    <h1 class="anim-hidden font-display font-black text-coco-brown text-5xl sm:text-6xl lg:text-7xl leading-[1.05] animate-fade-up delay-200">
                        From Husk<br>
                        <span class="text-coco-orange italic">to Craft.</span>
                    </h1>

                    <p class="anim-hidden max-w-lg font-light text-coco-mid text-lg leading-relaxed animate-fade-up delay-300">
                        COCOIR transforms discarded coconut husks into premium eco-friendly products — for construction, gardening, and everyday living. Rooted in the Philippines, growing worldwide.
                    </p>

                    <div class="anim-hidden flex flex-wrap gap-4 animate-fade-up delay-500">
                        <a href="<?= site_url('products') ?>" class="inline-flex items-center gap-2 bg-coco-orange hover:bg-coco-dark shadow-coco-orange/30 shadow-lg px-8 py-4 rounded-full font-bold text-white hover:scale-105 transition-all duration-300 transform">
                            <i class="text-sm fas fa-leaf"></i> Shop Products
                        </a>
                        <a href="#about" class="inline-flex items-center gap-2 hover:bg-coco-brown px-8 py-4 border-2 border-coco-brown rounded-full font-bold text-coco-brown hover:text-coco-cream transition-all duration-300">
                            Our Story <i class="fa-arrow-right text-sm fas"></i>
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="anim-hidden flex gap-8 pt-4 animate-fade-up delay-700">
                        <div>
                            <div class="font-display font-black text-coco-brown text-3xl">200+</div>
                            <div class="font-light text-coco-mid text-sm">Products</div>
                        </div>
                        <div class="self-stretch bg-coco-sand w-px"></div>
                        <div>
                            <div class="font-display font-black text-coco-brown text-3xl">1,500+</div>
                            <div class="font-light text-coco-mid text-sm">Customers</div>
                        </div>
                        <div class="self-stretch bg-coco-sand w-px"></div>
                        <div>
                            <div class="font-display font-black text-coco-brown text-3xl">4.8★</div>
                            <div class="font-light text-coco-mid text-sm">Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Visual -->
                <div class="relative flex justify-center items-center">
                    <!-- Main hero image: coco.png with transparent bg -->
                    <div class="relative flex justify-center items-center w-80 lg:w-[460px] h-80 lg:h-[460px] animate-float">
                        <img src="/images/coco.png" alt="Coconut Coir" class="drop-shadow-2xl w-full h-full object-contain">
                    </div>

                    <!-- Badge: Eco-Friendly -->
                    <div class="-top-4 -left-2 lg:-left-10 absolute bg-coco-green shadow-xl px-4 py-3 rounded-2xl text-white animate-sway">
                        <div class="opacity-80 font-bold text-[10px] uppercase tracking-widest">Eco-Friendly</div>
                        <div class="font-display font-black text-xl leading-tight">100%<br>Natural</div>
                    </div>

                    <!-- Badge: PH Made -->
                    <div class="-right-2 lg:-right-8 -bottom-4 absolute bg-coco-cream shadow-xl px-4 py-3 border-2 border-coco-sand rounded-2xl">
                        <div class="font-bold text-[10px] text-coco-mid tracking-widest">🇵🇭 Made in the</div>
                        <div class="font-display font-black text-coco-brown text-xl">Philippines</div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Wave divider -->
        <div class="right-0 bottom-0 left-0 absolute pointer-events-none">
            <svg viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-14 lg:h-20">
                <path d="M0 36 Q360 0 720 36 Q1080 72 1440 36 L1440 72 L0 72 Z" fill="#EDE0CC" />
            </svg>
        </div>
    </section>


    <!-- ══════════════════════════ ABOUT ══════════════════════════ -->
    <section id="about" class="bg-coco-sand py-24">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="items-center gap-16 grid grid-cols-1 lg:grid-cols-2">

                <!-- Story -->
                <div class="space-y-6">
                    <p class="font-bold text-coco-orange text-xs uppercase tracking-[0.3em]">Our Story</p>
                    <h2 class="font-display font-black text-coco-brown text-4xl sm:text-5xl leading-tight">
                        The Fiber That<br><em>Changes Everything</em>
                    </h2>
                    <p class="font-light text-coco-dark text-lg leading-relaxed">
                        Coconut coir — the tough fibrous material between the hard inner shell and outer husk of the coconut — has long been discarded as waste. COCOIR is changing that story.
                    </p>
                    <p class="text-coco-mid text-sm leading-relaxed">
                        In the Philippines, where coconuts are abundant and coir utilization is still in its infancy, we lead the charge: turning agricultural byproduct into durable mats, grow bags, erosion control nets, and construction liners. Every product supports local farmers, reduces waste, and brings world-class sustainable materials to your doorstep.
                    </p>
                    <div class="flex gap-6 pt-2">
                        <div class="flex items-start gap-3">
                            <div class="flex flex-shrink-0 justify-center items-center bg-coco-green/15 rounded-full w-10 h-10">
                                <i class="text-coco-green text-sm fas fa-seedling"></i>
                            </div>
                            <div>
                                <div class="font-bold text-coco-brown text-sm">Zero Waste</div>
                                <div class="text-coco-mid text-xs">Every husk has purpose</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex flex-shrink-0 justify-center items-center bg-coco-orange/15 rounded-full w-10 h-10">
                                <i class="text-coco-orange text-sm fas fa-hands-helping"></i>
                            </div>
                            <div>
                                <div class="font-bold text-coco-brown text-sm">Farmer First</div>
                                <div class="text-coco-mid text-xs">Fair trade, local sourcing</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats grid -->
                <div class="gap-5 grid grid-cols-2">
                    <div class="shadow-sm p-7 border border-coco-tan/20 rounded-3xl fiber-bg">
                        <div class="mb-1 font-display font-black text-coco-orange text-5xl">15+</div>
                        <div class="mb-1 font-bold text-coco-brown text-sm">Product Categories</div>
                        <div class="text-coco-mid text-xs leading-relaxed">Gardening, construction, craft, and more</div>
                    </div>
                    <div class="bg-coco-green shadow-sm p-7 rounded-3xl">
                        <div class="mb-1 font-display font-black text-coco-cream text-5xl">5+</div>
                        <div class="mb-1 font-bold text-coco-cream text-sm">Years Expertise</div>
                        <div class="text-coco-sage text-xs leading-relaxed">Pioneering coir craft in the Philippines</div>
                    </div>
                    <div class="bg-coco-brown shadow-sm p-7 rounded-3xl">
                        <div class="mb-1 font-display font-black text-coco-amber text-5xl">100%</div>
                        <div class="mb-1 font-bold text-coco-cream text-sm">Biodegradable</div>
                        <div class="text-coco-tan text-xs leading-relaxed">No synthetics, no compromise</div>
                    </div>
                    <div class="flex flex-col justify-center items-center shadow-sm p-7 border border-coco-tan/20 rounded-3xl text-center fiber-bg">
                        <div class="mb-2 text-5xl">🌴</div>
                        <div class="font-bold text-coco-brown text-sm">PH Sourced</div>
                        <div class="text-coco-mid text-xs">Proudly Philippine farmers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════ FEATURED PRODUCTS ══════════════════════════ -->
    <section id="products" class="bg-coco-cream py-24">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="space-y-4 mb-16 text-center">
                <p class="font-bold text-coco-orange text-xs uppercase tracking-[0.3em]">Our Range</p>
                <h2 class="font-display font-black text-coco-brown text-4xl sm:text-5xl">Featured Products</h2>
                <p class="mx-auto max-w-2xl font-light text-coco-mid text-lg">Eco-friendly coir products handcrafted for gardening, construction, and sustainable living.</p>
            </div>

            <div class="gap-7 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Card 1 -->
                <div class="group bg-white shadow-md border border-coco-sand/60 rounded-3xl overflow-hidden product-card">
                    <div class="relative bg-gradient-to-br from-coco-green/20 to-coco-sage/20 aspect-[4/3] overflow-hidden">
                        <img src="/images/coirdoormat.png" alt="Coir Door Mat" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="top-3 left-3 absolute px-3 py-1 rounded-full font-black text-[10px] uppercase tracking-widest badge-new">New</span>
                        <button class="top-3 right-3 absolute flex justify-center items-center bg-white/80 hover:bg-coco-orange opacity-0 group-hover:opacity-100 backdrop-blur-sm rounded-full w-8 h-8 text-coco-brown hover:text-white transition-opacity">
                            <i class="text-xs fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-5">
                        <p class="mb-1 font-bold text-[10px] text-coco-green uppercase tracking-widest">Home & Living</p>
                        <h3 class="mb-1 font-display font-bold text-coco-brown text-lg leading-snug">Coir Door Mat</h3>
                        <p class="mb-4 text-coco-mid text-xs leading-relaxed">Natural, durable, fully biodegradable entrance mats.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-display font-black text-coco-orange text-xl">₱350</span>
                            <a href="<?= site_url('products') ?>" class="bg-coco-brown hover:bg-coco-orange px-4 py-2 rounded-full font-bold text-coco-cream text-xs transition-colors">Add to Cart</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="group bg-white shadow-md border border-coco-sand/60 rounded-3xl overflow-hidden product-card">
                    <div class="relative bg-gradient-to-br from-coco-amber/20 to-coco-orange/10 aspect-[4/3] overflow-hidden">
                        <img src="/images/growbag.png" alt="Coco Grow Bag" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="top-3 left-3 absolute px-3 py-1 rounded-full font-black text-[10px] uppercase tracking-widest badge-trending">Trending</span>
                    </div>
                    <div class="p-5">
                        <p class="mb-1 font-bold text-[10px] text-coco-green uppercase tracking-widest">Gardening</p>
                        <h3 class="mb-1 font-display font-bold text-coco-brown text-lg leading-snug">Coco Grow Bag</h3>
                        <p class="mb-4 text-coco-mid text-xs leading-relaxed">Perfect moisture retention for vegetables and herbs.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-display font-black text-coco-orange text-xl">₱180</span>
                            <a href="<?= site_url('products') ?>" class="bg-coco-brown hover:bg-coco-orange px-4 py-2 rounded-full font-bold text-coco-cream text-xs transition-colors">Add to Cart</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="group bg-white shadow-md border border-coco-sand/60 rounded-3xl overflow-hidden product-card">
                    <div class="relative bg-gradient-to-br from-coco-brown/10 to-coco-tan/20 aspect-[4/3] overflow-hidden">
                        <img src="/images/geotextitle.png" alt="Geotextile Erosion Mat" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="top-3 left-3 absolute px-3 py-1 rounded-full font-black text-[10px] uppercase tracking-widest badge-bestseller">Best Seller</span>
                    </div>
                    <div class="p-5">
                        <p class="mb-1 font-bold text-[10px] text-coco-green uppercase tracking-widest">Construction</p>
                        <h3 class="mb-1 font-display font-bold text-coco-brown text-lg leading-snug">Geotextile Mat</h3>
                        <p class="mb-4 text-coco-mid text-xs leading-relaxed">Slope stabilization and erosion control, project-grade.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-display font-black text-coco-orange text-xl">₱1,200</span>
                            <a href="<?= site_url('products') ?>" class="bg-coco-brown hover:bg-coco-orange px-4 py-2 rounded-full font-bold text-coco-cream text-xs transition-colors">Add to Cart</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="group bg-white shadow-md border border-coco-sand/60 rounded-3xl overflow-hidden product-card">
                    <div class="relative bg-gradient-to-br from-coco-leaf/20 to-coco-green/10 aspect-[4/3] overflow-hidden">
                        <img src="/images/coirrope.jpg" alt="Twisted Coir Rope" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5">
                        <p class="mb-1 font-bold text-[10px] text-coco-green uppercase tracking-widest">Craft & Utility</p>
                        <h3 class="mb-1 font-display font-bold text-coco-brown text-lg leading-snug">Twisted Coir Rope</h3>
                        <p class="mb-4 text-coco-mid text-xs leading-relaxed">Strong, salt-resistant rope for marine and garden use.</p>
                        <div class="flex justify-between items-center">
                            <span class="font-display font-black text-coco-orange text-xl">₱220</span>
                            <a href="<?= site_url('products') ?>" class="bg-coco-brown hover:bg-coco-orange px-4 py-2 rounded-full font-bold text-coco-cream text-xs transition-colors">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="<?= site_url('products') ?>" class="inline-flex items-center gap-2 bg-coco-brown hover:bg-coco-orange shadow-md hover:shadow-xl px-10 py-4 rounded-full font-bold text-coco-cream hover:scale-105 transition-all duration-300 transform">
                    View All Products <i class="fa-arrow-right text-sm fas"></i>
                </a>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════ WHY COCOIR ══════════════════════════ -->
    <section id="why-us" class="bg-coco-sand py-24">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <div class="space-y-4 mb-16 text-center">
                <p class="font-bold text-coco-orange text-xs uppercase tracking-[0.3em]">Why COCOIR</p>
                <h2 class="font-display font-black text-coco-brown text-4xl sm:text-5xl">Rooted in Nature,<br><em>Built for You</em></h2>
                <p class="mx-auto max-w-2xl font-light text-coco-mid text-lg">Experience eco-excellence with our commitment to quality, sustainability, and Filipino craftsmanship.</p>
            </div>

            <div class="gap-7 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <?php
                $features = [
                    ['icon' => 'leaf',               'color' => 'coco-green',  'title' => 'Fully Biodegradable',     'desc' => 'Every COCOIR product breaks down naturally — no synthetic residue in your soil, water, or air.'],
                    ['icon' => 'truck',              'color' => 'coco-orange', 'title' => 'Nationwide Delivery',     'desc' => 'Fast, secure shipping across Metro Manila and all major Philippine provinces.'],
                    ['icon' => 'shield-alt',         'color' => 'coco-brown',  'title' => 'Quality Assured',         'desc' => 'Each batch is tested for tensile strength, moisture resistance, and durability before shipping.'],
                    ['icon' => 'hand-holding-heart', 'color' => 'coco-orange', 'title' => 'Farmer-Sourced',          'desc' => 'We source directly from Philippine coconut farmers, ensuring fair prices and sustainable livelihoods.'],
                    ['icon' => 'headset',            'color' => 'coco-green',  'title' => 'Expert Support',          'desc' => 'Free consultations for construction and gardening projects. Our team guides you every step.'],
                    ['icon' => 'recycle',            'color' => 'coco-brown',  'title' => 'Circular Economy',        'desc' => 'From husk to product to compost — our supply chain creates zero waste at every stage.'],
                ];
                foreach ($features as $f): ?>
                    <div class="group hover:shadow-xl p-8 border border-coco-tan/20 rounded-3xl transition-all hover:-translate-y-1 duration-300 fiber-bg">
                        <div class="w-14 h-14 bg-<?= $f['color'] ?>/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <i class="fas fa-<?= $f['icon'] ?> text-<?= $f['color'] ?> text-xl"></i>
                        </div>
                        <h3 class="mb-2 font-display font-bold text-coco-brown text-xl"><?= $f['title'] ?></h3>
                        <p class="text-coco-mid text-sm leading-relaxed"><?= $f['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════ CTA / NEWSLETTER ══════════════════════════ -->
    <section id="contact" class="relative bg-coco-brown py-24 overflow-hidden">
        <!-- Decorative -->
        <div class="top-0 right-0 absolute bg-coco-orange/10 w-80 h-80 pointer-events-none blob-1"></div>
        <div class="bottom-0 left-0 absolute bg-coco-leaf/10 w-64 h-64 pointer-events-none blob-2"></div>
        <div class="absolute inset-0 pointer-events-none" style="background:repeating-linear-gradient(90deg,transparent,transparent 60px,rgba(255,255,255,0.015) 60px,rgba(255,255,255,0.015) 61px);"></div>

        <div class="z-10 relative space-y-8 mx-auto px-4 max-w-4xl text-center">
            <p class="font-bold text-coco-amber text-xs uppercase tracking-[0.3em]">Stay Connected</p>
            <h2 class="font-display font-black text-coco-cream text-4xl sm:text-5xl leading-tight">
                Join the Coir Revolution.<br>
                <span class="text-coco-amber italic">Get 10% Off Your First Order.</span>
            </h2>
            <p class="mx-auto max-w-xl font-light text-coco-tan text-lg">Subscribe for exclusive deals, new product launches, and tips on sustainable living with coconut coir.</p>

            <form class="flex sm:flex-row flex-col gap-3 mx-auto max-w-lg" onsubmit="return false;">
                <input type="ilil" placeholder="cocoirph@gmail.com"
                    class="flex-1 bg-white/10 px-6 py-4 border border-coco-tan/30 focus:border-coco-amber rounded-full focus:outline-none text-coco-cream text-sm transition-colors placeholder-coco-tan/50">
                <button type="submit" class="bg-coco-orange hover:bg-coco-amber shadow-lg px-8 py-4 rounded-full font-bold text-white hover:text-coco-brown whitespace-nowrap transition-all duration-300">
                    Subscribe
                </button>
            </form>
            <p class="text-coco-tan/40 text-xs">No spam. Unsubscribe anytime. 🥥</p>

            <!-- Contact strip -->
            <div class="flex flex-wrap justify-center gap-8 pt-8 border-white/10 border-t">
                <a href="mailto:cocoirph@gmail.com" class="flex items-center gap-2 text-coco-tan hover:text-coco-amber text-sm transition-colors">
                    <i class="text-coco-orange fas fa-envelope"></i> cocoirph@gmail.com
                </a>
                <a href="tel:+639XXXXXXXXX" class="flex items-center gap-2 text-coco-tan hover:text-coco-amber text-sm transition-colors">
                    <i class="text-coco-orange fas fa-phone"></i> +63 945 872 2774
                </a>
                <span class="flex items-center gap-2 text-coco-tan text-sm">
                    <i class="text-coco-orange fas fa-map-marker-alt"></i> Philippines
                </span>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════ FOOTER ══════════════════════════ -->
    <?= $this->include('components/footer') ?>


    <script src="<?= base_url('js/main.js') ?>"></script>
    <script>
        // Nav scroll
        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            header.classList.toggle('nav-scrolled', window.scrollY > 50);
        });

        // Mobile menu
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        hamburger.addEventListener('click', () => mobileMenu.classList.toggle('open'));
        mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.remove('open')));

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll-triggered fade-in
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.product-card, .fiber-bg').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>

</body>

</html>