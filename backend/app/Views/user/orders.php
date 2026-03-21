<?php

/**
 * View: user/orders.php
 * Controller: Users::orders()
 * Route: GET /orders
 * Protected: must be logged in
 */
$user   = session()->get('user');
$orders = $orders ?? []; // passed from controller via OrderModel

// Group orders by status
$grouped = [
    'to_pay'     => [],
    'to_ship'    => [],
    'to_receive' => [],
    'completed'  => [],
    'refund'     => [],
];
foreach ($orders as $o) {
    $s = $o['status'] ?? 'to_pay';
    if (isset($grouped[$s])) $grouped[$s][] = $o;
}

$tabs = [
    'to_pay'     => ['label' => 'To Pay',     'icon' => 'fa-credit-card',    'color' => 'text-coco-amber'],
    'to_ship'    => ['label' => 'To Ship',    'icon' => 'fa-box',            'color' => 'text-coco-orange'],
    'to_receive' => ['label' => 'To Receive', 'icon' => 'fa-truck',          'color' => 'text-coco-green'],
    'completed'  => ['label' => 'Completed',  'icon' => 'fa-check-circle',   'color' => 'text-coco-green'],
    'refund'     => ['label' => 'Refund',     'icon' => 'fa-undo',           'color' => 'text-red-400'],
];

$activeTab = $_GET['tab'] ?? 'to_pay';
if (!isset($tabs[$activeTab])) $activeTab = 'to_pay';

// Orders come from real DB via OrderModel::getByUser()

$statusBadge = [
    'to_pay'     => ['bg' => 'bg-yellow-100',  'text' => 'text-yellow-700',  'label' => 'Awaiting Payment'],
    'to_ship'    => ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'label' => 'Preparing Order'],
    'to_receive' => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'label' => 'Out for Delivery'],
    'completed'  => ['bg' => 'bg-green-100',   'text' => 'text-green-700',   'label' => 'Completed'],
    'refund'     => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'label' => 'Refund / Return'],
];
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'My Orders — COCOIR']) ?>

