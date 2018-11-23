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
Route::get('sistema/proveedores', 'SistemaController@proveedores');
Route::get('sistema/proveedor/{ruc}', 'SistemaController@proveedor');
				
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
Route::post('farmacia/reporte_ingresos_almacen', 'ReporteAlmacenController@reporte_ingresos_almacen');
Route::get('farmacia/reporte_traslados_excel/{inicio}/{fin}/{idalmacen}', 'ReporteAlmacenController@reporte_traslados_excel');
Route::get('farmacia/reporte_ingresos_almacen_excel/{inicio}/{fin}/{ruc}', 'ReporteAlmacenController@reporte_ingresos_almacen_excel');


/**
 * Cajas
 */
Route::get('Cajas','CajaController@cajas');
Route::get('cajas/listar', 'CajaController@listar_cajas');
Route::get('cajas/listar_tipo_documento', 'CajaController@listar_caja_tipo_documento');
Route::post('cajas/aperturar_caja', 'CajaController@aperturar_caja');
Route::get('cajas/tipo_seguro_paciente/{dni}', 'CajaController@tipo_seguro_paciente');
Route::get('cajas/servicios_medicamentos/{seguro}/{parametro}','CajaController@servicios_medicamentos');
Route::get('cajas/detalle_boleta/{serio}/{ndocumento}','CajaController@buscar_detalle_boleta_x_codigo');	