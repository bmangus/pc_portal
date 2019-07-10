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

Route::redirect('/', '/public/extendedcare');

Route::get('/public/extendedcare', 'ExtendedCare@index');

Route::post('/ExtendedCareRegistration', 'ExtendedCare@submit');
