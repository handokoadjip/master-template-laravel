<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__ . '/backoffice/user.php';
require __DIR__ . '/backoffice/group.php';
require __DIR__ . '/backoffice/action.php';
require __DIR__ . '/backoffice/menu.php';
require __DIR__ . '/backoffice/dashboard.php';
require __DIR__ . '/backoffice/work-unit.php';

require __DIR__ . '/example/crud.php';
require __DIR__ . '/example/crudonetoone.php';
require __DIR__ . '/example/crudonetomany.php';

require __DIR__ . '/auth.php';
