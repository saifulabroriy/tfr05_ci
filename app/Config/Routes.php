<?php

namespace Config;

use App\Controllers\Barang;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');

// Barang
$routes->get('admin/barang', 'Barang::index');
$routes->get('admin/barang/create', 'Barang::create');
$routes->post('admin/barang/store', 'Barang::store');
$routes->get('admin/barang/(:segment)/edit', 'Barang::edit/$1');
$routes->put('admin/barang/(:segment)', 'Barang::update/$1');
$routes->delete('admin/barang/(:segment)', 'Barang::delete/$1');

// Kategori
$routes->get('admin/kategori', 'Kategori::index');
$routes->get('admin/kategori/create', 'Kategori::create');
$routes->post('admin/kategori/store', 'Kategori::store');
$routes->get('admin/kategori/(:segment)/edit', 'Kategori::edit/$1');
$routes->put('admin/kategori/(:segment)', 'Kategori::update/$1');
$routes->delete('admin/kategori/(:segment)', 'Kategori::delete/$1');





/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
