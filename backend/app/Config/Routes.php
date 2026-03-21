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
    $routes->get('dashboard',          'Admin::dashboard');

    // Storefront
    $routes->get('storefront',         'Admin::storefront');
    $routes->post('storefront/save',   'Admin::saveStorefront');

    // Inventory
    $routes->get('products',           'Admin::products');
    $routes->post('products/save',     'Admin::saveProduct');
    $routes->post('products/toggle',   'Admin::toggleProduct');
    $routes->post('products/delete',   'Admin::deleteProduct');

    // Orders
    $routes->get('orders',             'Admin::orders');
    $routes->post('orders/status',     'Admin::updateOrderStatus');

    // Reports
    $routes->get('reports',            'Admin::reports');

    // Accounts Management Routes
    $routes->get('accounts', 'Admin::accounts');
    $routes->post('accounts/save', 'Admin::saveAccount');
    $routes->post('accounts/delete', 'Admin::deleteAccount');
    $routes->post('accounts/toggle', 'Admin::toggleAccountStatus');
});
