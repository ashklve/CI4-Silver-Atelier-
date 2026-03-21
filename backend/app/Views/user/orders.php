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

    <main class="flex-grow mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 w-full max-w-5xl">

        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 mb-3 text-coco-mid text-xs">
                <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
                <i class="fa-chevron-right text-[9px] fas"></i>
                <span class="font-semibold text-coco-orange">My Orders</span>
            </nav>
            <div class="flex justify-between items-end">
                <div>
                    <h1 class="font-display font-black text-coco-brown text-4xl sm:text-5xl">My Orders</h1>
                    <p class="mt-1 text-coco-mid text-sm">Track and manage your COCOIR purchases</p>
                </div>
                <a href="<?= site_url('products') ?>" class="hidden sm:inline-flex items-center gap-2 font-semibold text-coco-orange hover:text-coco-dark text-sm transition-colors">
                    <i class="text-xs fas fa-leaf"></i> Shop More
                </a>
            </div>
        </div>

        <!-- Tab bar -->
        <div class="bg-white shadow-sm mb-8 border border-coco-sand/60 rounded-2xl overflow-x-auto">
            <div class="flex px-2 min-w-max">
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
                <div class="flex flex-col justify-center items-center py-20 text-center">
                    <div class="mb-4 text-5xl">📦</div>
                    <h3 class="mb-2 font-display font-bold text-coco-brown text-xl">No <?= $tabs[$activeTab]['label'] ?> orders</h3>
                    <p class="mb-6 text-coco-mid text-sm">You don't have any orders here yet.</p>
                    <a href="<?= site_url('products') ?>" class="bg-coco-orange hover:bg-coco-dark px-8 py-3 rounded-full font-bold text-white text-sm transition-colors">
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
                    <div class="order-card bg-white shadow-sm border border-coco-sand/60 rounded-3xl overflow-hidden">

                        <!-- Order header -->
                        <div class="flex flex-wrap justify-between items-center gap-3 px-5 py-4 border-coco-sand/40 border-b fiber-bg">
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
                        <div class="space-y-3 px-5 py-4">
                            <?php foreach (($order['items'] ?? []) as $item): ?>
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 bg-coco-sand/30 rounded-xl w-14 h-14 overflow-hidden">
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
                            <div class="px-5 py-4 border-coco-sand/30 border-t">
                                <div class="flex justify-between items-center max-w-sm">
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
                        <div class="flex flex-wrap justify-between items-center gap-3 bg-coco-cream/30 px-5 py-4 border-coco-sand/30 border-t">
                            <div class="text-sm">
                                <span class="text-coco-mid"><?= $itemCount ?> item(s) · </span>
                                <span class="font-black text-coco-brown">Total: </span>
                                <span class="font-black text-coco-orange text-lg">₱<?= number_format($orderTotal, 2) ?></span>
                            </div>
                            <div class="flex gap-2">
                                <!-- View Details -->
                                <button onclick="showOrderDetail('<?= esc($order['order_number'] ?? '#' . $order['id']) ?>')"
                                    class="px-4 py-2 border-2 border-coco-sand hover:border-coco-orange rounded-full font-bold text-coco-mid hover:text-coco-orange text-xs transition-all">
                                    View Details
                                </button>
                                <?php if ($order['status'] === 'to_pay'): ?>
                                    <button onclick="openPayModal(<?= $order['id'] ?>)" class="bg-coco-orange hover:bg-coco-dark px-4 py-2 rounded-full font-bold text-white text-xs transition-colors">
                                        Pay Now
                                    </button>
                                <?php elseif ($order['status'] === 'to_receive'): ?>
                                    <button class="bg-coco-green hover:bg-coco-dark px-4 py-2 rounded-full font-bold text-white text-xs transition-colors">
                                        Order Received
                                    </button>
                                <?php elseif ($order['status'] === 'completed'): ?>
                                    <a href="<?= site_url('products') ?>" class="bg-coco-brown hover:bg-coco-orange px-4 py-2 rounded-full font-bold text-white text-xs transition-colors">
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
    <div id="order-modal" class="hidden z-[9997] fixed inset-0 justify-center items-center p-4" onclick="if(event.target===this)closeModal()">
        <div class="absolute inset-0 bg-coco-brown/40 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white shadow-2xl rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center px-6 py-4 border-coco-sand/40 border-b">
                <h3 class="font-display font-bold text-coco-brown text-xl" id="modal-order-id">Order Details</h3>
                <button onclick="closeModal()" class="flex justify-center items-center bg-coco-sand/40 hover:bg-coco-sand rounded-full w-8 h-8 transition-colors">
                    <i class="text-coco-mid text-xs fas fa-times"></i>
                </button>
            </div>
            <div id="modal-body" class="space-y-4 p-6">
                <!-- filled by JS -->
            </div>
        </div>
    </div>

    <!-- ── Pay Now Modal ── -->
    <div id="pay-modal" class="hidden z-[9998] fixed inset-0 justify-center items-center p-4" onclick="if(event.target===this)closePayModal()">
        <div class="absolute inset-0 bg-coco-brown/60 backdrop-blur-sm" onclick="closePayModal()"></div>
        <div class="relative bg-white shadow-2xl rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-display font-bold text-coco-brown text-xl">Complete Payment</h3>
                    <button onclick="closePayModal()" class="flex justify-center items-center bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 transition-colors">
                        <i class="text-coco-mid text-xs fas fa-times"></i>
                    </button>
                </div>

                <form action="<?= site_url('orders/pay') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <?= csrf_field() ?>
                    <input type="hidden" name="order_id" id="pay-order-id">

                    <!-- Amount Display -->
                    <div class="bg-coco-cream p-4 border border-coco-sand rounded-2xl text-center">
                        <p class="mb-1 font-bold text-coco-mid text-xs uppercase tracking-wide">Total Amount Due</p>
                        <p class="font-display font-black text-coco-orange text-3xl" id="pay-amount">₱0.00</p>
                    </div>

                    <!-- QR Code -->
                    <div class="space-y-3 pt-2 text-center">
                        <p class="font-bold text-coco-brown text-sm">Scan to Pay via InstaPay / GCash / Maya</p>
                        <div class="flex justify-center">
                            <div class="inline-block bg-white shadow-sm p-2 border-2 border-coco-orange rounded-xl">
                                <svg viewBox="0 0 100 100" class="w-40 h-40" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="100" height="100" fill="white" />
                                    <rect x="10" y="10" width="30" height="30" fill="none" stroke="#3B2314" stroke-width="3" />
                                    <rect x="15" y="15" width="20" height="20" fill="#3B2314" />
                                    <rect x="60" y="10" width="30" height="30" fill="none" stroke="#3B2314" stroke-width="3" />
                                    <rect x="65" y="15" width="20" height="20" fill="#3B2314" />
                                    <rect x="10" y="60" width="30" height="30" fill="none" stroke="#3B2314" stroke-width="3" />
                                    <rect x="15" y="65" width="20" height="20" fill="#3B2314" />
                                    <rect x="60" y="55" width="5" height="5" fill="#3B2314" />
                                    <rect x="70" y="55" width="5" height="5" fill="#3B2314" />
                                    <rect x="65" y="60" width="5" height="5" fill="#3B2314" />
                                    <rect x="80" y="60" width="5" height="5" fill="#3B2314" />
                                    <rect x="60" y="65" width="5" height="5" fill="#3B2314" />
                                    <rect x="75" y="65" width="10" height="5" fill="#3B2314" />
                                    <rect x="60" y="75" width="10" height="5" fill="#3B2314" />
                                    <rect x="75" y="70" width="5" height="10" fill="#3B2314" />
                                    <rect x="85" y="75" width="5" height="10" fill="#3B2314" />
                                    <text x="50" y="54" text-anchor="middle" font-size="5" fill="#E87722" font-family="sans-serif" font-weight="bold">COCOIR</text>
                                </svg>
                            </div>
                        </div>
                        <p class="text-coco-mid text-xs">Account: <strong class="text-coco-brown">COCOIR Co. / 09XX-XXX-XXXX</strong></p>
                    </div>

                    <!-- Upload Proof -->
                    <div class="pt-2">
                        <label class="block mb-2 font-bold text-coco-mid text-xs uppercase tracking-wide">Upload Payment Screenshot</label>
                        <label class="group flex flex-col items-center gap-2 hover:bg-coco-orange/5 p-6 border-2 border-coco-sand hover:border-coco-orange border-dashed rounded-xl transition-all cursor-pointer">
                            <div class="flex justify-center items-center bg-coco-cream group-hover:bg-white rounded-full w-10 h-10 transition-colors">
                                <i class="text-coco-tan group-hover:text-coco-orange fas fa-cloud-upload-alt"></i>
                            </div>
                            <span class="text-coco-mid text-xs" id="pay-file-label">Click to select image</span>
                            <input type="file" name="payment_proof" accept="image/*" class="sr-only" required onchange="previewPayProof(this)">
                        </label>
                        <div id="pay-preview-area" class="hidden mt-3">
                            <img id="pay-preview-img" class="bg-coco-cream/30 border border-coco-sand rounded-xl w-full h-32 object-contain">
                        </div>
                    </div>

                    <button type="submit" class="bg-coco-orange hover:bg-coco-dark shadow-lg hover:shadow-xl py-3.5 rounded-xl w-full font-bold text-white transition-colors hover:-translate-y-0.5 transform">
                        Submit Payment Proof
                    </button>
                </form>
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
        <div class="flex items-center gap-4 py-3 border-coco-sand/30 last:border-0 border-b">
            <img src="/images/${item.product_image || 'default.png'}" class="flex-shrink-0 bg-gray-100 rounded-xl w-14 h-14 object-cover">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-coco-brown text-sm truncate">${item.product_name}</div>
                <div class="text-coco-mid text-xs">Qty: ${item.quantity} &times; ₱${Number(item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
            </div>
            <div class="font-black text-coco-orange text-sm">₱${(item.quantity * item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
        </div>`).join('');
            if (!itemsHtml) {
                itemsHtml = '<p class="py-4 text-coco-mid text-sm text-center">No items in this order.</p>';
            }

            // Action buttons at the bottom
            let actionButton = '';
            if (order.status === 'to_receive') {
                actionButton = `<button class="bg-coco-green hover:bg-coco-dark py-3 rounded-full w-full font-bold text-white transition-colors" onclick="closeModal()">✓ Confirm Order Received</button>`;
            } else if (order.status === 'completed') {
                actionButton = `<button class="hover:bg-red-50 py-3 border-2 border-red-200 rounded-full w-full font-bold text-red-500 text-sm transition-colors" onclick="closeModal()">Request Refund / Return</button>`;
            }

            // Inject everything into the modal body
            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = `
        <div class="flex justify-between items-center gap-2 mb-4">
            <span class="text-xs ${badge.bg} ${badge.text} font-bold rounded-full px-3 py-1">${badge.label}</span>
            <span class="text-coco-mid text-xs">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-PH',{year:'numeric',month:'short',day:'numeric'}) : ''}</span>
        </div>

        <!-- Delivery Address -->
        <div>
            <h4 class="flex items-center gap-2 mb-2 font-bold text-coco-brown"><i class="text-coco-orange text-xs fas fa-map-marker-alt"></i> Delivery Address</h4>
            <div class="space-y-1 bg-coco-sand/30 p-4 rounded-xl text-sm">
                <p class="font-semibold text-coco-dark">${order.recipient_name || 'N/A'}</p>
                <p class="text-coco-mid">${order.recipient_phone || ''}</p>
                <p class="text-coco-mid">${receiveLabel === '🏪 Store Pickup' ? 'For Store Pickup' : (fullAddress || 'No address provided')}</p>
            </div>
        </div>

        <!-- Products -->
        <div>
            <h4 class="flex items-center gap-2 mb-2 font-bold text-coco-brown"><i class="text-coco-orange text-xs fas fa-box-open"></i> Products Ordered</h4>
            <div class="border-coco-sand/40 border-y">${itemsHtml}</div>
        </div>

        <!-- Payment Details -->
        <div>
            <h4 class="flex items-center gap-2 mb-2 font-bold text-coco-brown"><i class="text-coco-orange text-xs fas fa-receipt"></i> Payment Details</h4>
            <div class="space-y-2 bg-coco-sand/30 p-4 rounded-xl text-sm">
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
                <div class="flex justify-between mt-2 pt-2 border-coco-sand border-t font-bold text-base">
                    <span class="text-coco-brown">Order Total:</span>
                    <span class="text-coco-orange">₱${Number(order.total_amount||0).toLocaleString('en-PH',{minimumFractionDigits:2})}</span>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="space-y-1 pt-4 border-coco-sand/40 border-t text-coco-mid text-xs">
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

        function openPayModal(orderId) {
            const order = ordersData.find(o => o.id == orderId);
            if (!order) return;

            document.getElementById('pay-order-id').value = order.id;
            document.getElementById('pay-amount').textContent = '₱' + Number(order.total_amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2
            });

            // Reset form
            document.getElementById('pay-preview-area').classList.add('hidden');
            document.getElementById('pay-file-label').textContent = 'Click to select image';

            const modal = document.getElementById('pay-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePayModal() {
            const modal = document.getElementById('pay-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal();
                closePayModal();
            }
        });

        function previewPayProof(input) {
            if (input.files && input.files[0]) {
                document.getElementById('pay-file-label').textContent = input.files[0].name;
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('pay-preview-img').src = e.target.result;
                    document.getElementById('pay-preview-area').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>