<?php
/**
 * View: admin/accounts.php
 * Controller: Admin::accounts()
 * Route: GET /admin/accounts
 */
$users = $users ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'Accounts — COCOIR Admin']) ?>

<style>
.user-row { transition: background 0.15s ease; }
.user-row:hover { background: #FFFBF7; }
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(20,10,5,0.6); backdrop-filter:blur(6px); z-index:9998; align-items:center; justify-content:center; padding:16px; }
.modal-overlay.open { display:flex; }
</style>

<body class="bg-gray-50 font-body text-coco-brown">
<?= $this->include('components/admin_header') ?>

<main class="px-6 py-8 admin-main">
    <div class="flex sm:flex-row flex-col justify-between sm:items-center gap-4 mb-6">
        <div>
            <h1 class="font-display font-black text-coco-brown text-3xl">Accounts</h1>
            <p class="mt-0.5 text-coco-mid text-sm"><?= count($users) ?> registered users</p>
        </div>
        <button onclick="openModal()"
            class="inline-flex items-center gap-2 bg-coco-orange hover:bg-coco-dark shadow-md px-6 py-3 rounded-xl font-bold text-white text-sm hover:scale-105 transition-all transform">
            <i class="fas fa-plus"></i> Add Account
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-3 bg-white shadow-sm mb-6 p-4 border border-gray-100 rounded-2xl">
        <input type="text" id="search-users" placeholder="Search name, email, username..."
            class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-64 text-sm transition-colors">
        
        <select id="filter-type" class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none text-sm transition-colors appearance-none">
            <option value="">All Types</option>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
        </select>

        <span class="ml-auto text-coco-mid text-xs">Showing <strong id="visible-count"><?= count($users) ?></strong> users</span>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="users-table">
                <thead class="bg-gray-50 border-gray-100 border-b text-coco-mid text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold text-left">User</th>
                        <th class="px-5 py-3.5 font-semibold text-left">Role</th>
                        <th class="px-5 py-3.5 font-semibold text-left">Contact</th>
                        <th class="px-5 py-3.5 font-semibold text-left">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-left">Joined</th>
                        <th class="px-5 py-3.5 font-semibold text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="users-tbody">
                    <?php if (empty($users)): ?>
                    <tr><td colspan="6" class="px-5 py-16 text-coco-mid text-center">No users found.</td></tr>
                    <?php endif; ?>
                    
                    <?php foreach ($users as $u): 
                        // Handle Entity or Array
                        $u = is_object($u) ? $u->toArray() : $u;
                        $name = trim($u['first_name'] . ' ' . $u['last_name']);
                        $initial = strtoupper(substr($u['first_name'] ?? $u['username'], 0, 1));
                    ?>
                    <tr class="user-row"
                        data-search="<?= strtolower($name . ' ' . $u['email'] . ' ' . $u['username']) ?>"
                        data-type="<?= $u['type'] ?>">
                        
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-shrink-0 justify-center items-center bg-coco-cream border border-coco-sand rounded-full w-10 h-10 overflow-hidden font-black text-coco-brown text-sm">
                                    <?php if (!empty($u['profile_image'])): ?>
                                        <img src="/images/profiles/<?= esc($u['profile_image']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= $initial ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="font-bold text-coco-brown"><?= esc($name) ?></div>
                                    <div class="text-coco-mid text-xs">@<?= esc($u['username']) ?></div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-5 py-4">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $u['type'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?= ucfirst($u['type']) ?>
                            </span>
                        </td>
                        
                        <td class="px-5 py-4 text-coco-dark text-xs">
                            <div><?= esc($u['email']) ?></div>
                            <div class="text-coco-mid"><?= esc($u['phone'] ?? '-') ?></div>
                        </td>
                        
                        <td class="px-5 py-4">
                            <form action="<?= site_url('admin/accounts/toggle') ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" class="<?= $u['account_status'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> text-xs font-bold px-2.5 py-1 rounded-full hover:opacity-80 transition-opacity">
                                    <?= $u['account_status'] ? 'Active' : 'Inactive' ?>
                                </button>
                            </form>
                        </td>
                        
                        <td class="px-5 py-4 text-coco-mid text-xs">
                            <?= date('M d, Y', strtotime($u['created_at'])) ?>
                        </td>
                        
                        <td class="px-5 py-4">
                            <div class="flex gap-2">
                                <button onclick='openEditModal(<?= json_encode($u) ?>)'
                                    class="flex justify-center items-center bg-coco-orange/10 hover:bg-coco-orange rounded-lg w-8 h-8 text-coco-orange hover:text-white text-xs transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if (session()->get('user')['id'] != $u['id']): ?>
                                <form action="<?= site_url('admin/accounts/delete') ?>" method="POST" onsubmit="return confirm('Delete this account permanently?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit"
                                        class="flex justify-center items-center bg-red-50 hover:bg-red-500 rounded-lg w-8 h-8 text-red-400 hover:text-white text-xs transition-all" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal -->
