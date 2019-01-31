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
Route::get('sistema/especialidad/{idespecialidad?}', 'SistemaController@especialidades');
Route::get('sistema/especialidad_tipo_servicio/{idtiposervicio}', 'SistemaController@Especialidades_Tipo_Servicio');
Route::get('sistema/tiposervicios/{idtiposervicios?}', 'SistemaController@tiposervicios');
Route::get('sistema/servicios/{idespecialidad}', 'SistemaController@Servicio_Especialidad');
Route::get('sistema/qr_s/{cadena}', 'QRController@qr_simple')->name('qrsimple');
				
/**
 * Farmacia
 */
Route::get('reportegestion', 'FarmaciaController@reportegestion');
Route::get('reporalmacen', 'FarmaciaController@reporalmacen');
Route::get('reporfarmacia', 'FarmaciaController@reporfarmacia');
Route::get('farmacia/almacenes', 'FarmaciaController@almacenes');
Route::get('farmacia/farmacias/{tipo}', 'FarmaciaController@farmacias');
Route::get('NI', 'FarmaciaController@notaingresoalmacen');
Route::get('NS', 'FarmaciaController@notasalidaalmacen');

// Reportes Gestion
Route::get('reporteici/generar_dbf', 'ReporteICIController@generar_dbf');
Route::get('descargar_dbf_ici/{nombre}', 'ReporteICIController@descargar_dbf');

// Reportes Almaces
Route::post('farmacia/reporte_traslados', 'ReporteAlmacenController@reporte_traslados');
Route::post('farmacia/reporte_ingresos_almacen', 'ReporteAlmacenController@reporte_ingresos_almacen');
Route::get('farmacia/reporte_traslados_excel/{inicio}/{fin}/{idalmacen}', 'ReporteAlmacenController@reporte_traslados_excel');
Route::get('farmacia/reporte_ingresos_almacen_excel/{inicio}/{fin}/{ruc}', 'ReporteAlmacenController@reporte_ingresos_almacen_excel');

// Reportes Farmacia 
Route::get('farmacia/reporte_es_documentos/{inicio}/{fin}/{almacenid}/{movtipo}', 'ReporteFarmaciaController@reporte_entradas_salidas_documentos');
Route::get('farmacia/reporte_por_usuario/{inicio}/{fin}/{almacenid}', 'ReporteFarmaciaController@reporte_por_usuario');
Route::get('farmacia/reporte_venta_producto/{inicio}/{fin}/{almacenid}/{insumomedicamento}/{movinicio?}/{movfin?}', 'ReporteFarmaciaController@reporte_venta_producto_resumen');
Route::get('farmacia/reporte_es_documentos_excel/{inicio}/{fin}/{almacenid}/{movtipo}', 'ReporteFarmaciaController@reporte_entradas_salidas_documentos_excel');
Route::get('farmacia/reporte_por_usuario_excel/{inicio}/{fin}/{almacenid}', 'ReporteFarmaciaController@reporte_por_usuario_excel');
Route::get('farmacia/reporte_venta_producto_excel/{inicio}/{fin}/{almacenid}/{insumomedicamento}/{movinicio?}/{movfin?}', 'ReporteFarmaciaController@reporte_venta_producto_resumen_excel');


/**
 * Cajas
 */
Route::get('Cajas','CajaController@cajas');
Route::get('cajas/listar', 'CajaController@listar_cajas');
Route::get('cajas/listar_tipo_documento', 'CajaController@listar_caja_tipo_documento');
Route::post('cajas/aperturar_caja', 'CajaController@aperturar_caja');
Route::get('cajas/cerrar_caja/{IdGestionCaja}/{EstadoLote}/{FechaCierre}/{TotalCobrado}','CajaController@cierre_caja');
Route::get('cajas/tipo_seguro_paciente/{dni}', 'CajaController@tipo_seguro_paciente');
Route::get('cajas/servicios_medicamentos/{seguro}/{parametro}','CajaController@servicios_medicamentos');
Route::get('cajas/detalle_boleta/{serio}/{ndocumento}/{idorden?}','CajaController@buscar_detalle_boleta_x_codigo');	
Route::get('cajas/detalle_cuenta/{cuenta}','CajaController@buscar_boleta_x_cuenta');
Route::get('cajas/nuevo_correlativo/{idcaja}/{idtipocomprobante}','CajaController@correlativo');
Route::get('cajas/nuevo_proveedor/{ruc}/{razonsocial}/{direccion}','CajaController@Crear_Nuevo_Proveedor');
Route::get('cajas/reporte_facturas/{fechainicio}/{fechafin}','CajaController@Listar_Facturas_Reportes');
Route::get('cajas/eliminar_factura/{idcajafacturacion}','CajaController@Eliminacion_Facturas_Reporte');

// Facturación Electrónica
Route::post('cajas/registro_factura','CajaController@registro_factura');
Route::get('cajas/generar_pdf/{idorden}','PDFComprobantesController@generar');
Route::get('cajas/generar_pdf_partida/{idorden}','PDFComprobantesController@generar_partida');
Route::get('cajas/generar_ticket/{idorden}','PDFTicketController@generar');

// Caja central
Route::get('cajasc','CajaController@cajas_central');
Route::get('protocolo/nuevo/{nombre}/{precio}','CajaController@nuevo_protocolo');

