<?php
/**
 * View: admin/products.php
 * Controller: Admin::products()
 * Route: GET /admin/products
 */
$products = $products ?? [];
$categoryLabels = [
    'home_living'   => 'Home & Living',
    'gardening'     => 'Gardening',
    'construction'  => 'Construction',
    'craft_utility' => 'Craft & Utility',
    'agriculture'   => 'Agriculture',
];
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Inventory — COCOIR Admin']) ?>

<style>
.product-row:hover { background: #FAFAFA; }
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(59,35,20,0.45); backdrop-filter:blur(4px); z-index:9998; align-items:center; justify-content:center; padding:16px; }
.modal-overlay.open { display:flex; }
</style>

<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="admin-main px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display font-black text-3xl text-coco-brown">Inventory</h1>
            <p class="text-coco-mid text-sm mt-1"><?= count($products) ?> products total</p>
        </div>
        <button onclick="openModal()"
            class="bg-coco-orange text-white font-bold px-6 py-3 rounded-xl hover:bg-coco-dark transition-all shadow-md hover:scale-105 transform inline-flex items-center gap-2 text-sm">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
        <input type="text" id="search-products" placeholder="Search products..."
            class="border-2 border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-coco-orange transition-colors w-64">
        <select id="filter-category" class="border-2 border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-coco-orange transition-colors appearance-none">
            <option value="">All Categories</option>
            <?php foreach ($categoryLabels as $val => $label): ?>
            <option value="<?= $val ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <select id="filter-status" class="border-2 border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-coco-orange transition-colors appearance-none">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <span class="text-xs text-coco-mid ml-auto">Showing <strong id="visible-count"><?= count($products) ?></strong> products</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="products-table">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold">Product</th>
                        <th class="px-5 py-4 text-left font-semibold">Category</th>
                        <th class="px-5 py-4 text-left font-semibold">Price</th>
                        <th class="px-5 py-4 text-left font-semibold">Stock</th>
                        <th class="px-5 py-4 text-left font-semibold">Status</th>
                        <th class="px-5 py-4 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="products-tbody">
                    <?php if (empty($products)): ?>
                    <tr><td colspan="6" class="px-5 py-16 text-center text-coco-mid">No products yet. <button onclick="openModal()" class="text-coco-orange font-semibold hover:underline">Add your first product →</button></td></tr>
                    <?php endif; ?>
                    <?php foreach ($products as $p):
                        $catLabel = $categoryLabels[$p['category']] ?? $p['category'];
                        $stockClass = $p['stock'] == 0 ? 'text-red-600 bg-red-50' : ($p['stock'] <= 5 ? 'text-yellow-600 bg-yellow-50' : 'text-green-600 bg-green-50');
                    ?>
                    <tr class="product-row transition-colors"
                        data-name="<?= strtolower(esc($p['name'])) ?>"
                        data-category="<?= $p['category'] ?>"
                        data-status="<?= $p['status'] ?>">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-coco-sand/30 flex-shrink-0">
                                    <img src="/images/<?= esc($p['image'] ?? 'default.png') ?>" alt="<?= esc($p['name']) ?>" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-bold text-coco-brown"><?= esc($p['name']) ?></div>
                                    <div class="text-xs text-coco-mid truncate max-w-[200px]"><?= esc(substr($p['description'] ?? '', 0, 50)) ?>...</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="bg-coco-sand/50 text-coco-dark text-xs font-semibold px-3 py-1 rounded-full"><?= $catLabel ?></span>
                        </td>
                        <td class="px-5 py-4 font-bold text-coco-orange">₱<?= number_format($p['price'], 2) ?></td>
                        <td class="px-5 py-4">
                            <span class="<?= $stockClass ?> text-xs font-bold px-2.5 py-1 rounded-full"><?= $p['stock'] ?> units</span>
                        </td>
                        <td class="px-5 py-4">
                            <form action="<?= site_url('admin/products/toggle') ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="<?= $p['status'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?> text-xs font-bold px-3 py-1 rounded-full hover:opacity-80 transition-opacity">
                                    <?= $p['status'] ? 'Active' : 'Inactive' ?>
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex gap-2">
                                <button onclick='openEditModal(<?= json_encode($p) ?>)'
                                    class="w-8 h-8 rounded-lg bg-coco-orange/10 text-coco-orange hover:bg-coco-orange hover:text-white transition-all flex items-center justify-center text-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?= site_url('admin/products/delete') ?>" method="POST" onsubmit="return confirm('Delete this product?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center text-xs" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- ── Add/Edit Product Modal ── -->
<div id="product-modal" class="modal-overlay">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h3 class="font-display font-bold text-xl text-coco-brown" id="modal-title">Add Product</h3>
            <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-coco-mid">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="product-form" action="<?= site_url('admin/products/save') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form-id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Product Name *</label>
                    <input type="text" name="name" id="form-name" required
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Category *</label>
                    <select name="category" id="form-category" required
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors appearance-none">
                        <?php foreach ($categoryLabels as $val => $label): ?>
                        <option value="<?= $val ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Price (₱) *</label>
                    <input type="number" name="price" id="form-price" step="0.01" min="0" required
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Stock *</label>
                    <input type="number" name="stock" id="form-stock" min="0" required
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Status</label>
                    <select name="status" id="form-status"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors appearance-none">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Description</label>
                    <textarea name="description" id="form-description" rows="3"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors resize-none"></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-coco-mid mb-1.5 uppercase tracking-wide">Product Image</label>
                    <label class="flex flex-col items-center gap-2 border-2 border-dashed border-gray-200 rounded-xl p-5 cursor-pointer hover:border-coco-orange transition-colors">
                        <i class="fas fa-cloud-upload-alt text-coco-tan text-2xl"></i>
                        <span class="text-xs text-coco-mid">Click to upload image</span>
                        <input type="file" name="image" accept="image/*" class="sr-only" onchange="previewImg(this)">
                    </label>
                    <div id="img-preview" class="hidden mt-2">
                        <img id="preview-img" class="h-24 rounded-xl object-contain border border-gray-200">
                    </div>
                    <div id="current-img" class="hidden mt-2 text-xs text-coco-mid">Current: <span id="current-img-name"></span></div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()"
                    class="flex-1 border-2 border-gray-200 text-coco-mid font-bold py-3 rounded-xl hover:border-gray-300 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 bg-coco-orange text-white font-bold py-3 rounded-xl hover:bg-coco-dark transition-colors shadow-md text-sm">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Modal ──
function openModal() {
    document.getElementById('modal-title').textContent = 'Add Product';
    document.getElementById('product-form').reset();
    document.getElementById('form-id').value = '';
    document.getElementById('img-preview').classList.add('hidden');
    document.getElementById('current-img').classList.add('hidden');
    document.getElementById('product-modal').classList.add('open');
}

function openEditModal(p) {
    document.getElementById('modal-title').textContent = 'Edit Product';
    document.getElementById('form-id').value          = p.id;
    document.getElementById('form-name').value        = p.name;
    document.getElementById('form-category').value    = p.category;
    document.getElementById('form-price').value       = p.price;
    document.getElementById('form-stock').value       = p.stock;
    document.getElementById('form-status').value      = p.status;
    document.getElementById('form-description').value = p.description || '';
    document.getElementById('img-preview').classList.add('hidden');
    if (p.image) {
        document.getElementById('current-img').classList.remove('hidden');
        document.getElementById('current-img-name').textContent = p.image;
    }
    document.getElementById('product-modal').classList.add('open');
}

function closeModal() {
    document.getElementById('product-modal').classList.remove('open');
}

function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('img-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Click outside to close
document.getElementById('product-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ── Search & filter ──
function filterTable() {
    const q    = document.getElementById('search-products').value.toLowerCase();
    const cat  = document.getElementById('filter-category').value;
    const stat = document.getElementById('filter-status').value;
    const rows = document.querySelectorAll('#products-tbody tr[data-name]');
    let visible = 0;

    rows.forEach(row => {
        const nameMatch = !q   || row.dataset.name.includes(q);
        const catMatch  = !cat || row.dataset.category === cat;
        const statMatch = stat === '' || row.dataset.status === stat;
        const show = nameMatch && catMatch && statMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('visible-count').textContent = visible;
}

document.getElementById('search-products').addEventListener('input', filterTable);
document.getElementById('filter-category').addEventListener('change', filterTable);
document.getElementById('filter-status').addEventListener('change', filterTable);
</script>
</body>
</html>