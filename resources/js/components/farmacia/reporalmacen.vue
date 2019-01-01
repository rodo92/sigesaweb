<template>
    
    <div>
        <section class="content-header">
            <h1>
                Reporte de Almac&eacute;n
                <small>Farmacia</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Farmacia</a></li>
                <li class="active">Reporte Almac&eacute;n</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">

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
                            <tr>
                                <td>
                                    <label v-if="errores.inicio" class="text-danger">{{ errores.inicio[0] }}</label>
                                </td>
                                <td>
                                    <label v-if="errores.fin" class="text-danger">{{ errores.fin[0] }}</label>
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
                almacenes: [],
                inicio: '',
                fin: '',
                ia_inicio: '',
                ia_fin: '',
                almacenid: '',
                errores: '',
                habilitado: false,
                nombre_proveedor: '',
                id_proveedores: {},
            }
        },
        created: function() {
            this.loadData();
            this.loadProveedores();
        },
        methods: {

            // carga de datos
            loadData: function() {
                var url = 'farmacia/almacenes';                
                // cargando almacenes
                axios.get(url).then(response => {                    
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
                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = 'farmacia/reporte_traslados';
                axios.post(url, {
                    'inicio'    : this.inicio,
                    'fin'       : this.fin,
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
            excelExport: function() {
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var fechainicio = this.inicio.split("/").reverse().join("-");
                var fechafin = this.fin.split("/").reverse().join("-");
                var url = 'farmacia/reporte_traslados_excel/' + fechainicio + '/' + fechafin + '/' + this.almacenid;
                window.open(url);
                toastr.clear();
            },
            pdfExport: function()
            {
                toastr.clear();
                toastr.info('Funcionalidad en fase de desarrollo. Gracias por la comprensión.', 'WebSigesa');
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
                format: 'dd/mm/yyyy',
                language: 'es'             
            }).on(
            "changeDate", () => {this.inicio = $('#fecha_inicio').val()}
            );

            $('#fecha_fin').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy',
                language: 'es'             
            }).on(
            "changeDate", () => {this.fin = $('#fecha_fin').val()}
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