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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get(
//     '{uri}',
//     '\\'.Pallares\LaravelNuxt\Controllers\NuxtController::class
// )->where('uri', '.*');

// Route::get('/get_hello', function() {
// 	return 'Hello! [OK]';
// });

// CATEGORIES
Route::get('db_query_get_categories', 'DbQuery@selectAllCategories');
Route::get('db_query_get_category_values/{category_id}', 'DbQuery@selectCategoryValues');
Route::get('db_query_get_category_values', 'DbQuery@selectAllCategoryValues');
Route::post('db_query_add_category_value/{category_id?}/{category_value_name?}', 'DbQuery@addCategoryValue');
Route::post('db_query_add_category/{category_id}/{category_name}', 'DbQuery@addCategory');
Route::post('db_query_delete_category/{category_id}', 'DbQuery@deleteCategory');

// USERS
Route::get('db_query_get_users', 'DbQuery@selectAllUsers');
Route::post('db_query_add_user/{user_id?}/{user_name?}/{user_email?}/{user_password?}', 'DbQuery@addUser');
Route::post('db_query_delete_user/{user_id?}', 'DbQuery@deleteUser');

// EQUIPMENT
Route::get('db_query_get_equipment', 'DbQuery@selectAllEquipment');
Route::post('db_query_add_equipment/{equipment_info}', 'DbQuery@insertEquipment');
Route::post('db_query_remove_equipment/{equipment_id}', 'DbQuery@deleteEquipment');

// EQUIPMENT CATEGORIES
Route::get('db_query_get_equipment_categories/{equipment_id?}', 'DbQuery@selectEquipmentCategories');
// Route::post('db_query_remove_all_equipment_categories/{equipment_id?}', 'DbQuery@deleteAllEquipmentCategories');

// REPORT CHARTS
Route::get('get_electricity_consumption_info', 'ChartsQuery@getInfo');
 