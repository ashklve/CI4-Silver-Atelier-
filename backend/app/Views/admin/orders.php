<?php
/**
 * View: admin/orders.php
 * Controller: Admin::orders()
 * Route: GET /admin/orders
 */
$orders       = $orders ?? [];
$activeStatus = $activeStatus ?? '';

$statusConfig = [
    'to_pay'     => ['label'=>'To Pay',     'bg'=>'bg-yellow-100', 'text'=>'text-yellow-700', 'dot'=>'bg-yellow-400',  'icon'=>'fa-credit-card'],
    'to_ship'    => ['label'=>'To Ship',    'bg'=>'bg-orange-100', 'text'=>'text-orange-700', 'dot'=>'bg-orange-400',  'icon'=>'fa-box'],
    'to_receive' => ['label'=>'To Receive', 'bg'=>'bg-blue-100',   'text'=>'text-blue-700',   'dot'=>'bg-blue-400',    'icon'=>'fa-truck'],
    'completed'  => ['label'=>'Completed',  'bg'=>'bg-green-100',  'text'=>'text-green-700',  'dot'=>'bg-green-400',   'icon'=>'fa-check-circle'],
    'cancelled'  => ['label'=>'Cancelled',  'bg'=>'bg-gray-100',   'text'=>'text-gray-500',   'dot'=>'bg-gray-400',    'icon'=>'fa-times-circle'],
    'refund'     => ['label'=>'Refund',     'bg'=>'bg-red-100',    'text'=>'text-red-600',    'dot'=>'bg-red-400',     'icon'=>'fa-undo'],
];

$nextStatus = [
    'to_pay'     => 'to_ship',
    'to_ship'    => 'to_receive',
    'to_receive' => 'completed',
];
$nextLabel = [
    'to_pay'     => ['icon'=>'fa-box',              'text'=>'Pack & Ready to Ship'],
    'to_ship'    => ['icon'=>'fa-truck',             'text'=>'Mark as Shipped'],
    'to_receive' => ['icon'=>'fa-check',             'text'=>'Mark as Delivered'],
];
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Orders — COCOIR Admin']) ?>

