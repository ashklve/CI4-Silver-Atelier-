<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Users::index');
$routes->get('/login', 'Users::login');
$routes->get('/signup', 'Users::signup');
$routes->get('/signup', 'Users::signup');
$routes->get('/moodboard', 'Users::moodBoard');
$routes->get('/roadmap', 'Users::roadMapPage');

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
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('products', 'Admin::products');
    $routes->get('orders', 'Admin::orders');
    $routes->get('accounts', 'Admin::accounts');
});
