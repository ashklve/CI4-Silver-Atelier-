<?php
/**
 * View: admin/storefront.php
 * Controller: Admin::storefront()
 * Route: GET /admin/storefront
 */
$settings = $settings ?? [];
$s = function($key, $default = '') use ($settings) {
    return $settings[$key] ?? $default;
};
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Storefront — COCOIR Admin']) ?>

<style>
    .settings-section { background:white; border-radius:20px; border:1px solid #f0f0f0; box-shadow:0 1px 8px rgba(0,0,0,0.05); margin-bottom:24px; overflow:hidden; }
    .settings-header  { padding:20px 24px; border-bottom:1px solid #f5f5f5; display:flex; align-items:center; gap:12px; }
    .settings-body    { padding:24px; }
    .field-label { display:block; font-size:0.7rem; font-weight:700; color:#8B5E3C; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px; }
    .field-input { width:100%; border:2px solid #e5e7eb; border-radius:12px; padding:10px 16px; font-size:0.875rem; color:#3B2314; transition:border-color 0.2s; outline:none; }
    .field-input:focus { border-color:#E87722; box-shadow:0 0 0 3px rgba(232,119,34,0.1); }
    .preview-banner { width:100%; height:160px; border-radius:16px; overflow:hidden; position:relative; background:#5C3317; }
    .preview-banner img { width:100%; height:100%; object-fit:cover; }
    .color-swatch { width:36px; height:36px; border-radius:8px; cursor:pointer; border:3px solid transparent; transition:all 0.2s; }
    .color-swatch.selected { border-color:#E87722; transform:scale(1.1); }
</style>

<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="admin-main px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display font-black text-3xl text-coco-brown">Storefront</h1>
            <p class="text-coco-mid text-sm mt-1">Customize how your store looks to customers</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= site_url('/') ?>" target="_blank"
               class="border-2 border-coco-sand text-coco-dark font-bold px-5 py-2.5 rounded-xl hover:border-coco-orange hover:text-coco-orange transition-all text-sm inline-flex items-center gap-2">
                <i class="fas fa-external-link-alt text-xs"></i> Preview
            </a>
            <button form="storefront-form" type="submit"
               class="bg-coco-orange text-white font-bold px-6 py-2.5 rounded-xl hover:bg-coco-dark transition-all shadow-md text-sm inline-flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> Save Changes
            </button>
        </div>
    </div>

    <form id="storefront-form" action="<?= site_url('admin/storefront/save') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-5">

                <!-- Store Info -->
                <div class="settings-section">
                    <div class="settings-header">
                        <div class="w-9 h-9 bg-coco-orange/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-store text-coco-orange text-sm"></i>
                        </div>
                        <div>
                            <div class="font-display font-bold text-lg text-coco-brown">Store Information</div>
                            <div class="text-xs text-coco-mid">Basic details shown across your storefront</div>
                        </div>
                    </div>
                    <div class="settings-body grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="field-label">Store Name</label>
                            <input type="text" name="store_name" value="<?= esc($s('store_name', 'COCOIR')) ?>" class="field-input">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Store Tagline</label>
                            <input type="text" name="store_tagline" value="<?= esc($s('store_tagline', 'Eco-Friendly Coconut Coir Products')) ?>" class="field-input">
                        </div>
                        <div>
                            <label class="field-label">Contact Email</label>
                            <input type="email" name="contact_email" value="<?= esc($s('contact_email', 'hello@cocoir.ph')) ?>" class="field-input">
                        </div>
                        <div>
                            <label class="field-label">Contact Phone</label>
                            <input type="text" name="contact_phone" value="<?= esc($s('contact_phone')) ?>" class="field-input" placeholder="+63 9XX XXX XXXX">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">Store Address</label>
                            <input type="text" name="store_address" value="<?= esc($s('store_address')) ?>" class="field-input" placeholder="e.g. 123 Coconut Ave., Quezon City">
                        </div>
                        <div>
                            <label class="field-label">Store Hours</label>
                            <input type="text" name="store_hours" value="<?= esc($s('store_hours', 'Mon–Sat: 8AM–6PM')) ?>" class="field-input">
                        </div>
                        <div>
                            <label class="field-label">Shipping Fee (₱)</label>
                            <input type="number" name="shipping_fee" value="<?= esc($s('shipping_fee', 150)) ?>" class="field-input" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Hero Banner -->
                <div class="settings-section">
                    <div class="settings-header">
                        <div class="w-9 h-9 bg-coco-green/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-image text-coco-green text-sm"></i>
                        </div>
                        <div>
                            <div class="font-display font-bold text-lg text-coco-brown">Hero Banner</div>
                            <div class="text-xs text-coco-mid">The big image on your landing page</div>
                        </div>
                    </div>
                    <div class="settings-body space-y-5">
                        <!-- Preview -->
                        <div class="preview-banner">
                            <?php if ($s('hero_image')): ?>
                            <img src="/images/<?= esc($s('hero_image')) ?>" id="hero-preview-img" alt="Hero">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-coco-tan text-sm" id="hero-placeholder">
                                <i class="fas fa-image mr-2"></i> No hero image set
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="field-label">Hero Title</label>
                                <input type="text" name="hero_title" value="<?= esc($s('hero_title', 'From Husk to Craft.')) ?>" class="field-input">
                            </div>
                            <div>
                                <label class="field-label">Hero Subtitle</label>
                                <input type="text" name="hero_subtitle" value="<?= esc($s('hero_subtitle')) ?>" class="field-input" placeholder="e.g. 100% Natural · Philippine-Made">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="field-label">Hero Image</label>
                                <label class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-coco-orange transition-colors">
                                    <i class="fas fa-upload text-coco-tan"></i>
                                    <span class="text-sm text-coco-mid">Upload hero image</span>
                                    <input type="file" name="hero_image" accept="image/*" class="sr-only" onchange="previewHero(this)">
                                </label>
                                <input type="hidden" name="hero_image_current" value="<?= esc($s('hero_image')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="settings-section">
                    <div class="settings-header">
                        <div class="w-9 h-9 bg-coco-brown/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-coco-brown text-sm"></i>
                        </div>
                        <div>
                            <div class="font-display font-bold text-lg text-coco-brown">About Section</div>
                            <div class="text-xs text-coco-mid">Text shown in the About section of the landing page</div>
                        </div>
                    </div>
                    <div class="settings-body space-y-4">
                        <div>
                            <label class="field-label">About Heading</label>
                            <input type="text" name="about_heading" value="<?= esc($s('about_heading', 'The Fiber That Changes Everything')) ?>" class="field-input">
                        </div>
                        <div>
                            <label class="field-label">About Description</label>
                            <textarea name="about_description" rows="4" class="field-input resize-none"><?= esc($s('about_description')) ?></textarea>
                        </div>
                        <div>
                            <label class="field-label">About Image</label>
                            <label class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-coco-orange transition-colors">
                                <i class="fas fa-upload text-coco-tan"></i>
                                <span class="text-sm text-coco-mid">Upload about image <?= $s('about_image') ? '(current: '.$s('about_image').')' : '' ?></span>
                                <input type="file" name="about_image" accept="image/*" class="sr-only">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="settings-section">
                    <div class="settings-header">
                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-share-alt text-blue-500 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-display font-bold text-lg text-coco-brown">Social Media</div>
                            <div class="text-xs text-coco-mid">Links shown in the footer</div>
                        </div>
                    </div>
                    <div class="settings-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php
                        $socials = [
                            ['name'=>'facebook',  'icon'=>'fa-facebook-f',  'label'=>'Facebook URL'],
                            ['name'=>'instagram', 'icon'=>'fa-instagram',   'label'=>'Instagram URL'],
                            ['name'=>'tiktok',    'icon'=>'fa-tiktok',      'label'=>'TikTok URL'],
                            ['name'=>'shopee',    'icon'=>'fa-shopping-bag','label'=>'Shopee Store URL'],
                        ];
                        foreach ($socials as $social):
                        ?>
                        <div>
                            <label class="field-label flex items-center gap-2">
                                <i class="fab <?= $social['icon'] ?> text-coco-mid"></i> <?= $social['label'] ?>
                            </label>
                            <input type="url" name="social_<?= $social['name'] ?>"
                                value="<?= esc($s('social_' . $social['name'])) ?>"
                                class="field-input" placeholder="https://...">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="settings-section">
                    <div class="settings-header">
                        <div class="w-9 h-9 bg-coco-orange/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-coco-orange text-sm"></i>
                        </div>
                        <div>
                            <div class="font-display font-bold text-lg text-coco-brown">Newsletter Section</div>
                            <div class="text-xs text-coco-mid">CTA section at the bottom of the landing page</div>
                        </div>
                    </div>
                    <div class="settings-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="field-label">CTA Heading</label>
                            <input type="text" name="newsletter_heading" value="<?= esc($s('newsletter_heading', 'Join the Coir Revolution.')) ?>" class="field-input">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="field-label">CTA Subtext</label>
                            <input type="text" name="newsletter_subtext" value="<?= esc($s('newsletter_subtext', 'Subscribe for exclusive deals and eco-living tips.')) ?>" class="field-input">
                        </div>
                        <div>
                            <label class="field-label">Discount Offer (%)</label>
                            <input type="number" name="newsletter_discount" value="<?= esc($s('newsletter_discount', 10)) ?>" class="field-input" min="0" max="100">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right sidebar: Quick stats + tips -->
            <div class="space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-display font-bold text-lg text-coco-brown mb-4">Live Preview</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 p-3 bg-coco-cream/50 rounded-xl">
                            <i class="fas fa-store text-coco-orange w-4"></i>
                            <span class="text-coco-mid">Store name: <strong class="text-coco-brown" id="preview-name"><?= esc($s('store_name', 'COCOIR')) ?></strong></span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-coco-cream/50 rounded-xl">
                            <i class="fas fa-tag text-coco-orange w-4"></i>
                            <span class="text-coco-mid text-xs">Tagline: <strong class="text-coco-brown" id="preview-tagline"><?= esc($s('store_tagline', '—')) ?></strong></span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-coco-cream/50 rounded-xl">
                            <i class="fas fa-truck text-coco-orange w-4"></i>
                            <span class="text-coco-mid">Shipping: <strong class="text-coco-brown">₱<span id="preview-shipping"><?= $s('shipping_fee', 150) ?></span></strong></span>
                        </div>
                    </div>
                    <a href="<?= site_url('/') ?>" target="_blank"
                       class="mt-4 flex items-center justify-center gap-2 bg-coco-sand/50 text-coco-brown text-sm font-semibold py-2.5 rounded-xl hover:bg-coco-orange hover:text-white transition-all">
                        <i class="fas fa-eye text-xs"></i> Open Storefront
                    </a>
                </div>

                <div class="bg-coco-orange/5 border border-coco-orange/20 rounded-2xl p-5">
                    <h4 class="font-bold text-coco-brown text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-coco-orange text-xs"></i> Tips
                    </h4>
                    <ul class="space-y-2 text-xs text-coco-mid leading-relaxed">
                        <li class="flex gap-2"><span class="text-coco-orange">→</span> Upload a high-res hero image (min 1920×600px) for best results.</li>
                        <li class="flex gap-2"><span class="text-coco-orange">→</span> Keep your store tagline short and memorable — under 8 words.</li>
                        <li class="flex gap-2"><span class="text-coco-orange">→</span> Changes save immediately and are visible to customers.</li>
                        <li class="flex gap-2"><span class="text-coco-orange">→</span> Use the Preview button to check before saving.</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
function previewHero(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            let img = document.getElementById('hero-preview-img');
            const placeholder = document.getElementById('hero-placeholder');
            if (!img) {
                img = document.createElement('img');
                img.id = 'hero-preview-img';
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                document.querySelector('.preview-banner').appendChild(img);
            }
            if (placeholder) placeholder.style.display = 'none';
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Live preview updates
document.querySelector('[name="store_name"]').addEventListener('input', function() {
    document.getElementById('preview-name').textContent = this.value || 'COCOIR';
});
document.querySelector('[name="store_tagline"]').addEventListener('input', function() {
    document.getElementById('preview-tagline').textContent = this.value || '—';
});
document.querySelector('[name="shipping_fee"]').addEventListener('input', function() {
    document.getElementById('preview-shipping').textContent = this.value || '0';
});
</script>
</body>
</html>