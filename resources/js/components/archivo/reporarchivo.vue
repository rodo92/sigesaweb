<template>
    
    <div>
        <section class="content-header">
            <h1>
                Reporte de Archivo
                <small>Archivo</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Archivo</a></li>
                <li class="active">Reporte de Archivo</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">

            <!-- ENTRADAS Y SALIDAS DE DOCUMENTOS -->
            <div id="entradas_salidas_documentos">
                <div class="box box-primary color-palette-box collapsed-box"><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte Conserjeria</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio" :value="inicio">
                                    </div>
                                </td>
                                <td width="25%" style="padding-right: 5px;">
                                    <label for="">Turno:</label>
                                    <div class="input-group">
                                        <select name="" id="id_turno" class="form-control" v-model="turnoid">
                                            <option value="0">Seleccione</option>
                                            <option value="1">Mañana</option>
                                            <option value="2">Tarde</option>
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
                        <table class="table table-bordered table-striped" id="tabla_conserjeria" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>RUTA</th>
                                <th>ESPECIALIDAD</th>
                                <th>SERVICIO</th>
                                <th>NRO HISTORIA</th>
                                <th>PACIENTE</th>
                                <th>FECHA SOLICITUD</th>
                                <th>TIPO PACIENTE</th>
                                <th>TIPO CITA</th>
                                <th>HORA INICIO</th>
                                <th>CONSERJE</th>
                                <th>ARCHIVERO</th>
                                <th>TECNICA</th>
                                <th>MEDICO</th>
                                <th>SEGURO</th>
                                <th>DIGITO TERMINAL</th>
                                <th>DESTINO</th>
                                <th>ESTADO</th>
                                <th>UBICACION</th>
                                <th>ULTIMO MOVIMIENTO</th>
                                <th>TURNO</th>
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
                        <h3 class="box-title">Reporte Listado de citados</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Fecha:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="fecha_inicio_ru" :value="inicio_ru">
                                    </div>
                                </td>
                                <td width="5%" style="padding-right: 5px;">
                                    <label for="">Turno:</label>
                                    <div class="input-group">
                                        <select name="" id="id_farmacia" class="form-control" v-model="turno_lc">
                                            <option value="1">Mañana</option>
                                            <option value="2">Tarde</option>
                                        </select>
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Serie Inicial:</label>
                                    <div class="input-group">
                                        <input type="number" max="2" class="form-control" id="serie_inicial_id" placeholder="00" v-model="si_lc">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <label for="">Serie Final:</label>
                                    <div class="input-group">
                                        <input type="number" maxlength="2" class="form-control" id="serie_final_id" placeholder="99" v-model="sf_lc">
                                    </div>
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
                                <th>FECHA REQUERIDA</th>
                                <th>FECHA SOLICITUD</th>
                                <th>OBSERVACION</th>
                                <th>ESTADO</th>
                                <th>TIPO PACIENTE</th>
                                <th>NRO HISTORIA</th>
                                <th>PACIENTE</th>
                                <th>DIGITO TERMINAL</th>
                                <th>TIPO HISTORIA</th>
                                <th>SERVICIO</th>
                                <th>TURNO</th>
                                <th>ESPECIALIDAD</th>
                                <th>DESTINO</th>
                                <th>ULTIMO MOVIMIENTO</th>
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
                turnoid: '',
                inicio_ru: '',
                fin_ru: '',
                turno_lc: '',
                si_lc: '',
                sf_lc: '',
                movtipo: '',
                errores: '',
            }
        },
        created: function() {
        },
        methods: {

            postData: function() {

                if (this.inicio == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                // if (this.fin == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.turnoid == '') { toastr.error('Debe seleccionar un turno','WebSigesa');return false; }
                // if (this.movtipo == '') { toastr.error('Debe seleccionar un tipo de movimiento','WebSigesa');return false; }

                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = 'Archivo/reporte_consejeria/'+this.turnoid+'/'+this.inicio;
                // console.log(url);return false;
                axios.get(url).then(reponse => {
                    var respuesta_a = reponse.data.data;
                    if (respuesta_a == 'sindatos') {
                        $('#tabla_conserjeria').DataTable().clear().draw();
                        toastr.clear();
                        toastr.warning('No hay información para esta busqueda', 'WebSigesa');
                    }
                    else {
                        $('#tabla_conserjeria').dataTable().fnDestroy();
                        $('#tabla_conserjeria').DataTable({
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
                                {data: 'RUTA'},
                                {data: 'ESPECIALIDAD'},
                                {data: 'SERVICIO'},
                                {data: 'NRO HISTORIA'},
                                {data: 'PACIENTE'},
                                {data: 'FECHA SOLICITUD'},
                                {data: 'TIPO PACIENTE'},
                                {data: 'TIPO CITA'},
                                {data: 'HORA INICIO'},
                                {data: 'CONSERJE'},
                                {data: 'ARCHIVERO'},
                                {data: 'TECNICA'},
                                {data: 'MEDICO'},
                                {data: 'SEGURO'},
                                {data: 'DIGITO TERMINAL'},
                                {data: 'DESTINO'},
                                {data: 'ESTADO'},
                                {data: 'UBICACION'},
                                {data: 'ULTIMO MOVIMIENTO'},
                                {data: 'TURNO'}
                            ]
                        });
                        toastr.clear();
                        $('#tabla_conserjeria').show();
                    }
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },
            // envio de Datos para traslados de unidades ejecutoras
            postData_ru: function() {
                if (this.inicio_ru == '') { toastr.error('Debe seleccionar una fecha','WebSigesa');return false; }
                if (this.turno_lc == '') { toastr.error('Debe seleccionar un turno','WebSigesa');return false; }
                if (this.si_lc == '') { toastr.error('Debe ingresar una serie de inicio.','WebSigesa');return false; }
                if (this.sf_lc == '') { toastr.error('Debe ingresar una serie final.','WebSigesa');return false; }

                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                
                var url = 'Archivo/reporte_listado_citados/'+this.turno_lc+'/'+this.inicio_ru+'/'+this.si_lc+'/'+this.sf_lc;
                // console.log(url);return false;
                axios.get(url).then(reponse => {
                    var respuesta_a = reponse.data.data;
                    if (respuesta_a == 'sindatos') {
                        $('#tabla_ru').DataTable().clear().draw();
                        toastr.clear();
                        toastr.warning('No hay información para esta busqueda', 'WebSigesa');
                    }
                    else {
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
                                { data: 'FECHA REQUERIDA' },
                                { data: 'FECHA SOLICITUD' },
                                { data: 'OBSERVACION' },
                                { data: 'ESTADO' },
                                { data: 'TIPO PACIENTE' },
                                { data: 'NRO HISTORIA' },
                                { data: 'PACIENTE' },
                                { data: 'DIGITO TERMINAL' },
                                { data: 'TIPO HISTORIA' },
                                { data: 'SERVICIO' },
                                { data: 'TURNO' },
                                { data: 'ESPECIALIDAD' },
                                { data: 'DESTINO' },
                                { data: 'ULTIMO MOVIMIENTO' }
                            ]
                        });
                        toastr.clear();
                        $('#tabla_ru').show();
                    }
                    // console.log(reponse.data.data);
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },
            excelExport: function() {
                if (this.inicio == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                // if (this.fin == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.turnoid == '') { toastr.error('Debe seleccionar un turno','WebSigesa');return false; }
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var url = 'Archivo/reporte_consejeria_excel/'+this.turnoid+'/'+this.inicio;
                window.open(url);
                toastr.clear();
            },
            excelExport_ru: function() {
                if (this.inicio_ru == '') { toastr.error('Debe seleccionar una fecha','WebSigesa');return false; }
                if (this.turno_lc == '') { toastr.error('Debe seleccionar un turno','WebSigesa');return false; }
                if (this.si_lc == '') { toastr.error('Debe ingresar una serie de inicio.','WebSigesa');return false; }
                if (this.sf_lc == '') { toastr.error('Debe ingresar una serie final.','WebSigesa');return false; }
                var alerta_espera = toastr.info('Espere un momento mientras se genera el archivo','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var url = 'Archivo/reporte_listado_citados_excel/'+this.turno_lc+'/'+this.inicio_ru+'/'+this.si_lc+'/'+this.sf_lc;
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