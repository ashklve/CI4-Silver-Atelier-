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
        <div class="w-full max-w-md">
            <!-- Welcome Section -->
            <div class="mb-12 text-center">
                <h1 class="mb-4 font-display font-black text-coco-brown text-5xl sm:text-6xl leading-tight">
                    Welcome Back
                </h1>
                <p class="font-light text-coco-mid text-lg">
                    Sign in to your COCOIR account
                </p>
            </div>

            <!-- Login Form -->
            <div class="bg-white shadow-2xl p-8 md:p-12 rounded-2xl">
                <form id="loginForm" class="space-y-6" action="<?= base_url('auth/login') ?>" method="POST" novalidate>
                    <?= csrf_field() ?>

                    <!-- Email/Username Field -->
                    <div>
                        <label for="username" class="block mb-2 font-semibold text-coco-brown text-sm">
                            Email or Username <span class="text-coco-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="left-0 absolute inset-y-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-coco-green fas fa-at"></i>
                            </div>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                required
                                value="<?= esc($old['username'] ?? '') ?>"
                                aria-invalid="<?= isset($errors['username']) ? 'true' : 'false' ?>"
                                aria-describedby="username-error"
                                class="w-full pl-12 pr-4 py-3 border-2 <?= isset($errors['username']) ? 'border-red-500' : 'border-coco-sand' ?> rounded-lg focus:outline-none focus:ring-4 focus:ring-coco-orange/20 focus:border-coco-orange text-coco-brown placeholder-coco-tan bg-coco-cream transition-all duration-300"
                                placeholder="Enter your email or username">
                        </div>
                        <?php if (!empty($errors['username'])): ?>
                            <p id="username-error" class="mt-2 text-red-600 text-sm">
                                <?= esc($errors['username']) ?>
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
                                placeholder="Enter your password">
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

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="border-coco-sand rounded focus:ring-coco-orange w-4 h-4 text-coco-orange">
                            <label for="remember" class="ml-2 text-coco-mid text-sm">
                                Remember me
                            </label>
                        </div>
                        <a href="<?= base_url('forgot-password') ?>" class="font-medium text-coco-orange hover:text-coco-dark text-sm transition-colors duration-300">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="bg-coco-orange hover:bg-coco-dark shadow-lg hover:shadow-xl py-3 rounded-lg w-full font-bold text-white text-lg hover:scale-[1.02] transition-all duration-300 transform">
                        Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="mt-8 pt-6 border-coco-sand border-t text-center">
                    <p class="text-coco-mid">
                        Don't have an account?
                        <a href="<?= base_url('signup') ?>" class="font-semibold text-coco-orange hover:text-coco-dark transition-colors duration-300">
                            Sign up here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('components/footer') ?>

    <script>
        document.getElementById('togglePassword').addEventListener('click', () => {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const isHidden = password.type === 'password';
            password.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>