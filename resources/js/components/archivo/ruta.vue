<style>
    table.tabla_servicios {
        width: 100%;
        border-collapse: collapse;
    }

    table.tabla_servicios td,table.tabla_servicios th {
        border: 1px solid #ddd;
        padding: 4px;
    }
</style>
<template>
    <div>
        <section class="content-header">
            <h1>
                Rutas
                <small>Archivo</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Archivo</a></li>
                <li class="active">Rutas</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
            <div id="lista_rutas">
                <div class="box box-primary color-palette-box">
                    <div class="box-body">
                        <table style="width: 100%">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">NOMBRE RUTA</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <!-- <td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">Apellido Paterno</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">Apellido Materno</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">Nombres</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </td> -->
                                <td width="40%" align="right">
                                    <button class="btn btn-primary" v-on:click.prevent="mostrar_agregar_rutas"><i class="fa fa-plus"></i> Agregar</button>
                                    <button class="btn btn-default"><i class="fa fa-search" v-on:click.prevent=""></i> Buscar</button>
                                    <a class="btn btn-success" href="rutas"><i class="fa fa-refresh"></i> Limpiar</a>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table class="tabla_archiveros_lista">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">#</th>
                                    <th style="text-align:center;">RUTA</th>
                                    <th style="text-align:center;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr v-for="(arch_lista,index) in archiveros_lista" v-bind:index="index" v-show="(pag - 1) * NUM_RESULTS <= index  && pag * NUM_RESULTS > index">
                                    <td align="center">{{ index + 1 }}</td>
                                    <td align="center" v-text="arch_lista.DNI"></td>
                                    <td v-text="arch_lista.EMPLEADO"></td>
                                    <td align="center" v-text="arch_lista.DIGITOINICIAL"></td>
                                    <td align="center" v-text="arch_lista.DIGITOFINAL"></td>
                                    <td align="center">
                                        <button class="btn btn-danger btn-sm" v-on:click.prevent="eliminar_archivero(index)"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>

                        <!-- <nav aria-label="Page navigation" class="text-center">
                            <ul class="pagination text-center">
                                <li>
                                    <a href="#" aria-label="Previous" v-show="pag != 1" @click.prevent="pag -= 1">
                                        <span aria-hidden="true">&larr;</span> Anterior
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="Next" v-show="pag * NUM_RESULTS / archiveros_lista.length < 1" @click.prevent="pag += 1">
                                        Siguiente <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav> -->
                    </div>
                </div>
            </div>
            <div id="agregar_rutas" style="display: none;">
                <div class="box box-primary color-palette-box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">Nombre de ruta:</label>
                                                <input type="text" class="form-control" v-model="ruta_nueva" style="text-transform: uppercase;" v-on:keyup.13="pasar_tipo_servicio">
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                           <div class="form-group">
                                                <label for="">Tipo de Servicio:</label>
                                                <input id="id_tipo_especialidad" class="form-control" type="text" placeholder="" v-on:keyup.13="buscar_especialidad_por_tipo">
                                                <typeahead v-model="modaltiposervicio" target="#id_tipo_especialidad" :data="tiposervicios" item-key="name"/>
                                            </div> 
                                        </td>
                                        <td style="padding-left: 5px;">
                                            <div class="form-group">
                                                <label for="">Especialidad:</label>
                                                <input id="id_especialidad" class="form-control" type="text" placeholder="" v-on:keyup.13="buscar_servicio_por_especialidad">
                                                <typeahead v-model="modalespecialidad" target="#id_especialidad" :data="especialidades" item-key="name"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">DNI Conserje:</label>
                                                <input type="text" class="form-control" v-model.trim="dni_busqueda" v-on:keyup.13="buscar_archivero_dni_l" id="dni_busqueda_id">
                                            </div> 
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>

                                <div style="overflow:scroll;height: 300px;width: 100%;" v-if="servicios.length > 0">
                                    <table class="tabla_servicios">
                                        <caption>Lista de Servicios</caption>
                                        <tr v-for="(servicio,index) in servicios" v-bind:index="index">
                                            <td v-text="servicio.name"></td>
                                            <td align="center"><button class="btn btn-success btn-xs" v-on:click.prevent="agregar_temp_servicio(index)"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <dl>
                                            <dt>Nombre para la nueva ruta:</dt>
                                            <dd style="text-transform: uppercase;text-align: center;padding: 3%;" class="bg-info">
                                                <h1>{{ ruta_nueva }}</h1>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-6">
                                        <table class="tabla_servicios" v-if="archiveros_lista.length > 0">
                                            <caption>Conserjes para la nueva Ruta</caption>
                                            <tr v-for="(archivero_lista,index) in archiveros_lista" v-bind:index="index">
                                                <td v-text="archivero_lista.Conserje"></td>
                                                <td align="center"><button class="btn btn-danger btn-xs" v-on:click.prevent="eliminar_conserje(index)"><i class="fa fa-trash-o"></i></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <table class="tabla_servicios" v-if="servicios_agregar.length > 0">
                                    <caption>Servicios para la nueva Ruta</caption>
                                    <tr v-for="(servicio_agregar,index) in servicios_agregar" v-bind:index="index">
                                        <td v-text="servicio_agregar.Nombre"></td>
                                        <td align="center"><button class="btn btn-danger btn-xs" v-on:click.prevent="eliminar_servicio_agregar(index)"><i class="fa fa-trash-o"></i></button></td>
                                    </tr>
                                </table>
                                <hr>
                                <div style="text-align: center;">
                                    <button class="btn btn-default" v-on:click.prevent="ocultar_agregar_rutas"><i class="fa fa-arrow-left" ></i> RETORNAR</button>
                                    <button type="" class="btn btn-primary" v-on:click.prevent="agregar_nueva_ruta"><i class="fa fa-save"></i> REGISTRAR RUTA</button>
                                    <button type="" class="btn btn-default" v-on:click.prevent="limpiar_todo_agregar_ruta"><i class="fa fa-save"></i> CANCELAR</button>
                                </div>
                            </div>
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
    import {Typeahead} from 'uiv'

    export default {
        components: {
            Typeahead,
        },
        data() {
            return {
                ruta_nueva: '',
                tiposervicios:[],
                especialidades:[],
                modaltiposervicio:'',
                modalespecialidad:'',
                idtiposervicio: '',
                idespecialidad: '',
                servicios: [],
                servicios_agregar: [],
                dni_busqueda: '',
                archiveros_lista: []
            }
        },
        created: function() {
            this.cargar_tipo_servicio();
        },
        methods: {
            agregar_nueva_ruta: function() {
                
            },
            limpiar_todo_agregar_ruta: function() {
                this.ruta_nueva = '';
                // this.tiposervicios =[];
                this.especialidades =[];
                this.modaltiposervicio ='';
                this.modalespecialidad ='';
                this.idtiposervicio = '';
                this.idespecialidad = '';
                this.servicios = [];
                this.servicios_agregar = [];
                this.dni_busqueda = '';
                this.archiveros_lista = [];
            },
            pasar_tipo_servicio: function() {
                $('#id_tipo_especialidad').focus();
            },
            mostrar_agregar_rutas: function() {
                this.limpiar_todo_agregar_ruta();
                $('#lista_rutas').hide();
                $('#agregar_rutas').fadeIn(400);
            },
            ocultar_agregar_rutas: function()
            {
                this.limpiar_todo_agregar_ruta();
                $('#agregar_rutas').hide();
                $('#lista_rutas').fadeIn(400);
            },
            cargar_tipo_servicio: function() {
                var url = 'sistema/tiposervicios';
                axios.get(url).then(response => {  
                    this.tiposervicios = response.data;                        
                    // console.log(response.data);
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de tipos de servicios cargadas.');
                });
                
            },
            buscar_especialidad_por_tipo: function() {
                this.idtiposervicio = this.modaltiposervicio.id;
                
                var url = 'sistema/especialidad_tipo_servicio/' + this.idtiposervicio;
                axios.get(url).then(response => {  
                    this.especialidades = response.data; 
                    this.modalespecialidad = '';
                    $('#id_especialidad').focus();
                    // console.log(response.data);
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de especialidades cargadas.');
                });
            },
            buscar_servicio_por_especialidad: function() {
                this.idespecialidad = this.modalespecialidad.id;

                var url = 'sistema/servicios/' + this.idespecialidad;
                axios.get(url).then(response => {  
                    this.servicios = response.data; 
                }).catch(error => {
                    console.log(url);
                    console.log('no hay datos de servicios cargadas.');
                });
            },
            agregar_temp_servicio: function(index) {
                for(var key in this.servicios_agregar)
                {
                    if(this.servicios_agregar[key]['IdServicio'] == this.servicios[index]['id'])
                    {
                        toastr.clear();
                        toastr.warning('Este servicio ya esta agregado para ser registrado.','WebSigesa');
                        return false;
                    }
                }
                this.servicios_agregar.push({
                    IdServicio: this.servicios[index]['id'],
                    Nombre: this.servicios[index]['name']
                });
            },
            eliminar_servicio_agregar: function(index) {
                this.servicios_agregar.splice(index,1);
            },
            eliminar_conserje: function(index) {
                this.archiveros_lista.splice(index,1);
            },
            buscar_archivero_dni_l: function() {
               if (this.dni_busqueda.length <= 0) {
                    toastr.clear();
                    toastr.error('Debe ingresar un número de DNI.','WebSigesa');
                    this.dni_busqueda = '';
                    $('#dni_busqueda_id').focus();
                    return false;
                } else if (this.dni_busqueda.length > 8) {
                    toastr.clear();
                    toastr.error('La cantidad de digitos no es valida para el DNI.','WebSigesa');
                    this.dni_busqueda = '';
                    $('#dni_busqueda_id').focus();
                    return false;
                }
                var url = 'Archivero/buscar/' + this.dni_busqueda;
                axios.get(url).then(response => {
                    if (response.data.data == 'sindatos') { 
                        toastr.clear();
                        toastr.warning('No existe el empleado registrado en el sistema.','WebSigesa');
                        this.dni_busqueda = '';
                        $('#dni_busqueda_id').focus();
                    }
                    else {
                        var data = response.data.data;
                        for(var key in this.archiveros_lista)
                        {
                            if(this.archiveros_lista[key]['IdEmpleado'] == data.IDEMPLEADO)
                            {
                                toastr.clear();
                                toastr.warning('El conserje ya ha sido agregado para ser registrado.','WebSigesa');
                                this.dni_busqueda = '';
                                $('#id_digito_inicial').focus();
                                return false;
                            }
                        }
                        this.archiveros_lista.push({
                            IdEmpleado: data.IDEMPLEADO,
                            Conserje:  data.PATERNO + ' ' + data.MATERNO + ' ' + data.NOMBRE
                        });
                        this.dni_busqueda = '';
                        $('#id_digito_inicial').focus();
                    }
                }).catch(error => {
                    toastr.clear();
                    toastr.warning('No se puede buscar el DNI.','WebSigesa');
                });
            },
        }
    }
</script>
