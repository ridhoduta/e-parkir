/**
 * CONTOH ROUTES UNTUK SOFT DELETE
 * 
 * Tambahkan routes ini ke app/Config/Routes.php
 */

// ========== ADMIN ROUTES ==========
$routes->group('admin', ['filter' => 'auth'], static function($routes) {
    
    // ===== USER MANAGEMENT =====
    $routes->get('users', 'Admin\ManageUsers::index');                      // List user aktif
    $routes->get('users/trash', 'Admin\ManageUsers::trash');                // List user terhapus
    $routes->get('users/show/(:num)', 'Admin\ManageUsers::show/$1');        // Detail user
    $routes->delete('users/delete/(:num)', 'Admin\ManageUsers::delete/$1'); // Soft delete
    $routes->patch('users/restore/(:num)', 'Admin\ManageUsers::restore/$1');// Restore
    $routes->delete('users/destroy/(:num)', 'Admin\ManageUsers::destroy/$1');// Hard delete
    
});

// ========== MEMBER ROUTES ==========
$routes->group('member', ['filter' => 'auth'], static function($routes) {
    
    $routes->get('list', 'Member\ManageMembers::index');                    // List member aktif
    $routes->get('trash', 'Member\ManageMembers::trash');                   // List member terhapus
    $routes->delete('delete/(:num)', 'Member\ManageMembers::delete/$1');    // Soft delete
    $routes->patch('restore/(:num)', 'Member\ManageMembers::restore/$1');   // Restore
    
});

// ========== TRANSAKSI ROUTES ==========
$routes->group('transaksi', ['filter' => 'auth'], static function($routes) {
    
    $routes->get('list', 'Transaksi\ManageTransaksi::index');               // List transaksi aktif
    $routes->get('trash', 'Transaksi\ManageTransaksi::trash');              // List transaksi terhapus
    $routes->delete('delete/(:num)', 'Transaksi\ManageTransaksi::delete/$1');// Soft delete
    
});
