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
Auth::routes();


// Protected Routes - allows only logged in users
Route::middleware('is_admin')->group(function () {
    Route::resource('package', 'PackageController');

});
