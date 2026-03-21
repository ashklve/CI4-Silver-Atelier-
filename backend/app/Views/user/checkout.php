<?php
/**
 * View: user/checkout.php
 * Controller: Users::checkout()
 * Route: GET /checkout
 * Protected: must be logged in
 */
$user      = session()->get('user');
$fullUser  = $fullUser ?? [];
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
    .step-active   { background:#E87722; color:#fff; border-color:#E87722; }
    .step-done     { background:#4A7C59; color:#fff; border-color:#4A7C59; }
    .step-inactive { background:#fff;    color:#C8956C; border-color:#EDE0CC; }

    .method-card input[type="radio"]:checked ~ .card-body {
        border-color: #E87722;
        background: #FFF8F2;
        box-shadow: 0 0 0 3px rgba(232,119,34,0.15);
    }
    .card-body { transition: all 0.2s ease; }

    .panel { display: none; }
    .panel.active { display: block; }

    @keyframes qrPulse { 0%,100%{box-shadow:0 0 0 0 rgba(232,119,34,0.35)} 50%{box-shadow:0 0 0 12px rgba(232,119,34,0)} }
    .qr-pulse { animation: qrPulse 2s ease-in-out infinite; }

    .fiber-bg {
        background: repeating-linear-gradient(
            108deg, transparent, transparent 2px,
            rgba(139,94,60,0.03) 2px, rgba(139,94,60,0.03) 4px
        ), #FAF3E8;
    }
</style>

<main class="flex-grow mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 w-full max-w-7xl">

    <!-- Page title -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 mb-3 text-coco-mid text-xs">
            <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
            <i class="fa-chevron-right text-[9px] fas"></i>
            <a href="<?= site_url('cart') ?>" class="hover:text-coco-orange transition-colors">Cart</a>
            <i class="fa-chevron-right text-[9px] fas"></i>
            <span class="font-semibold text-coco-orange">Checkout</span>
        </nav>
        <h1 class="font-display font-black text-coco-brown text-4xl sm:text-5xl">Checkout</h1>
    </div>

    <!-- Step indicator -->
    <div class="flex items-center gap-0 mb-10 max-w-sm">
        <div class="flex items-center gap-2">
            <div class="flex justify-center items-center border-2 rounded-full w-8 h-8 font-black text-xs step-active">1</div>
            <span class="hidden sm:block font-bold text-coco-orange text-xs">Delivery</span>
        </div>
        <div class="flex-1 bg-coco-sand mx-2 h-0.5"></div>
        <div class="flex items-center gap-2">
            <div class="flex justify-center items-center border-2 rounded-full w-8 h-8 font-black text-xs step-inactive" id="step2-indicator">2</div>
            <span class="hidden sm:block font-bold text-coco-mid text-xs" id="step2-label">Payment</span>
        </div>
        <div class="flex-1 bg-coco-sand mx-2 h-0.5"></div>
        <div class="flex items-center gap-2">
            <div class="flex justify-center items-center border-2 rounded-full w-8 h-8 font-black text-xs step-inactive" id="step3-indicator">3</div>
            <span class="hidden sm:block font-bold text-coco-mid text-xs">Confirm</span>
        </div>
    </div>

    <!-- ══════════════════════ MAIN FORM ══════════════════════ -->
    <!-- The entire form wraps both columns so ALL fields are submitted -->
    <form id="checkout-form" action="<?= site_url('checkout/place') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Hidden fields synced via JS before submit -->
        <input type="hidden" name="receive_method"  id="hidden-receive"     value="delivery">
        <input type="hidden" name="payment_method"  id="hidden-payment"     value="instapay">
        <input type="hidden" name="shipping_amount" id="hidden-shipping"    value="<?= $shipping ?>">

        <div class="gap-10 grid grid-cols-1 lg:grid-cols-3">

            <!-- LEFT: Steps -->
            <div class="space-y-6 lg:col-span-2">

                <!-- ── STEP 1: Delivery Method ── -->
                <div id="step1" class="bg-white shadow-sm border border-coco-sand/60 rounded-3xl overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-coco-sand/40 border-b">
                        <span class="flex justify-center items-center bg-coco-orange/10 rounded-full w-7 h-7 font-black text-coco-orange text-xs">1</span>
                        <h2 class="font-display font-bold text-coco-brown text-lg">How do you want to receive your order?</h2>
                    </div>
                    <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 p-6">

                        <!-- Delivery option -->
                        <label class="cursor-pointer method-card">
                            <input type="radio" name="_receive_method_ui" value="delivery" class="sr-only" checked onchange="switchReceive('delivery')">
                            <div class="flex flex-col gap-2 bg-coco-orange/5 p-5 border-2 border-coco-orange rounded-2xl card-body" id="card-delivery">
                                <div class="flex items-center gap-3">
                                    <div class="flex justify-center items-center bg-coco-orange/10 rounded-xl w-10 h-10">
                                        <i class="text-coco-orange fas fa-truck"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-coco-brown text-sm">Home Delivery</div>
                                        <div class="text-coco-mid text-xs">We deliver to your address</div>
                                    </div>
                                </div>
                                <div class="font-semibold text-coco-green text-xs">₱150 shipping fee</div>
                            </div>
                        </label>

                        <!-- Pickup option -->
                        <label class="cursor-pointer method-card">
                            <input type="radio" name="_receive_method_ui" value="pickup" class="sr-only" onchange="switchReceive('pickup')">
                            <div class="flex flex-col gap-2 p-5 border-2 border-coco-sand rounded-2xl card-body" id="card-pickup">
                                <div class="flex items-center gap-3">
                                    <div class="flex justify-center items-center bg-coco-green/10 rounded-xl w-10 h-10">
                                        <i class="text-coco-green fas fa-store"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-coco-brown text-sm">Store Pickup</div>
                                        <div class="text-coco-mid text-xs">Pick up at our shop — FREE</div>
                                    </div>
                                </div>
                                <div class="font-semibold text-coco-green text-xs">Free · No shipping fee</div>
                            </div>
                        </label>
                    </div>

                    <!-- Delivery address form -->
                    <div id="delivery-form" class="space-y-4 px-6 pb-6">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-coco-brown text-sm tracking-wide">Delivery Address</h3>
                            <?php if (!empty($fullUser['address'])): ?>
                            <span class="flex items-center gap-1.5 bg-coco-green/10 px-3 py-1 rounded-full font-bold text-[10px] text-coco-green">
                                <i class="text-[9px] fas fa-check-circle"></i> Saved address loaded
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="gap-4 grid grid-cols-1 sm:grid-cols-2">
                            <div>
                                <label class="block mb-1 font-semibold text-coco-mid text-xs">First Name</label>
                                <input type="text" name="first_name" id="field-first_name"
                                       value="<?= esc($fullUser['first_name'] ?? $user['first_name'] ?? '') ?>"
                                       class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-coco-mid text-xs">Last Name</label>
                                <input type="text" name="last_name" id="field-last_name"
                                       value="<?= esc($fullUser['last_name'] ?? $user['last_name'] ?? '') ?>"
                                       class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-coco-mid text-xs">Email</label>
                            <input type="email" name="email" id="field-email"
                                   value="<?= esc($fullUser['email'] ?? $user['email'] ?? '') ?>"
                                   class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-coco-mid text-xs">Complete Address</label>
                            <textarea name="address" id="field-address" rows="2"
                                class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors resize-none"><?= esc($fullUser['address'] ?? '') ?></textarea>
                        </div>
                        <div class="gap-4 grid grid-cols-1 sm:grid-cols-2">
                            <div>
                                <label class="block mb-1 font-semibold text-coco-mid text-xs">City</label>
                                <input type="text" name="city" id="field-city"
                                       value="<?= esc($fullUser['city'] ?? '') ?>" placeholder="e.g. Quezon City"
                                       class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-coco-mid text-xs">ZIP Code</label>
                                <input type="text" name="zip" id="field-zip"
                                       value="<?= esc($fullUser['postal_code'] ?? '') ?>" placeholder="e.g. 1100"
                                       class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-coco-mid text-xs">Contact Number</label>
                            <input type="tel" name="phone" id="field-phone"
                                   value="<?= esc($fullUser['phone'] ?? '') ?>" placeholder="+63 9XX XXX XXXX"
                                   class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-coco-mid text-xs">Order Notes <span class="font-normal">(optional)</span></label>
                            <textarea name="notes" id="field-notes" rows="2" placeholder="Special instructions for your delivery..."
                                class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-coco-brown text-sm transition-colors resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Pickup info panel -->
                    <div id="pickup-form" class="hidden px-6 pb-6">
                        <div class="space-y-4 bg-coco-green/8 p-5 border border-coco-leaf/30 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <i class="mt-0.5 text-coco-orange fas fa-map-marker-alt"></i>
                                <div>
                                    <div class="mb-0.5 font-bold text-coco-brown text-sm">COCOIR Store</div>
                                    <div class="text-coco-mid text-xs leading-relaxed">
                                        67 Conti Ave., Barangay Uxplorers,<br>
                                        Quezon City, Metro Manila 1100<br>
                                        Philippines
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="mt-0.5 text-coco-orange fas fa-clock"></i>
                                <div class="text-coco-mid text-xs leading-relaxed">
                                    Mon–Sat: 9:00 AM – 6:00 PM<br>
                                    Sun: 9:00 AM – 3:00 PM
                                </div>
                            </div>
                            <div class="flex justify-center items-center bg-coco-sand border border-coco-sand rounded-2xl w-full h-52 overflow-hidden">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.619!2d121.0437!3d14.6760!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDQwJzMzLjYiTiAxMjHCsDAyJzM3LjMiRQ!5e0!3m2!1sen!2sph!4v1"
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade" class="w-full h-full">
                                </iframe>
                            </div>
                            <div class="font-semibold text-coco-mid text-xs text-center">
                                <i class="mr-1 text-coco-orange fas fa-info-circle"></i>
                                Your order will be ready for pickup within 1–2 business days.
                            </div>
                        </div>

                        <!-- Pickup contact fields -->
                        <div class="space-y-3 mt-4">
                            <h3 class="font-bold text-coco-brown text-sm tracking-wide">Your Contact Details</h3>
                            <div class="gap-3 grid grid-cols-1 sm:grid-cols-2">
                                <div>
                                    <label class="block mb-1 font-semibold text-coco-mid text-xs">Name</label>
                                    <input type="text" name="pickup_name" id="field-pickup_name"
                                           value="<?= esc(trim(($fullUser['first_name'] ?? $user['first_name'] ?? '') . ' ' . ($fullUser['last_name'] ?? $user['last_name'] ?? ''))) ?>"
                                           class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors">
                                </div>
                                <div>
                                    <label class="block mb-1 font-semibold text-coco-mid text-xs">Contact Number</label>
                                    <input type="tel" name="pickup_phone" id="field-pickup_phone"
                                           value="<?= esc($fullUser['phone'] ?? '') ?>"
                                           placeholder="+63 9XX XXX XXXX"
                                           class="bg-coco-cream/50 px-4 py-2.5 border-2 border-coco-sand focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── STEP 2: Payment Method ── -->
                <div id="step2" class="bg-white shadow-sm border border-coco-sand/60 rounded-3xl overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-coco-sand/40 border-b">
                        <span class="flex justify-center items-center bg-coco-orange/10 rounded-full w-7 h-7 font-black text-coco-orange text-xs">2</span>
                        <h2 class="font-display font-bold text-coco-brown text-lg">Payment Method</h2>
                    </div>
                    <div class="space-y-4 p-6">

                        <!-- InstaPay / QR -->
                        <label class="block cursor-pointer method-card">
                            <input type="radio" name="_payment_method_ui" value="instapay" class="sr-only" checked onchange="switchPayment('instapay')">
                            <div class="flex items-center gap-4 bg-coco-orange/5 p-4 border-2 border-coco-orange rounded-2xl card-body" id="card-instapay">
                                <div class="flex flex-shrink-0 justify-center items-center bg-white shadow-sm rounded-xl w-10 h-10">
                                    <i class="text-coco-orange text-lg fas fa-qrcode"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-coco-brown text-sm">InstaPay / QR Ph</div>
                                    <div class="text-coco-mid text-xs">GCash · Maya · BPI · UnionBank · and more</div>
                                </div>
                                <div class="flex gap-1.5 ml-auto">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/GCash_logo.svg/120px-GCash_logo.svg.png" class="h-5 object-contain" alt="GCash" onerror="this.style.display='none'">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Maya_Logo.svg/120px-Maya_Logo.svg.png" class="h-5 object-contain" alt="Maya" onerror="this.style.display='none'">
                                </div>
                            </div>
                        </label>

                        <!-- COD -->
                        <label class="block cursor-pointer method-card">
                            <input type="radio" name="_payment_method_ui" value="cod" class="sr-only" onchange="switchPayment('cod')">
                            <div class="flex items-center gap-4 p-4 border-2 border-coco-sand rounded-2xl card-body" id="card-cod">
                                <div class="flex flex-shrink-0 justify-center items-center bg-coco-green/10 rounded-xl w-10 h-10">
                                    <i class="text-coco-green text-lg fas fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-coco-brown text-sm">Cash on Delivery</div>
                                    <div class="text-coco-mid text-xs">Pay in cash when your order arrives</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- InstaPay QR panel -->
                    <div id="panel-instapay" class="px-6 pb-6 panel active">
                        <div class="flex flex-col items-center gap-4 bg-gradient-to-br from-coco-orange/5 to-coco-amber/5 p-6 border border-coco-orange/20 rounded-2xl">
                            <p class="font-semibold text-coco-brown text-sm text-center">Scan to pay using GCash, Maya, or any InstaPay-enabled app</p>

                            <div class="shadow-xl border-4 border-white rounded-2xl overflow-hidden qr-pulse">
                                <div class="flex justify-center items-center bg-white w-48 h-48">
                                    <svg viewBox="0 0 100 100" class="w-40 h-40" xmlns="http://www.w3.org/2000/svg">
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
                                        <text x="50" y="54" text-anchor="middle" font-size="5" fill="#E87722" font-family="sans-serif" font-weight="bold">COCOIR</text>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-1 text-center">
                                <p class="text-coco-mid text-xs">Account Name: <strong class="text-coco-brown">COCOIR Co.</strong></p>
                                <p class="text-coco-mid text-xs">Account Number: <strong class="text-coco-brown">09XX XXX XXXX</strong></p>
                            </div>

                            <div class="bg-white p-3 border border-coco-sand rounded-xl w-full text-coco-mid text-xs text-center">
                                <i class="mr-1 text-coco-orange fas fa-info-circle"></i>
                                After payment, upload your screenshot below. Your order will be confirmed once payment is verified.
                            </div>

                            <!-- Screenshot upload — name="payment_screenshot" is inside the form now -->
                            <div class="w-full">
                                <label class="block mb-2 font-semibold text-coco-mid text-xs">Upload Payment Screenshot</label>
                                <label class="flex flex-col items-center gap-2 bg-coco-cream/30 p-4 border-2 border-coco-sand hover:border-coco-orange border-dashed rounded-xl transition-colors cursor-pointer">
                                    <i class="text-coco-tan text-xl fas fa-cloud-upload-alt"></i>
                                    <span class="text-coco-mid text-xs">Click to upload or drag & drop</span>
                                    <input type="file" name="payment_screenshot" accept="image/*" class="sr-only" onchange="previewScreenshot(this)">
                                </label>
                                <div id="screenshot-preview" class="hidden mt-2">
                                    <img id="screenshot-img" class="border border-coco-sand rounded-xl max-h-32 object-contain">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COD panel -->
                    <div id="panel-cod" class="px-6 pb-6 panel">
                        <div class="flex items-start gap-4 bg-coco-green/8 p-5 border border-coco-leaf/30 rounded-2xl">
                            <i class="flex-shrink-0 mt-0.5 text-coco-green fas fa-info-circle"></i>
                            <div class="text-coco-dark text-sm leading-relaxed">
                                <strong>Cash on Delivery</strong> — You pay the exact amount in cash when your order arrives.<br>
                                <span class="block mt-1 text-coco-mid text-xs">Please prepare the exact amount. Our riders do not carry change.</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /left col -->

            <!-- RIGHT: Order Summary -->
            <div class="lg:col-span-1">
                <div class="top-28 sticky space-y-5">
                    <div class="bg-white shadow-sm p-6 border border-coco-sand/60 rounded-3xl">
                        <h3 class="mb-5 pb-4 border-coco-sand/40 border-b font-display font-bold text-coco-brown text-lg">Order Summary</h3>

                        <!-- Items -->
                        <div class="space-y-3 mb-5 pr-1 max-h-52 overflow-y-auto">
                            <?php foreach ($cartItems as $item): ?>
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 bg-coco-sand/30 rounded-xl w-12 h-12 overflow-hidden">
                                    <img src="/images/<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-coco-brown text-xs truncate"><?= esc($item['name']) ?></div>
                                    <div class="text-[10px] text-coco-mid">Qty: <?= $item['quantity'] ?></div>
                                </div>
                                <div class="font-black text-coco-orange text-xs whitespace-nowrap">
                                    ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-2 pb-4 border-coco-sand/40 border-b text-sm">
                            <div class="flex justify-between">
                                <span class="text-coco-mid">Subtotal</span>
                                <span class="font-bold text-coco-brown">₱<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-coco-mid">Shipping</span>
                                <span class="font-bold text-coco-brown" id="shipping-display">₱<?= number_format($shipping, 2) ?></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-6 pt-4">
                            <span class="font-bold text-coco-brown">Total</span>
                            <span class="font-display font-black text-coco-orange text-2xl" id="total-display">
                                ₱<?= number_format($total, 2) ?>
                            </span>
                        </div>

                        <button type="submit"
                            class="bg-coco-orange hover:bg-coco-dark shadow-coco-orange/25 shadow-lg hover:shadow-xl py-4 rounded-full w-full font-bold text-white text-lg transition-all hover:-translate-y-0.5 duration-300">
                            Place Order
                        </button>

                        <div class="flex justify-center items-center gap-2 mt-4 text-coco-mid/70 text-xs">
                            <i class="text-coco-green fas fa-shield-alt"></i>
                            Secure · Encrypted · Protected
                        </div>
                    </div>

                    <a href="<?= site_url('cart') ?>" class="flex justify-center items-center gap-2 text-coco-mid hover:text-coco-orange text-sm transition-colors">
                        <i class="fa-arrow-left text-xs fas"></i> Back to Cart
                    </a>
                </div>
            </div>

        </div><!-- /grid -->
    </form><!-- /checkout-form -->

</main>

<?= $this->include('components/footer') ?>

<script>
const subtotal = <?= $subtotal ?>;
const shipping = <?= $shipping ?>;

// ── Sync visible fields into hidden inputs before submit ──
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const receive = document.getElementById('hidden-receive').value;

    if (receive === 'pickup') {
        // For pickup, use pickup contact fields
        const pickupName  = document.getElementById('field-pickup_name');
        const pickupPhone = document.getElementById('field-pickup_phone');
        // Split name into first/last best-effort
        const nameParts = (pickupName?.value ?? '').trim().split(' ');
        document.querySelector('[name="first_name"]') && (document.querySelector('[name="first_name"]').value = nameParts[0] ?? '');
        document.querySelector('[name="last_name"]')  && (document.querySelector('[name="last_name"]').value  = nameParts.slice(1).join(' ') ?? '');
        document.querySelector('[name="phone"]')      && (document.querySelector('[name="phone"]').value      = pickupPhone?.value ?? '');
        document.querySelector('[name="address"]')    && (document.querySelector('[name="address"]').value    = 'Store Pickup — COCOIR, 67 Conti Ave., Quezon City');
        document.querySelector('[name="city"]')       && (document.querySelector('[name="city"]').value       = 'Quezon City');
        document.querySelector('[name="zip"]')        && (document.querySelector('[name="zip"]').value        = '1100');
    }
    // Delivery fields are already named correctly and inside the form — no extra sync needed
});

function switchReceive(method) {
    const deliveryForm  = document.getElementById('delivery-form');
    const pickupForm    = document.getElementById('pickup-form');
    const shippingEl    = document.getElementById('shipping-display');
    const totalEl       = document.getElementById('total-display');
    const hiddenReceive  = document.getElementById('hidden-receive');
    const hiddenShipping = document.getElementById('hidden-shipping');

    document.getElementById('card-delivery').classList.toggle('border-coco-orange', method === 'delivery');
    document.getElementById('card-delivery').classList.toggle('bg-coco-orange/5',   method === 'delivery');
    document.getElementById('card-delivery').classList.toggle('border-coco-sand',   method !== 'delivery');
    document.getElementById('card-pickup').classList.toggle('border-coco-orange',   method === 'pickup');
    document.getElementById('card-pickup').classList.toggle('bg-coco-orange/5',     method === 'pickup');
    document.getElementById('card-pickup').classList.toggle('border-coco-sand',     method !== 'pickup');

    if (method === 'delivery') {
        deliveryForm.classList.remove('hidden');
        pickupForm.classList.add('hidden');
        shippingEl.textContent   = '₱150.00';
        totalEl.textContent      = '₱' + (subtotal + 150).toLocaleString('en-PH', { minimumFractionDigits: 2 });
        hiddenShipping.value     = 150;
    } else {
        deliveryForm.classList.add('hidden');
        pickupForm.classList.remove('hidden');
        shippingEl.textContent   = 'FREE';
        totalEl.textContent      = '₱' + subtotal.toLocaleString('en-PH', { minimumFractionDigits: 2 });
        hiddenShipping.value     = 0;
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
    cod.classList.toggle('border-coco-orange',      method === 'cod');
    cod.classList.toggle('bg-coco-orange/5',        method === 'cod');
    cod.classList.toggle('border-coco-sand',        method !== 'cod');
}

function previewScreenshot(input) {
    const preview = document.getElementById('screenshot-preview');
    const img     = document.getElementById('screenshot-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>