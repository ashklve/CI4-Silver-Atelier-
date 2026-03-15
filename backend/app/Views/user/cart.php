<?php

$cartItems = $cartItems ?? [];
$subtotal = 0;
$shipping = 150.00;

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

            <!-- Summary -->
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
                        <span class="font-display font-black text-coco-orange text-3xl leading-none" id="summary-total">₱<?= number_format($subtotal + $shipping, 2) ?></span>
                    </div>

                    <button onclick="checkout()" class="bg-coco-brown hover:bg-coco-orange hover:shadow-lg py-4 rounded-full w-full font-bold text-white text-lg transition-all hover:-translate-y-1 duration-300 transform">
                        Checkout
                    </button>

                    <div class="flex justify-center items-center gap-2 mt-6 text-coco-mid/70 text-xs">
                        <i class="text-coco-green fas fa-lock"></i> Secure Checkout via PayMongo
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?= $this->include('components/footer') ?>

    <!-- Toast Notification (Reused) -->
    <div id="toast" class="right-6 bottom-6 z-[9998] fixed opacity-0 transition-all translate-y-24 duration-300">
        <div class="flex items-center gap-3 bg-coco-brown shadow-2xl px-6 py-3 rounded-2xl font-semibold text-coco-cream text-sm">
            <i class="text-coco-leaf fas fa-check-circle"></i>
            <span id="toast-msg">Updated!</span>
        </div>
    </div>

    <script>
        // Cart Logic
        const cartKey = 'cocoir_cart';

        function getCart() {
            return JSON.parse(localStorage.getItem(cartKey)) || [];
        }

        function saveCart(cart) {
            localStorage.setItem(cartKey, JSON.stringify(cart));
            renderCart();
            // Dispatch event for header count
            window.dispatchEvent(new Event('cartUpdated'));
        }

        function formatPrice(price) {
            return '₱' + parseFloat(price).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function renderCart() {
            const cart = getCart();
            const container = document.getElementById('cart-items-container');
            const content = document.getElementById('cart-content');
            const empty = document.getElementById('cart-empty');

            if (cart.length === 0) {
                content.classList.add('hidden');
                empty.classList.remove('hidden');
                empty.classList.add('flex');
                return;
            }

            content.classList.remove('hidden');
            empty.classList.add('hidden');
            empty.classList.remove('flex');

            container.innerHTML = '';
            let subtotal = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                container.innerHTML += `
                    <div class="flex sm:flex-row flex-col gap-6 bg-white hover:shadow-md p-5 border border-coco-sand/40 hover:border-coco-orange/30 rounded-3xl transition-all duration-300">
                        <!-- Image -->
                        <div class="group relative flex-shrink-0 bg-coco-cream rounded-2xl w-full sm:w-28 h-28 overflow-hidden">
                            <img src="/images/${item.image}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        
                        <!-- Details -->
                        <div class="flex flex-col flex-1 justify-between">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-display font-bold text-coco-brown text-xl">${item.name}</h4>
                                    <p class="text-coco-mid text-sm">Unit Price: ${formatPrice(item.price)}</p>
                                </div>
                                <button onclick="removeItem(${index})" class="flex justify-center items-center hover:bg-red-50 rounded-full w-8 h-8 text-coco-mid/50 hover:text-red-500 transition-colors" title="Remove Item">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            
                            <div class="flex flex-wrap justify-between items-center gap-4 mt-4 sm:mt-0">
                                <div class="flex items-center gap-3 bg-coco-sand/20 px-1 py-1 border border-coco-sand rounded-full">
                                    <button onclick="updateQty(${index}, -1)" class="flex justify-center items-center hover:bg-white hover:shadow-sm rounded-full w-8 h-8 font-bold text-coco-brown transition-all">-</button>
                                    <span class="w-6 font-bold text-coco-brown text-sm text-center">${item.quantity}</span>
                                    <button onclick="updateQty(${index}, 1)" class="flex justify-center items-center hover:bg-white hover:shadow-sm rounded-full w-8 h-8 font-bold text-coco-brown transition-all">+</button>
                                </div>
                                <div class="font-display font-black text-coco-orange text-lg">
                                    ${formatPrice(itemTotal)}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Update Summary
            const shipping = 150;
            document.getElementById('summary-subtotal').textContent = formatPrice(subtotal);
            document.getElementById('summary-total').textContent = formatPrice(subtotal + shipping);
        }

        function updateQty(index, change) {
            const cart = getCart();
            if (cart[index]) {
                cart[index].quantity += change;
                if (cart[index].quantity < 1) cart[index].quantity = 1;
                saveCart(cart);
            }
        }

        function removeItem(index) {
            const cart = getCart();
            cart.splice(index, 1);
            saveCart(cart);
        }

        function checkout() {
            alert('Proceeding to checkout...');
            // Redirect to checkout controller logic
        }

        // Init
        document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>

</html>