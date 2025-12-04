<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('/landing', 'AuthController::landing');
$routes->match(['GET', 'POST'], '/login', 'AuthController::login');
$routes->match(['GET', 'POST'], '/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');
$routes->match(['GET', 'POST'], '/onboarding', 'AuthController::onboarding', ['filter' => 'auth']);

// Invitation routes
$routes->match(['GET', 'POST'], '/invite/accept/(:any)', 'HouseholdController::acceptInvite/$1');

// Protected routes - Dashboard
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

// Protected routes - Households
$routes->group('household', ['filter' => 'auth'], function($routes) {
    $routes->match(['GET', 'POST'], 'create', 'HouseholdController::create');
    $routes->get('switch/(:num)', 'HouseholdController::switch/$1');
    $routes->match(['GET', 'POST'], 'settings', 'HouseholdController::settings');
    $routes->match(['GET', 'POST'], 'invite', 'HouseholdController::invite');
    $routes->post('member/remove/(:num)', 'HouseholdController::removeMember/$1');
});

// Protected routes - Items
$routes->group('items', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ItemController::index');
    $routes->get('add', 'ItemController::create');
    $routes->post('add', 'ItemController::store');
    $routes->get('edit/(:num)', 'ItemController::edit/$1');
    $routes->post('edit/(:num)', 'ItemController::update/$1');
    $routes->post('delete/(:num)', 'ItemController::delete/$1');
});

// Protected routes - Places
$routes->group('places', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PlaceController::index');
    $routes->get('add', 'PlaceController::create');
    $routes->post('add', 'PlaceController::store');
    $routes->get('edit/(:num)', 'PlaceController::edit/$1');
    $routes->post('edit/(:num)', 'PlaceController::update/$1');
    $routes->post('delete/(:num)', 'PlaceController::delete/$1');
});
