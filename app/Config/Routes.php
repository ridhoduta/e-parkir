<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

$routes->get('/owner/dashboard', 'Owner\Dashboard::index');
$routes->get('/admin/dashboard', 'Admin\Dashboard::index');
$routes->get('/petugas/dashboard', 'Petugas\Dashboard::index');

$routes->group('owner', function ($routes) {
    $routes->get('profile', 'Owner\ProfileController::index');
    $routes->post('profile/update', 'Owner\ProfileController::update');

    $routes->get('areas', 'Owner\AreaController::index');
    $routes->get('areas/show/(:num)', 'Owner\AreaController::show/$1');

    $routes->get('kapasitas', 'Owner\KapasitasController::index');

    $routes->get('report', 'Owner\ReportController::index');
$routes->get('report/export-excel', 'Owner\ReportController::exportExcel');
$routes->get('report/export-pdf', 'Owner\ReportController::exportPdf');
    });

$routes->group('admin', function ($routes) {
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\UserController::delete/$1');

    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/update', 'Admin\ProfileController::update');

    $routes->get('areas', 'Admin\AreaController::index');
    $routes->get('areas/create', 'Admin\AreaController::create');
    $routes->post('areas/store', 'Admin\AreaController::store');
    $routes->get('areas/edit/(:num)', 'Admin\AreaController::edit/$1');
    $routes->post('areas/update/(:num)', 'Admin\AreaController::update/$1');
    $routes->get('areas/delete/(:num)', 'Admin\AreaController::delete/$1');

    $routes->get('tipe_kendaraan', 'Admin\TipeKendaraanController::index');
    $routes->get('tipe_kendaraan/create', 'Admin\TipeKendaraanController::create');
    $routes->post('tipe_kendaraan/store', 'Admin\TipeKendaraanController::store');
    $routes->get('tipe_kendaraan/edit/(:num)', 'Admin\TipeKendaraanController::edit/$1');
    $routes->post('tipe_kendaraan/update/(:num)', 'Admin\TipeKendaraanController::update/$1');
    $routes->get('tipe_kendaraan/delete/(:num)', 'Admin\TipeKendaraanController::delete/$1');

    $routes->get('tarif_parkir', 'Admin\TarifParkirController::index');
    $routes->get('tarif_parkir/create', 'Admin\TarifParkirController::create');
    $routes->post('tarif_parkir/store', 'Admin\TarifParkirController::store');
    $routes->get('tarif_parkir/edit/(:num)', 'Admin\TarifParkirController::edit/$1');
    $routes->post('tarif_parkir/update/(:num)', 'Admin\TarifParkirController::update/$1');
    $routes->get('tarif_parkir/delete/(:num)', 'Admin\TarifParkirController::delete/$1');

    $routes->get('kendaraan', 'Admin\KendaraanController::index');
    $routes->get('kendaraan/create', 'Admin\KendaraanController::create');
    $routes->post('kendaraan/store', 'Admin\KendaraanController::store');
    $routes->get('kendaraan/edit/(:num)', 'Admin\KendaraanController::edit/$1');
    $routes->post('kendaraan/update/(:num)', 'Admin\KendaraanController::update/$1');
    $routes->get('kendaraan/delete/(:num)', 'Admin\KendaraanController::delete/$1');

    $routes->get('members', 'Admin\MemberController::index');
    $routes->get('members/create', 'Admin\MemberController::create');
    $routes->post('members/store', 'Admin\MemberController::store');
    $routes->get('members/edit/(:num)', 'Admin\MemberController::edit/$1');
    $routes->post('members/update/(:num)', 'Admin\MemberController::update/$1');
    $routes->get('members/delete/(:num)', 'Admin\MemberController::delete/$1');

    $routes->get('member_types', 'Admin\MemberTypeController::index');
    $routes->get('member_types/create', 'Admin\MemberTypeController::create');
    $routes->post('member_types/store', 'Admin\MemberTypeController::store');
    $routes->get('member_types/edit/(:num)', 'Admin\MemberTypeController::edit/$1');
    $routes->post('member_types/update/(:num)', 'Admin\MemberTypeController::update/$1');
    $routes->get('member_types/delete/(:num)', 'Admin\MemberTypeController::delete/$1');

    $routes->get('report', 'Admin\ReportController::index');
    $routes->get('report/export-excel', 'Admin\ReportController::exportExcel');
    $routes->get('report/export-pdf', 'Admin\ReportController::exportPdf');

    $routes->get('transaksi', 'Admin\TransaksiController::index');
    $routes->get('transaksi/delete/(:num)', 'Admin\TransaksiController::delete/$1');

    $routes->get('logs', 'Admin\LogController::index');

    $routes->get('backup', 'Admin\BackupController::index');
    $routes->get('backup/download', 'Admin\BackupController::download');

});

$routes->group('petugas', function ($routes) {
    $routes->get('profile', 'Petugas\ProfileController::index');
    $routes->post('profile/update', 'Petugas\ProfileController::update');

    $routes->get('areas', 'Petugas\AreaController::index');
    $routes->get('areas/show/(:num)', 'Petugas\AreaController::show/$1');

    $routes->get('kapasitas', 'Petugas\KapasitasController::index');

    $routes->get('parkir', 'Petugas\ParkirController::index');
    $routes->get('parkir/masuk', 'Petugas\ParkirController::masuk');
    $routes->post('parkir/store-masuk', 'Petugas\ParkirController::storeMasuk');
    $routes->get('parkir/tiket/(:any)', 'Petugas\ParkirController::tiket/$1');
    $routes->get('parkir/keluar', 'Petugas\ParkirController::keluar');
    $routes->post('parkir/proses-keluar', 'Petugas\ParkirController::prosesKeluar');
    $routes->get('parkir/pembayaran/(:num)', 'Petugas\ParkirController::pembayaran/$1');
    $routes->post('parkir/proses-pembayaran/(:num)', 'Petugas\ParkirController::prosesPembayaran/$1');
    $routes->get('parkir/struk/(:num)', 'Petugas\ParkirController::struk/$1');
});