<body class="flex flex-col bg-coco-cream min-h-screen font-body text-coco-brown">
    <?= $this->include('components/header') ?>

    <style>
        .tab-btn {
            transition: all 0.2s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-btn.active {
            border-bottom-color: #E87722;
            color: #E87722;
        }

        .order-card {
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 8px 32px rgba(59, 35, 20, 0.10);
            transform: translateY(-2px);
        }

        .fiber-bg {
            background: repeating-linear-gradient(108deg, transparent, transparent 2px,
                    rgba(139, 94, 60, 0.03) 2px, rgba(139, 94, 60, 0.03) 4px), #FAF3E8;
        }

        /* Timeline */
        .timeline-step.done .dot {
            background: #4A7C59;
        }

        .timeline-step.active .dot {
            background: #E87722;
            box-shadow: 0 0 0 4px rgba(232, 119, 34, 0.2);
        }

        .timeline-step.idle .dot {
            background: #EDE0CC;
        }

        .timeline-line.done {
            background: #4A7C59;
        }

        .timeline-line.idle {
            background: #EDE0CC;
        }
    </style>

    <main class="flex-grow pt-28 pb-16 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs text-coco-mid mb-3">
                <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[9px]"></i>
                <span class="text-coco-orange font-semibold">My Orders</span>
            </nav>
            <div class="flex items-end justify-between">
                <div>
                    <h1 class="font-display font-black text-4xl sm:text-5xl text-coco-brown">My Orders</h1>
                    <p class="text-coco-mid mt-1 text-sm">Track and manage your COCOIR purchases</p>
                </div>
                <a href="<?= site_url('products') ?>" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-coco-orange hover:text-coco-dark transition-colors">
                    <i class="fas fa-leaf text-xs"></i> Shop More
                </a>
            </div>
        </div>

        <!-- Tab bar -->
        <div class="bg-white rounded-2xl border border-coco-sand/60 shadow-sm mb-8 overflow-x-auto">
            <div class="flex min-w-max px-2">
                <?php foreach ($tabs as $key => $tab): ?>
                    <a href="?tab=<?= $key ?>"
                        class="tab-btn <?= $activeTab === $key ? 'active' : 'text-coco-mid' ?> flex items-center gap-2 px-4 py-4 font-semibold text-sm whitespace-nowrap hover:text-coco-orange">
                        <i class="fas <?= $tab['icon'] ?> text-xs"></i>
                        <?= $tab['label'] ?>
                        <?php if (count($grouped[$key]) > 0): ?>
                            <span class="<?= $activeTab === $key ? 'bg-coco-orange text-white' : 'bg-coco-sand text-coco-mid' ?> text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center">
                                <?= count($grouped[$key]) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Orders list -->
        <div class="space-y-5" id="orders-list">
            <?php $activeOrders = $grouped[$activeTab]; ?>

            <?php if (empty($activeOrders)): ?>
                <!-- Empty state -->
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="text-5xl mb-4">📦</div>
                    <h3 class="font-display font-bold text-xl text-coco-brown mb-2">No <?= $tabs[$activeTab]['label'] ?> orders</h3>
                    <p class="text-coco-mid text-sm mb-6">You don't have any orders here yet.</p>
                    <a href="<?= site_url('products') ?>" class="bg-coco-orange text-white px-8 py-3 rounded-full font-bold text-sm hover:bg-coco-dark transition-colors">
                        Start Shopping
                    </a>
                </div>

            <?php else: ?>

                <?php foreach ($activeOrders as $order):
                    $badge = $statusBadge[$order['status']] ?? $statusBadge['to_pay'];
                    $orderTotal = $order['total_amount'] ?? 0;
                    $itemCount  = array_sum(array_column($order['items'] ?? [], 'quantity'));

                    // Timeline progress
                    $statusOrder = ['to_pay', 'to_ship', 'to_receive', 'completed'];
                    $currentIdx  = array_search($order['status'], $statusOrder);
                ?>
                    <div class="order-card bg-white rounded-3xl border border-coco-sand/60 shadow-sm overflow-hidden">

                        <!-- Order header -->
                        <div class="px-5 py-4 border-b border-coco-sand/40 flex flex-wrap items-center justify-between gap-3 fiber-bg">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-bold text-coco-brown text-sm"><?= esc($order['order_number'] ?? '#' . $order['id']) ?></div>
                                    <div class="text-coco-mid text-xs"><?= esc(date('M d, Y', strtotime($order['created_at'] ?? 'now'))) ?></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="<?= $badge['bg'] ?> <?= $badge['text'] ?> text-[10px] font-black tracking-wide uppercase rounded-full px-3 py-1">
                                    <?= $badge['label'] ?>
                                </span>
                                <span class="text-coco-mid text-xs">
                                    <?= $order['payment_method'] === 'cod' ? '💵 COD' : '📱 InstaPay' ?>
                                    · <?= $order['receive_method'] === 'pickup' ? '🏪 Pickup' : '🚚 Delivery' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Order items -->
                        <div class="px-5 py-4 space-y-3">
                            <?php foreach (($order['items'] ?? []) as $item): ?>
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-coco-sand/30 flex-shrink-0">
                                        <img src="/images/<?= esc($item['product_image']) ?>" alt="<?= esc($item['product_name']) ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-coco-brown text-sm"><?= esc($item['product_name']) ?></div>
                                        <div class="text-coco-mid text-xs">Qty: <?= $item['quantity'] ?> × ₱<?= number_format($item['unit_price'], 2) ?></div>
                                    </div>
                                    <div class="font-black text-coco-orange text-sm">₱<?= number_format($item['quantity'] * $item['unit_price'], 2) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Progress timeline (only for active orders, not refund) -->
                        <?php if ($order['status'] !== 'refund'): ?>
                            <div class="px-5 py-4 border-t border-coco-sand/30">
                                <div class="flex items-center justify-between max-w-sm">
                                    <?php
                                    $steps = [
                                        ['key' => 'to_pay',     'label' => 'Order Placed', 'icon' => 'fa-shopping-bag'],
                                        ['key' => 'to_ship',    'label' => 'Processing',   'icon' => 'fa-box-open'],
                                        ['key' => 'to_receive', 'label' => 'On the Way',   'icon' => 'fa-truck'],
                                        ['key' => 'completed',  'label' => 'Delivered',    'icon' => 'fa-check'],
                                    ];
                                    foreach ($steps as $si => $step):
                                        $stepIdx = array_search($step['key'], $statusOrder);
                                        $state = $stepIdx < $currentIdx ? 'done' : ($stepIdx === $currentIdx ? 'active' : 'idle');
                                    ?>
                                        <div class="timeline-step <?= $state ?> flex flex-col items-center gap-1">
                                            <div class="dot w-7 h-7 rounded-full flex items-center justify-center <?= $state === 'done' ? 'bg-coco-green' : ($state === 'active' ? 'bg-coco-orange' : 'bg-coco-sand') ?>">
                                                <i class="fas <?= $step['icon'] ?> text-[10px] <?= $state === 'idle' ? 'text-coco-tan' : 'text-white' ?>"></i>
                                            </div>
                                            <span class="text-[9px] font-semibold <?= $state === 'idle' ? 'text-coco-tan' : ($state === 'active' ? 'text-coco-orange' : 'text-coco-green') ?> text-center leading-tight max-w-[50px]"><?= $step['label'] ?></span>
                                        </div>
                                        <?php if ($si < count($steps) - 1):
                                            $lineState = $stepIdx < $currentIdx ? 'done' : 'idle';
                                        ?>
                                            <div class="timeline-line <?= $lineState ?> flex-1 h-0.5 mb-4 mx-1"></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Order footer -->
                        <div class="px-5 py-4 border-t border-coco-sand/30 flex flex-wrap items-center justify-between gap-3 bg-coco-cream/30">
                            <div class="text-sm">
                                <span class="text-coco-mid"><?= $itemCount ?> item(s) · </span>
                                <span class="font-black text-coco-brown">Total: </span>
                                <span class="font-black text-coco-orange text-lg">₱<?= number_format($orderTotal, 2) ?></span>
                            </div>
                            <div class="flex gap-2">
                                <!-- View Details -->
                                <button onclick="showOrderDetail('<?= esc($order['order_number'] ?? '#' . $order['id']) ?>')"
                                    class="border-2 border-coco-sand text-coco-mid text-xs font-bold px-4 py-2 rounded-full hover:border-coco-orange hover:text-coco-orange transition-all">
                                    View Details
                                </button>
                                <?php if ($order['status'] === 'to_pay'): ?>
                                    <a href="<?= site_url('checkout') ?>" class="bg-coco-orange text-white text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-dark transition-colors">
                                        Pay Now
                                    </a>
                                <?php elseif ($order['status'] === 'to_receive'): ?>
                                    <button class="bg-coco-green text-white text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-dark transition-colors">
                                        Order Received
                                    </button>
                                <?php elseif ($order['status'] === 'completed'): ?>
                                    <a href="<?= site_url('products') ?>" class="bg-coco-brown text-white text-xs font-bold px-4 py-2 rounded-full hover:bg-coco-orange transition-colors">
                                        Buy Again
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- ── Order Detail Modal ── -->
    <div id="order-modal" class="fixed inset-0 z-[9997] hidden items-center justify-center p-4" onclick="if(event.target===this)closeModal()">
        <div class="absolute inset-0 bg-coco-brown/40 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-coco-sand/40">
                <h3 class="font-display font-bold text-xl text-coco-brown" id="modal-order-id">Order Details</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-coco-sand/40 flex items-center justify-center hover:bg-coco-sand transition-colors">
                    <i class="fas fa-times text-coco-mid text-xs"></i>
                </button>
            </div>
            <div id="modal-body" class="p-6 space-y-4">
                <!-- filled by JS -->
            </div>
        </div>
    </div>

    <?= $this->include('components/footer') ?>

    <script>
        const ordersData = <?= json_encode($orders) ?>;

        function showOrderDetail(orderIdentifier) {
            // Find the order using either order_number or the #id format
            const order = ordersData.find(o => (o.order_number || '#' + o.id) == orderIdentifier);
            if (!order) return;

            // Set modal title
            document.getElementById('modal-order-id').textContent = 'Order ' + (order.order_number || '#' + order.id);

            // Prepare data for injection
            const payLabel = order.payment_method === 'cod' ? '💵 Cash on Delivery' : '📱 InstaPay / QR';
            const receiveLabel = order.receive_method === 'pickup' ? '🏪 Store Pickup' : '🚚 Home Delivery';
            const fullAddress = [order.address, order.city, order.postal_code].filter(Boolean).join(', ');

            const badgeMap = {
                to_pay: {
                    bg: 'bg-yellow-100',
                    text: 'text-yellow-700',
                    label: 'Awaiting Payment'
                },
                to_ship: {
                    bg: 'bg-orange-100',
                    text: 'text-orange-700',
                    label: 'Processing'
                },
                to_receive: {
                    bg: 'bg-blue-100',
                    text: 'text-blue-700',
                    label: 'Out for Delivery'
                },
                completed: {
                    bg: 'bg-green-100',
                    text: 'text-green-700',
                    label: 'Completed'
                },
                refund: {
                    bg: 'bg-red-100',
                    text: 'text-red-700',
                    label: 'Refund / Return'
                },
            };
            const badge = badgeMap[order.status] || badgeMap['to_pay'];

            // Map items to HTML
            let itemsHtml = (order.items || []).map(item => `
        <div class="flex items-center gap-4 py-3 border-b border-coco-sand/30 last:border-0">
            <img src="/images/${item.product_image || 'default.png'}" class="w-14 h-14 rounded-xl object-cover bg-gray-100 flex-shrink-0">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-coco-brown truncate">${item.product_name}</div>
                <div class="text-xs text-coco-mid">Qty: ${item.quantity} &times; ₱${Number(item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
            </div>
            <div class="font-black text-coco-orange text-sm">₱${(item.quantity * item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
        </div>`).join('');
            if (!itemsHtml) {
                itemsHtml = '<p class="text-center text-coco-mid text-sm py-4">No items in this order.</p>';
            }

            // Action buttons at the bottom
            let actionButton = '';
            if (order.status === 'to_receive') {
                actionButton = `<button class="w-full bg-coco-green text-white font-bold py-3 rounded-full hover:bg-coco-dark transition-colors" onclick="closeModal()">✓ Confirm Order Received</button>`;
            } else if (order.status === 'completed') {
                actionButton = `<button class="w-full border-2 border-red-200 text-red-500 font-bold py-3 rounded-full hover:bg-red-50 transition-colors text-sm" onclick="closeModal()">Request Refund / Return</button>`;
            }

            // Inject everything into the modal body
            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = `
        <div class="flex items-center justify-between gap-2 mb-4">
            <span class="text-xs ${badge.bg} ${badge.text} font-bold rounded-full px-3 py-1">${badge.label}</span>
            <span class="text-xs text-coco-mid">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-PH',{year:'numeric',month:'short',day:'numeric'}) : ''}</span>
        </div>

        <!-- Delivery Address -->
        <div>
            <h4 class="font-bold text-coco-brown mb-2 flex items-center gap-2"><i class="fas fa-map-marker-alt text-coco-orange text-xs"></i> Delivery Address</h4>
            <div class="bg-coco-sand/30 p-4 rounded-xl text-sm space-y-1">
                <p class="font-semibold text-coco-dark">${order.recipient_name || 'N/A'}</p>
                <p class="text-coco-mid">${order.recipient_phone || ''}</p>
                <p class="text-coco-mid">${receiveLabel === '🏪 Store Pickup' ? 'For Store Pickup' : (fullAddress || 'No address provided')}</p>
            </div>
        </div>

        <!-- Products -->
        <div>
            <h4 class="font-bold text-coco-brown mb-2 flex items-center gap-2"><i class="fas fa-box-open text-coco-orange text-xs"></i> Products Ordered</h4>
            <div class="border-y border-coco-sand/40">${itemsHtml}</div>
        </div>

        <!-- Payment Details -->
        <div>
            <h4 class="font-bold text-coco-brown mb-2 flex items-center gap-2"><i class="fas fa-receipt text-coco-orange text-xs"></i> Payment Details</h4>
            <div class="bg-coco-sand/30 p-4 rounded-xl text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-coco-mid">Payment Method:</span>
                    <span class="font-semibold text-coco-dark">${payLabel}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-coco-mid">Subtotal:</span>
                    <span class="font-semibold text-coco-dark">₱${Number(order.subtotal||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-coco-mid">Shipping Fee:</span>
                    <span class="font-semibold text-coco-dark">${Number(order.shipping_amount||0) > 0 ? '₱'+Number(order.shipping_amount).toLocaleString('en-PH',{minimumFractionDigits:2}) : 'FREE'}</span>
                </div>
                <div class="flex justify-between font-bold text-base border-t border-coco-sand pt-2 mt-2">
                    <span class="text-coco-brown">Order Total:</span>
                    <span class="text-coco-orange">₱${Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="text-xs text-coco-mid space-y-1 border-t border-coco-sand/40 pt-4">
            <div class="flex justify-between"><span>Order ID:</span> <span class="font-mono">${order.order_number || '#' + order.id}</span></div>
            <div class="flex justify-between"><span>Order Time:</span> <span>${order.created_at ? new Date(order.created_at).toLocaleString('en-PH', {dateStyle: 'medium', timeStyle: 'short'}) : ''}</span></div>
        </div>

        <!-- Action Button -->
        ${actionButton ? `<div class="pt-2">${actionButton}</div>` : ''}
    `;

            // Show the modal
            const modal = document.getElementById('order-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('order-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>