<!DOCTYPE html>
<html lang="en">
<?= view('components/head', ['title' => 'Admin Dashboard - Silver Atelier']) ?>
<body class="font-sans bg-light-cream min-h-screen">
    
    <?= view('components/admin_header') ?>

    <div class="max-w-7xl mx-auto p-8">
        <!-- Header with Search -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-warm-brown">User Accounts</h2>
            <div class="flex items-center space-x-4">
                <input 
                    type="text" 
                    placeholder="Search users..." 
                    class="px-4 py-2 border-2 border-cream-beige rounded-lg focus:outline-none focus:border-warm-brown"
                >
                <button class="bg-warm-brown text-light-cream px-6 py-2 rounded hover:bg-sage-green transition">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6 flex space-x-4">
            <button class="px-4 py-2 bg-warm-brown text-light-cream rounded hover:bg-sage-green transition">
                All Users
            </button>
            <button class="px-4 py-2 bg-cream-beige text-warm-brown rounded hover:bg-light-cream transition">
                Clients Only
            </button>
            <button class="px-4 py-2 bg-cream-beige text-warm-brown rounded hover:bg-light-cream transition">
                Admins Only
            </button>
            <button class="px-4 py-2 bg-cream-beige text-warm-brown rounded hover:bg-light-cream transition">
                Inactive Accounts
            </button>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-cream-beige p-4 rounded-lg shadow">
                <p class="text-sage-green text-sm">Total Users</p>
                <p class="text-2xl font-bold text-warm-brown"><?= $totalUsers ?? 0 ?></p>
            </div>
            <div class="bg-cream-beige p-4 rounded-lg shadow">
                <p class="text-sage-green text-sm">Active Clients</p>
                <p class="text-2xl font-bold text-warm-brown"><?= $activeClients ?? 0 ?></p>
            </div>
            <div class="bg-cream-beige p-4 rounded-lg shadow">
                <p class="text-sage-green text-sm">Admins</p>
                <p class="text-2xl font-bold text-warm-brown"><?= $totalAdmins ?? 0 ?></p>
            </div>
            <div class="bg-cream-beige p-4 rounded-lg shadow">
                <p class="text-sage-green text-sm">New This Month</p>
                <p class="text-2xl font-bold text-warm-brown"><?= $newUsers ?? 0 ?></p>
            </div>
        </div>

        <!-- Accounts Table -->
        <div class="bg-cream-beige rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-warm-brown text-light-cream">
                    <tr>
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Username</th>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Type</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Created</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-warm-brown">
                    <?php if (isset($accounts) && count($accounts) > 0): ?>
                        <?php foreach ($accounts as $account): ?>
                        <tr class="border-b border-light-cream hover:bg-light-cream transition">
                            <td class="p-4"><?= $account['id'] ?></td>
                            <td class="p-4 font-semibold"><?= esc($account['username']) ?></td>
                            <td class="p-4"><?= esc($account['first_name'] . ' ' . $account['last_name']) ?></td>
                            <td class="p-4"><?= esc($account['email']) ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $account['type'] === 'admin' ? 'bg-purple-200 text-purple-800' : 'bg-blue-200 text-blue-800' ?>">
                                    <?= ucfirst($account['type']) ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <?php if ($account['account_status'] == 1): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">Active</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-200 text-red-800">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-sm"><?= date('M d, Y', strtotime($account['created_at'])) ?></td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($account['account_status'] == 1): ?>
                                        <button class="text-orange-600 hover:text-orange-800" title="Deactivate">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="text-green-600 hover:text-green-800" title="Activate">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="text-red-600 hover:text-red-800" title="Delete" onclick="return confirm('Are you sure you want to delete this account?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="p-8 text-center text-sage-green">No accounts found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <p class="text-sage-green">Showing 1 to 10 of <?= $totalUsers ?? 0 ?> entries</p>
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