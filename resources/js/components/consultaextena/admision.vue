<template>    
    <div>
        <section class="content-header">
            <h1>
                Citas y Admisi&oacute;n
                <small>Consulta Externa</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="restaurar"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Consulta Externa</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
    
            <div class="box box-default color-palette-box" >
                
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="box-body">
                    
                    <div class="row">
                        <div class="col-xs-5">
                            <table>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" placeholder="ESPECIALIDAD" id="txt_especialidad">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" placeholder="MEDICO">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-4"></div>
                        <div class="col-xs-3">
                            <div id="calendario" style="border: 1px solid #d7d7d7;"></div>
                        </div>
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
                
            }
        },
        created: function() {
            
        },
        methods: {},
        mounted() {
            $('#calendario').datepicker({
                inline: true,
                sideBySide: true          
            }).on('changeDate', function(e) 
                {
                    var dia     = e.date.getDate();
                    var mes     = e.date.getMonth() + 1;
                    var anio    = e.date.getFullYear();

                    var fecha_seleccionada = dia + '/' + mes + '/' + anio;

                }
            );
        }
    }
</script>