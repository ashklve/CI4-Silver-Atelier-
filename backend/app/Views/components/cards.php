<div class="bg-light-cream p-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300">
    <div class="w-16 h-16 bg-<?= $iconBg ?? 'warm-brown' ?> rounded-full flex items-center justify-center mb-6 mx-auto">
        <i class="fas fa-<?= $icon ?? 'gem' ?> text-2xl text-light-cream"></i>
    </div>
    <h3 class="text-2xl font-serif font-bold text-warm-brown mb-4 text-center"><?= $title ?? 'Feature Title' ?></h3>
    <p class="text-sage-green text-center leading-relaxed">
        <?= $description ?? 'Feature description goes here.' ?>
    </p>
</div>