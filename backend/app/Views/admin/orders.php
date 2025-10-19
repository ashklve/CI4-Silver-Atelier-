<!DOCTYPE html>
<html lang="en">
<?= view('components/head', ['title' => 'Admin Dashboard - Silver Atelier']) ?>
<body class="font-sans bg-light-cream min-h-screen">
    
    <?= view('components/admin_header') ?>

    <div class="max-w-7xl mx-auto p-8">
        <!-- Header with Filters -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-warm-brown">Customer Orders</h2>
            <div class="flex items-center space-x-4">
                <select class="px-4 py-2 border-2 border-cream-beige rounded-lg focus:outline-none focus:border-warm-brown">
                    <option>All Orders</option>
                    <option>Pending</option>
                    <option>Processing</option>
                    <option>Shipped</option>
                    <option>Delivered</option>
                    <option>Cancelled</option>
                </select>
                <input 
                    type="date" 
                    class="px-4 py-2 border-2 border-cream-beige rounded-lg focus:outline-none focus:border-warm-brown"
                >
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-cream-beige p-4 rounded-lg shadow">
                <p class="text-sage-green text-sm">Total Orders</p>
                <p class="text-2xl font-bold text-warm-brown"><?= $totalOrders ?? 0 ?></p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                <p class="text-yellow-700 text-sm">Pending</p>
                <p class="text-2xl font-bold text-yellow-800"><?= $pendingOrders ?? 0 ?></p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <p class="text-blue-700 text-sm">Processing</p>
                <p class="text-2xl font-bold text-blue-800"><?= $processingOrders ?? 0 ?></p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <p class="text-green-700 text-sm">Delivered</p>
                <p class="text-2xl font-bold text-green-800"><?= $deliveredOrders ?? 0 ?></p>
            </div>
            <div class="bg-red-100 p-4 rounded-lg shadow">
                <p class="text-red-700 text-sm">Cancelled</p>
                <p class="text-2xl font-bold text-red-800"><?= $cancelledOrders ?? 0 ?></p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-cream-beige rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-warm-brown text-light-cream">
                    <tr>
                        <th class="p-4 text-left">Order ID</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Items</th>
                        <th class="p-4 text-left">Total</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Payment</th>
                        <th class="p-4 text-left">Date</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-warm-brown">
                    <?php if (isset($orders) && count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr class="border-b border-light-cream hover:bg-light-cream transition">
                            <td class="p-4 font-semibold">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td class="p-4">
                                <div>
                                    <p class="font-semibold"><?= esc($order['customer_name']) ?></p>
                                    <p class="text-sm text-sage-green"><?= esc($order['customer_email']) ?></p>
                                </div>
                            </td>
                            <td class="p-4"><?= $order['total_items'] ?> items</td>
                            <td class="p-4 font-bold">₱<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="p-4">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-200 text-yellow-800',
                                    'processing' => 'bg-blue-200 text-blue-800',
                                    'shipped' => 'bg-purple-200 text-purple-800',
                                    'delivered' => 'bg-green-200 text-green-800',
                                    'cancelled' => 'bg-red-200 text-red-800'
                                ];
                                $statusColor = $statusColors[$order['status']] ?? 'bg-gray-200 text-gray-800';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <?php if ($order['payment_status'] === 'paid'): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">Paid</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-200 text-orange-800">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-sm"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800" title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-purple-600 hover:text-purple-800" title="Print Invoice">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="p-8 text-center text-sage-green">No orders found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <p class="text-sage-green">Showing 1 to 10 of <?= $totalOrders ?? 0 ?> orders</p>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-cream-beige text-warm-brown rounded hover:bg-warm-brown hover:text-light-cream transition">Previous</button>
                <button class="px-4 py-2 bg-warm-brown text-light-cream rounded">1</button>
                <button class="px-4 py-2 bg-cream-beige text-warm-brown rounded hover:bg-warm-brown hover:text-light-cream transition">2</button>
                <button class="px-4 py-2 bg-cream-beige text-warm-brown rounded hover:bg-warm-brown hover:text-light-cream transition">Next</button>
            </div>
        </div>
    </div>

</body>
</html>