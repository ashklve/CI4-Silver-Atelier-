<?php
/**
 * View: user/checkout.php
 * Controller: Users::checkout()
 * Route: GET /checkout
 * Protected: must be logged in
 */
$user      = session()->get('user');
$fullUser  = $fullUser ?? [];   // full DB row with address, city, postal_code, phone
$cartItems = $cartItems ?? [];
$subtotal  = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 150.00;
$total    = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Checkout — COCOIR']) ?>

<body class="flex flex-col bg-coco-cream min-h-screen font-body text-coco-brown">
<?= $this->include('components/header') ?>

<style>
    /* Step indicator */
    .step-active   { background:#E87722; color:#fff; border-color:#E87722; }
    .step-done     { background:#4A7C59; color:#fff; border-color:#4A7C59; }
    .step-inactive { background:#fff;    color:#C8956C; border-color:#EDE0CC; }

    /* Radio card selection */
    .method-card input[type="radio"]:checked ~ .card-body {
        border-color: #E87722;
        background: #FFF8F2;
        box-shadow: 0 0 0 3px rgba(232,119,34,0.15);
    }
    .card-body { transition: all 0.2s ease; }

    /* Panels */
    .panel { display: none; }
    .panel.active { display: block; }

    /* QR pulse */
    @keyframes qrPulse { 0%,100%{box-shadow:0 0 0 0 rgba(232,119,34,0.35)} 50%{box-shadow:0 0 0 12px rgba(232,119,34,0)} }
    .qr-pulse { animation: qrPulse 2s ease-in-out infinite; }

    .fiber-bg {
        background: repeating-linear-gradient(
            108deg, transparent, transparent 2px,
            rgba(139,94,60,0.03) 2px, rgba(139,94,60,0.03) 4px
        ), #FAF3E8;
    }
</style>

<main class="flex-grow pt-28 pb-16 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Page title -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs text-coco-mid mb-3">
            <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= site_url('cart') ?>" class="hover:text-coco-orange transition-colors">Cart</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-coco-orange font-semibold">Checkout</span>
        </nav>
        <h1 class="font-display font-black text-4xl sm:text-5xl text-coco-brown">Checkout</h1>
    </div>

    <!-- Step indicator -->
    <div class="flex items-center gap-0 mb-10 max-w-sm">
        <div class="flex items-center gap-2">
            <div class="step-active w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-black">1</div>
            <span class="text-xs font-bold text-coco-orange hidden sm:block">Delivery</span>
        </div>
        <div class="flex-1 h-0.5 bg-coco-sand mx-2"></div>
        <div class="flex items-center gap-2">
            <div class="step-inactive w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-black" id="step2-indicator">2</div>
            <span class="text-xs font-bold text-coco-mid hidden sm:block" id="step2-label">Payment</span>
        </div>
        <div class="flex-1 h-0.5 bg-coco-sand mx-2"></div>
        <div class="flex items-center gap-2">
            <div class="step-inactive w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-black" id="step3-indicator">3</div>
            <span class="text-xs font-bold text-coco-mid hidden sm:block">Confirm</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- LEFT: Form -->
        <div class="lg:col-span-2 space-y-6">

            <!-- ── STEP 1: Delivery Method ── -->
            <div id="step1" class="bg-white rounded-3xl border border-coco-sand/60 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-coco-sand/40 flex items-center gap-3">
                    <span class="w-7 h-7 bg-coco-orange/10 rounded-full flex items-center justify-center text-coco-orange text-xs font-black">1</span>
                    <h2 class="font-display font-bold text-lg text-coco-brown">How do you want to receive your order?</h2>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Delivery option -->
                    <label class="method-card cursor-pointer">
                        <input type="radio" name="receive_method" value="delivery" class="sr-only" checked onchange="switchReceive('delivery')">
                        <div class="card-body border-2 border-coco-orange bg-coco-orange/5 rounded-2xl p-5 flex flex-col gap-2" id="card-delivery">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-coco-orange/10 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-truck text-coco-orange"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-coco-brown text-sm">Home Delivery</div>
                                    <div class="text-coco-mid text-xs">We deliver to your address</div>
                                </div>
                            </div>
                            <div class="text-xs text-coco-green font-semibold">₱150 shipping fee</div>
                        </div>
                    </label>

                    <!-- Pickup option -->
                    <label class="method-card cursor-pointer">
                        <input type="radio" name="receive_method" value="pickup" class="sr-only" onchange="switchReceive('pickup')">
                        <div class="card-body border-2 border-coco-sand rounded-2xl p-5 flex flex-col gap-2" id="card-pickup">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-coco-green/10 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-store text-coco-green"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-coco-brown text-sm">Store Pickup</div>
                                    <div class="text-coco-mid text-xs">Pick up at our shop — FREE</div>
                                </div>
                            </div>
                            <div class="text-xs text-coco-green font-semibold">Free · No shipping fee</div>
                        </div>
                    </label>
                </div>

                <!-- Delivery form -->
                <div id="delivery-form" class="px-6 pb-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-coco-brown text-sm tracking-wide">Delivery Address</h3>
                        <?php if (!empty($fullUser['address'])): ?>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-coco-green bg-coco-green/10 px-3 py-1 rounded-full">
                            <i class="fas fa-check-circle text-[9px]"></i> Saved address loaded
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-coco-mid mb-1">First Name</label>
                            <input type="text" name="first_name" value="<?= esc($fullUser['first_name'] ?? $user['first_name'] ?? '') ?>"
                                class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-coco-mid mb-1">Last Name</label>
                            <input type="text" name="last_name" value="<?= esc($fullUser['last_name'] ?? $user['last_name'] ?? '') ?>"
                                class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-coco-mid mb-1">Email</label>
                        <input type="email" name="email" value="<?= esc($fullUser['email'] ?? $user['email'] ?? '') ?>"
                            class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-coco-mid mb-1">Complete Address</label>
                        <textarea name="address" rows="2"
                            class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50 resize-none"><?= esc($fullUser['address'] ?? '') ?></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-coco-mid mb-1">City</label>
                            <input type="text" name="city" value="<?= esc($fullUser['city'] ?? '') ?>" placeholder="e.g. Quezon City"
                                class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-coco-mid mb-1">ZIP Code</label>
                            <input type="text" name="zip" value="<?= esc($fullUser['postal_code'] ?? '') ?>" placeholder="e.g. 1100"
                                class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-coco-mid mb-1">Contact Number</label>
                        <input type="tel" name="phone" value="<?= esc($fullUser['phone'] ?? '') ?>" placeholder="+63 9XX XXX XXXX"
                            class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-coco-mid mb-1">Order Notes <span class="font-normal">(optional)</span></label>
                        <textarea name="notes" rows="2" placeholder="Special instructions for your delivery..."
                            class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm text-coco-brown focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50 resize-none"></textarea>
                    </div>
                </div>

                <!-- Pickup info panel -->
                <div id="pickup-form" class="hidden px-6 pb-6">
                    <div class="bg-coco-green/8 border border-coco-leaf/30 rounded-2xl p-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-coco-orange mt-0.5"></i>
                            <div>
                                <div class="font-bold text-coco-brown text-sm mb-0.5">COCOIR Store</div>
                                <div class="text-coco-mid text-xs leading-relaxed">
                                    67 Conti Ave., Barangay Uxplorers,<br>
                                    Quezon City, Metro Manila 1100<br>
                                    Philippines
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-coco-orange mt-0.5"></i>
                            <div class="text-coco-mid text-xs leading-relaxed">
                                Mon–Sat: 9:00 AM – 6:00 PM<br>
                                Sun: 9:00 AM – 3:00 PM
                            </div>
                        </div>
                        <!-- Embedded map -->
                        <div class="rounded-2xl overflow-hidden border border-coco-sand h-52 w-full bg-coco-sand flex items-center justify-center">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.619!2d121.0437!3d14.6760!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDQwJzMzLjYiTiAxMjHCsDAyJzM3LjMiRQ!5e0!3m2!1sen!2sph!4v1"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" class="w-full h-full">
                            </iframe>
                        </div>
                        <div class="text-xs text-coco-mid font-semibold text-center">
                            <i class="fas fa-info-circle text-coco-orange mr-1"></i>
                            Your order will be ready for pickup within 1–2 business days. We'll email you when it's ready.
                        </div>
                    </div>

                    <!-- Contact for pickup -->
                    <div class="mt-4 space-y-3">
                        <h3 class="font-bold text-coco-brown text-sm tracking-wide">Your Contact Details</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-coco-mid mb-1">Name</label>
                                <input type="text" name="pickup_name" value="<?= esc(trim(($fullUser['first_name'] ?? $user['first_name'] ?? '') . ' ' . ($fullUser['last_name'] ?? $user['last_name'] ?? ''))) ?>"
                                    class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-coco-mid mb-1">Contact Number</label>
                                <input type="tel" name="pickup_phone" placeholder="+63 9XX XXX XXXX"
                                    class="w-full border-2 border-coco-sand rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-coco-orange transition-colors bg-coco-cream/50">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 2: Payment Method ── -->
            <div id="step2" class="bg-white rounded-3xl border border-coco-sand/60 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-coco-sand/40 flex items-center gap-3">
                    <span class="w-7 h-7 bg-coco-orange/10 rounded-full flex items-center justify-center text-coco-orange text-xs font-black">2</span>
                    <h2 class="font-display font-bold text-lg text-coco-brown">Payment Method</h2>
                </div>
                <div class="p-6 space-y-4">

                    <!-- GCash / InstaPay QR -->
                    <label class="method-card cursor-pointer block">
                        <input type="radio" name="payment_method" value="instapay" class="sr-only" checked onchange="switchPayment('instapay')">
                        <div class="card-body border-2 border-coco-orange bg-coco-orange/5 rounded-2xl p-4 flex items-center gap-4" id="card-instapay">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                <i class="fas fa-qrcode text-coco-orange text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-coco-brown text-sm">InstaPay / QR Ph</div>
                                <div class="text-coco-mid text-xs">GCash · Maya · BPI · UnionBank · and more</div>
                            </div>
                            <div class="ml-auto flex gap-1.5">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/GCash_logo.svg/120px-GCash_logo.svg.png" class="h-5 object-contain" alt="GCash" onerror="this.style.display='none'">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Maya_Logo.svg/120px-Maya_Logo.svg.png" class="h-5 object-contain" alt="Maya" onerror="this.style.display='none'">
                            </div>
                        </div>
                    </label>

                    <!-- COD -->
                    <label class="method-card cursor-pointer block">
                        <input type="radio" name="payment_method" value="cod" class="sr-only" onchange="switchPayment('cod')">
                        <div class="card-body border-2 border-coco-sand rounded-2xl p-4 flex items-center gap-4" id="card-cod">
                            <div class="w-10 h-10 bg-coco-green/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-coco-green text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-coco-brown text-sm">Cash on Delivery</div>
                                <div class="text-coco-mid text-xs">Pay in cash when your order arrives</div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- InstaPay QR panel -->
                <div id="panel-instapay" class="panel active px-6 pb-6">
                    <div class="bg-gradient-to-br from-coco-orange/5 to-coco-amber/5 border border-coco-orange/20 rounded-2xl p-6 flex flex-col items-center gap-4">
                        <p class="text-coco-brown text-sm font-semibold text-center">Scan to pay using GCash, Maya, or any InstaPay-enabled app</p>

                        <!-- QR Code placeholder — replace src with your actual QR -->
                        <div class="qr-pulse rounded-2xl overflow-hidden border-4 border-white shadow-xl">
                            <div class="w-48 h-48 bg-white flex items-center justify-center">
                                <!-- Replace this SVG with your actual QR image: <img src="/images/instapay-qr.png" class="w-full h-full object-contain"> -->
                                <svg viewBox="0 0 100 100" class="w-40 h-40" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Simple QR placeholder pattern -->
                                    <rect width="100" height="100" fill="white"/>
                                    <rect x="10" y="10" width="30" height="30" fill="none" stroke="#3B2314" stroke-width="3"/>
                                    <rect x="15" y="15" width="20" height="20" fill="#3B2314"/>
                                    <rect x="60" y="10" width="30" height="30" fill="none" stroke="#3B2314" stroke-width="3"/>
                                    <rect x="65" y="15" width="20" height="20" fill="#3B2314"/>
                                    <rect x="10" y="60" width="30" height="30" fill="none" stroke="#3B2314" stroke-width="3"/>
                                    <rect x="15" y="65" width="20" height="20" fill="#3B2314"/>
                                    <rect x="60" y="55" width="5" height="5" fill="#3B2314"/>
                                    <rect x="70" y="55" width="5" height="5" fill="#3B2314"/>
                                    <rect x="65" y="60" width="5" height="5" fill="#3B2314"/>
                                    <rect x="80" y="60" width="5" height="5" fill="#3B2314"/>
                                    <rect x="60" y="65" width="5" height="5" fill="#3B2314"/>
                                    <rect x="75" y="65" width="10" height="5" fill="#3B2314"/>
                                    <rect x="60" y="75" width="10" height="5" fill="#3B2314"/>
                                    <rect x="75" y="70" width="5" height="10" fill="#3B2314"/>
                                    <rect x="85" y="75" width="5" height="10" fill="#3B2314"/>
                                    <!-- Center COCOIR label -->
                                    <text x="50" y="54" text-anchor="middle" font-size="5" fill="#E87722" font-family="sans-serif" font-weight="bold">COCOIR</text>
                                </svg>
                            </div>
                        </div>

                        <div class="text-center space-y-1">
                            <p class="text-xs text-coco-mid">Account Name: <strong class="text-coco-brown">COCOIR Co.</strong></p>
                            <p class="text-xs text-coco-mid">Account Number: <strong class="text-coco-brown">09XX XXX XXXX</strong></p>
                        </div>

                        <div class="w-full bg-white border border-coco-sand rounded-xl p-3 text-xs text-coco-mid text-center">
                            <i class="fas fa-info-circle text-coco-orange mr-1"></i>
                            After payment, upload your screenshot below. Your order will be confirmed once payment is verified.
                        </div>

                        <!-- Screenshot upload -->
                        <div class="w-full">
                            <label class="block text-xs font-semibold text-coco-mid mb-2">Upload Payment Screenshot</label>
                            <label class="flex flex-col items-center gap-2 border-2 border-dashed border-coco-sand rounded-xl p-4 cursor-pointer hover:border-coco-orange transition-colors bg-coco-cream/30">
                                <i class="fas fa-cloud-upload-alt text-coco-tan text-xl"></i>
                                <span class="text-xs text-coco-mid">Click to upload or drag & drop</span>
                                <input type="file" name="payment_screenshot" accept="image/*" class="sr-only" onchange="previewScreenshot(this)">
                            </label>
                            <div id="screenshot-preview" class="hidden mt-2">
                                <img id="screenshot-img" class="rounded-xl max-h-32 object-contain border border-coco-sand">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COD panel -->
                <div id="panel-cod" class="panel px-6 pb-6">
                    <div class="bg-coco-green/8 border border-coco-leaf/30 rounded-2xl p-5 flex items-start gap-4">
                        <i class="fas fa-info-circle text-coco-green mt-0.5 flex-shrink-0"></i>
                        <div class="text-sm text-coco-dark leading-relaxed">
                            <strong>Cash on Delivery</strong> — You pay the exact amount in cash when your order arrives at your doorstep.<br>
                            <span class="text-coco-mid text-xs mt-1 block">Please prepare the exact amount. Our riders do not carry change.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Order Summary -->
        <div class="lg:col-span-1">
            <div class="sticky top-28 space-y-5">

                <!-- Summary card -->
                <div class="bg-white rounded-3xl border border-coco-sand/60 shadow-sm p-6">
                    <h3 class="font-display font-bold text-lg text-coco-brown mb-5 pb-4 border-b border-coco-sand/40">Order Summary</h3>

                    <!-- Items -->
                    <div class="space-y-3 mb-5 max-h-52 overflow-y-auto pr-1">
                        <?php foreach ($cartItems as $item): ?>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-coco-sand/30 flex-shrink-0">
                                <img src="/images/<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-coco-brown truncate"><?= esc($item['name']) ?></div>
                                <div class="text-[10px] text-coco-mid">Qty: <?= $item['quantity'] ?></div>
                            </div>
                            <div class="text-xs font-black text-coco-orange whitespace-nowrap">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="space-y-2 pb-4 border-b border-coco-sand/40 text-sm">
                        <div class="flex justify-between">
                            <span class="text-coco-mid">Subtotal</span>
                            <span class="font-bold text-coco-brown">₱<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between" id="shipping-row">
                            <span class="text-coco-mid">Shipping</span>
                            <span class="font-bold text-coco-brown" id="shipping-display">₱<?= number_format($shipping, 2) ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end pt-4 mb-6">
                        <span class="font-bold text-coco-brown">Total</span>
                        <span class="font-display font-black text-2xl text-coco-orange" id="total-display">₱<?= number_format($total, 2) ?></span>
                    </div>

                    <!-- Place Order button -->
                    <form id="checkout-form" action="<?= site_url('checkout/place') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="receive_method"   id="hidden-receive"  value="delivery">
                        <input type="hidden" name="payment_method"   id="hidden-payment"  value="instapay">
                        <input type="hidden" name="shipping_amount"  id="hidden-shipping" value="<?= $shipping ?>">
                        <button type="submit"
                            class="w-full bg-coco-orange hover:bg-coco-dark text-white font-bold py-4 rounded-full text-lg transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl shadow-lg shadow-coco-orange/25">
                            Place Order
                        </button>
                    </form>

                    <div class="flex items-center justify-center gap-2 mt-4 text-coco-mid/70 text-xs">
                        <i class="fas fa-shield-alt text-coco-green"></i>
                        Secure · Encrypted · Protected
                    </div>
                </div>

                <!-- Back to cart -->
                <a href="<?= site_url('cart') ?>" class="flex items-center justify-center gap-2 text-coco-mid text-sm hover:text-coco-orange transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Cart
                </a>
            </div>
        </div>
    </div>
</main>

<?= $this->include('components/footer') ?>

<script>
const subtotal = <?= $subtotal ?>;
const shipping = <?= $shipping ?>;

function switchReceive(method) {
    const deliveryForm = document.getElementById('delivery-form');
    const pickupForm   = document.getElementById('pickup-form');
    const shippingRow  = document.getElementById('shipping-display');
    const totalDisplay = document.getElementById('total-display');
    const hiddenReceive= document.getElementById('hidden-receive');
    const hiddenShipping = document.getElementById('hidden-shipping');

    // Card border styles
    document.getElementById('card-delivery').classList.toggle('border-coco-orange', method === 'delivery');
    document.getElementById('card-delivery').classList.toggle('bg-coco-orange/5', method === 'delivery');
    document.getElementById('card-delivery').classList.toggle('border-coco-sand', method !== 'delivery');
    document.getElementById('card-pickup').classList.toggle('border-coco-orange', method === 'pickup');
    document.getElementById('card-pickup').classList.toggle('bg-coco-orange/5', method === 'pickup');
    document.getElementById('card-pickup').classList.toggle('border-coco-sand', method !== 'pickup');

    if (method === 'delivery') {
        deliveryForm.classList.remove('hidden');
        pickupForm.classList.add('hidden');
        shippingRow.textContent = '₱150.00';
        totalDisplay.textContent = '₱' + (subtotal + 150).toLocaleString('en-PH', {minimumFractionDigits:2});
        hiddenShipping.value = 150;
    } else {
        deliveryForm.classList.add('hidden');
        pickupForm.classList.remove('hidden');
        shippingRow.textContent = 'FREE';
        totalDisplay.textContent = '₱' + subtotal.toLocaleString('en-PH', {minimumFractionDigits:2});
        hiddenShipping.value = 0;
    }
    hiddenReceive.value = method;
}

function switchPayment(method) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + method).classList.add('active');
    document.getElementById('hidden-payment').value = method;

    const instapay = document.getElementById('card-instapay');
    const cod      = document.getElementById('card-cod');

    instapay.classList.toggle('border-coco-orange', method === 'instapay');
    instapay.classList.toggle('bg-coco-orange/5',   method === 'instapay');
    instapay.classList.toggle('border-coco-sand',   method !== 'instapay');

    cod.classList.toggle('border-coco-orange', method === 'cod');
    cod.classList.toggle('bg-coco-orange/5',   method === 'cod');
    cod.classList.toggle('border-coco-sand',   method !== 'cod');
}

function previewScreenshot(input) {
    const preview = document.getElementById('screenshot-preview');
    const img     = document.getElementById('screenshot-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>