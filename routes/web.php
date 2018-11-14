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

/**
 * Login
 */
Route::get('/', 'LoginController@index');
Route::resource('login', 'LoginController', ['only' => [ 'index', 'store', 'destroy'] ]);

/**
 * Rutas del sistema
 */
Route::get('inicio', 'InicioController@index');
Route::get('menu/{idlistgrupo}', 'InicioController@menu');
Route::get('restaurar', 'InicioController@restaurar');
Route::get('sistema/get_data_session', 'SistemaController@get_data_session');
				
/**
 * Farmacia
 */
Route::get('reportegestion', 'FarmaciaController@reportegestion');
Route::get('reporalmacen', 'FarmaciaController@reporalmacen');
Route::get('farmacia/almacenes', 'FarmaciaController@almacenes');

// Reportes Gestion
Route::post('reporteici/generar_dbf', 'ReporteICIController@generar_dbf');
Route::get('descargar_dbf_ici/{nombre}', 'ReporteICIController@descargar_dbf');

// Reportes Almaces
Route::post('farmacia/reporte_traslados', 'ReporteAlmacenController@reporte_traslados');