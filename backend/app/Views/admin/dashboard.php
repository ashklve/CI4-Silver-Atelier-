<!DOCTYPE html>
<html lang="en">
<?= view('components/head', ['title' => 'Admin Dashboard - Silver Atelier']) ?>
<body class="font-sans bg-light-cream min-h-screen">
    
    <!-- Admin Header -->
    <nav class="bg-warm-brown text-light-cream p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span>Welcome, <?= esc(session('user')['username']) ?>!</span>
                <a href="<?= site_url('logout') ?>" class="bg-sage-green px-4 py-2 rounded hover:bg-light-cream hover:text-warm-brown transition">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-cream-beige p-6 rounded-lg shadow">
                <h3 class="text-sage-green text-sm">Total Users</h3>
                <p class="text-3xl font-bold text-warm-brown">150</p>
            </div>
            <div class="bg-cream-beige p-6 rounded-lg shadow">
                <h3 class="text-sage-green text-sm">Total Products</h3>
                <p class="text-3xl font-bold text-warm-brown">45</p>
            </div>
            <div class="bg-cream-beige p-6 rounded-lg shadow">
                <h3 class="text-sage-green text-sm">Total Orders</h3>
                <p class="text-3xl font-bold text-warm-brown">89</p>
            </div>
            <div class="bg-cream-beige p-6 rounded-lg shadow">
                <h3 class="text-sage-green text-sm">Revenue</h3>
                <p class="text-3xl font-bold text-warm-brown">₱150K</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-cream-beige p-6 rounded-lg shadow mb-8">
            <h2 class="text-2xl font-bold text-warm-brown mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="<?= site_url('admin/products') ?>" class="bg-warm-brown text-light-cream p-4 rounded text-center hover:bg-sage-green transition">
                    <i class="fas fa-box text-3xl mb-2"></i>
                    <p>Manage Products</p>
                </a>
                <a href="<?= site_url('admin/orders') ?>" class="bg-warm-brown text-light-cream p-4 rounded text-center hover:bg-sage-green transition">
                    <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                    <p>View Orders</p>
                </a>
                <a href="<?= site_url('admin/accounts') ?>" class="bg-warm-brown text-light-cream p-4 rounded text-center hover:bg-sage-green transition">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p>Manage Accounts</p>
                </a>
            </div>
        </div>
    </div>

</body>
</html>