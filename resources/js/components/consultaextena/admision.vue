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
                                        <!-- <label for="input">Especialidades:</label> -->
                                        <input id="input" class="form-control" type="text" placeholder="ESPECIALIDAD" v-on:keyup.13="buscar_por_especialidad">
                                        <typeahead v-model="model" target="#input" :data="especialidades" item-key="name"/>
                                        <!-- <span v-show="model">You selected {{model}}</span> -->
                                    </td>
                                    <td>
                                       <!--  <li v-for="especialidad in especialidades">
                                            {{ especialidad.name }}
                                        </li> -->
                                        <!-- <label for="input">Medicos:</label> -->
                                        <input id="input-med" class="form-control" type="text" placeholder="MEDICO" v-on:keyup.13="buscar_por_medico">
                                        <typeahead v-model="modelmedicos" target="#input-med" :data="medicos" item-key="name"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-3"></div>
                        <div class="col-xs-4">
                            <datepicker :inline="true" :language="es" :value="fecha" @selected="buscar_por_fecha"></datepicker>
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
    import {Typeahead} from 'uiv'
    

    export default {
        components: {
            Typeahead,
            Datepicker
        },
        data() {
            return {
                es: es,
                id_medico: '',
                id_especialidad: '',
                fecha: moment(new Date()).format('YYYY-MM-DD'),
                model: '',
                modelmedicos:'',
                especialidades: [],
                medicos: [],
            }
        },
        created: function() {
            this.cargar_especialidades();
            this.cargar_medicos();
        },
        methods: {
            buscar_por_fecha: function(fecha) {
                console.log(moment(fecha).format('YYYY-MM-DD'));
            },
            buscar_por_especialidad: function() {
                this.id_especialidad = this.model.id;
                console.log(this.id_especialidad);
            },
            buscar_por_medico: function() {
                this.id_medico = this.modelmedicos.id;
                console.log(this.id_medico);
            },
            cargar_especialidades: function() {
                var url_especialidades = 'AdmisionCE/especialidades';
                axios.get(url_especialidades).then(response => {  
                    this.especialidades = response.data;                        
                    // console.log(response.data);
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de especialidades cargadas.');
                });
                
            },
            cargar_medicos: function() {
                var url_medicos = 'AdmisionCE/medicos';
                axios.get(url_medicos).then(response => {  
                    this.medicos = response.data;                        
                    // console.log(response.data);
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de medicos cargadas.');
                });
                
            }
        },
        mounted() {
            
        }
    }
</script>