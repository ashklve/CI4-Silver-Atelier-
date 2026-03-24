<?php
/**
 * View:       user/profile.php
 * Controller: Users::profile()     GET  /profile
 *             Users::updateProfile() POST /profile/update
 */
$user   = session()->get('user');
$data   = $profileData ?? $user; // full DB row passed from controller
$errors = session()->getFlashdata('errors') ?? [];
$success= session()->getFlashdata('success');
?>
<!DOCTYPE html>
<html lang="en">
<?= $this->include('components/head', ['title' => 'My Profile — COCOIR']) ?>

<body class="flex flex-col bg-coco-cream min-h-screen font-body text-coco-brown">
<?= $this->include('components/header') ?>

<style>
    .input-field {
        width: 100%;
        border: 2px solid #EDE0CC;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 0.875rem;
        color: #3B2314;
        background: rgba(250,243,232,0.5);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
    }
    .input-field:focus {
        border-color: #E87722;
        box-shadow: 0 0 0 3px rgba(232,119,34,0.12);
    }
    .input-field.error { border-color: #ef4444; }
    .input-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #8B5E3C;
        margin-bottom: 6px;
        letter-spacing: 0.03em;
    }
    .section-card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(237,224,204,0.6);
        box-shadow: 0 1px 8px rgba(59,35,20,0.06);
        overflow: hidden;
    }
    .section-header {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(237,224,204,0.5);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .tab-btn { transition: all 0.2s ease; }
    .tab-btn.active { background: #E87722; color: white; }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Avatar upload hover */
    .avatar-wrap:hover .avatar-overlay { opacity: 1; }
    .avatar-overlay { opacity: 0; transition: opacity 0.2s ease; }
</style>

<main class="flex-grow pt-28 pb-16 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-coco-mid mb-6">
        <a href="<?= site_url('/') ?>" class="hover:text-coco-orange transition-colors">Home</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <span class="text-coco-orange font-semibold">My Profile</span>
    </nav>

    <!-- Flash messages -->
    <?php if ($success): ?>
    <div class="mb-6 flex items-center gap-3 bg-coco-green text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-md">
        <i class="fas fa-check-circle"></i> <?= esc($success) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($errors['general'])): ?>
    <div class="mb-6 flex items-center gap-3 bg-red-500 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-md">
        <i class="fas fa-exclamation-circle"></i> <?= esc($errors['general']) ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- ── LEFT: Avatar + Quick Info ── -->
        <div class="lg:col-span-1 space-y-5">

            <!-- Avatar card -->
            <div class="section-card p-6 flex flex-col items-center text-center gap-4">
                <form action="<?= site_url('profile/avatar') ?>" method="POST" enctype="multipart/form-data" id="avatar-form">
                    <?= csrf_field() ?>
                    <label class="avatar-wrap relative cursor-pointer block">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-coco-sand shadow-lg mx-auto">
                            <?php if (!empty($data['profile_image'])): ?>
                                <img src="/images/profiles/<?= esc($data['profile_image']) ?>" alt="Profile" class="w-full h-full object-cover" id="avatar-preview">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-coco-orange to-coco-dark flex items-center justify-center" id="avatar-placeholder">
                                    <span class="text-white font-black text-3xl">
                                        <?= strtoupper(substr($data['first_name'] ?? $data['username'], 0, 1)) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Overlay on hover -->
                        <div class="avatar-overlay absolute inset-0 flex items-center justify-center">
                            <div class="w-24 h-24 rounded-full bg-black/40 flex items-center justify-center mx-auto">
                                <i class="fas fa-camera text-white text-lg"></i>
                            </div>
                        </div>
                        <input type="file" name="avatar" accept="image/*" class="sr-only" onchange="previewAvatar(this); this.form.submit();">
                    </label>
                </form>

                <div>
                    <div class="font-display font-black text-xl text-coco-brown">
                        <?= esc(trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''))) ?>
                    </div>
                    <div class="text-coco-mid text-xs mt-0.5">@<?= esc($data['username'] ?? '') ?></div>
                    <div class="mt-2 inline-flex items-center gap-1.5 bg-coco-green/10 text-coco-green text-[10px] font-bold px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-coco-green rounded-full"></span>
                        <?= ucfirst($data['type'] ?? 'client') ?>
                    </div>
                </div>

                <div class="w-full border-t border-coco-sand/50 pt-3 space-y-2 text-xs text-left">
                    <div class="flex items-center gap-2 text-coco-mid">
                        <i class="fas fa-envelope w-4 text-coco-tan"></i>
                        <span class="truncate"><?= esc($data['email'] ?? '') ?></span>
                    </div>
                    <?php if (!empty($data['phone'])): ?>
                    <div class="flex items-center gap-2 text-coco-mid">
                        <i class="fas fa-phone w-4 text-coco-tan"></i>
                        <span><?= esc($data['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($data['city'])): ?>
                    <div class="flex items-center gap-2 text-coco-mid">
                        <i class="fas fa-map-marker-alt w-4 text-coco-tan"></i>
                        <span><?= esc($data['city']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2 text-coco-mid">
                        <i class="fas fa-calendar w-4 text-coco-tan"></i>
                        <span>Joined <?= date('M Y', strtotime($data['created_at'] ?? 'now')) ?></span>
                    </div>
                </div>
            </div>

            <!-- Quick nav -->
            <div class="section-card overflow-hidden">
                <button onclick="switchTab('personal')"  id="tab-personal"  class="tab-btn active w-full text-left px-5 py-3.5 text-sm font-semibold flex items-center gap-3 border-b border-coco-sand/40">
                    <i class="fas fa-user w-4 text-xs"></i> Personal Info
                </button>
                <button onclick="switchTab('address')"   id="tab-address"   class="tab-btn w-full text-left px-5 py-3.5 text-sm font-semibold flex items-center gap-3 text-coco-dark border-b border-coco-sand/40">
                    <i class="fas fa-map-marker-alt w-4 text-xs"></i> Address
                </button>
                <button onclick="switchTab('security')"  id="tab-security"  class="tab-btn w-full text-left px-5 py-3.5 text-sm font-semibold flex items-center gap-3 text-coco-dark border-b border-coco-sand/40">
                    <i class="fas fa-lock w-4 text-xs"></i> Password
                </button>
                <a href="<?= site_url('orders') ?>"
                   class="w-full text-left px-5 py-3.5 text-sm font-semibold flex items-center gap-3 text-coco-dark hover:bg-coco-cream hover:text-coco-orange transition-colors">
                    <i class="fas fa-box w-4 text-xs"></i> My Orders
                </a>
            </div>
        </div>

        <!-- ── RIGHT: Edit Forms ── -->
        <div class="lg:col-span-3">
            <form action="<?= site_url('profile/update') ?>" method="POST" id="profile-form">
                <?= csrf_field() ?>

                <!-- ════ TAB: Personal Info ════ -->
                <div id="panel-personal" class="tab-panel active section-card">
                    <div class="section-header">
                        <span class="w-8 h-8 bg-coco-orange/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-coco-orange text-xs"></i>
                        </span>
                        <div>
                            <h2 class="font-display font-bold text-lg text-coco-brown leading-none">Personal Information</h2>
                            <p class="text-coco-mid text-xs mt-0.5">Update your name, contact, and personal details</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Name row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="input-label">First Name <span class="text-coco-orange">*</span></label>
                                <input type="text" name="first_name" value="<?= esc($data['first_name'] ?? '') ?>"
                                    class="input-field <?= isset($errors['first_name']) ? 'error' : '' ?>" required>
                                <?php if (!empty($errors['first_name'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= esc($errors['first_name']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="input-label">Middle Name</label>
                                <input type="text" name="middle_name" value="<?= esc($data['middle_name'] ?? '') ?>"
                                    class="input-field">
                            </div>
                            <div>
                                <label class="input-label">Last Name <span class="text-coco-orange">*</span></label>
                                <input type="text" name="last_name" value="<?= esc($data['last_name'] ?? '') ?>"
                                    class="input-field <?= isset($errors['last_name']) ? 'error' : '' ?>" required>
                                <?php if (!empty($errors['last_name'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= esc($errors['last_name']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="input-label">Username <span class="text-coco-orange">*</span></label>
                            <input type="text" name="username" value="<?= esc($data['username'] ?? '') ?>"
                                class="input-field <?= isset($errors['username']) ? 'error' : '' ?>" required>
                            <?php if (!empty($errors['username'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= esc($errors['username']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="input-label">Email Address <span class="text-coco-orange">*</span></label>
                            <input type="email" name="email" value="<?= esc($data['email'] ?? '') ?>"
                                class="input-field <?= isset($errors['email']) ? 'error' : '' ?>" required>
                            <?php if (!empty($errors['email'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= esc($errors['email']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Phone + Gender -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="input-label">Phone Number</label>
                                <input type="tel" name="phone" value="<?= esc($data['phone'] ?? '') ?>"
                                    placeholder="+63 9XX XXX XXXX" class="input-field">
                            </div>
                            <div>
                                <label class="input-label">Gender</label>
                                <select name="gender" class="input-field">
                                    <option value="">Prefer not to say</option>
                                    <option value="Male"   <?= ($data['gender'] ?? '') === 'Male'   ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($data['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other"  <?= ($data['gender'] ?? '') === 'Other'  ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="flex items-center gap-3 p-4 bg-coco-cream/60 rounded-2xl border border-coco-sand/50">
                            <input type="checkbox" name="newsletter" id="newsletter" value="1"
                                <?= ($data['newsletter'] ?? 0) ? 'checked' : '' ?>
                                class="w-4 h-4 rounded border-coco-sand text-coco-orange focus:ring-coco-orange">
                            <label for="newsletter" class="text-sm text-coco-dark cursor-pointer">
                                <span class="font-semibold">Subscribe to newsletter</span>
                                <span class="text-coco-mid block text-xs">Get exclusive deals and eco-living tips</span>
                            </label>
                        </div>

                        <button type="submit" name="section" value="personal"
                            class="bg-coco-orange text-white font-bold px-8 py-3 rounded-full hover:bg-coco-dark transition-all duration-300 hover:scale-105 transform shadow-md">
                            Save Changes
                        </button>
                    </div>
                </div>

                <!-- ════ TAB: Address ════ -->
                <div id="panel-address" class="tab-panel section-card">
                    <div class="section-header">
                        <span class="w-8 h-8 bg-coco-green/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-coco-green text-xs"></i>
                        </span>
                        <div>
                            <h2 class="font-display font-bold text-lg text-coco-brown leading-none">Default Address</h2>
                            <p class="text-coco-mid text-xs mt-0.5">Saved address will auto-fill at checkout</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">

                        <!-- Info banner -->
                        <div class="flex items-start gap-3 bg-coco-green/8 border border-coco-leaf/30 rounded-2xl p-4 text-xs text-coco-dark">
                            <i class="fas fa-info-circle text-coco-green mt-0.5 flex-shrink-0"></i>
                            <span>This address will be <strong>automatically filled</strong> when you checkout. You can still change it per order.</span>
                        </div>

                        <div>
                            <label class="input-label">Street / Barangay Address</label>
                            <textarea name="address" rows="2"
                                class="input-field resize-none"
                                placeholder="House No., Street, Barangay"><?= esc($data['address'] ?? '') ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="input-label">City / Municipality</label>
                                <input type="text" name="city" value="<?= esc($data['city'] ?? '') ?>"
                                    placeholder="e.g. Quezon City" class="input-field">
                            </div>
                            <div>
                                <label class="input-label">ZIP / Postal Code</label>
                                <input type="text" name="postal_code" value="<?= esc($data['postal_code'] ?? '') ?>"
                                    placeholder="e.g. 1100" class="input-field">
                            </div>
                        </div>

                        <!-- Map preview if address is set -->
                        <?php if (!empty($data['city'])): ?>
                        <div class="rounded-2xl overflow-hidden border border-coco-sand h-40 bg-coco-sand/30 flex items-center justify-center text-coco-mid text-xs gap-2">
                            <i class="fas fa-map-marker-alt text-coco-tan"></i>
                            <?= esc($data['address'] ?? '') ?>, <?= esc($data['city']) ?> <?= esc($data['postal_code'] ?? '') ?>
                        </div>
                        <?php endif; ?>

                        <button type="submit" name="section" value="address"
                            class="bg-coco-green text-white font-bold px-8 py-3 rounded-full hover:bg-coco-dark transition-all duration-300 hover:scale-105 transform shadow-md">
                            Save Address
                        </button>
                    </div>
                </div>

                <!-- ════ TAB: Password ════ -->
                <div id="panel-security" class="tab-panel section-card">
                    <div class="section-header">
                        <span class="w-8 h-8 bg-coco-brown/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lock text-coco-brown text-xs"></i>
                        </span>
                        <div>
                            <h2 class="font-display font-bold text-lg text-coco-brown leading-none">Change Password</h2>
                            <p class="text-coco-mid text-xs mt-0.5">Use a strong password of at least 8 characters</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="input-label">Current Password <span class="text-coco-orange">*</span></label>
                            <div class="relative">
                                <input type="password" name="current_password" id="cur-pwd"
                                    class="input-field pr-10 <?= isset($errors['current_password']) ? 'error' : '' ?>"
                                    placeholder="Enter current password">
                                <button type="button" onclick="togglePwd('cur-pwd','eye-cur')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-coco-tan hover:text-coco-dark transition-colors">
                                    <i class="fas fa-eye text-sm" id="eye-cur"></i>
                                </button>
                            </div>
                            <?php if (!empty($errors['current_password'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= esc($errors['current_password']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="input-label">New Password <span class="text-coco-orange">*</span></label>
                            <div class="relative">
                                <input type="password" name="new_password" id="new-pwd"
                                    class="input-field pr-10 <?= isset($errors['new_password']) ? 'error' : '' ?>"
                                    placeholder="Min. 8 characters">
                                <button type="button" onclick="togglePwd('new-pwd','eye-new')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-coco-tan hover:text-coco-dark transition-colors">
                                    <i class="fas fa-eye text-sm" id="eye-new"></i>
                                </button>
                            </div>
                            <!-- Strength bar -->
                            <div class="mt-2 space-y-1">
                                <div class="h-1.5 bg-coco-sand rounded-full overflow-hidden">
                                    <div id="strength-bar" class="h-full rounded-full transition-all duration-300 w-0"></div>
                                </div>
                                <div id="strength-label" class="text-xs text-coco-mid"></div>
                            </div>
                            <?php if (!empty($errors['new_password'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= esc($errors['new_password']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="input-label">Confirm New Password <span class="text-coco-orange">*</span></label>
                            <div class="relative">
                                <input type="password" name="confirm_password" id="conf-pwd"
                                    class="input-field pr-10 <?= isset($errors['confirm_password']) ? 'error' : '' ?>"
                                    placeholder="Repeat new password">
                                <button type="button" onclick="togglePwd('conf-pwd','eye-conf')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-coco-tan hover:text-coco-dark transition-colors">
                                    <i class="fas fa-eye text-sm" id="eye-conf"></i>
                                </button>
                            </div>
                            <?php if (!empty($errors['confirm_password'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= esc($errors['confirm_password']) ?></p>
                            <?php endif; ?>
                        </div>

                        <button type="submit" name="section" value="security"
                            class="bg-coco-brown text-coco-cream font-bold px-8 py-3 rounded-full hover:bg-coco-orange transition-all duration-300 hover:scale-105 transform shadow-md">
                            Update Password
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</main>

<?= $this->include('components/footer') ?>

<script>
// ── Tab switcher ──
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('active');
        b.classList.add('text-coco-dark');
    });
    document.getElementById('panel-' + tab).classList.add('active');
    const btn = document.getElementById('tab-' + tab);
    btn.classList.add('active');
    btn.classList.remove('text-coco-dark');
}

// ── Password toggle ──
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const show  = input.type === 'password';
    input.type  = show ? 'text' : 'password';
    icon.classList.toggle('fa-eye',       !show);
    icon.classList.toggle('fa-eye-slash',  show);
}

// ── Password strength ──
document.getElementById('new-pwd').addEventListener('input', function () {
    const val  = this.value;
    const bar  = document.getElementById('strength-bar');
    const label= document.getElementById('strength-label');
    let score  = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const map = {
        0: ['w-0',   '',          ''],
        1: ['w-1/4', 'bg-red-400','Weak'],
        2: ['w-2/4', 'bg-yellow-400','Fair'],
        3: ['w-3/4', 'bg-coco-amber','Good'],
        4: ['w-full','bg-coco-green','Strong'],
    };
    const [w, color, text] = map[score];
    bar.className   = `h-full rounded-full transition-all duration-300 ${w} ${color}`;
    label.textContent = text;
    label.className   = `text-xs ${score <= 1 ? 'text-red-400' : score <= 2 ? 'text-yellow-600' : 'text-coco-green'}`;
});

// ── Avatar preview ──
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                placeholder.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" id="avatar-preview">`;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Auto-open tab from URL hash ──
const hash = window.location.hash.replace('#', '');
if (['personal','address','security'].includes(hash)) switchTab(hash);
</script>
</body>
</html>