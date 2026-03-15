<?php
$errors = $errors ?? [];
$old = $old ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<?= view('components/head') ?>

<body class="bg-coco-cream font-body text-coco-brown">
    <?= $this->include('components/header') ?>

    <!-- Main Content -->
    <div class="flex justify-center items-center px-4 sm:px-6 lg:px-8 py-24 min-h-screen">
        <div class="w-full max-w-2xl">
            <!-- Welcome Section -->
            <div class="mb-12 text-center">
                <h1 class="mb-4 font-display font-black text-coco-brown text-5xl sm:text-6xl leading-tight">
                    Join COCOIR
                </h1>
                <p class="mx-auto max-w-lg font-light text-coco-mid text-lg">
                    Create your account and become part of our eco-friendly community
                </p>
            </div>

            <!-- Signup Form -->
            <div class="bg-white shadow-2xl p-8 md:p-12 rounded-2xl">
                <form class="space-y-6" id="signupForm" action="<?= base_url('auth/register') ?>" method="POST" novalidate>
                    <?= csrf_field() ?>

                    <!-- Complete Name Field -->
                    <div>
                        <label for="fullname" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Complete Name <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="left-0 absolute inset-y-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-user"></i>
                            </div>
                            <input
                                type="text"
                                id="fullname"
                                name="fullname"
                                required
                                value="<?= esc($old['fullname'] ?? '') ?>"
                                aria-invalid="<?= isset($errors['fullname']) ? 'true' : 'false' ?>"
                                aria-describedby="fullname-error"
                                class="w-full pl-12 pr-4 py-3 border-2 <?= isset($errors['fullname']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="John Doe">
                        </div>
                        <?php if (!empty($errors['fullname'])): ?>
                            <p id="fullname-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['fullname']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Email Address <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="left-0 absolute inset-y-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-envelope"></i>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="<?= esc($old['email'] ?? '') ?>"
                                aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
                                aria-describedby="email-error"
                                class="w-full pl-12 pr-4 py-3 border-2 <?= isset($errors['email']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="you@example.com">
                        </div>
                        <?php if (!empty($errors['email'])): ?>
                            <p id="email-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['email']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile Number Field -->
                    <div>
                        <label for="phone" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Mobile Number <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="left-0 absolute inset-y-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-phone"></i>
                            </div>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                required
                                value="<?= esc($old['phone'] ?? '') ?>"
                                aria-invalid="<?= isset($errors['phone']) ? 'true' : 'false' ?>"
                                aria-describedby="phone-error"
                                class="w-full pl-12 pr-4 py-3 border-2 <?= isset($errors['phone']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="+63 912 345 6789">
                        </div>
                        <?php if (!empty($errors['phone'])): ?>
                            <p id="phone-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['phone']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Address Field -->
                    <div>
                        <label for="address" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Address <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="top-4 left-0 absolute flex pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-map-marker-alt"></i>
                            </div>
                            <textarea
                                id="address"
                                name="address"
                                required
                                rows="3"
                                value="<?= esc($old['address'] ?? '') ?>"
                                aria-invalid="<?= isset($errors['address']) ? 'true' : 'false' ?>"
                                aria-describedby="address-error"
                                class="w-full pl-12 pr-4 py-3 border-2 <?= isset($errors['address']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="Street address, city, and postal code"><?= esc($old['address'] ?? '') ?></textarea>
                        </div>
                        <?php if (!empty($errors['address'])): ?>
                            <p id="address-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['address']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Password <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="left-0 absolute inset-y-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-lock"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
                                aria-describedby="password-error"
                                class="w-full pl-12 pr-12 py-3 border-2 <?= isset($errors['password']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="Create a strong password">
                            <button
                                type="button"
                                id="togglePassword"
                                class="right-0 absolute inset-y-0 flex items-center pr-4 text-coco-tan hover:text-coco-dark transition-colors duration-300">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <?php if (!empty($errors['password'])): ?>
                            <p id="password-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['password']) ?>
                            </p>
                        <?php endif; ?>
                        <div class="mt-2 text-coco-mid text-xs">
                            <p class="mb-1 font-semibold">Password must contain:</p>
                            <ul class="space-y-1 ml-2 list-disc list-inside">
                                <li id="length" class="text-red-500">At least 8 characters</li>
                                <li id="uppercase" class="text-red-500">One uppercase letter</li>
                                <li id="lowercase" class="text-red-500">One lowercase letter</li>
                                <li id="number" class="text-red-500">One number</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="confirm_password" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Confirm Password <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="left-0 absolute inset-y-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-lock"></i>
                            </div>
                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                required
                                aria-invalid="<?= isset($errors['confirm_password']) ? 'true' : 'false' ?>"
                                aria-describedby="confirm-password-error"
                                class="w-full pl-12 pr-12 py-3 border-2 <?= isset($errors['confirm_password']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="Confirm your password">
                            <button
                                type="button"
                                id="toggleConfirmPassword"
                                class="right-0 absolute inset-y-0 flex items-center pr-4 text-coco-tan hover:text-coco-dark transition-colors duration-300">
                                <i class="fas fa-eye" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                        <?php if (!empty($errors['confirm_password'])): ?>
                            <p id="confirm-password-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['confirm_password']) ?>
                            </p>
                        <?php endif; ?>
                        <p id="passwordMatch" class="hidden mt-1 text-xs"></p>
                    </div>

                    <!-- General Error Message -->
                    <?php if (!empty($errors['general'])): ?>
                        <div class="bg-red-50 p-4 border-red-500 border-l-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="text-red-500 fas fa-exclamation-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-700 text-sm">
                                        <?= esc($errors['general']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Terms and Privacy -->
                    <div class="flex items-start">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            required
                            <?= isset($old['terms']) && $old['terms'] ? 'checked' : '' ?>
                            class="mt-1 border-coco-sand rounded focus:ring-coco-orange w-4 h-4 text-coco-orange">
                        <label for="terms" class="ml-3 text-coco-mid text-sm leading-relaxed">
                            I agree to the
                            <a href="#" class="font-medium text-coco-orange hover:text-coco-dark transition-colors duration-300">Terms of Service</a>
                            and
                            <a href="#" class="font-medium text-coco-orange hover:text-coco-dark transition-colors duration-300">Privacy Policy</a>
                        </label>
                    </div>
                    <?php if (!empty($errors['terms'])): ?>
                        <p class="-mt-4 text-red-600 text-sm">
                            <?= esc($errors['terms']) ?>
                        </p>
                    <?php endif; ?>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        id="registerBtn"
                        disabled
                        class="bg-coco-orange hover:bg-coco-dark disabled:opacity-50 shadow-lg hover:shadow-xl py-3 rounded-lg w-full font-bold text-white text-lg hover:scale-[1.02] disabled:hover:scale-100 transition-all duration-300 disabled:cursor-not-allowed transform">
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-8 pt-6 border-coco-sand border-t text-center">
                    <p class="text-coco-mid">
                        Already have an account?
                        <a href="<?= base_url('login') ?>" class="font-semibold text-coco-orange hover:text-coco-dark transition-colors duration-300">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('components/footer') ?>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Confirm password toggle functionality
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirm_password');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);

            if (type === 'text') {
                eyeIconConfirm.classList.remove('fa-eye');
                eyeIconConfirm.classList.add('fa-eye-slash');
            } else {
                eyeIconConfirm.classList.remove('fa-eye-slash');
                eyeIconConfirm.classList.add('fa-eye');
            }
        });

        // Password validation
        const passwordInput = document.getElementById('password');
        const lengthCheck = document.getElementById('length');
        const uppercaseCheck = document.getElementById('uppercase');
        const lowercaseCheck = document.getElementById('lowercase');
        const numberCheck = document.getElementById('number');

        passwordInput.addEventListener('input', function() {
            const value = this.value;

            // Check length
            if (value.length >= 8) {
                lengthCheck.classList.remove('text-red-500');
                lengthCheck.classList.add('text-green-500');
            } else {
                lengthCheck.classList.remove('text-green-500');
                lengthCheck.classList.add('text-red-500');
            }

            // Check uppercase
            if (/[A-Z]/.test(value)) {
                uppercaseCheck.classList.remove('text-red-500');
                uppercaseCheck.classList.add('text-green-500');
            } else {
                uppercaseCheck.classList.remove('text-green-500');
                uppercaseCheck.classList.add('text-red-500');
            }

            // Check lowercase
            if (/[a-z]/.test(value)) {
                lowercaseCheck.classList.remove('text-red-500');
                lowercaseCheck.classList.add('text-green-500');
            } else {
                lowercaseCheck.classList.remove('text-green-500');
                lowercaseCheck.classList.add('text-red-500');
            }

            // Check number
            if (/[0-9]/.test(value)) {
                numberCheck.classList.remove('text-red-500');
                numberCheck.classList.add('text-green-500');
            } else {
                numberCheck.classList.remove('text-green-500');
                numberCheck.classList.add('text-red-500');
            }

            validateForm();
        });

        // Password match validation
        const passwordMatchMsg = document.getElementById('passwordMatch');

        confirmPassword.addEventListener('input', function() {
            if (this.value === '') {
                passwordMatchMsg.classList.add('hidden');
            } else if (this.value === passwordInput.value) {
                passwordMatchMsg.textContent = 'Passwords match ✓';
                passwordMatchMsg.classList.remove('text-red-500', 'hidden');
                passwordMatchMsg.classList.add('text-green-500');
            } else {
                passwordMatchMsg.textContent = 'Passwords do not match';
                passwordMatchMsg.classList.remove('text-green-500', 'hidden');
                passwordMatchMsg.classList.add('text-red-500');
            }
            validateForm();
        });

        // Form validation
        function validateForm() {
            const registerBtn = document.getElementById('registerBtn');
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            const isPasswordValid = lengthCheck.classList.contains('text-green-500') &&
                uppercaseCheck.classList.contains('text-green-500') &&
                lowercaseCheck.classList.contains('text-green-500') &&
                numberCheck.classList.contains('text-green-500');
            const isPasswordMatch = passwordInput.value === confirmPassword.value && confirmPassword.value !== '';
            const termsChecked = document.getElementById('terms').checked;

            registerBtn.disabled = !(fullname && email && phone && address && isPasswordValid && isPasswordMatch && termsChecked);
        }

        // Add event listeners
        document.getElementById('fullname').addEventListener('input', validateForm);
        document.getElementById('email').addEventListener('input', validateForm);
        document.getElementById('phone').addEventListener('input', validateForm);
        document.getElementById('address').addEventListener('input', validateForm);
        document.getElementById('terms').addEventListener('change', validateForm);
    </script>