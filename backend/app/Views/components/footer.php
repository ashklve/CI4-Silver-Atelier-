<?php $isLanding = uri_string() === '' || uri_string() === '/'; ?>
<footer class="bg-coco-dark py-16">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="gap-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 mb-12">

            <!-- Brand -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="font-display font-black text-coco-cream text-2xl">COCOIR</span>
                    <span class="text-coco-tan text-xs uppercase tracking-widest">by UXPlorers</span>
                </div>
                <p class="font-light text-coco-tan text-sm leading-relaxed">Transforming coconut husks into premium eco-products. Proudly made in the Philippines.</p>
                <div class="flex gap-3 pt-2">
                    <a href="#" class="flex justify-center items-center bg-white/10 hover:bg-coco-orange rounded-full w-9 h-9 text-coco-tan hover:text-white transition-all"><i class="text-xs fab fa-facebook-f"></i></a>
                    <a href="#" class="flex justify-center items-center bg-white/10 hover:bg-coco-orange rounded-full w-9 h-9 text-coco-tan hover:text-white transition-all"><i class="text-xs fab fa-instagram"></i></a>
                    <a href="#" class="flex justify-center items-center bg-white/10 hover:bg-coco-orange rounded-full w-9 h-9 text-coco-tan hover:text-white transition-all"><i class="text-xs fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Products -->
            <div>
                <h4 class="mb-4 font-bold text-coco-cream text-xs uppercase tracking-widest">Products</h4>
                <ul class="space-y-2">
                    <?php foreach (['Door Mats', 'Grow Bags', 'Geotextile Mats', 'Coir Rope', 'Mulch Mat', 'Erosion Netting'] as $item): ?>
                        <li><a href="<?= site_url('products') ?>" class="text-coco-tan hover:text-coco-amber text-sm transition-colors"><?= $item ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="mb-4 font-bold text-coco-cream text-xs uppercase tracking-widest">Company</h4>
                <ul class="space-y-2">
                    <?php foreach (['About Us' => $isLanding ? '#about' : site_url('/') . '#about', 'Our Farmers' => '#', 'Sustainability' => '#', 'Blog' => '#', 'Careers' => '#'] as $label => $link): ?>
                        <li><a href="<?= $link ?>" class="text-coco-tan hover:text-coco-amber text-sm transition-colors"><?= $label ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="mb-4 font-bold text-coco-cream text-xs uppercase tracking-widest">Support</h4>
                <ul class="space-y-2">
                    <?php foreach (['My Orders' => site_url('orders'), 'Track Delivery' => '#', 'Returns & Refunds' => '#', 'FAQs' => '#', 'Contact Us' => $isLanding ? '#contact' : site_url('/') . '#contact', 'Seller Portal' => site_url('seller')] as $label => $link): ?>
                        <li><a href="<?= $link ?>" class="text-coco-tan hover:text-coco-amber text-sm transition-colors"><?= $label ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="flex sm:flex-row flex-col justify-between items-center gap-4 pt-8 border-white/10 border-t">
            <p class="text-coco-tan/50 text-xs">© <?= date('Y') ?> COCOIR — Coconut Coir Co. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="text-coco-tan/50 hover:text-coco-amber text-xs transition-colors">Privacy Policy</a>
                <a href="#" class="text-coco-tan/50 hover:text-coco-amber text-xs transition-colors">Terms of Service</a>
            </div>
        </div>
        <div class="mt-6 pt-6 border-white/10 border-t text-center">
            <p class="text-coco-tan/50 text-xs">For educational purposes only, and no copyright infringement is intended.</p>
        </div>
    </div>
</footer>