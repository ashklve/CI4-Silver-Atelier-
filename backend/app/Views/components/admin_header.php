<!-- ══════════════════════════ ADMIN HEADER ══════════════════════════ -->
<?php
$adminUser   = session()->get('user');
$currentUri  = uri_string();

$navItems = [
    ['href' => 'admin/dashboard',  'icon' => 'fa-tachometer-alt', 'label' => 'Dashboard'],
    ['href' => 'admin/storefront', 'icon' => 'fa-store',          'label' => 'Storefront'],
    ['href' => 'admin/products',   'icon' => 'fa-box-open',       'label' => 'Inventory'],
    ['href' => 'admin/orders',     'icon' => 'fa-shopping-cart',  'label' => 'Orders'],
    ['href' => 'admin/reports',    'icon' => 'fa-chart-bar',      'label' => 'Reports'],
    ['href' => 'admin/accounts',   'icon' => 'fa-users',          'label' => 'Accounts'],
];
?>

<style>
    /* ── Sidebar ── */
    #admin-sidebar {
        width: 240px;
        min-height: 100vh;
        position: fixed;
        top: 0; left: 0;
        z-index: 40;
        background: #3B2314;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease;
    }
    #admin-sidebar.collapsed { transform: translateX(-240px); }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #C8956C;
        border-radius: 12px;
        margin: 2px 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        letter-spacing: 0.03em;
    }
    .admin-nav-link:hover {
        background: rgba(232,119,34,0.15);
        color: #E87722;
    }
    .admin-nav-link.active {
        background: #E87722;
        color: #fff;
        box-shadow: 0 4px 12px rgba(232,119,34,0.35);
    }
    .admin-nav-link .nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.75rem;
        background: rgba(255,255,255,0.06);
        transition: background 0.2s ease;
    }
    .admin-nav-link.active .nav-icon { background: rgba(255,255,255,0.2); }
    .admin-nav-link:hover .nav-icon  { background: rgba(232,119,34,0.2); }

    /* ── Topbar ── */
    #admin-topbar {
        position: fixed;
        top: 0;
        left: 240px;
        right: 0;
        height: 64px;
        background: rgba(250,243,232,0.97);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #EDE0CC;
        z-index: 30;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        transition: left 0.3s ease;
        box-shadow: 0 1px 12px rgba(59,35,20,0.06);
    }
    #admin-topbar.expanded { left: 0; }

    /* ── Main content offset ── */
    .admin-main {
        margin-left: 240px;
        margin-top: 64px;
        min-height: calc(100vh - 64px);
        transition: margin-left 0.3s ease;
    }
    .admin-main.expanded { margin-left: 0; }

    /* Mobile overlay */
    #sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(59,35,20,0.5);
        z-index: 35;
    }

    @media (max-width: 1024px) {
        #admin-sidebar { transform: translateX(-240px); }
        #admin-sidebar.mobile-open { transform: translateX(0); }
        #admin-topbar { left: 0 !important; }
        .admin-main   { margin-left: 0 !important; }
    }

    /* Dropdown */
    .admin-dropdown { opacity:0; pointer-events:none; transform:translateY(6px); transition:all 0.2s ease; }
    .admin-dropdown.open { opacity:1; pointer-events:auto; transform:translateY(0); }
</style>

<!-- ── Sidebar ── -->
<aside id="admin-sidebar">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/8">
        <svg class="w-9 h-9 flex-shrink-0" viewBox="0 0 56 56" fill="none">
            <circle cx="19" cy="13" r="8" fill="#E87722" opacity="0.92"/>
            <path d="M33 45 Q31 33 35 21" stroke="#C8956C" stroke-width="3" stroke-linecap="round"/>
            <path d="M35 21 Q44 15 48 11" stroke="#6BAF78" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M35 21 Q27 13 25 8"  stroke="#6BAF78" stroke-width="2"   stroke-linecap="round"/>
            <ellipse cx="30" cy="46" rx="14" ry="3.5" fill="#6BAF78" opacity="0.4"/>
            <circle cx="21" cy="44" r="5" fill="#5C3317"/>
            <circle cx="30" cy="44" r="5" fill="#3B2314"/>
        </svg>
        <div>
            <div class="font-display font-black text-coco-cream text-lg leading-none tracking-wide">COCOIR</div>
            <div class="text-[9px] text-coco-tan tracking-[0.2em] uppercase">Admin Panel</div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-4 overflow-y-auto">
        <div class="px-4 mb-3">
            <span class="text-[9px] text-coco-tan/50 font-bold tracking-[0.25em] uppercase">Main Menu</span>
        </div>
        <?php foreach ($navItems as $item):
            $isActive = str_starts_with($currentUri, $item['href']);
        ?>
        <a href="<?= site_url($item['href']) ?>"
           class="admin-nav-link <?= $isActive ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas <?= $item['icon'] ?>"></i></span>
            <?= $item['label'] ?>
        </a>
        <?php endforeach; ?>

        <div class="px-4 mt-6 mb-3">
            <span class="text-[9px] text-coco-tan/50 font-bold tracking-[0.25em] uppercase">System</span>
        </div>
        <a href="<?= site_url('/') ?>" target="_blank"
           class="admin-nav-link">
            <span class="nav-icon"><i class="fas fa-external-link-alt"></i></span>
            View Storefront
        </a>
        <a href="<?= site_url('logout') ?>"
           class="admin-nav-link" style="color:#f87171;">
            <span class="nav-icon" style="background:rgba(248,113,113,0.1);"><i class="fas fa-sign-out-alt" style="color:#f87171;"></i></span>
            Log Out
        </a>
    </nav>

    <!-- User strip -->
    <div class="px-4 py-4 border-t border-white/8 flex items-center gap-3">
        <div class="w-8 h-8 bg-coco-orange rounded-full flex items-center justify-center font-black text-white text-sm flex-shrink-0">
            <?= strtoupper(substr($adminUser['first_name'] ?? $adminUser['username'] ?? 'A', 0, 1)) ?>
        </div>
        <div class="min-w-0">
            <div class="text-coco-cream text-xs font-bold truncate">
                <?= esc(trim(($adminUser['first_name'] ?? '') . ' ' . ($adminUser['last_name'] ?? ''))) ?: esc($adminUser['username'] ?? 'Admin') ?>
            </div>
            <div class="text-coco-tan text-[10px] truncate">Administrator</div>
        </div>
    </div>
