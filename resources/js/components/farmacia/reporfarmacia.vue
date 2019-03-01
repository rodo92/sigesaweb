<template>
    
    <div>
        <section class="content-header">
            <h1>
                Reporte de Farmacia
                <small>Farmacia</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Farmacia</a></li>
                <li class="active">Reporte de Farmacia</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">

            <!-- ENTRADAS Y SALIDAS DE DOCUMENTOS -->
            <div id="entradas_salidas_documentos">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte de Movimientos de Entrada y Salida de Documentos</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Inicio:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio" :value="inicio">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Fin:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_fin" :value="fin">
                                    </div>
                                </td>
                                <td width="25%" style="padding-right: 5px;">
                                    <label for="">Farmacia:</label>
                                    <div class="input-group">
                                        <select name="" id="id_farmacia" class="form-control" v-model="farmaciaid">
                                            <option value="0">TODOS</option>
                                            option
                                            <option v-for="farmacia in farmacias" :value="farmacia.idAlmacen">
                                                {{ farmacia.descripcion }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td width="30%" style="padding-right: 5px;">
                                    <label for="">Tipo Movimiento:</label>
                                    <div class="input-group">
                                        <select name="" id="id_movtipo" class="form-control" v-model="movtipo">
                                            <option value="0">Seleccione</option>
                                            <option value="E">ENTRADAS</option>
                                            <option value="S">SALIDAS</option>
                                        </select>
                                    </div>
                                </td>
                                <td width="15%" class="text-center">
                                    <button class="btn btn-primary" v-on:click.prevent="postData">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-success" v-on:click.prevent="excelExport">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                                    <a class="btn btn-danger" v-on:click.prevent="pdfExport">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table table-bordered table-striped" id="tabla_es_documentos" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>FECHA</th>
                                <th>MOVTIPO</th>
                                <th>MOVNUMERO</th>
                                <th>USUARIO</th>
                                <th>DOCUMENTONUMERO</th>
                                <th>DOCUMENTO</th>
                                <th>ORIGEN</th>
                                <th>OBSERVACIONES</th>
                                <th>DESTINO</th>
                                <th>ESTADO</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- FIN ENTRADAS Y SALIDAS DE DOCUMENTOS -->

            <!-- REPORTES POR USUARIO -->
            <div id="reportes_por_usuario">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte por Usuario</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Inicio:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio_ru" :value="inicio_ru">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Fin:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_fin_ru" :value="fin_ru">
                                    </div>
                                </td>
                                <td width="25%" style="padding-right: 5px;">
                                    <label for="">Farmacia:</label>
                                    <div class="input-group">
                                        <select name="" id="id_farmacia" class="form-control" v-model="farmaciaid_ru">
                                            <option value="0">TODOS</option>
                                            option
                                            <option v-for="farmacia in farmacias" :value="farmacia.idAlmacen">
                                                {{ farmacia.descripcion }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td width="30%" style="padding-right: 5px;">
                                    
                                </td>
                                <td width="15%" class="text-center">
                                    <button class="btn btn-primary" v-on:click.prevent="postData_ru">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-success" v-on:click.prevent="excelExport_ru">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                                    <a class="btn btn-danger" v-on:click.prevent="pdfExport_ru">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table table-bordered table-striped" id="tabla_ru" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>MOVNUMERO</th>
                                <th>FECHA</th>
                                <!-- <th>IDALMACEN</th> -->
                                <th>ALMACEN</th>
                                <th>USUARIO</th>
                                <th>ESTADO</th>
                                <th>CODIGO</th>
                                <th>PRODUCTO</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- FIN ENTRADAS Y SALIDAS DE DOCUMENTOS -->

            <!-- ENTRADAS Y SALIDAS DE DOCUMENTOS -->
            <div id="ventas_producto_resumen">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte de Ventas y Productos Resumen</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Inicio:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio_vpr" :value="inicio_vpr">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Fin:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_fin_vpr" :value="fin_vpr">
                                    </div>
                                </td>
                                <td width="25%" style="padding-right: 5px;">
                                    <label for="">Farmacia:</label>
                                    <div class="input-group">
                                        <select name="" id="id_farmacia_vpr" class="form-control" v-model="farmaciaid_vpr">
                                            <option value="0">TODOS</option>
                                            <option v-for="farmacia in farmacias" :value="farmacia.idAlmacen">
                                                {{ farmacia.descripcion }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td width="30%" style="padding-right: 5px;">
                                    <label for="">Tipo Movimiento:</label>
                                    <div class="input-group">
                                        <select name="" id="id_movtipo_vpr" class="form-control" v-model="movtipo_vpr">
                                            <option value="0">MEDICAMENTOS</option>
                                            <option value="1">INSUMOS</option>
                                        </select>
                                    </div>
                                </td>
                                <td width="15%" class="text-center">
                                    <button class="btn btn-primary" v-on:click.prevent="postData_vpr">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-success" v-on:click.prevent="excelExport_vpr">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                                    <a class="btn btn-danger" v-on:click.prevent="pdfExport_vpr">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <table style="width: 40%;">
                            <tr>   
                                <td width="15%" style="padding-right: 2%;">
                                    <div class="form-group">
                                        <label for="">N&uacute;mero de Movimiento Inicio</label>
                                        <input type="text" class="form-control" v-model="nummovini">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 2%;">
                                    <div class="form-group">
                                        <label for="">N&uacute;mero de Movimiento Fin</label>
                                        <input type="text" class="form-control" v-model="nummovfin">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table table-bordered table-striped" id="tabla_es_ventas_productos" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>CODIGO<br>SISMED</th>
                                <th>PRODUCTO</th>
                                <th>CANT. VENT.</th>
                                <th>CX.</th>
                                <th>HOSP.</th>
                                <th>EMER.</th>
                                <th>PAC.EXT.</th>
                                <th>PAC.PAR.</th>
                                <th>SIS</th>
                                <th>SOAT</th>
                                <th>PEND.</th>
                                <th>EXON.</th>
                                <th>DONA.</th>
                                <th>INTER.<br>SANI.</th>
                                <th>STOCK</th>
                                <th>CANT.<br>FACT.</th>
                                <th>TOTAL</th>
                                <th>DEVOL</th>
                                <th>CX_DEV</th>
                                <th>HO_DEV</th>
                                <th>EME_DEV</th>
                                <th>CANT.VENT.<br>-<br>DEVOL</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- FIN ENTRADAS Y SALIDAS DE DOCUMENTOS -->

            <!-- TRASLADOS -->
            <div id="traslados">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte de Traslados entre Unidades Ejecutoras</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Inicio:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio_trans" :value="inicio_trans">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Fin:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_fin_trans" :value="fin_trans">
                                    </div>
                                </td>
                                <td width="25%" style="padding-right: 5px;">
                                    <label for="">Almacen:</label>
                                    <div class="input-group">
                                        <select name="" id="id_almacen" class="form-control" v-model="almacenid">
                                            <option value="0">TODOS</option>
                                            option
                                            <option v-for="almacen in almacenes" :value="almacen.idAlmacen">
                                                {{ almacen.descripcion }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td width="30%" style="padding-right: 5px;">
                                   
                                </td>
                                <td width="15%" class="text-center">
                                    <button class="btn btn-primary" v-on:click.prevent="postData_trans">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-success" v-on:click.prevent="excelExport_trans">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                                    <a class="btn btn-danger" v-on:click.prevent="pdfExport_trans">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label v-if="errores.inicio_trans" class="text-danger">{{ errores.inicio_trans[0] }}</label>
                                </td>
                                <td>
                                    <label v-if="errores.fin_trans" class="text-danger">{{ errores.fin_trans[0] }}</label>
                                </td>
                                <td>
                                    <label v-if="errores.almacenid" class="text-danger">{{ errores.almacenid[0] }}</label>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table table-bordered table-striped" id="tabla_traslados" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>FECHA</th>
                                <th>N°REGISTRO</th>
                                <th>ORIGEN</th>
                                <th>DESTINO</th>
                                <th>N°DOCUMENTO</th>
                                <th>ESTADO</th>
                                <th>SISMED</th>
                                <th>CANTIDAD</th>
                                <th>DESCRIPCION</th>
                                <th>LOTE</th>
                                <th>F.V.</th>
                                <th>R.S.</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- fin de traslados -->

            <!-- inicio de ingreso por almacen-->
                <div id="ingreso">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte de Ingresos de Almacen</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Inicio:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="ia_fecha_inicio" :value="ia_inicio">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha Fin:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="ia_fecha_fin" :value="ia_fin">
                                    </div>
                                </td>
                                <td width="40%" style="padding-right: 5px;">
                                    <label for="">Laboratorio:</label>
                                    <div class="input-group" style="width: 100%;">
                                        <input type="text" v-model="nombre_proveedor" id="id_proveedor" class="form-control" data-provide="typeahead" autocomplete = "off" :disabled="habilitado">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <br>
                                    <div class="checkbox" style="margin-left: 2%;">
                                        <label>
                                            <input type="checkbox" v-model="habilitado"> Todos
                                        </label>
                                    </div>
                                </td>
                                <td width="15%" class="text-center">
                                    <button class="btn btn-primary" v-on:click.prevent="postDataIA">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-success" v-on:click.prevent="excelExportIA">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                                    <a class="btn btn-danger">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label v-if="errores.inicioia" class="text-danger">{{ errores.inicioia[0] }}</label>
                                </td>
                                <td>
                                   <label v-if="errores.finia" class="text-danger">{{ errores.finia[0] }}</label>
                                </td>
                                <td>
                                    <label v-if="errores.idProveedor" class="text-danger">{{ errores.idProveedor[0] }}</label>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <hr>
                        <table class="table table-bordered table-striped" id="tabla_ingresos_almacen" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>FECHA</th>
                                <th>ORDEN</th>
                                <th>LABORATORIO</th>
                                <th>N°ENTREGA</th>
                                <th>LICITACION</th>
                                <th>COD. SISMED</th>
                                <th>DESCRIPCION</th>
                                <th>CANT</th>
                                <th>OBSERVACIONES</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- fin de ingreso por almacen -->
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import datepicker from 'bootstrap-datepicker'
    import toastr from 'toastr'
    import DataTable from 'datatables.net-bs'
    import dataTable from 'datatables.net'
    import typeahead from 'bootstrap-3-typeahead'

    export default {
        data() {
            return {
                farmacias: [],
                almacenes: [],
                inicio: '',
                fin: '',
                farmaciaid: '',
                inicio_ru: '',
                fin_ru: '',
                farmaciaid_ru: '',
                movtipo: '',
                inicio_vpr: '',
                fin_vpr: '',
                farmaciaid_vpr: '',
                movtipo_vpr: '',
                errores: '',
                nummovini: '',
                nummovfin: '',
                inicio_trans: '',
                fin_trans: '',
                almacenid: '',
                ia_inicio: '',
                ia_fin: '',
                habilitado: '',
                nombre_proveedor: ''
            }
        },
        created: function() {
            this.loadData();
            this.loadProveedores();
        },
        methods: {

            // carga de datos
            loadData: function() {
                var url = 'farmacia/farmacias/F'; 
                var url2 = 'farmacia/almacenes';                    
                // cargando almacenes
                axios.get(url).then(response => {                    
                    this.farmacias = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de almacenes');
                });           

                axios.get(url2).then(response => {                    
                    this.almacenes = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de almacenes');
                });     
            },

            loadProveedores: function() {
                var url = 'sistema/proveedores';
                axios.get(url).then(response => {  
                    var data = response.data;
                    var proveedores = [];
                    
                    $.each(data, function(i, object) {
                        //this.id_proveedores[object.RAZONSOCIAL] = object.IDPROVEEDOR;
                        proveedores.push(object.RUC + ' | ' + object.RAZONSOCIAL);
                    });

                    $("#id_proveedor").typeahead({
                        source: proveedores,
                        minLength: 3
                    });
                    
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de proveedores');
                });
            },
            // envio de Datos para traslados de unidades ejecutoras
            postData: function() {

                if (this.inicio == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.farmaciaid == '') { toastr.error('Debe seleccionar una farmacia','WebSigesa');return false; }
                if (this.movtipo == '') { toastr.error('Debe seleccionar un tipo de movimiento','WebSigesa');return false; }

                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = '/farmacia/reporte_es_documentos/'+this.inicio+'/'+this.fin+'/'+this.farmaciaid+'/'+this.movtipo;
                // console.log(url);return false;
                axios.get(url).then(reponse => {
                    console.log(reponse.data.data);
                    $('#tabla_es_documentos').dataTable().fnDestroy();
                    $('#tabla_es_documentos').DataTable({
                        language: {
                            search: 'Buscar:',
                            paginate: {
                                first: "Primero",
                                previous: "Atr&aacute;s",
                                next: "Adelante",
                                last: "&Uacute;ltimo"
                            },
                            "infoEmpty": "Mostrando 0 al 0 de 0 entradas",
                            "lengthMenu": "Mostrar _MENU_ entradas",
                            "info": "Mostrando _START_ al _END_ de _TOTAL_ entradas"
                        },
                        "lengthMenu": [5, 10, 25, 50, 75, 100],
                        data: reponse.data.data,
                        columns: [
                            {data:'FECHA'},
                            {data:'MOVTIPO'},
                            {data:'MOVNUMERO'},
                            {data:'USUARIO'},
                            {data:'DOCUMENTONUMERO'},
                            {data:'DOCUMENTO'},
                            {data:'ORIGEN'},
                            {data:'OBSERVACIONES'},
                            {data:'DESTINO'},
                            {data:'ESTADO'},
                            {data:'TOTAL'}
                        ]
                    });
                    toastr.clear();
                    $('#tabla_es_documentos').show();
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },
            // envio de Datos para resumen ventas productos
            postData_vpr: function() {

                if (this.inicio_vpr == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin_vpr == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.farmaciaid_vpr == '') { toastr.error('Debe seleccionar una farmacia','WebSigesa');return false; }
                if (this.movtipo_vpr == '') { toastr.error('Debe seleccionar un tipo de movimiento','WebSigesa');return false; }

                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = '/farmacia/reporte_venta_producto/'+this.inicio_vpr+'/'+this.fin_vpr+'/'+this.farmaciaid_vpr+'/'+this.movtipo_vpr+'/'+this.nummovini+'/'+this.nummovfin;
                // console.log(url);return false;
                axios.get(url).then(reponse => {
                    console.log(reponse.data.data);

                    if (reponse.data.data == 'sindatos') {
                        $('#tabla_es_ventas_productos').hide();
                        toastr.success('No hay datos encontrados', 'WebSigesa');
                        return false;
                    }

                    $('#tabla_es_ventas_productos').dataTable().fnDestroy();
                    $('#tabla_es_ventas_productos').DataTable({
                        language: {
                            search: 'Buscar:',
                            paginate: {
                                first: "Primero",
                                previous: "Atr&aacute;s",
                                next: "Adelante",
                                last: "&Uacute;ltimo"
                            },
                            "infoEmpty": "Mostrando 0 al 0 de 0 entradas",
                            "lengthMenu": "Mostrar _MENU_ entradas",
                            "info": "Mostrando _START_ al _END_ de _TOTAL_ entradas"
                        },
                        "lengthMenu": [5, 10, 25, 50, 75, 100],
                        data: reponse.data.data,
                        columns: [
                            {data:'CODIGOSISMED'},
                            {data:'PRODUCTO'},
                            {data:'CANTIDADVENTAS'},
                            {data:'CONSULTAEXTERNA'},
                            {data:'HOSPITALIZACION'},
                            {data:'EMERGENCIA'},
                            {data:'PACIENTEEXTERNO'},
                            {data:'PARTICULAR'},
                            {data:'SIS'},
                            {data:'SOAT'},
                            {data:'PENDIENTE'},
                            {data:'EXONERADO'},
                            {data:'DONACION'},
                            {data:'INTERVENCIONSANITARIA'},
                            {data:'STOCK'},
                            {data:'CANTIDADFACTURADA'},
                            {data:'TOTAL'},
                            {data:'DEVOLUCIONES'},
                            {data:'CONSULTAEXTERNADEVOLUCIONES'},
                            {data:'HOSPITALIZACIONDEVOLUCIONES'},
                            {data:'EMERGENCIADEVOLUCIONES'},
                            {data:'CANTVENTASMENOSDEVOLUCIONES'}
                        ]
                    });
                    toastr.clear();
                    $('#tabla_es_ventas_productos').show();
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },
            // envio de Datos para traslados de unidades ejecutoras
            postData_ru: function() {

                if (this.inicio_ru == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin_ru == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.farmaciaid_ru == '') { toastr.error('Debe seleccionar una farmacia','WebSigesa');return false; }

                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = '/farmacia/reporte_por_usuario/'+this.inicio_ru+'/'+this.fin_ru+'/'+this.farmaciaid_ru;
                // console.log(url);return false;
                axios.get(url).then(reponse => {
                    console.log(reponse.data.data);
                    $('#tabla_ru').dataTable().fnDestroy();
                    $('#tabla_ru').DataTable({
                        language: {
                            search: 'Buscar:',
                            paginate: {
                                first: "Primero",
                                previous: "Atr&aacute;s",
                                next: "Adelante",
                                last: "&Uacute;ltimo"
                            },
                            "infoEmpty": "Mostrando 0 al 0 de 0 entradas",
                            "lengthMenu": "Mostrar _MENU_ entradas",
                            "info": "Mostrando _START_ al _END_ de _TOTAL_ entradas"
                        },
                        "lengthMenu": [5, 10, 25, 50, 75, 100],
                        data: reponse.data.data,
                        columns: [
                            {data:'MOVNUMERO'},
                            {data:'FECHA'},
                            // {data:'IDALMACEN'},
                            {data:'ALMACEN'},
                            {data:'USUARIO'},
                            {data:'ESTADO'},
                            {data:'CODIGO'},
                            {data:'PRODUCTO'},
                            {data:'CANTIDAD'},
                            {data:'PRECIO'},
                            {data:'TOTAL'}
                        ]
                    });
                    toastr.clear();
                    $('#tabla_ru').show();
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },
            excelExport: function() {
                if (this.inicio == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.farmaciaid == '') { toastr.error('Debe seleccionar una farmacia','WebSigesa');return false; }
                if (this.movtipo == '') { toastr.error('Debe seleccionar un tipo de movimiento','WebSigesa');return false; }
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var url = '/farmacia/reporte_es_documentos_excel/'+this.inicio+'/'+this.fin+'/'+this.farmaciaid+'/'+this.movtipo;
                window.open(url);
                toastr.clear();
            },
            excelExport_ru: function() {
                if (this.inicio_ru == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin_ru == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.farmaciaid_ru == '') { toastr.error('Debe seleccionar una farmacia','WebSigesa');return false; }
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var url = '/farmacia/reporte_por_usuario_excel/'+this.inicio_ru+'/'+this.fin_ru+'/'+this.farmaciaid_ru;
                window.open(url);
                toastr.clear();
            },
            excelExport_vpr: function() {
                if (this.inicio_vpr == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin_vpr == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.farmaciaid_vpr == '') { toastr.error('Debe seleccionar una farmacia','WebSigesa');return false; }
                if (this.movtipo_vpr == '') { toastr.error('Debe seleccionar un tipo de movimiento','WebSigesa');return false; }
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var url = '/farmacia/reporte_venta_producto_excel/'+this.inicio_vpr+'/'+this.fin_vpr+'/'+this.farmaciaid_vpr+'/'+this.movtipo_vpr+'/'+this.nummovini+'/'+this.nummovfin;
                window.open(url);
                toastr.clear();
            },
            pdfExport: function()
            {
                toastr.clear();
                toastr.info('Funcionalidad en fase de desarrollo. Gracias por la comprensión.', 'WebSigesa');
            },
            pdfExport_ru: function()
            {
                toastr.clear();
                toastr.info('Funcionalidad en fase de desarrollo. Gracias por la comprensión.', 'WebSigesa');
            },
            pdfExport_vpr: function()
            {
                toastr.clear();
                toastr.info('Funcionalidad en fase de desarrollo. Gracias por la comprensión.', 'WebSigesa');
            },
            // envio de Datos para traslados de unidades ejecutoras
            postData_trans: function() {
                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = 'farmacia/reporte_traslados';
                axios.post(url, {
                    'inicio'    : this.inicio_trans,
                    'fin'       : this.fin_trans,
                    'almacenid' : this.almacenid
                }).then(reponse => {
                    $('#tabla_traslados').dataTable().fnDestroy();
                    $('#tabla_traslados').DataTable({
                        language: {
                            search: 'Buscar:',
                            paginate: {
                                first: "Primero",
                                previous: "Atr&aacute;s",
                                next: "Adelante",
                                last: "&Uacute;ltimo"
                            },
                            "infoEmpty": "Mostrando 0 al 0 de 0 entradas",
                            "lengthMenu": "Mostrar _MENU_ entradas",
                            "info": "Mostrando _START_ al _END_ de _TOTAL_ entradas"
                        },
                        "lengthMenu": [5, 10, 25, 50, 75, 100],
                        data: reponse.data.data,
                        columns: [
                            {data: 'FECHA'},
                            {data: 'NRO REGISTRO'},
                            {data: 'ORIGEN'},
                            {data: 'DESTINO'},
                            {data: 'NRO DOCUMENTO'},
                            {data: 'ESTADO'},
                            {data: 'SISMED'},
                            {data: 'CANTIDAD'},
                            {data: 'DESCRIPCION'},
                            {data: 'LOTE'},
                            {data: 'FV'},
                            {data: 'RS'}
                        ]
                    });
                    toastr.clear();
                    $('#tabla_traslados').show();
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },

            excelExport_trans: function() {
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var fechainicio = this.inicio_trans.split("/").reverse().join("-");
                var fechafin = this.fin_trans.split("/").reverse().join("-");
                var url = 'farmacia/reporte_traslados_excel/' + fechainicio + '/' + fechafin + '/' + this.almacenid;
                window.open(url);
                toastr.clear();
            },

            // ingresos a almacen
            postDataIA: function() {
                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var nombre_temp = $("#id_proveedor").val();
                var url = 'farmacia/reporte_ingresos_almacen';
                var ruc = '';
                if (this.habilitado) {
                    ruc = '0';
                }
                else {
                    ruc = nombre_temp.substring(0,11);
                }

                axios.post(url, {
                    'inicioia': this.ia_inicio,
                    'finia': this.ia_fin,
                    'idProveedor': ruc
                }).then(response => {
                    $('#tabla_ingresos_almacen').dataTable().fnDestroy();
                    $('#tabla_ingresos_almacen').DataTable({
                        language: {
                            search: 'Buscar:',
                            paginate: {
                                first: "Primero",
                                previous: "Atr&aacute;s",
                                next: "Adelante",
                                last: "&Uacute;ltimo"
                            },
                            "infoEmpty": "Mostrando 0 al 0 de 0 entradas",
                            "lengthMenu": "Mostrar _MENU_ entradas",
                            "info": "Mostrando _START_ al _END_ de _TOTAL_ entradas"
                        },
                        "lengthMenu": [5, 10, 25, 50, 75, 100],
                        data: response.data.data,
                        columns: [
                            {data: 'FECHA'},
                            {data: 'ORDEN DE COMPRA'},
                            {data: 'LABORATORIO'},
                            {data: 'NRO DE ENTREGA'},
                            {data: 'LICITACION'},
                            {data: 'CODIGO SISMED'},
                            {data: 'DESCRIPCIÓN DE PRODUCTO'},
                            {data: 'CANTIDAD'},
                            {data: 'OBSERVACIONES'}
                        ]
                    });
                    toastr.clear();
                    $('#tabla_ingresos_almacen').show();
                    //console.log(response.data.data);
                    toastr.clear();
                }).catch(error => {
                    this.errores = error.response.data.errors;
                });
            },
            excelExportIA: function() {
                var nombre_temp = $("#id_proveedor").val();
                var ruc = '';
                if (this.habilitado) {
                    ruc = '0';
                }
                else {
                    ruc = nombre_temp.substring(0,11);
                }
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var fechainicio = this.ia_inicio.split("/").reverse().join("-");
                var fechafin = this.ia_fin.split("/").reverse().join("-");
                var url = 'farmacia/reporte_ingresos_almacen_excel/' + fechainicio + '/' + fechafin + '/' + ruc;
                window.open(url);
                toastr.clear();
            }
        },
        mounted() {
            $('#fecha_inicio').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es'             
            }).on(
            "changeDate", () => {this.inicio = $('#fecha_inicio').val()}
            );

            $('#fecha_fin').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es'             
            }).on(
            "changeDate", () => {this.fin = $('#fecha_fin').val()}
            );
            $('#fecha_inicio_vpr').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es',
                orientation: 'bottom'             
            }).on(
            "changeDate", () => {this.inicio_vpr = $('#fecha_inicio_vpr').val()}
            );

            $('#fecha_fin_vpr').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es',
                orientation: 'bottom'             
            }).on(
            "changeDate", () => {this.fin_vpr = $('#fecha_fin_vpr').val()}
            );
            $('#fecha_inicio_ru').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es',
                orientation: 'bottom'             
            }).on(
            "changeDate", () => {this.inicio_ru = $('#fecha_inicio_ru').val()}
            );

            $('#fecha_fin_ru').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es',
                orientation: 'bottom'             
            }).on(
            "changeDate", () => {this.fin_ru = $('#fecha_fin_ru').val()}
            );

            $('#fecha_inicio_trans').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                language: 'es'             
            }).on(
            "changeDate", () => {this.inicio_trans = $('#fecha_inicio_trans').val()}
            );

            $('#fecha_fin_trans').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                language: 'es'             
            }).on(
            "changeDate", () => {this.fin_trans = $('#fecha_fin_trans').val()}
            );

            $('#ia_fecha_inicio').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                language: 'es',
                orientation: 'bottom'             
            }).on(
            "changeDate", () => {this.ia_inicio = $('#ia_fecha_inicio').val()}
            );

            $('#ia_fecha_fin').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                language: 'es',
                orientation: 'bottom'             
            }).on(
            "changeDate", () => {this.ia_fin = $('#ia_fecha_fin').val()}
            );

        }
    }    
</script>