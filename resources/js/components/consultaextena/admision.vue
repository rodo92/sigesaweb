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
               <div class="box-body">
                    
                    <div class="row">
                        <div class="col-xs-5">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" placeholder="ESPECIALIDAD" id="txt_especialidad">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="MEDICO" id="txt_medicos">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-3"></div>
                        <div class="col-xs-4">
                            <datepicker :inline="true" :language="es" :value="fecha"></datepicker>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import toastr from 'toastr'
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import {es} from 'vuejs-datepicker/dist/locale'

    export default {
        components: {
            Datepicker
        },
        data() {
            return {
                es: es,
                id_medico: '',
                id_especialidad: '',
                fecha: moment(new Date()).format('YYYY-MM-DD'),
            }
        },
        created: function() {
            this.carga_especialidades();
            this.cargar_medicos();
        },
        methods: {
            carga_especialidades: function()
            {
                console.log(this.fecha);
                var url_especialidades = 'AdmisionCE/especialidades';
                axios.get(url_especialidades).then(response => {  
                    var data = response.data;
                    $('#txt_especialidad').typeahead({
                        source: data,
                        // minLength: 3,
                        autoSelect: true,
                        afterSelect: function (item) {
                            // console.log('afterSelect: ' + item.id);
                            this.id_especialidad = item.id;
                        }
                    });
                    $('#txt_especialidad').focus();
                    
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de especialidades cargadas.');
                });

            },
            cargar_medicos: function() {

                var url_medicos = 'AdmisionCE/medicos';
                axios.get(url_medicos).then(response => {  
                    var data = response.data;
                    $('#txt_medicos').typeahead({
                        source: data,
                        // minLength: 3,
                        autoSelect: true,
                        afterSelect: function (item) {
                            // console.log('afterSelect: ' + item.id);
                            this.id_medico = item.id;
                        }
                    });
                    
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de medicos registrados.');
                });
            }
        },
        mounted() {
            
        }
    }
</script>