</aside>

<!-- ── Topbar ── -->
<div id="admin-topbar">
    <div class="flex items-center gap-4">
        <!-- Hamburger -->
        <button id="sidebar-toggle" class="w-9 h-9 rounded-xl bg-coco-sand/50 flex items-center justify-center text-coco-mid hover:bg-coco-orange hover:text-white transition-all">
            <i class="fas fa-bars text-sm"></i>
        </button>
        <!-- Breadcrumb -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-coco-mid">
            <span class="text-coco-tan">Admin</span>
            <i class="fas fa-chevron-right text-[8px] text-coco-tan/50"></i>
            <span class="font-semibold text-coco-brown capitalize">
                <?= ucfirst(explode('/', $currentUri)[1] ?? 'Dashboard') ?>
            </span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Notifications -->
        <button class="relative w-9 h-9 rounded-xl bg-coco-sand/50 flex items-center justify-center text-coco-mid hover:bg-coco-orange hover:text-white transition-all">
            <i class="fas fa-bell text-sm"></i>
            <span class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-red-500 rounded-full text-[8px] text-white flex items-center justify-center font-black">3</span>
        </button>

        <!-- View site -->
        <a href="<?= site_url('/') ?>" target="_blank"
           class="hidden sm:flex items-center gap-2 text-xs font-semibold text-coco-mid hover:text-coco-orange transition-colors px-3 py-2 rounded-xl hover:bg-coco-sand/50">
            <i class="fas fa-external-link-alt text-xs"></i> View Site
        </a>

        <!-- Admin profile dropdown -->
        <div class="relative">
            <button id="admin-profile-btn"
                class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full border-2 border-coco-sand hover:border-coco-orange transition-all text-sm font-semibold text-coco-dark bg-white/60">
                <span class="w-7 h-7 bg-coco-orange rounded-full flex items-center justify-center text-white font-black text-xs">
                    <?= strtoupper(substr($adminUser['first_name'] ?? $adminUser['username'] ?? 'A', 0, 1)) ?>
                </span>
                <span class="hidden sm:block max-w-[100px] truncate text-xs">
                    <?= esc($adminUser['first_name'] ?? $adminUser['username'] ?? 'Admin') ?>
                </span>
                <i class="fas fa-chevron-down text-[9px] text-coco-tan"></i>
            </button>
            <div id="admin-dropdown" class="admin-dropdown absolute right-0 top-full mt-2 w-44 bg-white rounded-2xl shadow-xl border border-coco-sand/60 overflow-hidden">
                <a href="<?= site_url('profile') ?>" class="flex items-center gap-2 px-4 py-3 text-sm text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors border-b border-coco-sand/30">
                    <i class="fas fa-user text-xs w-4 text-coco-tan"></i> My Profile
                </a>
                <a href="<?= site_url('logout') ?>" class="flex items-center gap-2 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-colors font-semibold">
                    <i class="fas fa-sign-out-alt text-xs w-4"></i> Log Out
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobile overlay -->
<div id="sidebar-overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar  = document.getElementById('admin-sidebar');
    const topbar   = document.getElementById('admin-topbar');
    const main     = document.querySelector('.admin-main');
    const toggle   = document.getElementById('sidebar-toggle');
    const overlay  = document.getElementById('sidebar-overlay');
    const isMobile = () => window.innerWidth < 1024;

    toggle.addEventListener('click', () => {
        if (isMobile()) {
            const open = sidebar.classList.toggle('mobile-open');
            overlay.style.display = open ? 'block' : 'none';
        } else {
            const collapsed = sidebar.classList.toggle('collapsed');
            topbar.classList.toggle('expanded', collapsed);
            if (main) main.classList.toggle('expanded', collapsed);
        }
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
    });

    // Profile dropdown
    const profileBtn = document.getElementById('admin-profile-btn');
    const dropdown   = document.getElementById('admin-dropdown');
    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('open');
    });
    document.addEventListener('click', () => dropdown.classList.remove('open'));
});
</script>