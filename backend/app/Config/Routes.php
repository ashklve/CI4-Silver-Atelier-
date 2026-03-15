<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Users::index');
$routes->get('products', 'Users::products');
$routes->get('cart', 'Users::cart');
$routes->post('cart/add', 'Users::addToCart');
$routes->post('cart/update', 'Users::updateCart');
$routes->post('cart/remove', 'Users::removeFromCart');

// authentication routes
$routes->get('/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::authenticate');

// routes for handling user logout (both POST and GET methods)
$routes->post('/auth/logout', 'Auth::logout');
$routes->get('/logout', 'Auth::logout');

$routes->get('/signup', 'Auth::signup');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/forgot-password', 'Auth::forgotPassword');

// admin routes (protected)
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('products', 'Admin::products');
    $routes->get('orders', 'Admin::orders');
    $routes->get('accounts', 'Admin::accounts');
});
