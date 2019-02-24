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
                        <h3 class="box-title">Resumen Caja</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table style="width: 100%;">
                            <tr>
                                <td width="10%" style="padding-right: 5px;">
                                    <label for="">Consulta:</label>
                                    <div class="input-group">
                                        <select id="id_consulta" class="form-control" v-model="consultaid" v-on:change="consultaChange">
                                            <option value="1">Cajeros</option>
                                            <option value="2">Cajas</option>
                                        </select>
                                    </div>
                                </td>
                                <td width="20%" style="padding-right: 5px; display: none;" id="cajeros">
                                    <label for="">Cajeros:</label>
                                    <div class="input-group">
                                        <select name="" id="id_cajero" class="form-control" v-model="cajeroid">
                                            <option value="0">TODOS</option>
                                            <option v-for="cajero in cajeros" :value="cajero.IdEmpleado">
                                                {{ cajero.dCajero }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td width="20%" style="padding-right: 5px; display: none;" id="cajas">
                                    <label for="">Cajas:</label>
                                    <div class="input-group">
                                        <select name="" id="id_caja" class="form-control" v-model="cajaid">
                                            <option value="0">TODOS</option>
                                            <option v-for="caja in cajas" :value="caja.IdCaja" >
                                                {{ caja.Descripcion }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
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
                                <td width="5%" style="padding-right: 5px;">
                                   
                                </td>
                                <td width="20%" class="text-center">
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
                        <table class="table table-bordered table-striped" id="table_reporte_cajero" style="display: none;width: 100%;">
                            <thead>
                            <tr class="bg-gray">
                                <th>CAJERO</th>
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
                cajeros: [],
                cajas: [],
                inicio: '',
                fin: '',
                errores: '',
                habilitado: false,
                consultaid: '',
                cajeroid: '',
                cajaid: '',
                inicio: '',
                fin: ''
            }
        },
        created: function() {
            this.loadData();
        },
        methods: {
            // carga de datos
            loadData: function() {
                var url = 'cajas/listar_cajeros';   
                var url2 = 'cajas/listar';              
                // cargando cajeros
                axios.get(url).then(response => {                    
                    this.cajeros = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de cajeros');
                });                

                 // cargando cajas
                axios.get(url2).then(response => {                    
                    this.cajas = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de cajas');
                });          
            },

            consultaChange: function() {
                if (this.consultaid==1) {
                    $("#cajas").fadeOut();
                    $("#cajeros").fadeIn( "slow" );
                }else if(this.consultaid==2){
                    $("#cajeros").fadeOut();
                    $("#cajas").fadeIn( "slow" );
                }
            },

            // envio de Datos para facturas
            // envio de Datos para traslados de unidades ejecutoras
            postData: function() {

                if (this.inicio == '') { toastr.error('Debe seleccionar una fecha de inicio','WebSigesa');return false; }
                if (this.fin == '') { toastr.error('Debe seleccionar una fecha de fin','WebSigesa');return false; }
                if (this.consultaid == '') { toastr.error('Debe seleccionar un tipo´de consulta','WebSigesa');return false; }

                if (this.consultaid == '1') {if (this.cajeroid == '') { toastr.error('Debe seleccionar un cajero','WebSigesa');return false; }}
                if (this.consultaid == '2') {if (this.cajaid == '') { toastr.error('Debe seleccionar una caja','WebSigesa');return false; }}

                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                alert(this.cajeroid);
                var url = '/cajas/reporte_resumen_por_cajeros/'+this.inicio+'/'+this.fin+'/'+this.cajeroid;

                // console.log(url);return false;
                axios.get(url).then(reponse => {
                    console.log(reponse.data.data);
                    $('#table_reporte_cajero').dataTable().fnDestroy();
                    $('#table_reporte_cajero').DataTable({
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
                            {data:'CAJERO'}
                        ]
                    });
                    toastr.clear();
                    $('#tabla_es_documentos').show();
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
            },
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