<div id="account-modal" class="modal-overlay">
    <div class="bg-white shadow-2xl rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-gray-100 border-b">
            <h3 class="font-display font-bold text-coco-brown text-xl" id="modal-title">Add Account</h3>
            <button onclick="closeModal()" class="flex justify-center items-center bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 text-coco-mid transition-colors">
                <i class="text-xs fas fa-times"></i>
            </button>
        </div>

        <form id="account-form" action="<?= site_url('admin/accounts/save') ?>" method="POST" class="space-y-5 p-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="form-id">

            <div class="gap-4 grid grid-cols-1 sm:grid-cols-2">
                <div>
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">First Name *</label>
                    <input type="text" name="first_name" id="form-first_name" required
                        class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors">
                </div>
                <div>
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">Last Name *</label>
                    <input type="text" name="last_name" id="form-last_name" required
                        class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors">
                </div>
                
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">Username *</label>
                    <input type="text" name="username" id="form-username" required
                        class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors">
                </div>
                
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">Email *</label>
                    <input type="email" name="email" id="form-email" required
                        class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors">
                </div>

                <div>
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">Role</label>
                    <select name="type" id="form-type" class="bg-white px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors appearance-none">
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div>
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">Status</label>
                    <select name="status" id="form-status" class="bg-white px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors appearance-none">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-1.5 font-bold text-coco-mid text-xs uppercase tracking-wide">Password <span id="pwd-hint" class="font-normal text-gray-400 normal-case"></span></label>
                    <input type="password" name="password" id="form-password"
                        class="px-4 py-2.5 border-2 border-gray-200 focus:border-coco-orange rounded-xl focus:outline-none w-full text-sm transition-colors" placeholder="Leave blank to keep current">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()"
                    class="flex-1 py-3 border-2 border-gray-200 hover:border-gray-300 rounded-xl font-bold text-coco-mid text-sm transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 bg-coco-orange hover:bg-coco-dark shadow-md py-3 rounded-xl font-bold text-white text-sm transition-colors">
                    Save Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Search & Filter
const searchInput = document.getElementById('search-users');
const filterType = document.getElementById('filter-type');

function filterTable() {
    const q = searchInput.value.toLowerCase().trim();
    const type = filterType.value;
    const rows = document.querySelectorAll('.user-row');
    let visible = 0;

    rows.forEach(row => {
        const textMatch = !q || row.dataset.search.includes(q);
        const typeMatch = !type || row.dataset.type === type;
        
        if (textMatch && typeMatch) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });
    document.getElementById('visible-count').textContent = visible;
}

searchInput.addEventListener('input', filterTable);
filterType.addEventListener('change', filterTable);

// Modal
function openModal() {
    document.getElementById('modal-title').textContent = 'Add Account';
    document.getElementById('account-form').reset();
    document.getElementById('form-id').value = '';
    document.getElementById('pwd-hint').textContent = '(required for new)';
    document.getElementById('form-password').required = true;
    document.getElementById('account-modal').classList.add('open');
}

function openEditModal(u) {
    document.getElementById('modal-title').textContent = 'Edit Account';
    document.getElementById('form-id').value = u.id;
    document.getElementById('form-first_name').value = u.first_name;
    document.getElementById('form-last_name').value = u.last_name;
    document.getElementById('form-username').value = u.username;
    document.getElementById('form-email').value = u.email;
    document.getElementById('form-type').value = u.type;
    document.getElementById('form-status').value = u.account_status;
    
    document.getElementById('pwd-hint').textContent = '(leave blank to keep)';
    document.getElementById('form-password').required = false;
    document.getElementById('form-password').value = '';
    
    document.getElementById('account-modal').classList.add('open');
}

function closeModal() {
    document.getElementById('account-modal').classList.remove('open');
}

document.getElementById('account-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

</body>
</html>