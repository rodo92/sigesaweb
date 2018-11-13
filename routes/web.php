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

Route::get('/', 'LoginController@index');
Route::resource('login', 'LoginController', ['only' => [ 'index', 'store', 'destroy'] ]);

Route::get('inicio', 'InicioController@index');
Route::get('menu/{idlistgrupo}', 'InicioController@menu');
Route::get('restaurar', 'InicioController@restaurar');

Route::get('sistema/get_data_session', 'SistemaController@get_data_session');
				