<style>
    .status-tab { transition:all 0.2s ease; border-bottom:3px solid transparent; white-space:nowrap; }
    .status-tab.active { border-bottom-color:#E87722; color:#E87722; font-weight:700; }
    .order-row { transition:background 0.15s ease; cursor:pointer; }
    .order-row:hover { background:#FFFBF7; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(20,10,5,0.6); backdrop-filter:blur(6px); z-index:9998; align-items:center; justify-content:center; padding:16px; }
    .modal-overlay.open { display:flex; }
    .waybill-print { display:none; }
    @media print {
        body > *:not(#waybill-print-area) { display:none !important; }
        #waybill-print-area { display:block !important; font-family:monospace; }
    }
    .proof-img { cursor:zoom-in; transition:transform 0.2s; }
    .proof-img:hover { transform:scale(1.02); }
    .tab-content { display:none; }
    .tab-content.active { display:block; }
    .detail-tab { transition:all 0.15s; border-bottom:2px solid transparent; }
    .detail-tab.active { border-bottom-color:#E87722; color:#E87722; font-weight:700; }
</style>

<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="admin-main px-6 py-8">

    <!-- Page header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-display font-black text-3xl text-coco-brown">Orders</h1>
            <p class="text-coco-mid text-sm mt-0.5">
                <strong class="text-coco-brown"><?= count($orders) ?></strong> orders
                <?php if ($activeStatus): ?>— filtered by <span class="text-coco-orange font-semibold"><?= $statusConfig[$activeStatus]['label'] ?></span><?php endif; ?>
            </p>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" id="order-search" placeholder="Search order # or customer..."
                    class="border-2 border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:border-coco-orange transition-colors w-64">
                <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>
        </div>
    </div>

    <!-- Status filter tabs -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-x-auto">
        <div class="flex min-w-max px-2">
            <a href="<?= site_url('admin/orders') ?>"
               class="status-tab <?= $activeStatus==='' ? 'active' : 'text-coco-mid' ?> flex items-center gap-2 px-5 py-4 text-sm font-semibold hover:text-coco-orange transition-colors">
                <i class="fas fa-list text-xs"></i> All
                <span class="bg-gray-100 text-gray-600 text-[10px] font-black px-2 py-0.5 rounded-full"><?= count($orders) ?></span>
            </a>
            <?php foreach ($statusConfig as $key => $cfg):
                $cnt = count(array_filter($orders, fn($o) => $o['status'] === $key));
            ?>
            <a href="<?= site_url('admin/orders?status='.$key) ?>"
               class="status-tab <?= $activeStatus===$key ? 'active' : 'text-coco-mid' ?> flex items-center gap-2 px-5 py-4 text-sm font-semibold hover:text-coco-orange transition-colors">
                <i class="fas <?= $cfg['icon'] ?> text-xs"></i>
                <?= $cfg['label'] ?>
                <?php if ($cnt > 0): ?>
                <span class="<?= $activeStatus===$key ? 'bg-coco-orange text-white' : 'bg-gray-100 text-gray-500' ?> text-[10px] font-black px-2 py-0.5 rounded-full"><?= $cnt ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Orders table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-coco-mid border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-semibold">Order</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Customer</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Items</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Total</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Payment</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Deliver</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Status</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Date</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="orders-tbody">
                    <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="9" class="py-20 text-center">
                            <div class="text-5xl text-gray-200 mb-3"><i class="fas fa-box-open"></i></div>
                            <p class="text-coco-mid font-semibold">No orders found.</p>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($orders as $order):
                        $cfg     = $statusConfig[$order['status']] ?? $statusConfig['to_pay'];
                        $itemCnt = array_sum(array_column($order['items'] ?? [], 'quantity'));
                        $next    = $nextStatus[$order['status']] ?? null;
                        $needsPaymentVerify = $order['payment_method'] === 'instapay'
                            && $order['payment_status'] !== 'paid'
                            && !empty($order['payment_proof']);
                    ?>
                    <tr class="order-row" onclick="openOrderModal(<?= $order['id'] ?>)"
                        data-search="<?= strtolower(esc($order['order_number'] ?? $order['id'])) ?> <?= strtolower(esc($order['recipient_name'] ?? '')) ?> <?= strtolower(esc($order['username'] ?? '')) ?>">

                        <td class="px-5 py-4" onclick="event.stopPropagation()">
                            <div class="font-bold text-coco-brown text-sm"><?= esc($order['order_number'] ?? '#'.str_pad($order['id'],5,'0',STR_PAD_LEFT)) ?></div>
                            <div class="text-[10px] text-coco-mid mt-0.5"><?= date('M d', strtotime($order['created_at'])) ?></div>
                            <?php if ($needsPaymentVerify): ?>
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-[9px] font-black px-2 py-0.5 rounded-full mt-1">
                                <i class="fas fa-exclamation text-[8px]"></i> Verify Payment
                            </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-5 py-4">
                            <div class="font-semibold text-coco-brown text-sm"><?= esc($order['recipient_name'] ?? '—') ?></div>
                            <?php if (!empty($order['username'])): ?>
                            <div class="text-[10px] text-coco-orange font-semibold">@<?= esc($order['username']) ?></div>
                            <?php endif; ?>
                            <div class="text-xs text-coco-mid"><?= esc($order['recipient_phone'] ?? '') ?></div>
                        </td>

                        <td class="px-5 py-4">
                            <span class="font-bold text-coco-brown"><?= $itemCnt ?></span>
                            <span class="text-coco-mid text-xs"> item<?= $itemCnt !== 1 ? 's' : '' ?></span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="font-bold text-coco-orange">₱<?= number_format($order['total_amount'], 2) ?></div>
                            <?php if ($order['shipping_amount'] > 0): ?>
                            <div class="text-[10px] text-coco-tan">+₱<?= number_format($order['shipping_amount'],2) ?> ship</div>
                            <?php endif; ?>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 text-xs font-semibold <?= $order['payment_method']==='cod' ? 'text-green-700' : 'text-blue-700' ?>">
                                <?php if ($order['payment_method'] === 'cod'): ?>
                                    <i class="fas fa-money-bill-wave text-xs"></i> COD
                                <?php else: ?>
                                    <i class="fas fa-qrcode text-xs"></i> InstaPay
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] mt-0.5 <?= $order['payment_status']==='paid' ? 'text-green-600 font-bold' : 'text-yellow-600' ?>">
                                <?php if ($order['payment_status'] === 'paid'): ?>
                                    <i class="fas fa-check-circle text-[9px]"></i> Paid
                                <?php else: ?>
                                    <i class="fas fa-clock text-[9px]"></i> Pending
                                <?php endif; ?>
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 text-xs text-coco-dark">
                                <?php if ($order['receive_method'] === 'pickup'): ?>
                                    <i class="fas fa-store text-coco-green text-xs"></i> Pickup
                                <?php else: ?>
                                    <i class="fas fa-truck text-coco-orange text-xs"></i> Delivery
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($order['city'])): ?>
                            <div class="text-[10px] text-coco-mid"><?= esc($order['city']) ?></div>
                            <?php endif; ?>
                        </td>

                        <td class="px-5 py-4">
                            <span class="<?= $cfg['bg'] ?> <?= $cfg['text'] ?> text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1.5 w-fit">
                                <span class="w-1.5 h-1.5 <?= $cfg['dot'] ?> rounded-full"></span>
                                <?= $cfg['label'] ?>
                            </span>
                        </td>

                        <td class="px-5 py-4 text-xs text-coco-mid whitespace-nowrap">
                            <?= date('M d, Y', strtotime($order['created_at'])) ?><br>
                            <span class="text-[10px]"><?= date('h:i A', strtotime($order['created_at'])) ?></span>
                        </td>

                        <td class="px-5 py-4" onclick="event.stopPropagation()">
                            <div class="flex flex-col gap-1.5 min-w-[150px]">
                                <?php if ($next): ?>
                                <form action="<?= site_url('admin/orders/status') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                    <input type="hidden" name="status" value="<?= $next ?>">
                                    <button type="submit" class="w-full bg-coco-orange text-white text-[10px] font-bold px-3 py-2 rounded-lg hover:bg-coco-dark transition-colors flex items-center gap-1.5">
                                        <i class="fas <?= $nextLabel[$order['status']]['icon'] ?> text-[9px]"></i>
                                        <?= $nextLabel[$order['status']]['text'] ?>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <?php if ($needsPaymentVerify): ?>
                                <button onclick="openOrderModal(<?= $order['id'] ?>, 'payment')"
                                    class="w-full bg-yellow-400 text-yellow-900 text-[10px] font-bold px-3 py-2 rounded-lg hover:bg-yellow-500 transition-colors flex items-center gap-1.5">
                                    <i class="fas fa-receipt text-[9px]"></i> Verify Payment
                                </button>
                                <?php endif; ?>

                                <button onclick="printWaybill(<?= $order['id'] ?>)"
                                    class="w-full border border-gray-200 text-coco-mid text-[10px] font-bold px-3 py-2 rounded-lg hover:border-coco-brown hover:text-coco-brown transition-all flex items-center gap-1.5">
                                    <i class="fas fa-print text-[9px]"></i> Print Waybill
                                </button>

                                <form action="<?= site_url('admin/orders/status') ?>" method="POST" class="flex gap-1">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                    <select name="status" class="flex-1 border border-gray-200 rounded-lg px-2 py-1.5 text-[10px] text-coco-dark focus:outline-none focus:border-coco-orange bg-white appearance-none">
                                        <?php foreach ($statusConfig as $sKey => $sCfg): ?>
                                        <option value="<?= $sKey ?>" <?= $order['status']===$sKey ? 'selected' : '' ?>><?= $sCfg['label'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="bg-gray-100 text-coco-mid px-2 py-1.5 rounded-lg hover:bg-coco-orange hover:text-white transition-colors text-[10px]">
                                        <i class="fas fa-check text-[9px]"></i>
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

<!-- ══════════════════════ ORDER DETAIL MODAL ══════════════════════ -->
<div id="order-modal" class="modal-overlay" onclick="if(event.target===this)closeModal()">
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden">

        <!-- Modal header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
            <div>
                <h3 class="font-display font-bold text-xl text-coco-brown" id="modal-order-num">Order Details</h3>
                <div id="modal-status-badge" class="mt-1"></div>
            </div>
            <div class="flex items-center gap-2">
                <button id="modal-print-btn" onclick="printCurrentWaybill()"
                    class="flex items-center gap-1.5 bg-coco-sand/50 text-coco-dark text-xs font-bold px-4 py-2 rounded-xl hover:bg-coco-brown hover:text-white transition-all">
                    <i class="fas fa-print text-xs"></i> Print Waybill
                </button>
                <button onclick="closeModal()" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times text-xs text-coco-mid"></i>
                </button>
            </div>
        </div>

        <!-- Detail tabs -->
        <div class="flex border-b border-gray-100 px-6 flex-shrink-0">
            <button onclick="switchDetailTab('overview')" id="dtab-overview" class="detail-tab active text-sm font-semibold px-4 py-3 text-coco-orange">
                <i class="fas fa-info-circle mr-1.5 text-xs"></i>Overview
            </button>
            <button onclick="switchDetailTab('customer')" id="dtab-customer" class="detail-tab text-sm font-semibold px-4 py-3 text-coco-mid">
                <i class="fas fa-user mr-1.5 text-xs"></i>Customer
            </button>
            <button onclick="switchDetailTab('items')" id="dtab-items" class="detail-tab text-sm font-semibold px-4 py-3 text-coco-mid">
                <i class="fas fa-box-open mr-1.5 text-xs"></i>Items
            </button>
            <button onclick="switchDetailTab('payment')" id="dtab-payment" class="detail-tab text-sm font-semibold px-4 py-3 text-coco-mid">
                <i class="fas fa-receipt mr-1.5 text-xs"></i>Payment
            </button>
        </div>

        <!-- Tab content area -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">

            <!-- Overview tab -->
            <div id="tcontent-overview" class="tab-content active space-y-4">
                <div id="overview-content"></div>
            </div>

            <!-- Customer tab -->
            <div id="tcontent-customer" class="tab-content space-y-4">
                <div id="customer-content"></div>
            </div>

            <!-- Items tab -->
            <div id="tcontent-items" class="tab-content space-y-4">
                <div id="items-content"></div>
            </div>

            <!-- Payment tab -->
            <div id="tcontent-payment" class="tab-content space-y-4">
                <div id="payment-content"></div>
            </div>
        </div>

        <!-- Modal footer: status update -->
        <div class="border-t border-gray-100 px-6 py-4 flex-shrink-0 bg-gray-50/50">
            <form action="<?= site_url('admin/orders/status') ?>" method="POST" class="flex gap-3 items-center">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="modal-order-id">
                <label class="text-xs font-bold text-coco-mid uppercase tracking-wide whitespace-nowrap">Update Status:</label>
                <select name="status" id="modal-status-select"
                    class="flex-1 border-2 border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-coco-orange transition-colors appearance-none bg-white">
                    <?php foreach ($statusConfig as $sKey => $sCfg): ?>
                    <option value="<?= $sKey ?>"><?= $sCfg['label'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-coco-orange text-white font-bold px-5 py-2 rounded-xl hover:bg-coco-dark transition-colors text-sm whitespace-nowrap">
                    <i class="fas fa-save mr-1.5 text-xs"></i> Save
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════ PAYMENT VERIFY MODAL ══════════════════════ -->
<div id="verify-modal" class="modal-overlay" onclick="if(event.target===this)closeVerifyModal()">
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="font-display font-bold text-xl text-coco-brown">Verify Payment</h3>
                <p class="text-xs text-coco-mid mt-0.5" id="verify-order-label"></p>
            </div>
            <button onclick="closeVerifyModal()" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-times text-xs text-coco-mid"></i>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <!-- Payment proof image -->
            <div>
                <div class="text-xs font-bold text-coco-mid uppercase tracking-wide mb-3">Payment Screenshot</div>
                <div id="verify-proof-area" class="bg-gray-50 rounded-2xl p-4 text-center min-h-[200px] flex items-center justify-center">
                    <p class="text-coco-mid text-sm"><i class="fas fa-image text-2xl text-gray-300 block mb-2"></i>No proof uploaded</p>
                </div>
            </div>

            <!-- Payment details -->
            <div id="verify-details" class="bg-coco-cream/50 rounded-2xl p-4 space-y-2 text-sm"></div>

            <!-- Action buttons -->
            <div class="grid grid-cols-2 gap-3">
                <form action="<?= site_url('admin/orders/verify-payment') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="verify-order-id">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit"
                        class="w-full bg-coco-green text-white font-bold py-3 rounded-xl hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Approve Payment
                    </button>
                </form>
                <form action="<?= site_url('admin/orders/verify-payment') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="verify-order-id-reject">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit"
                        class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> Reject Payment
                    </button>
                </form>
            </div>
            <p class="text-xs text-coco-mid text-center">
                <i class="fas fa-info-circle text-coco-orange mr-1"></i>
                Approving will mark the order as <strong>To Ship</strong> and notify the customer.
            </p>
        </div>
    </div>
</div>

<!-- ══════════════════════ WAYBILL PRINT AREA ══════════════════════ -->
<div id="waybill-print-area" class="waybill-print">
    <div style="border:2px solid #000; padding:24px; max-width:400px; font-family:monospace;">
        <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:12px; margin-bottom:12px;">
            <div style="font-size:22px; font-weight:900; letter-spacing:2px;">COCOIR</div>
            <div style="font-size:10px;">Coconut Coir Co. · coconoirph@gmail.com</div>
            <div style="font-size:10px;">+63 941 972 2881 · Philippines</div>
        </div>
        <div style="font-size:11px; margin-bottom:8px;"><strong>WAYBILL / SHIPPING LABEL</strong></div>
        <div style="font-size:10px; margin-bottom:12px; border:1px dashed #000; padding:8px;">
            <div><strong>Order #:</strong> <span id="wb-order-num"></span></div>
            <div><strong>Date:</strong> <span id="wb-date"></span></div>
            <div><strong>Payment:</strong> <span id="wb-payment"></span></div>
        </div>
        <div style="font-size:10px; margin-bottom:12px;">
            <div style="font-weight:700; margin-bottom:4px; text-transform:uppercase; font-size:9px;">Ship To:</div>
            <div style="font-size:13px; font-weight:900;" id="wb-name"></div>
            <div id="wb-address"></div>
            <div id="wb-phone"></div>
        </div>
        <div style="font-size:10px; border-top:1px solid #000; padding-top:8px; margin-bottom:8px;">
            <div style="font-weight:700; margin-bottom:4px; text-transform:uppercase; font-size:9px;">Items:</div>
            <div id="wb-items"></div>
        </div>
        <div style="font-size:11px; border-top:2px solid #000; padding-top:8px; font-weight:900;">
            TOTAL: ₱<span id="wb-total"></span>
            <span id="wb-cod-note" style="font-size:9px; font-weight:normal;"></span>
        </div>
        <div style="text-align:center; margin-top:16px; font-size:9px; color:#666;">
            Thank you for your order! · cocoir.ph
        </div>
    </div>
</div>

<!-- Orders data for JS -->
<script>
const allOrders    = <?= json_encode($orders) ?>;
const statusConfig = <?= json_encode($statusConfig) ?>;
let currentOrderId = null;

// ── Search ──
document.getElementById('order-search').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#orders-tbody tr[data-search]').forEach(row => {
        row.style.display = !q || row.dataset.search.includes(q) ? '' : 'none';
    });
});

