<?php
/**
 * View: user/cart.php
 * The checkout() button now checks if user is logged in client-side
 * and redirects accordingly. The server also enforces the check in Users::checkout()
 */
$cartItems = $cartItems ?? [];
$subtotal  = 0;
$shipping  = 150.00;
$isLoggedIn = session()->has('user'); // pass login state to JS
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Your Cart - COCOIR']) ?>

<body class="flex flex-col bg-coco-cream min-h-screen font-body text-coco-brown">
    <?= $this->include('components/header') ?>
    <input type="hidden" class="csrf-token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

    <main class="flex-grow mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 w-full max-w-7xl">
        <div class="mb-10 lg:text-left text-center">
            <h1 class="mb-2 font-display font-black text-coco-brown text-4xl sm:text-5xl">Your Cart</h1>
            <p class="text-coco-mid">Review your sustainable picks before checkout.</p>
        </div>

        <!-- Empty State -->
        <div id="cart-empty" class="<?= empty($cartItems) ? 'flex' : 'hidden' ?> flex-col justify-center items-center py-20 text-center animate-fade-in">
            <div class="flex justify-center items-center bg-coco-sand/30 mb-6 rounded-full w-24 h-24 text-4xl animate-float">🥥</div>
            <h2 class="mb-2 font-display font-bold text-coco-brown text-2xl">Your cart is empty</h2>
            <p class="mb-8 text-coco-mid">Looks like you haven't added any coconut goodness yet.</p>
            <a href="<?= site_url('products') ?>" class="bg-coco-orange hover:bg-coco-dark shadow-lg hover:shadow-xl px-8 py-3 rounded-full font-bold text-white transition-colors hover:-translate-y-1 duration-300 transform">
                Start Shopping
            </a>
        </div>

        <!-- Cart Content -->
        <div id="cart-content" class="<?= empty($cartItems) ? 'hidden' : 'grid' ?> gap-10 grid grid-cols-1 lg:grid-cols-3 animate-fade-up">

            <!-- Items List -->
            <div class="space-y-6 lg:col-span-2" id="cart-items-container">
                <?php foreach ($cartItems as $item): ?>
                    <?php
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                    ?>
                    <div class="flex sm:flex-row flex-col gap-6 bg-white hover:shadow-md p-5 border border-coco-sand/40 hover:border-coco-orange/30 rounded-3xl transition-all duration-300 cart-item" data-id="<?= $item['id'] ?>">
                        <div class="group relative flex-shrink-0 bg-coco-cream rounded-2xl w-full sm:w-28 h-28 overflow-hidden">
                            <img src="/images/<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex flex-col flex-1 justify-between">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-display font-bold text-coco-brown text-xl"><?= esc($item['name']) ?></h4>
                                    <p class="text-coco-mid text-sm">Unit Price: ₱<?= number_format($item['price'], 2) ?></p>
                                </div>
                                <button onclick="removeItem('<?= $item['id'] ?>')" class="flex justify-center items-center hover:bg-red-50 rounded-full w-8 h-8 text-coco-mid/50 hover:text-red-500 transition-colors" title="Remove Item">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <div class="flex flex-wrap justify-between items-center gap-4 mt-4 sm:mt-0">
                                <div class="flex items-center gap-3 bg-coco-sand/20 px-1 py-1 border border-coco-sand rounded-full">
                                    <button onclick="updateQty('<?= $item['id'] ?>', <?= $item['quantity'] - 1 ?>)" class="flex justify-center items-center hover:bg-white hover:shadow-sm rounded-full w-8 h-8 font-bold text-coco-brown transition-all">-</button>
                                    <input type="number" value="<?= $item['quantity'] ?>" onchange="updateQty('<?= $item['id'] ?>', this.value)" class="bg-transparent focus:outline-none w-10 font-bold text-coco-brown text-sm text-center quantity-input">
                                    <button onclick="updateQty('<?= $item['id'] ?>', <?= $item['quantity'] + 1 ?>)" class="flex justify-center items-center hover:bg-white hover:shadow-sm rounded-full w-8 h-8 font-bold text-coco-brown transition-all">+</button>
                                </div>
                                <div class="font-display font-black text-coco-orange text-lg">
                                    ₱<?= number_format($itemTotal, 2) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="top-28 sticky bg-white shadow-lg p-6 border border-coco-sand/60 rounded-3xl">
                    <h3 class="mb-6 pb-4 border-coco-sand/60 border-b font-display font-bold text-coco-brown text-xl">Order Summary</h3>
                    <div class="space-y-4 mb-6 pb-6 border-coco-sand/60 border-b">
                        <div class="flex justify-between text-sm">
                            <span class="text-coco-mid">Subtotal</span>
                            <span class="font-bold text-coco-brown" id="summary-subtotal">₱<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-coco-mid">Shipping Estimate</span>
                            <span class="font-bold text-coco-brown">₱<?= number_format($shipping, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-coco-mid">Tax</span>
                            <span class="text-coco-mid italic">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-8">
                        <span class="font-bold text-coco-brown">Total</span>
                        <span class="font-display font-black text-coco-orange text-3xl leading-none" id="summary-total">
                            ₱<?= number_format($subtotal + $shipping, 2) ?>
                        </span>
                    </div>

                    <!-- ── CHECKOUT BUTTON: checks login state ── -->
                    <button onclick="proceedToCheckout()"
                        class="bg-coco-brown hover:bg-coco-orange hover:shadow-lg py-4 rounded-full w-full font-bold text-white text-lg transition-all hover:-translate-y-1 duration-300 transform">
                        Checkout
                    </button>

                    <div class="flex justify-center items-center gap-2 mt-6 text-coco-mid/70 text-xs">
                        <i class="text-coco-green fas fa-lock"></i> Secure Checkout
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?= $this->include('components/footer') ?>

    <!-- Login Prompt Modal (shown when guest tries to checkout) -->
    <div id="login-modal" class="fixed inset-0 z-[9997] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-coco-brown/40 backdrop-blur-sm" onclick="closeLoginModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center space-y-5">
            <div class="w-16 h-16 bg-coco-orange/10 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-lock text-coco-orange text-2xl"></i>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-coco-brown mb-2">Sign In Required</h3>
                <p class="text-coco-mid text-sm leading-relaxed">
                    Please sign in to your COCOIR account to proceed with checkout. Your cart will be saved!
                </p>
            </div>
            <div class="flex flex-col gap-3">
                <a href="<?= site_url('login') ?>"
                   class="bg-coco-orange text-white font-bold py-3 rounded-full hover:bg-coco-dark transition-colors">
                    Sign In
                </a>
                <a href="<?= site_url('signup') ?>"
                   class="border-2 border-coco-sand text-coco-dark font-bold py-3 rounded-full hover:border-coco-orange hover:text-coco-orange transition-colors text-sm">
                    Create Account
                </a>
                <button onclick="closeLoginModal()" class="text-coco-mid text-sm hover:text-coco-orange transition-colors">
                    Continue Browsing
                </button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="right-6 bottom-6 z-[9998] fixed opacity-0 transition-all translate-y-24 duration-300">
        <div class="flex items-center gap-3 bg-coco-brown shadow-2xl px-6 py-3 rounded-2xl font-semibold text-coco-cream text-sm">
            <i class="text-coco-leaf fas fa-check-circle"></i>
            <span id="toast-msg">Updated!</span>
        </div>
    </div>

    <script>
        // ── Is user logged in? (from PHP session) ──
        const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
        const checkoutUrl = '<?= site_url('checkout') ?>';

        function proceedToCheckout() {
            if (isLoggedIn) {
                window.location.href = checkoutUrl;
            } else {
                // Show login prompt modal
                const modal = document.getElementById('login-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeLoginModal() {
            const modal = document.getElementById('login-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLoginModal();
        });

        // ── Cart API ──
        async function updateCartAPI(url, data) {
            const csrfToken = document.querySelector('.csrf-token');
            data.append(csrfToken.name, csrfToken.value);
            try {
                const response = await fetch(url, {
                    method: 'POST', body: data,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert(result.message || 'An error occurred.');
                    if (response.status === 403) window.location.reload();
                }
            } catch (error) {
                console.error('Cart API Error:', error);
                alert('Could not update cart. Please try again.');
            }
        }

        function updateQty(id, quantity) {
            const q = parseInt(quantity, 10);
            if (isNaN(q) || q < 1) { removeItem(id); return; }
            const fd = new FormData();
            fd.append('id', id);
            fd.append('quantity', q);
            updateCartAPI('<?= site_url('cart/update') ?>', fd);
        }

        function removeItem(id) {
            if (!confirm('Remove this item from your cart?')) return;
            const fd = new FormData();
            fd.append('id', id);
            updateCartAPI('<?= site_url('cart/remove') ?>', fd);
        }
    </script>
</body>
</html>