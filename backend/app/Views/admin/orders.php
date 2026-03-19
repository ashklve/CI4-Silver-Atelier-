<?php
/**
 * View: admin/orders.php
 * Controller: Admin::orders()
 * Route: GET /admin/orders
 */
$orders       = $orders ?? [];
$activeStatus = $activeStatus ?? '';

$statusConfig = [
    'to_pay'     => ['label'=>'To Pay',      'bg'=>'bg-yellow-100', 'text'=>'text-yellow-700', 'dot'=>'bg-yellow-400'],
    'to_ship'    => ['label'=>'To Ship',     'bg'=>'bg-orange-100', 'text'=>'text-orange-700', 'dot'=>'bg-coco-orange'],
    'to_receive' => ['label'=>'To Receive',  'bg'=>'bg-blue-100',   'text'=>'text-blue-700',   'dot'=>'bg-blue-400'],
    'completed'  => ['label'=>'Completed',   'bg'=>'bg-green-100',  'text'=>'text-green-700',  'dot'=>'bg-green-400'],
    'cancelled'  => ['label'=>'Cancelled',   'bg'=>'bg-gray-100',   'text'=>'text-gray-500',   'dot'=>'bg-gray-400'],
    'refund'     => ['label'=>'Refund',      'bg'=>'bg-red-100',    'text'=>'text-red-600',    'dot'=>'bg-red-400'],
];

// Next logical status for each current status
$nextStatus = [
    'to_pay'     => 'to_ship',
    'to_ship'    => 'to_receive',
    'to_receive' => 'completed',
];
$nextLabel = [
    'to_pay'     => 'Mark as Packed & Ready to Ship',
    'to_ship'    => 'Mark as Shipped / Out for Delivery',
    'to_receive' => 'Mark as Delivered',
];
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Orders — COCOIR Admin']) ?>

