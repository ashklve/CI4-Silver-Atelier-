<!DOCTYPE html>
<html lang="en">
<?= view('components/head', ['title' => 'Manage Products - Admin']) ?>
<body class="font-sans bg-light-cream min-h-screen">
    
    <nav class="bg-warm-brown text-light-cream p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Manage Products</h1>
            <a href="<?= site_url('admin/dashboard') ?>" class="hover:text-sage-green">← Back to Dashboard</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-warm-brown">Products</h2>
            <a href="<?= site_url('admin/products/create') ?>" class="bg-warm-brown text-light-cream px-6 py-3 rounded hover:bg-sage-green transition">
                <i class="fas fa-plus mr-2"></i>Add New Product
            </a>
        </div>

        <!-- Products Table -->
        <div class="bg-cream-beige rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-warm-brown text-light-cream">
                    <tr>
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Category</th>
                        <th class="p-4 text-left">Price</th>
                        <th class="p-4 text-left">Stock</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-warm-brown">
                    <?php foreach ($products as $product): ?>
                    <tr class="border-b border-light-cream hover:bg-light-cream">
                        <td class="p-4"><?= $product['id'] ?></td>
                        <td class="p-4"><?= esc($product['name']) ?></td>
                        <td class="p-4"><?= ucfirst($product['category']) ?></td>
                        <td class="p-4">₱<?= number_format($product['price'], 2) ?></td>
                        <td class="p-4"><?= $product['stock'] ?></td>
                        <td class="p-4">
                            <?= $product['status'] ? '<span class="text-green-600">Active</span>' : '<span class="text-red-600">Inactive</span>' ?>
                        </td>
                        <td class="p-4">
                            <a href="<?= site_url('admin/products/edit/' . $product['id']) ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <a href="<?= site_url('admin/products/delete/' . $product['id']) ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>