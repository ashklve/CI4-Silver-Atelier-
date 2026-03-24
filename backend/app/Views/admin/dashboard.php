<?php
/**
 * View: admin/dashboard.php
 * Controller: Admin::dashboard()
 * Route: GET /admin/dashboard
 */
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Dashboard — COCOIR Admin']) ?>
<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="admin-main px-6 py-8">

    <!-- Page title -->
    <div class="mb-8">
        <h1 class="font-display font-black text-3xl text-coco-brown">Dashboard</h1>
        <p class="text-coco-mid text-sm mt-1">Welcome back, <?= esc($adminUser['first_name'] ?? 'Admin') ?>. Here's what's happening today.</p>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <?php
        $stats = [
            ['label'=>"Today's Sales",    'value'=>'₱'.number_format($todaySales ?? 0, 2),    'icon'=>'fa-peso-sign',     'color'=>'bg-coco-orange', 'sub'=>($todayOrders ?? 0).' orders today'],
            ['label'=>'Monthly Revenue',  'value'=>'₱'.number_format($monthlySales ?? 0, 2),  'icon'=>'fa-chart-line',    'color'=>'bg-coco-green',  'sub'=>'This month'],
            ['label'=>'Total Orders',     'value'=>number_format($totalOrders ?? 0),           'icon'=>'fa-shopping-bag',  'color'=>'bg-coco-brown',  'sub'=>($pendingOrders ?? 0).' pending'],
            ['label'=>'Total Products',   'value'=>number_format($totalProducts ?? 0),         'icon'=>'fa-box-open',      'color'=>'bg-coco-orange', 'sub'=>($lowStock ?? 0).' low stock'],
        ];
        foreach ($stats as $s):
        ?>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 <?= $s['color'] ?> rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fas <?= $s['icon'] ?> text-white text-xl"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-coco-mid uppercase tracking-wide mb-0.5"><?= $s['label'] ?></div>
                <div class="font-display font-black text-2xl text-coco-brown leading-tight"><?= $s['value'] ?></div>
                <div class="text-xs text-coco-tan mt-0.5"><?= $s['sub'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Recent Orders -->
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-display font-bold text-lg text-coco-brown">Recent Orders</h2>
                <a href="<?= site_url('admin/orders') ?>" class="text-xs font-semibold text-coco-orange hover:text-coco-dark transition-colors">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Order #</th>
                            <th class="px-6 py-3 text-left font-semibold">Customer</th>
                            <th class="px-6 py-3 text-left font-semibold">Amount</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (!empty($recentOrders)): ?>
                        <?php foreach ($recentOrders as $order):
                            $statusColor = [
                                'to_pay'     => 'bg-yellow-100 text-yellow-700',
                                'to_ship'    => 'bg-orange-100 text-orange-700',
                                'to_receive' => 'bg-blue-100 text-blue-700',
                                'completed'  => 'bg-green-100 text-green-700',
                                'cancelled'  => 'bg-gray-100 text-gray-500',
                                'refund'     => 'bg-red-100 text-red-600',
                            ][$order['status']] ?? 'bg-gray-100 text-gray-500';
                            $statusLabel = [
                                'to_pay'=>'To Pay','to_ship'=>'To Ship','to_receive'=>'To Receive',
                                'completed'=>'Completed','cancelled'=>'Cancelled','refund'=>'Refund'
                            ][$order['status']] ?? $order['status'];
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-coco-brown"><?= esc($order['order_number']) ?></td>
                            <td class="px-6 py-4 text-coco-dark"><?= esc($order['recipient_name'] ?? '—') ?></td>
                            <td class="px-6 py-4 font-bold text-coco-orange">₱<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="px-6 py-4">
                                <span class="<?= $statusColor ?> text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wide"><?= $statusLabel ?></span>
                            </td>
                            <td class="px-6 py-4 text-coco-mid text-xs"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-coco-mid text-sm">No orders yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right column -->
        <div class="space-y-5">

            <!-- Order status breakdown -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-display font-bold text-lg text-coco-brown mb-5">Order Status</h2>
                <div class="space-y-3">
                    <?php
                    $statusBreakdown = [
                        ['label'=>'To Pay',     'count'=>$statusCounts['to_pay']     ?? 0, 'color'=>'bg-yellow-400'],
                        ['label'=>'To Ship',    'count'=>$statusCounts['to_ship']    ?? 0, 'color'=>'bg-coco-orange'],
                        ['label'=>'To Receive', 'count'=>$statusCounts['to_receive'] ?? 0, 'color'=>'bg-blue-400'],
                        ['label'=>'Completed',  'count'=>$statusCounts['completed']  ?? 0, 'color'=>'bg-coco-green'],
                        ['label'=>'Refund',     'count'=>$statusCounts['refund']     ?? 0, 'color'=>'bg-red-400'],
                    ];
                    $total = array_sum(array_column($statusBreakdown, 'count')) ?: 1;
                    foreach ($statusBreakdown as $sb):
                        $pct = round(($sb['count'] / $total) * 100);
                    ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-semibold text-coco-dark"><?= $sb['label'] ?></span>
                            <span class="text-xs text-coco-mid"><?= $sb['count'] ?></span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full <?= $sb['color'] ?> rounded-full transition-all duration-700" style="width:<?= $pct ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Low stock alert -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-display font-bold text-lg text-coco-brown mb-4 flex items-center gap-2">
                    Low Stock
                    <?php if (!empty($lowStockProducts)): ?>
                    <span class="bg-red-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center"><?= count($lowStockProducts) ?></span>
                    <?php endif; ?>
                </h2>
                <?php if (!empty($lowStockProducts)): ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($lowStockProducts, 0, 4) as $p): ?>
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-coco-brown truncate max-w-[140px]"><?= esc($p['name']) ?></div>
                        <span class="text-xs font-black <?= $p['stock'] == 0 ? 'text-red-500' : 'text-yellow-600' ?> bg-<?= $p['stock'] == 0 ? 'red' : 'yellow' ?>-50 px-2 py-0.5 rounded-full">
                            <?= $p['stock'] == 0 ? 'Out of stock' : $p['stock'].' left' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= site_url('admin/products') ?>" class="block text-center text-xs font-semibold text-coco-orange hover:text-coco-dark mt-4 transition-colors">Manage Inventory →</a>
                <?php else: ?>
                <p class="text-coco-mid text-sm text-center py-4">All products well stocked ✓</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>