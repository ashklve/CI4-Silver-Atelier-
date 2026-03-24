<?php
/**
 * View: admin/reports.php
 * Controller: Admin::reports()
 * Route: GET /admin/reports
 */
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Reports — COCOIR Admin']) ?>

<style>
    .fiber-bg {
        background: repeating-linear-gradient(108deg,transparent,transparent 2px,rgba(139,94,60,0.03) 2px,rgba(139,94,60,0.03) 4px),#FAF3E8;
    }
    .tab-btn { transition:all 0.2s ease; }
    .tab-btn.active { background:#E87722; color:#fff; }
    .report-panel { display:none; }
    .report-panel.active { display:block; }
</style>

<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="admin-main px-6 py-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display font-black text-3xl text-coco-brown">Reports</h1>
            <p class="text-coco-mid text-sm mt-1">Sales performance and inventory insights</p>
        </div>
        <!-- Date filter -->
        <div class="flex items-center gap-2">
            <input type="month" id="report-month" value="<?= date('Y-m') ?>"
                class="border-2 border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-coco-orange transition-colors">
            <button onclick="window.print()"
                class="bg-coco-brown text-white font-bold px-5 py-2 rounded-xl hover:bg-coco-orange transition-colors text-sm inline-flex items-center gap-2">
                <i class="fas fa-print text-xs"></i> Print
            </button>
        </div>
    </div>

    <!-- Tab switcher -->
    <div class="flex gap-2 mb-6 bg-white border border-gray-100 rounded-2xl p-1.5 shadow-sm w-fit">
        <button onclick="switchTab('sales')" id="tab-sales"
            class="tab-btn active font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
            <i class="fas fa-chart-line mr-2"></i> Sales Report
        </button>
        <button onclick="switchTab('inventory')" id="tab-inventory"
            class="tab-btn text-coco-mid font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
            <i class="fas fa-boxes mr-2"></i> Inventory Report
        </button>
    </div>

    <!-- ══ SALES REPORT ══ -->
    <div id="panel-sales" class="report-panel active space-y-6">

        <!-- Summary cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php
            $salesCards = [
                ['label'=>"Today's Revenue",  'value'=>'₱'.number_format($todaySales ?? 0, 2),   'icon'=>'fa-calendar-day',  'color'=>'bg-coco-orange', 'sub'=>($todayOrders ?? 0).' orders'],
                ['label'=>'Monthly Revenue',  'value'=>'₱'.number_format($monthlySales ?? 0, 2), 'icon'=>'fa-calendar-alt',  'color'=>'bg-coco-green',  'sub'=>'This month'],
                ['label'=>'Avg Order Value',  'value'=>'₱'.number_format($avgOrderValue ?? 0, 2),'icon'=>'fa-receipt',       'color'=>'bg-coco-brown',  'sub'=>'Per transaction'],
                ['label'=>'Total Revenue',    'value'=>'₱'.number_format($totalRevenue ?? 0, 2), 'icon'=>'fa-coins',         'color'=>'bg-coco-orange', 'sub'=>'All time'],
            ];
            foreach ($salesCards as $c):
            ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 <?= $c['color'] ?> rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas <?= $c['icon'] ?> text-white"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wider mb-0.5"><?= $c['label'] ?></div>
                    <div class="font-display font-black text-xl text-coco-brown"><?= $c['value'] ?></div>
                    <div class="text-xs text-coco-tan"><?= $c['sub'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Daily sales table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-display font-bold text-lg text-coco-brown">Daily Sales — <?= date('F Y') ?></h2>
                <span class="text-xs text-coco-mid"><?= count($dailySales ?? []) ?> days with sales</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Date</th>
                            <th class="px-6 py-3 text-left font-semibold">Orders</th>
                            <th class="px-6 py-3 text-left font-semibold">Revenue</th>
                            <th class="px-6 py-3 text-left font-semibold">Payment Method</th>
                            <th class="px-6 py-3 text-left font-semibold">Bar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $dailySales = $dailySales ?? [];
                        $maxDaily   = !empty($dailySales) ? max(array_column($dailySales, 'revenue')) : 1;
                        if (empty($dailySales)):
                        ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-coco-mid">No sales data for this month.</td></tr>
                        <?php else: ?>
                        <?php foreach ($dailySales as $day):
                            $barPct = round(($day['revenue'] / $maxDaily) * 100);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-semibold text-coco-brown">
                                <?= date('M d, Y', strtotime($day['date'])) ?>
                            </td>
                            <td class="px-6 py-3 text-coco-mid"><?= $day['orders'] ?> orders</td>
                            <td class="px-6 py-3 font-bold text-coco-orange">₱<?= number_format($day['revenue'], 2) ?></td>
                            <td class="px-6 py-3 text-xs text-coco-mid">
                                <?= $day['instapay'] ?? 0 ?> InstaPay · <?= $day['cod'] ?? 0 ?> COD
                            </td>
                            <td class="px-6 py-3 w-32">
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-coco-orange rounded-full" style="width:<?= $barPct ?>%"></div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($dailySales)): ?>
                    <tfoot class="bg-coco-orange/5 border-t-2 border-coco-orange/20">
                        <tr>
                            <td class="px-6 py-3 font-black text-coco-brown">TOTAL</td>
                            <td class="px-6 py-3 font-black text-coco-brown"><?= array_sum(array_column($dailySales, 'orders')) ?> orders</td>
                            <td class="px-6 py-3 font-black text-coco-orange text-base">₱<?= number_format(array_sum(array_column($dailySales, 'revenue')), 2) ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Top selling products -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-display font-bold text-lg text-coco-brown">Top Selling Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">#</th>
                            <th class="px-6 py-3 text-left font-semibold">Product</th>
                            <th class="px-6 py-3 text-left font-semibold">Units Sold</th>
                            <th class="px-6 py-3 text-left font-semibold">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($topProducts)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-coco-mid">No sales data yet.</td></tr>
                        <?php else: ?>
                        <?php foreach ($topProducts as $rank => $p): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3">
                                <span class="w-6 h-6 rounded-full <?= $rank === 0 ? 'bg-coco-amber text-white' : 'bg-gray-100 text-coco-mid' ?> flex items-center justify-center text-xs font-black">
                                    <?= $rank + 1 ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 font-semibold text-coco-brown"><?= esc($p['product_name']) ?></td>
                            <td class="px-6 py-3 text-coco-mid"><?= $p['total_qty'] ?> units</td>
                            <td class="px-6 py-3 font-bold text-coco-orange">₱<?= number_format($p['total_revenue'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══ INVENTORY REPORT ══ -->
    <div id="panel-inventory" class="report-panel space-y-6">

        <!-- Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php
            $invCards = [
                ['label'=>'Total Products',  'value'=>$totalProducts ?? 0,  'icon'=>'fa-box-open',   'color'=>'bg-coco-orange'],
                ['label'=>'Active Listings', 'value'=>$activeProducts ?? 0, 'icon'=>'fa-check-circle','color'=>'bg-coco-green'],
                ['label'=>'Out of Stock',    'value'=>$outOfStock ?? 0,     'icon'=>'fa-times-circle','color'=>'bg-red-500'],
                ['label'=>'Low Stock (≤5)',  'value'=>$lowStockCount ?? 0,  'icon'=>'fa-exclamation-triangle','color'=>'bg-yellow-500'],
            ];
            foreach ($invCards as $c):
            ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 <?= $c['color'] ?> rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas <?= $c['icon'] ?> text-white"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wider mb-0.5"><?= $c['label'] ?></div>
                    <div class="font-display font-black text-3xl text-coco-brown"><?= $c['value'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Full inventory table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-display font-bold text-lg text-coco-brown">Full Inventory Status</h2>
                <a href="<?= site_url('admin/products') ?>" class="text-xs font-semibold text-coco-orange hover:text-coco-dark transition-colors">
                    Manage Inventory →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Product</th>
                            <th class="px-6 py-3 text-left font-semibold">Category</th>
                            <th class="px-6 py-3 text-left font-semibold">Price</th>
                            <th class="px-6 py-3 text-left font-semibold">Stock</th>
                            <th class="px-6 py-3 text-left font-semibold">Stock Value</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $categoryLabels = [
                            'home_living'=>'Home & Living','gardening'=>'Gardening',
                            'construction'=>'Construction','craft_utility'=>'Craft & Utility','agriculture'=>'Agriculture'
                        ];
                        $allProducts = $allProducts ?? [];
                        if (empty($allProducts)):
                        ?>
                        <tr><td colspan="6" class="px-6 py-12 text-center text-coco-mid">No products in inventory.</td></tr>
                        <?php else: ?>
                        <?php
                        $totalStockValue = 0;
                        foreach ($allProducts as $p):
                            $stockValue = $p['price'] * $p['stock'];
                            $totalStockValue += $stockValue;
                            $stockStatus = $p['stock'] == 0
                                ? ['class'=>'bg-red-100 text-red-600',    'label'=>'Out of Stock']
                                : ($p['stock'] <= 5
                                    ? ['class'=>'bg-yellow-100 text-yellow-600','label'=>'Low Stock']
                                    : ['class'=>'bg-green-100 text-green-600',  'label'=>'In Stock']);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors <?= $p['stock'] == 0 ? 'bg-red-50/30' : '' ?>">
                            <td class="px-6 py-3 font-semibold text-coco-brown"><?= esc($p['name']) ?></td>
                            <td class="px-6 py-3">
                                <span class="bg-coco-sand/50 text-coco-dark text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <?= $categoryLabels[$p['category']] ?? $p['category'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-coco-orange font-bold">₱<?= number_format($p['price'], 2) ?></td>
                            <td class="px-6 py-3 font-bold <?= $p['stock'] == 0 ? 'text-red-600' : ($p['stock'] <= 5 ? 'text-yellow-600' : 'text-coco-brown') ?>">
                                <?= $p['stock'] ?> units
                            </td>
                            <td class="px-6 py-3 text-coco-mid">₱<?= number_format($stockValue, 2) ?></td>
                            <td class="px-6 py-3">
                                <span class="<?= $stockStatus['class'] ?> text-xs font-bold px-2.5 py-1 rounded-full">
                                    <?= $stockStatus['label'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($allProducts)): ?>
                    <tfoot class="bg-coco-orange/5 border-t-2 border-coco-orange/20">
                        <tr>
                            <td colspan="4" class="px-6 py-3 font-black text-coco-brown">TOTAL INVENTORY VALUE</td>
                            <td class="px-6 py-3 font-black text-coco-orange text-base">₱<?= number_format($totalStockValue, 2) ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
function switchTab(tab) {
    document.querySelectorAll('.report-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active'); b.classList.add('text-coco-mid'); });
    document.getElementById('panel-' + tab).classList.add('active');
    const btn = document.getElementById('tab-' + tab);
    btn.classList.add('active');
    btn.classList.remove('text-coco-mid');
}
</script>
</body>
</html>