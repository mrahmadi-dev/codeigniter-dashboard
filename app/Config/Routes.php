<?php

namespace Config;

// Create a new instance of our RouteCollection class.
use App\Controllers\Auth;
use App\Controllers\Dashboard;
use App\Controllers\News;
use App\Controllers\Pages;
use App\Controllers\User;

$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/',[Dashboard::class,'index'],['filter' => 'authGuard']);

$routes->group('admin',static function($routes){
    $routes->match(['get','post'],'profile', [User::class,'profile'],['filter' => 'authGuard']);
    $routes->get('profile/deactivateAccount', [User::class,'deactivateAccount'],['filter' => 'authGuard']);
    $routes->get('/',[Dashboard::class,'index'],['filter' => 'authGuard']);
});

$routes->group('auth',static function($routes) {
    $routes->match(['get','post'],'login',[Auth::class,'login']);
    $routes->match(['get','post'],'register',[Auth::class,'register']);
    $routes->match(['get','post'],'forgetPassword',[Auth::class,'forgetPassword']);
    $routes->match(['get','post'],'changePassword/(:segment)',[Auth::class,'changePassword']);
    $routes->get('emailVerification/(:segment)',[Auth::class,'verifyEmail']);
    $routes->get('logout',[Auth::class,'logout']);
});


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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
