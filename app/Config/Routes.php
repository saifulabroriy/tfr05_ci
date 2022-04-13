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
$routes->post('/login', 'Login::login');
$routes->post('/logout', 'Login::logout');

$routes->get('/admin', 'Admin::index', ['filter' => 'ceklogin']);

// Barang
$routes->get('/admin/barang', 'Barang::index', ['filter' => 'ceklogin']);
$routes->get('/admin/barang/create', 'Barang::create', ['filter' => 'ceklogin']);
$routes->post('/admin/barang/store', 'Barang::store', ['filter' => 'ceklogin']);
$routes->get('/admin/barang/(:segment)/edit', 'Barang::edit/$1', ['filter' => 'ceklogin']);
$routes->put('/admin/barang/(:segment)', 'Barang::update/$1', ['filter' => 'ceklogin']);
$routes->delete('/admin/barang/(:segment)', 'Barang::delete/$1', ['filter' => 'ceklogin']);
$routes->get('/admin/barang/previewpdf', 'Barang::previewPDF', ['filter' => 'ceklogin']);
$routes->post('/admin/barang/exportpdf', 'Barang::exportPDF', ['filter' => 'ceklogin']);
$routes->post('/admin/barang/exportexcel', 'Barang::exportExcel', ['filter' => 'ceklogin']);

// Kategori
$routes->get('/admin/kategori', 'Kategori::index', ['filter' => 'ceklogin']);
$routes->get('/admin/kategori/create', 'Kategori::create', ['filter' => 'ceklogin']);
$routes->post('/admin/kategori/store', 'Kategori::store', ['filter' => 'ceklogin']);
$routes->get('/admin/kategori/(:segment)/edit', 'Kategori::edit/$1', ['filter' => 'ceklogin']);
$routes->put('/admin/kategori/(:segment)', 'Kategori::update/$1', ['filter' => 'ceklogin']);
$routes->delete('/admin/kategori/(:segment)', 'Kategori::delete/$1', ['filter' => 'ceklogin']);
$routes->get('/admin/kategori/previewpdf', 'Kategori::previewPDF', ['filter' => 'ceklogin']);
$routes->post('/admin/kategori/exportpdf', 'Kategori::exportPDF', ['filter' => 'ceklogin']);
$routes->post('/admin/kategori/exportexcel', 'Kategori::exportExcel');

// Pelanggan
$routes->get('/admin/pelanggan', 'Pelanggan::index', ['filter' => 'ceklogin']);
$routes->get('/admin/pelanggan/create', 'Pelanggan::create', ['filter' => 'ceklogin']);
$routes->post('/admin/pelanggan/store', 'Pelanggan::store', ['filter' => 'ceklogin']);
$routes->get('/admin/pelanggan/(:segment)/edit', 'Pelanggan::edit/$1', ['filter' => 'ceklogin']);
$routes->put('/admin/pelanggan/(:segment)', 'Pelanggan::update/$1', ['filter' => 'ceklogin']);
$routes->delete('/admin/pelanggan/(:segment)', 'Pelanggan::delete/$1', ['filter' => 'ceklogin']);
$routes->get('/admin/pelanggan/previewpdf', 'Pelanggan::previewPDF', ['filter' => 'ceklogin']);
$routes->post('/admin/pelanggan/exportpdf', 'Pelanggan::exportPDF', ['filter' => 'ceklogin']);
$routes->post('/admin/pelanggan/exportexcel', 'Pelanggan::exportExcel', ['filter' => 'ceklogin']);

// Routing Transaksi Penjualan
$routes->get('/admin/penjualan', 'Penjualan::create', ['filter' => 'ceklogin']);
$routes->post('/admin/penjualan', 'Penjualan::store', ['filter' => 'ceklogin']);
$routes->get('/admin/penjualan/pilihbarang', 'Penjualan::pilihBarang', ['filter' => 'ceklogin']);
$routes->post('/admin/penjualan/centang', 'Penjualan::centang', ['filter' => 'ceklogin']);
$routes->post('/admin/penjualan/uncentang', 'Penjualan::uncentang', ['filter' => 'ceklogin']);
$routes->post('/admin/penjualan/setsession', 'Penjualan::setSession', ['filter' => 'ceklogin']);
$routes->post('/admin/penjualan/hapusbarang', 'Penjualan::hapusBarang', ['filter' => 'ceklogin']);
$routes->post('/admin/penjualan/cekstok', 'Penjualan::cekStok', ['filter' => 'ceklogin']);

// Log
$routes->get('/admin/log', 'Log::index', ['filter' => 'ceklogin']);
$routes->post('/admin/log/exportpdf', 'Log::exportPDF', ['filter' => 'ceklogin']);
$routes->get('/admin/log/previewpdf', 'Log::previewPDF', ['filter' => 'ceklogin']);

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