// Cajas Farmacia
Route::get('cajasf','CajaController@cajas_farmacia');
Route::get('cajas/detalle_orden/{idorden}','CajaController@datos_orden_farmacia');

// Cajeros
Route::get('cajascajeros/orden/{idorder}','CajaController@datos_orden_caja');

/*
Consulta externa
*/

// Admision y citas
Route::get('AdmisionCE','AdmisionController@AdmisionCitas');
Route::get('AdmisionCE/especialidades','AdmisionController@Especialidades_Tipo_Servicio');
Route::get('AdmisionCE/medicos','AdmisionController@Medicos_CE');
Route::get('AdmisionCE/programacion/{fecha}/{especialidad?}/{medico?}','AdmisionController@Programacion_Por_Filtro');

/*
Programacion General
*/

// Medico
Route::resource('Medico', 'MedicoController');

/*Archivo*/

// Reportes de Archivo
Route::get('reporarchivo','ArchivoController@reporarchivo');
Route::get('Archivo/reporte_consejeria/{turno}/{fecha}','ArchivoController@Reporte_Conserjeria');
Route::get('Archivo/reporte_listado_citados/{turno}/{fecha}/{si}/{sf}','ArchivoController@Reporte_Listado_Citados');
Route::get('Archivo/reporte_consejeria_excel/{turno}/{fecha}','ArchivoController@Reporte_Conserjeria_Excel');
Route::get('Archivo/reporte_listado_citados_excel/{turno}/{fecha}/{si}/{sf}','ArchivoController@Reporte_Listado_Citados_Excel');

// Digito Terminal
Route::get('Archivero','ArchivoController@archivero');
Route::get('Archivero/listar','ArchivoController@listar_archiveros_detallados');
Route::get('Archivero/listar_dni/{dni}','ArchivoController@listar_archiveros_detallados_dni');
Route::get('Archivero/eliminar/{idarchivodigitoterminal}','ArchivoController@eliminar_archivero_id');
Route::get('Archivero/buscar/{dni}','ArchivoController@buscar_archivero');
Route::get('Archivero/nuevodigitoterminal/{digitoinicial}/{digitofinal}/{idempleado}','ArchivoController@nuevo_digito_terminal');

// Rutas
Route::get('rutas','RutasController@index');
Route::post('rutas/registrar_ruta', 'RutasController@registrar_ruta');
Route::post('rutas/editar_ruta', 'RutasController@editar_ruta');
Route::get('rutas/listar', 'RutasController@mostrar_rutas');
Route::get('rutas/ruta_detalle/{idrutas}', 'RutasController@mostrar_rutas_detalle');
Route::get('rutas/ruta_eliminar/{idrutas}', 'RutasController@eliminar_ruta');
Route::get('rutas/servicios/{idrutas}', 'RutasController@mostrar_servicios_ruta');

// Movimiento de Historias
Route::get('MovimientoHistoria','MovimientoHistoriaController@index');
Route::get('MovimientoHistoria/listado_historias_archivero','MovimientoHistoriaController@listado_historias_archivero');
Route::get('MovimientoHistoria/noencontradohistoria/{idhistoriasolicitada}', 'MovimientoHistoriaController@estado_no_encontrado_historia_archiver');
Route::get('MovimientoHistoria/encontradohistoria/{idhistoriasolicitada}', 'MovimientoHistoriaController@estado_encontrado_historia_archiver');
Route::get('MovimientoHistoria/imprimirlistadoarchivero', 'MovimientoHistoriaController@excel_listado_archivero');
Route::get('MovimientoHistoria/historiasenrutadas', 'MovimientoHistoriaController@Listado_Historias_Enrutadas');
Route::get('MovimientoHistoria/historiasenrutadasexcel', 'MovimientoHistoriaController@Listado_Historias_Enrutadas_Excel');
Route::get('MovimientoHistoria/listadoconserje', 'MovimientoHistoriaController@Listado_Historias_Conserje');
Route::get('MovimientoHistoria/generarlistadosconserje/', 'MovimientoHistoriaController@generar_listados_conserje');
Route::get('MovimientoHistoria/darsalidaconserje/{idhistoriasolicitada}', 'MovimientoHistoriaController@Dar_Salida_Historia_Conserje');
Route::get('MovimientoHistoria/darrecepcionconserje/{idhistoriasolicitada}', 'MovimientoHistoriaController@Dar_Recepcion_Historia_Conserje');
Route::post('MovimientoHistoria/salidatodosconserje', 'MovimientoHistoriaController@Dar_Salida_Toddas_Historia_Conserje');
Route::post('MovimientoHistoria/recepciontodosconserje', 'MovimientoHistoriaController@Dar_Recepcion_Toddas_Historia_Conserje');
Route::get('MovimientoHistoria/nosalidaconserje/{idhistoriasolicitada}','MovimientoHistoriaController@No_Dar_Salida_Historia_Conserje');
Route::get('MovimientoHistoria/norecepciontodosconserje/{idhistoriasolicitada}/{motivo}','MovimientoHistoriaController@No_Dar_Recepcion_Historia_Conserje');
Route::get('MovimientoHistoria/listadoarchiverocitadosdia', 'MovimientoHistoriaController@citados_del_dia_archiver');