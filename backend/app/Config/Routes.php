<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Public ─────────────────────────────────────────────────────────────────
$routes->get('/',        'Users::index');
$routes->get('about',    'Users::about');
$routes->get('products', 'Users::products');

// ── Cart ───────────────────────────────────────────────────────────────────
$routes->get('cart',          'Users::cart');
$routes->post('cart/add',     'Users::addToCart');
$routes->post('cart/update',  'Users::updateCart');
$routes->post('cart/remove',  'Users::removeFromCart');

// ── Checkout ───────────────────────────────────────────────────────────────
$routes->get('checkout',        'Users::checkout');
$routes->post('checkout/place', 'Users::placeOrder');

// ── Orders ─────────────────────────────────────────────────────────────────
$routes->get('orders', 'Users::orders');

// ── Profile ────────────────────────────────────────────────────────────────
$routes->get('profile',          'Users::profile');
$routes->post('profile/update',  'Users::updateProfile');
$routes->post('profile/avatar',  'Users::updateAvatar');

// ── Authentication ─────────────────────────────────────────────────────────
$routes->get('/login',           'Auth::login');
$routes->post('/auth/login',     'Auth::authenticate');
$routes->post('/auth/logout',    'Auth::logout');
$routes->get('/logout',          'Auth::logout');
$routes->get('/signup',          'Auth::signup');
$routes->post('/auth/register',  'Auth::register');
$routes->get('/forgot-password', 'Auth::forgotPassword');

// ── Admin ──────────────────────────────────────────────────────────────────
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('products',  'Admin::products');
    $routes->get('orders',    'Admin::orders');
    $routes->get('accounts',  'Admin::accounts');
});