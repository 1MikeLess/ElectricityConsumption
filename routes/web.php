<?php

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
    return view('welcome');
});

// Route::get(
//     '{uri}',
//     '\\'.Pallares\LaravelNuxt\Controllers\NuxtController::class
// )->where('uri', '.*');

Route::get('/get_hello', function() {
	return 'Hello! [OK]';
});