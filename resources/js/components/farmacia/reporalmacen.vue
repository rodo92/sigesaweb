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
                <div class="box box-primary color-palette-box "><!-- collapsed-box -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Reporte de Traslados</h3>
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
                                    <button class="btn btn-success" id="btn-generar-excel">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>&nbsp;
                                    <a class="btn btn-danger" id="btn-generar">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </td>
                            </tr>
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

    export default {
        data() {
            return {
                almacenes: [],
                inicio: '',
                fin: '',
                almacenid: '',
                errores: '',
            }
        },
        created: function() {
            this.loadData();
        },
        methods: {

            // carga de datos
            loadData: function() {
                var url = 'farmacia/almacenes';
                axios.get(url).then(response => {                    
                    this.almacenes = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de almacenes');
                });
            },

            // envio de Datos
            postData: function() {
                var url = 'farmacia/reporte_traslados';
                axios.post(url, {
                    'inicio': this.inicio,
                    'fin': this.fin,
                    'almacenid': this.almacenid
                }).then(reponse => {
                    console.log(reponse.data);
                }).catch(error => {
                    console.log(error.reponse.data.errors);
                }); 
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
        }
    }    
</script>