// ── Open order modal ──
function openOrderModal(orderId, defaultTab = 'overview') {
    const order = allOrders.find(o => o.id == orderId);
    if (!order) return;
    currentOrderId = orderId;

    const cfg = statusConfig[order.status] || {};

    // Header
    document.getElementById('modal-order-num').textContent = order.order_number || '#' + String(order.id).padStart(5,'0');
    document.getElementById('modal-status-badge').innerHTML = `
        <span class="text-[10px] font-black px-3 py-1 rounded-full ${cfg.bg||''} ${cfg.text||''} inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 ${cfg.dot||''} rounded-full"></span>${cfg.label||order.status}
        </span>`;

    // Footer select
    document.getElementById('modal-order-id').value = order.id;
    document.getElementById('modal-status-select').value = order.status;

    // ── Overview tab ──
    const deliveryInfo = order.receive_method === 'pickup'
        ? `<span class="flex items-center gap-1.5 text-coco-green font-semibold"><i class="fas fa-store text-xs"></i> Store Pickup</span>`
        : `<span class="flex items-center gap-1.5"><i class="fas fa-truck text-coco-orange text-xs"></i> ${[order.address, order.city, order.postal_code].filter(Boolean).join(', ')}</span>`;

    let refundSection = '';
    if (order.status === 'refund') {
        refundSection = `
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mt-4">
            <h4 class="text-sm font-bold text-red-800 mb-4 flex items-center gap-2">
                <i class="fas fa-undo"></i> Refund Request Details
            </h4>
            <div class="space-y-4">
                <div>
                    <div class="text-[10px] font-bold text-red-400 uppercase tracking-wide mb-1">Reason for Refund</div>
                    <div class="text-sm text-coco-brown bg-white p-3 rounded-xl border border-red-100">${order.refund_reason || 'No reason provided.'}</div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-[10px] font-bold text-red-400 uppercase tracking-wide mb-2">Proof of Defect</div>
                        ${order.refund_proof 
                            ? `<a href="/images/refunds/${order.refund_proof}" target="_blank"><img src="/images/refunds/${order.refund_proof}" class="w-full h-32 object-cover rounded-xl bg-white border border-red-100 hover:opacity-90 transition-opacity"></a>` 
                            : '<div class="h-32 bg-white rounded-xl border border-red-100 flex items-center justify-center text-xs text-red-300">No proof uploaded</div>'}
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-red-400 uppercase tracking-wide mb-2">Repayment QR (GCash/Bank)</div>
                        ${order.refund_qr 
                            ? `<a href="/images/refunds/${order.refund_qr}" target="_blank"><img src="/images/refunds/${order.refund_qr}" class="w-full h-32 object-cover rounded-xl bg-white border border-red-100 hover:opacity-90 transition-opacity"></a>` 
                            : '<div class="h-32 bg-white rounded-xl border border-red-100 flex items-center justify-center text-xs text-red-300">No QR uploaded</div>'}
                    </div>
                </div>
            </div>
        </div>`;
    }

    document.getElementById('overview-content').innerHTML = `
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-1">Order Date</div>
                <div class="font-semibold text-coco-brown text-sm">${new Date(order.created_at).toLocaleDateString('en-PH',{dateStyle:'medium'})}</div>
                <div class="text-xs text-coco-mid">${new Date(order.created_at).toLocaleTimeString('en-PH',{timeStyle:'short'})}</div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-1">Total Amount</div>
                <div class="font-black text-coco-orange text-xl">₱${Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
                ${Number(order.shipping_amount||0) > 0 ? `<div class="text-xs text-coco-tan">incl. ₱${Number(order.shipping_amount).toLocaleString('en-PH',{minimumFractionDigits:2})} shipping</div>` : ''}
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-1">Payment</div>
                <div class="flex items-center gap-1.5 text-sm font-semibold ${order.payment_method==='cod' ? 'text-green-700' : 'text-blue-700'}">
                    ${order.payment_method === 'cod' ? '<i class="fas fa-money-bill-wave text-xs"></i> Cash on Delivery' : '<i class="fas fa-qrcode text-xs"></i> InstaPay / QR'}
                </div>
                <div class="flex items-center gap-1 text-xs mt-1 ${order.payment_status==='paid' ? 'text-green-600 font-bold' : 'text-yellow-600'}">
                    ${order.payment_status==='paid' ? '<i class="fas fa-check-circle text-xs"></i> Payment Confirmed' : '<i class="fas fa-clock text-xs"></i> Awaiting Confirmation'}
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-1">Delivery</div>
                <div class="text-sm text-coco-dark">${deliveryInfo}</div>
            </div>
        </div>
        ${refundSection}
        ${order.order_notes ? `<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 flex gap-3 mt-3"><i class="fas fa-sticky-note text-yellow-500 mt-0.5 flex-shrink-0"></i><div><div class="text-xs font-bold text-yellow-800 mb-1">Order Notes</div><div class="text-sm text-yellow-700">${order.order_notes}</div></div></div>` : ''}
    `;

    // ── Customer tab ──
    const hasAccount = order.username;
    document.getElementById('customer-content').innerHTML = `
        ${hasAccount ? `
        <div class="bg-coco-orange/5 border border-coco-orange/20 rounded-2xl p-5">
            <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-3">Registered Account</div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-coco-orange rounded-full flex items-center justify-center text-white font-black text-lg flex-shrink-0">
                    ${(order.first_name || order.username || '?')[0].toUpperCase()}
                </div>
                <div>
                    <div class="font-black text-coco-brown text-lg">${order.first_name || ''} ${order.last_name || ''}</div>
                    <div class="text-sm text-coco-orange font-semibold">@${order.username}</div>
                    <div class="text-xs text-coco-mid">${order.user_email || order.recipient_email || ''}</div>
                </div>
            </div>
        </div>` : '<div class="bg-gray-50 rounded-2xl p-4 text-center text-coco-mid text-sm"><i class="fas fa-user-slash text-2xl text-gray-300 block mb-2"></i>Guest checkout</div>'}

        <div class="bg-gray-50 rounded-2xl p-5 space-y-3">
            <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide">Delivery Contact</div>
            <div class="flex items-center gap-3 text-sm">
                <i class="fas fa-user w-5 text-coco-tan text-xs flex-shrink-0"></i>
                <span class="font-semibold text-coco-brown">${order.recipient_name || '—'}</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <i class="fas fa-envelope w-5 text-coco-tan text-xs flex-shrink-0"></i>
                <span class="text-coco-dark">${order.recipient_email || '—'}</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <i class="fas fa-phone w-5 text-coco-tan text-xs flex-shrink-0"></i>
                <span class="text-coco-dark">${order.recipient_phone || '—'}</span>
            </div>
            ${order.receive_method === 'delivery' ? `
            <div class="flex items-start gap-3 text-sm">
                <i class="fas fa-map-marker-alt w-5 text-coco-tan text-xs flex-shrink-0 mt-0.5"></i>
                <span class="text-coco-dark">${[order.address, order.city, order.postal_code].filter(Boolean).join(', ') || '—'}</span>
            </div>` : ''}
        </div>
    `;

    // ── Items tab ──
    const items = (order.items || []);
    const itemsHtml = items.length > 0 ? items.map(item => `
        <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                <img src="/images/${item.product_image || 'default.png'}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center text-gray-300\\'><i class=\\'fas fa-image\\'></i></div>'">
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-coco-brown text-sm">${item.product_name}</div>
                <div class="text-xs text-coco-mid">Unit Price: ₱${Number(item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
                <div class="text-xs text-coco-mid">Qty: <strong>${item.quantity}</strong></div>
            </div>
            <div class="font-black text-coco-orange text-sm whitespace-nowrap">₱${(item.quantity*item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
        </div>`).join('') : '<p class="text-coco-mid text-sm text-center py-8">No items found.</p>';

    document.getElementById('items-content').innerHTML = `
        <div class="bg-white rounded-2xl border border-gray-100">${itemsHtml}</div>
        <div class="bg-coco-cream/60 rounded-2xl p-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-coco-mid">Subtotal</span><span class="font-bold">₱${Number(order.subtotal||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span></div>
            <div class="flex justify-between"><span class="text-coco-mid">Shipping</span><span class="font-bold">${Number(order.shipping_amount||0) > 0 ? '₱'+Number(order.shipping_amount).toLocaleString('en-PH',{minimumFractionDigits:2}) : 'FREE'}</span></div>
            <div class="flex justify-between border-t border-coco-sand/50 pt-2">
                <span class="font-black text-coco-brown">Total</span>
                <span class="font-black text-coco-orange text-lg">₱${Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span>
            </div>
        </div>
    `;

    // ── Payment tab ──
    const hasproof = order.payment_proof;
    document.getElementById('payment-content').innerHTML = `
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-1">Method</div>
                <div class="flex items-center gap-1.5 font-semibold ${order.payment_method==='cod' ? 'text-green-700' : 'text-blue-700'}">
                    ${order.payment_method === 'cod' ? '<i class="fas fa-money-bill-wave text-xs"></i> Cash on Delivery' : '<i class="fas fa-qrcode text-xs"></i> InstaPay / QR'}
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-1">Status</div>
                <div class="flex items-center gap-1.5 font-bold ${order.payment_status==='paid' ? 'text-green-600' : 'text-yellow-600'}">
                    ${order.payment_status==='paid' 
                        ? '<i class="fas fa-check-circle text-xs"></i> Confirmed' 
                        : '<i class="fas fa-clock text-xs"></i> Pending'}
                </div>
            </div>
        </div>

        ${hasproof ? `
        <div>
            <div class="text-[10px] font-bold text-coco-mid uppercase tracking-wide mb-3">Payment Proof / Screenshot</div>
            <a href="/images/payments/${order.payment_proof}" target="_blank">
                <img src="/images/payments/${order.payment_proof}" 
                     class="proof-img w-full max-h-64 object-contain rounded-2xl border border-gray-200 bg-gray-50"
                     onerror="this.parentElement.innerHTML='<div class=\\'bg-gray-50 rounded-2xl p-8 text-center text-coco-mid\\'><i class=\\'fas fa-file-image text-3xl text-gray-300 block mb-2\\'></i>Image not found</div>'">
            </a>
            <p class="text-xs text-coco-mid mt-2 text-center">Click image to open full size</p>
        </div>

        ${order.payment_status !== 'paid' ? `
        <div class="border-2 border-yellow-200 bg-yellow-50 rounded-2xl p-4">
            <div class="font-bold text-yellow-800 text-sm mb-3 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-yellow-500"></i> Payment Verification Required
            </div>
            <div class="grid grid-cols-2 gap-3">
                <form action="<?= site_url('admin/orders/verify-payment') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="${order.id}">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="w-full bg-coco-green text-white font-bold py-2.5 rounded-xl hover:bg-green-700 transition-colors text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-xs"></i> Approve
                    </button>
                </form>
                <form action="<?= site_url('admin/orders/verify-payment') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="${order.id}">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="w-full bg-red-500 text-white font-bold py-2.5 rounded-xl hover:bg-red-600 transition-colors text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle text-xs"></i> Reject
                    </button>
                </form>
            </div>
        </div>` : `
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3 text-sm text-green-700">
            <i class="fas fa-check-circle text-green-500"></i>
            Payment has been verified and confirmed.
        </div>`}` : `
        <div class="bg-gray-50 rounded-2xl p-8 text-center text-coco-mid">
            <i class="fas fa-receipt text-3xl text-gray-300 block mb-3"></i>
            ${order.payment_method === 'cod' 
                ? 'Cash on Delivery — payment collected upon delivery.' 
                : 'No payment screenshot uploaded yet.'}
        </div>`}
    `;

    // Switch to requested tab
    switchDetailTab(defaultTab);

    document.getElementById('order-modal').classList.add('open');
}

// ── Detail tab switcher ──
function switchDetailTab(tab) {
    document.querySelectorAll('.detail-tab').forEach(b => {
        b.classList.remove('active', 'text-coco-orange');
        b.classList.add('text-coco-mid');
    });
    document.querySelectorAll('.tab-content').forEach(p => p.classList.remove('active'));

    const btn = document.getElementById('dtab-' + tab);
    const content = document.getElementById('tcontent-' + tab);
    if (btn) { btn.classList.add('active', 'text-coco-orange'); btn.classList.remove('text-coco-mid'); }
    if (content) content.classList.add('active');
}

// ── Verify payment modal (from table button) ──
function openVerifyModal(orderId) {
    const order = allOrders.find(o => o.id == orderId);
    if (!order) return;

    document.getElementById('verify-order-id').value = order.id;
    document.getElementById('verify-order-id-reject').value = order.id;
    document.getElementById('verify-order-label').textContent = order.order_number || '#' + order.id;

    if (order.payment_proof) {
        document.getElementById('verify-proof-area').innerHTML = `
            <a href="/images/payments/${order.payment_proof}" target="_blank">
                <img src="/images/payments/${order.payment_proof}" class="proof-img max-h-56 object-contain rounded-xl mx-auto">
            </a>`;
    }

    document.getElementById('verify-details').innerHTML = `
        <div class="flex justify-between text-sm"><span class="text-coco-mid">Order</span><span class="font-bold">${order.order_number||'#'+order.id}</span></div>
        <div class="flex justify-between text-sm"><span class="text-coco-mid">Amount</span><span class="font-black text-coco-orange">₱${Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span></div>
        <div class="flex justify-between text-sm"><span class="text-coco-mid">Customer</span><span class="font-semibold">${order.recipient_name||'—'}</span></div>
    `;

    document.getElementById('verify-modal').classList.add('open');
}

function closeVerifyModal() {
    document.getElementById('verify-modal').classList.remove('open');
}

// ── Print waybill ──
function printWaybill(orderId) {
    const order = allOrders.find(o => o.id == orderId);
    if (!order) return;
    fillWaybill(order);
    window.print();
}

function printCurrentWaybill() {
    if (!currentOrderId) return;
    printWaybill(currentOrderId);
}

function fillWaybill(order) {
    document.getElementById('wb-order-num').textContent  = order.order_number || '#' + String(order.id).padStart(5,'0');
    document.getElementById('wb-date').textContent       = new Date(order.created_at).toLocaleDateString('en-PH',{dateStyle:'medium'});
    document.getElementById('wb-payment').textContent    = order.payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'InstaPay - PAID';
    document.getElementById('wb-name').textContent       = order.recipient_name || '—';
    document.getElementById('wb-address').textContent    = [order.address, order.city, order.postal_code].filter(Boolean).join(', ') || 'Store Pickup';
    document.getElementById('wb-phone').textContent      = order.recipient_phone || '';
    document.getElementById('wb-total').textContent      = Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2});
    document.getElementById('wb-cod-note').textContent   = order.payment_method === 'cod' ? ' — COLLECT ON DELIVERY' : '';
    document.getElementById('wb-items').innerHTML = (order.items||[])
        .map(i => `${i.product_name} × ${i.quantity} = ₱${(i.quantity*i.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}`)
        .join('<br>');
}

function closeModal() {
    document.getElementById('order-modal').classList.remove('open');
    currentOrderId = null;
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(); closeVerifyModal(); }});
</script>
</body>
</html>