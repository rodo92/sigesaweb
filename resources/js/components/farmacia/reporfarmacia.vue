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
                inicio: '',
                fin: '',
                farmaciaid: '',
                inicio_ru: '',
                fin_ru: '',
                farmaciaid_ru: '',
                movtipo: '',
                errores: '',
            }
        },
        created: function() {
            this.loadData();
        },
        methods: {

            // carga de datos
            loadData: function() {
                var url = 'farmacia/farmacias/F';                
                // cargando almacenes
                axios.get(url).then(response => {                    
                    this.farmacias = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de almacenes');
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
            pdfExport: function()
            {
                toastr.clear();
                toastr.info('Funcionalidad en fase de desarrollo. Gracias por la comprensión.', 'WebSigesa');
            },
            pdfExport_ru: function()
            {
                toastr.clear();
                toastr.info('Funcionalidad en fase de desarrollo. Gracias por la comprensión.', 'WebSigesa');
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
        }
    }    
</script>