<style>
    .status-tab { transition:all 0.2s ease; border-bottom:3px solid transparent; }
    .status-tab.active { border-bottom-color:#E87722; color:#E87722; font-weight:700; }
    .order-row { transition:background 0.15s ease; }
    .order-row:hover { background:#FAFAFA; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(59,35,20,0.45); backdrop-filter:blur(4px); z-index:9998; align-items:center; justify-content:center; padding:16px; }
    .modal-overlay.open { display:flex; }
    select.status-select { appearance:none; cursor:pointer; }
</style>

<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="admin-main px-6 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-display font-black text-3xl text-coco-brown">Orders</h1>
            <p class="text-coco-mid text-sm mt-1"><?= count($orders) ?> orders <?= $activeStatus ? 'with status: '.$statusConfig[$activeStatus]['label'] : 'total' ?></p>
        </div>
        <!-- Search -->
        <div class="relative w-full sm:w-72">
            <input type="text" id="order-search" placeholder="Search order # or customer..."
                class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:border-coco-orange transition-colors">
            <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
        </div>
    </div>

    <!-- Status filter tabs -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-x-auto">
        <div class="flex min-w-max px-2">
            <a href="<?= site_url('admin/orders') ?>"
               class="status-tab <?= $activeStatus === '' ? 'active' : 'text-coco-mid' ?> flex items-center gap-2 px-5 py-4 text-sm font-semibold whitespace-nowrap hover:text-coco-orange transition-colors">
                All Orders
                <span class="bg-gray-100 text-gray-500 text-[10px] font-black px-2 py-0.5 rounded-full"><?= count($orders) ?></span>
            </a>
            <?php foreach ($statusConfig as $key => $cfg): ?>
            <?php
                $count = count(array_filter($orders, fn($o) => $o['status'] === $key));
            ?>
            <a href="<?= site_url('admin/orders?status='.$key) ?>"
               class="status-tab <?= $activeStatus === $key ? 'active' : 'text-coco-mid' ?> flex items-center gap-2 px-5 py-4 text-sm font-semibold whitespace-nowrap hover:text-coco-orange transition-colors">
                <span class="w-2 h-2 <?= $cfg['dot'] ?> rounded-full"></span>
                <?= $cfg['label'] ?>
                <?php if ($count > 0): ?>
                <span class="<?= $activeStatus === $key ? 'bg-coco-orange text-white' : 'bg-gray-100 text-gray-500' ?> text-[10px] font-black px-2 py-0.5 rounded-full"><?= $count ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Orders table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="orders-table">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold">Order #</th>
                        <th class="px-5 py-4 text-left font-semibold">Customer</th>
                        <th class="px-5 py-4 text-left font-semibold">Items</th>
                        <th class="px-5 py-4 text-left font-semibold">Total</th>
                        <th class="px-5 py-4 text-left font-semibold">Payment</th>
                        <th class="px-5 py-4 text-left font-semibold">Receive</th>
                        <th class="px-5 py-4 text-left font-semibold">Status</th>
                        <th class="px-5 py-4 text-left font-semibold">Date</th>
                        <th class="px-5 py-4 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="orders-tbody">
                    <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="text-5xl mb-3 text-gray-300"><i class="fas fa-box-open"></i></div>
                            <p class="text-coco-mid font-semibold">No orders found.</p>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($orders as $order):
                        $cfg     = $statusConfig[$order['status']] ?? $statusConfig['to_pay'];
                        $itemCnt = array_sum(array_column($order['items'] ?? [], 'quantity'));
                        $next    = $nextStatus[$order['status']] ?? null;
                    ?>
                    <tr class="order-row"
                        data-search="<?= strtolower(esc($order['order_number'] ?? $order['id'])) ?> <?= strtolower(esc($order['recipient_name'] ?? '')) ?>">

                        <!-- Order # -->
                        <td class="px-5 py-4">
                            <div class="font-bold text-coco-brown"><?= esc($order['order_number'] ?? '#'.str_pad($order['id'],5,'0',STR_PAD_LEFT)) ?></div>
                            <div class="text-[10px] text-coco-mid">#<?= $order['id'] ?></div>
                        </td>

                        <!-- Customer -->
                        <td class="px-5 py-4">
                            <div class="font-semibold text-coco-brown"><?= esc($order['recipient_name'] ?? '—') ?></div>
                            <div class="text-xs text-coco-mid"><?= esc($order['recipient_email'] ?? '') ?></div>
                            <div class="text-xs text-coco-tan"><?= esc($order['recipient_phone'] ?? '') ?></div>
                        </td>

                        <!-- Items -->
                        <td class="px-5 py-4">
                            <button onclick="showItems(<?= $order['id'] ?>)"
                                class="text-coco-orange font-bold hover:underline text-sm">
                                <?= $itemCnt ?> item<?= $itemCnt !== 1 ? 's' : '' ?>
                            </button>
                        </td>

                        <!-- Total -->
                        <td class="px-5 py-4 font-bold text-coco-orange">
                            ₱<?= number_format($order['total_amount'], 2) ?>
                            <?php if ($order['shipping_amount'] > 0): ?>
                            <div class="text-[10px] text-coco-tan font-normal">+₱<?= number_format($order['shipping_amount'],2) ?> ship</div>
                            <?php endif; ?>
                        </td>

                        <!-- Payment -->
                        <td class="px-5 py-4">
                            <span class="<?= $order['payment_method'] === 'cod' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' ?> text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                                <?php if ($order['payment_method'] === 'cod'): ?>
                                    <i class="fas fa-money-bill-wave text-xs"></i> COD
                                <?php else: ?>
                                    <i class="fas fa-qrcode text-xs"></i> InstaPay
                                <?php endif; ?>
                            </span>
                            <div class="text-[10px] text-coco-mid mt-1">
                                <?php if ($order['payment_status'] === 'paid'): ?>
                                    <i class="fas fa-check-circle text-green-500 text-xs"></i> Paid
                                <?php else: ?>
                                    <i class="fas fa-clock text-yellow-500 text-xs"></i> Pending
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Receive method -->
                        <td class="px-5 py-4">
                            <span class="text-xs text-coco-dark flex items-center gap-1.5">
                                <?php if ($order['receive_method'] === 'pickup'): ?>
                                    <i class="fas fa-store text-coco-green text-xs"></i> Pickup
                                <?php else: ?>
                                    <i class="fas fa-truck text-coco-orange text-xs"></i> Delivery
                                <?php endif; ?>
                            </span>
                            <?php if ($order['receive_method'] === 'delivery' && $order['city']): ?>
                            <div class="text-[10px] text-coco-mid"><?= esc($order['city']) ?></div>
                            <?php endif; ?>
                        </td>

                        <!-- Status badge -->
                        <td class="px-5 py-4">
                            <span class="<?= $cfg['bg'] ?> <?= $cfg['text'] ?> text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wide flex items-center gap-1.5 w-fit">
                                <span class="w-1.5 h-1.5 <?= $cfg['dot'] ?> rounded-full"></span>
                                <?= $cfg['label'] ?>
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="px-5 py-4 text-xs text-coco-mid whitespace-nowrap">
                            <?= date('M d, Y', strtotime($order['created_at'])) ?><br>
                            <?= date('h:i A', strtotime($order['created_at'])) ?>
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-2 min-w-[160px]">
                                <!-- Quick advance status button -->
                                <?php if ($next): ?>
                                <form action="<?= site_url('admin/orders/status') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                    <input type="hidden" name="status" value="<?= $next ?>">
                                    <button type="submit"
                                        class="w-full bg-coco-orange text-white text-[10px] font-bold px-3 py-2 rounded-lg hover:bg-coco-dark transition-colors text-left flex items-center gap-2">
                                        <i class="fas fa-arrow-right text-[9px]"></i>
                                        <?= $nextLabel[$order['status']] ?>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Manual status select -->
                                <form action="<?= site_url('admin/orders/status') ?>" method="POST" class="flex gap-1">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                    <select name="status"
                                        class="status-select flex-1 border border-gray-200 rounded-lg px-2 py-1.5 text-[10px] text-coco-dark focus:outline-none focus:border-coco-orange bg-white">
                                        <?php foreach ($statusConfig as $sKey => $sCfg): ?>
                                        <option value="<?= $sKey ?>" <?= $order['status'] === $sKey ? 'selected' : '' ?>>
                                            <?= $sCfg['label'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit"
                                        class="bg-gray-100 text-coco-mid text-[10px] font-bold px-2 py-1.5 rounded-lg hover:bg-coco-orange hover:text-white transition-colors">
                                        <i class="fas fa-check text-[9px]"></i>
                                    </button>
                                </form>

                                <!-- View details -->
                                <button onclick="showOrderDetail(<?= $order['id'] ?>)"
                                    class="w-full border border-gray-200 text-coco-mid text-[10px] font-bold px-3 py-1.5 rounded-lg hover:border-coco-orange hover:text-coco-orange transition-all flex items-center gap-1.5">
                                    <i class="fas fa-eye text-[9px]"></i> View Details
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- ── Order Detail Modal ── -->
<div id="order-modal" class="modal-overlay" onclick="if(event.target===this)closeModal()">
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 sticky top-0 bg-white z-10">
            <h3 class="font-display font-bold text-xl text-coco-brown" id="modal-order-num">Order Details</h3>
            <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-times text-xs text-coco-mid"></i>
            </button>
        </div>
        <div id="modal-body" class="p-6 space-y-5"></div>
    </div>
</div>

<!-- Pass orders data to JS -->
<script>
const allOrders = <?= json_encode($orders) ?>;
const statusConfig = <?= json_encode($statusConfig) ?>;

// ── Search ──
document.getElementById('order-search').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#orders-tbody tr[data-search]').forEach(row => {
        row.style.display = !q || row.dataset.search.includes(q) ? '' : 'none';
    });
});

