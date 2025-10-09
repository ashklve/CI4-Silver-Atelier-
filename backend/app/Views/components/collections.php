<div class="group bg-cream-beige rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
    <div class="aspect-square overflow-hidden relative">
        <?php if (isset($image) && !empty($image)): ?>
            <img src="<?= base_url($image) ?>" 
                 alt="<?= $title ?? 'Collection' ?>" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        <?php else: ?>
            <div class="aspect-square bg-gradient-to-br <?= $gradient ?? 'from-warm-brown to-sage-green' ?> flex items-center justify-center relative">
                <i class="fas fa-<?= $icon ?? 'tshirt' ?> text-6xl text-light-cream group-hover:scale-110 transition-transform duration-300"></i>
            </div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
        
        <!-- Overlay Badge -->
        <?php if (isset($badge)): ?>
            <div class="absolute top-4 right-4 bg-warm-brown text-light-cream px-3 py-1 rounded-full text-xs font-semibold">
                <?= $badge ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="p-6">
        <h4 class="text-xl font-serif font-bold text-warm-brown mb-2"><?= $title ?? 'Collection Title' ?></h4>
        <p class="text-sage-green mb-4"><?= $description ?? 'Collection description' ?></p>
        <div class="flex items-center justify-between">
            <div class="text-2xl font-bold text-warm-brown"><?= $price ?? 'From ₱2,999' ?></div>
            <button class="text-sage-green hover:text-warm-brown transition-colors duration-300">
                <i class="fas fa-arrow-right text-xl"></i>
            </button>
        </div>
    </div>
</div>