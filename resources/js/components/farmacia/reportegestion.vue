<style>
    td { padding: 0 10px; }
</style>
<template>
    
    <div>
        <section class="content-header">
            <h1>
                Reporte de Gesti&oacute;n
                <small>Farmacia</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Farmacia</a></li>
                <li class="active">Reporte Gesti&oacute;n</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
            
            <!-- Reporte ICI -->
            <div class="box box-primary color-palette-box collapsed-box" id="reporteici">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte ICI</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="tabla_formato">
                                <tr>
                                    <td>De:
                                    </td>
                                    <td>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="fecha_inicio" :value="inicio">
                                            
                                        </div>
                                    </td>
                                    <td>Hasta:</td>
                                    <td>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="fecha_fin" v-model="fin">
                                            
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-default" v-on:click.prevent="postData">
                                            <i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Generar
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><label v-if="errores.inicio" class="text-danger">{{ errores.inicio[0] }}</label></td>
                                    <td></td>
                                    <td><label v-if="errores.fin" class="text-danger">{{ errores.fin[0] }}</label></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row" id="respuesta" style="display: none;">
                        <hr>
                        <div class="col-xs-12">
                            <a class="btn btn-app" href="/descargar_dbf_ici/formato">
                                <i class="fa fa-download"></i> FORMATO.DBF
                            </a>
                            <a class="btn btn-app" href="/descargar_dbf_ici/formdet">
                                <i class="fa fa-download"></i> FORMDET.DBF
                            </a>
                            <a class="btn btn-app" href="/descargar_dbf_ici/formdetl">
                                <i class="fa fa-download"></i> FORMDETL.DBF
                            </a>
                            <a class="btn btn-app" href="/descargar_dbf_ici/formdetm">
                                <i class="fa fa-download"></i> FORMDETM.DBF
                            </a>
                            <div class="box-footer">
                                <cite>Archivos generados correctamente. Descargue los archivos por favor.<br>Una vez
                                    descargados estos archivos seran eliminados del servidor.</cite>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Reporte Valorizado -->
            <div class="box box-primary color-palette-box collapsed-box" id="reporteime">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte Valorizado</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                </div>
            </div>


            <!-- REPORTE SALDOS POR ALMACEN -->
            <div class="box box-primary color-palette-box collapsed-box" id="reportesaldos">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte Saldos Por Almacen</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">  
                    <table style="width: 100%">
                        <tr>
                            <td width="25%">
                                <select name="" id="id_farmacia" class="form-control" v-model="farmaciaid">
                                            <option value="0" selected>TODOS</option>
                                            option
                                            <option v-for="farmacia in farmacias" :value="farmacia.idAlmacen">
                                                {{ farmacia.descripcion }}
                                            </option>
                                        </select>
                            </td>   
                            <td width="15%" class="text-center">
                                    <button class="btn btn-success" v-on:click.prevent="excelExport">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                            </td>
                        </tr>
                    </table>                        
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import datepicker from 'bootstrap-datepicker'
    import toastr from 'toastr'
    

    export default {
        data() {
            return {
                inicio: '',
                fin: '',
                errores: '',
                farmacias: [],
            }
        },
        created: function() {
            this.loadData();
        },
        methods: {

            // carga de datos
            loadData: function() {
                var url = 'farmacia/farmacias/X';                
                // cargando almacenes
                axios.get(url).then(response => {                    
                    this.farmacias = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de almacenes');
                });                
            },

            postData: function()
            {
                var alerta_espera = toastr.info('Espere un momento por favor','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                var url = 'reporteici/generar_dbf';
                axios.post(url, {
                    'inicio': this.inicio,
                    'fin': this.fin
                }).then(response => {
                    this.errores = '';
                    $('#fecha_inicio').val('');
                    $('#fecha_fin').val('');
                    if (response.data.correcto) {
                        toastr.clear(alerta_espera);
                        toastr.success(response.data.correcto,'WebSigesa');
                        $('#fecha_inicio').val('');
                        $('#fecha_fin').val('');
                        $('#respuesta').show();
                    }
                    else {
                        toastr.clear();
                        toastr.error('Hubo un error al procesar la información.', 'WebSigesa');
                    }
                    console.log(response.data);
                }).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                });
            },

            excelExport: function() {
                var alerta_espera = toastr.info('Espere un momento mientras se genera el reporte','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });

                if (this.farmaciaid == null) {
                    toastr.info('Ingrese un Almacen');
                }

                var url = '/farmacia/reportesaldosxalmacen';
                window.open(url);
                toastr.clear();
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
        }
    }    
</script>