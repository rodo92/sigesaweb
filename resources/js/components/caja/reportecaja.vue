<template>
    
    <div>
        <section class="content-header">
            <h1>
                Reporte de Caja
                <smallCaja</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i>Caja</a></li>
                <li class="active">Reporte de Caja</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">

            <!-- TRASLADOS -->
            <div id="traslados">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Parte Diario</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="20%" style="padding-right: 5px;">
                                    <label for="">Fecha Inicio:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio" :value="inicio">
                                    </div>
                                </td>
                                <td width="20%" style="padding-right: 5px;">
                                    <label for="">Fecha Fin:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_fin" :value="fin">
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
                inicio: '',
                fin: '',
                errores: '',
                habilitado: false,
            }
        },
        created: function() {
            
        },
        methods: {
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

                var url = 'rcaja/ParteDiarioPDF'+this.inicio+'/'+this.fin;

                axios.get(url).then(response => {
                    // console.log(response.data);
                    var ventana = window.open('', 'PRINT', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,height=50,width=50');
                    ventana.document.write(response.data);
                    ventana.document.close();
                    ventana.focus();
                    ventana.onload = function() {
                        ventana.print();
                        ventana.close();
                    };
                    return true;
                }).catch(error => {

                });
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