// ── View order items (items modal) ──
function showItems(orderId) {
    showOrderDetail(orderId);
}

// ── Order detail modal ──
function showOrderDetail(orderId) {
    const order = allOrders.find(o => o.id == orderId);
    if (!order) return;

    document.getElementById('modal-order-num').textContent = order.order_number || '#' + order.id;

    const cfg = statusConfig[order.status] || {};
    const items = (order.items || []).map(item => `
        <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                <img src="/images/${item.product_image || 'default.png'}" class="w-full h-full object-cover" onerror="this.style.display='none'">
            </div>
            <div class="flex-1">
                <div class="font-semibold text-sm text-coco-brown">${item.product_name}</div>
                <div class="text-xs text-coco-mid">Qty: ${item.quantity} × ₱${Number(item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
            </div>
            <div class="font-bold text-coco-orange text-sm">₱${(item.quantity*item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
        </div>
    `).join('');

    document.getElementById('modal-body').innerHTML = `
        <!-- Status + date -->
        <div class="flex items-center justify-between flex-wrap gap-3">
            <span class="text-xs font-black px-3 py-1.5 rounded-full ${cfg.bg || ''} ${cfg.text || ''}">
                ${cfg.label || order.status}
            </span>
            <span class="text-xs text-coco-mid">${new Date(order.created_at).toLocaleString('en-PH',{dateStyle:'medium',timeStyle:'short'})}</span>
        </div>

        <!-- Customer info -->
        <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-sm">
            <div class="font-bold text-coco-brown mb-2 text-xs uppercase tracking-wide">Customer</div>
            <div class="flex items-center gap-2 text-coco-dark"><i class="fas fa-user w-4 text-coco-tan text-xs"></i>${order.recipient_name || '—'}</div>
            <div class="flex items-center gap-2 text-coco-mid"><i class="fas fa-envelope w-4 text-coco-tan text-xs"></i>${order.recipient_email || '—'}</div>
            <div class="flex items-center gap-2 text-coco-mid"><i class="fas fa-phone w-4 text-coco-tan text-xs"></i>${order.recipient_phone || '—'}</div>
            ${order.receive_method === 'delivery' 
                ? `<div class="flex items-start gap-2 text-coco-mid"><i class="fas fa-map-marker-alt w-4 text-coco-tan text-xs mt-0.5"></i><span>${[order.address, order.city, order.postal_code].filter(Boolean).join(', ')}</span></div>` 
                : '<div class="flex items-center gap-1.5 text-coco-green font-semibold text-xs"><i class="fas fa-store text-xs"></i> Store Pickup</div>'}
            ${order.order_notes ? `<div class="flex items-start gap-2 text-coco-mid"><i class="fas fa-sticky-note w-4 text-coco-tan text-xs mt-0.5"></i><span>${order.order_notes}</span></div>` : ''}
        </div>

        <!-- Items -->
        <div>
            <div class="font-bold text-coco-brown text-xs uppercase tracking-wide mb-3">Order Items</div>
            <div class="space-y-1">${items || '<p class="text-coco-mid text-sm">No items found.</p>'}</div>
        </div>

        <!-- Totals -->
        <div class="bg-coco-cream/60 rounded-2xl p-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-coco-mid">Subtotal</span><span class="font-bold">₱${Number(order.subtotal||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span></div>
            <div class="flex justify-between"><span class="text-coco-mid">Shipping</span><span class="font-bold">${Number(order.shipping_amount||0) > 0 ? '₱'+Number(order.shipping_amount).toLocaleString('en-PH',{minimumFractionDigits:2}) : 'FREE'}</span></div>
            <div class="flex justify-between border-t border-coco-sand/50 pt-2 mt-2">
                <span class="font-black text-coco-brown">Total</span>
                <span class="font-black text-coco-orange text-lg">₱${Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span>
            </div>
        </div>

        <!-- Payment -->
        <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-4 text-sm">
            <div>
                <div class="font-bold text-coco-brown text-xs uppercase tracking-wide mb-1">Payment</div>
                <div class="text-coco-dark flex items-center gap-1.5">
                    ${order.payment_method === 'cod' 
                        ? '<i class="fas fa-money-bill-wave text-green-600 text-xs"></i> Cash on Delivery' 
                        : '<i class="fas fa-qrcode text-blue-500 text-xs"></i> InstaPay / QR'}
                </div>
                <div class="text-xs mt-1 flex items-center gap-1 ${order.payment_status === 'paid' ? 'text-green-600 font-bold' : 'text-yellow-600'}">
                    ${order.payment_status === 'paid' 
                        ? '<i class=\'fas fa-check-circle text-xs\'></i> Payment Confirmed' 
                        : '<i class=\'fas fa-clock text-xs\'></i> Awaiting Payment'}
                </div>
            </div>
            ${order.payment_proof ? `<a href="/images/payments/${order.payment_proof}" target="_blank" class="text-xs text-coco-orange font-semibold hover:underline flex items-center gap-1"><i class="fas fa-image"></i> View Receipt</a>` : ''}
        </div>

        <!-- Quick status update from modal -->
        <form action="<?= site_url('admin/orders/status') ?>" method="POST" class="flex gap-3">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="${order.id}">
            <select name="status" class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors appearance-none bg-white">
                ${Object.entries(statusConfig).map(([k,v]) => `<option value="${k}" ${order.status===k?'selected':''}>${v.label}</option>`).join('')}
            </select>
            <button type="submit" class="bg-coco-orange text-white font-bold px-6 py-2.5 rounded-xl hover:bg-coco-dark transition-colors text-sm">
                Update Status
            </button>
        </form>
    `;

    document.getElementById('order-modal').classList.add('open');
}

function closeModal() {
    document.getElementById('order-modal').classList.remove('open');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
</body>
</html>