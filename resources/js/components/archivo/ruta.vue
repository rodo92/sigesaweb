<style>
    table.tabla_servicios {
        width: 100%;
        border-collapse: collapse;
    }

    table.tabla_servicios td,table.tabla_servicios th {
        border: 1px solid #ddd;
        padding: 4px;
    }

    table.tabla_rutas {
        width: 100%;
        border-collapse: collapse;
    }

    table.tabla_rutas td,table.tabla_rutas th {
        border: 1px solid #ddd;
        padding: 6px;
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
                                <!-- <td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">NOMBRE RUTA</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <td width="15%" style="padding-right: 5px;">
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
                        <table class="tabla_rutas">
                            <caption>Rutas registrada en el Sistema</caption>
                            <thead>
                                <tr>
                                    <th style="text-align:center;" width="5%">#</th>
                                    <th style="text-align:left;" width="75%">RUTA</th>
                                    <th style="text-align:center;" width="20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ruta_mostrar,index) in rutas_mostrar" v-bind:index="index" v-show="(pag - 1) * NUM_RESULTS <= index  && pag * NUM_RESULTS > index">
                                    <td align="center" width="5%">{{ index + 1 }}</td>
                                    <td v-text="ruta_mostrar.Nombre" width="75%"></td>
                                    <td align="center" width="20%">
                                        <button class="btn btn-default btn-xs" v-on:click.prevent="ver_detalle_ruta(ruta_mostrar.IdRuta)"><i class="fa fa-search"></i></button>
                                        <button class="btn btn-warning btn-xs" v-on:click.prevent="ver_editar_ruta(ruta_mostrar.IdRuta)"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-xs" v-on:click.prevent="eliminar_ruta(ruta_mostrar.IdRuta)"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation" class="text-center">
                            <ul class="pagination text-center">
                                <li>
                                    <a href="#" aria-label="Previous" v-show="pag != 1" @click.prevent="pag -= 1">
                                        <span aria-hidden="true">&larr;</span> Anterior
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="Next" v-show="pag * NUM_RESULTS / rutas_mostrar.length < 1" @click.prevent="pag += 1">
                                        Siguiente <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
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
                                                <input type="text" class="form-control" v-model="ruta_nueva" style="text-transform: uppercase;" v-on:keyup.13="pasar_tipo_servicio" id="nueva_ruta_id">
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

            <div id="editar_rutas" style="display: none;">
                <div class="box box-primary color-palette-box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <table>
                                    <!-- <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">Nombre de ruta:</label>
                                                <input type="text" class="form-control" v-model="ruta_nueva" style="text-transform: uppercase;" v-on:keyup.13="pasar_tipo_servicio" id="nueva_ruta_id">
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr> -->
                                    <tr>
                                        <td>
                                           <div class="form-group">
                                                <label for="">Tipo de Servicio:</label>
                                                <input id="id_tipo_especialidad_editar" class="form-control" type="text" placeholder="" v-on:keyup.13="buscar_especialidad_por_tipo_editar">
                                                <typeahead v-model="modaltiposervicio_editar" target="#id_tipo_especialidad_editar" :data="tiposervicios_editar" item-key="name"/>
                                            </div> 
                                        </td>
                                        <td style="padding-left: 5px;">
                                            <div class="form-group">
                                                <label for="">Especialidad:</label>
                                                <input id="id_especialidad_editar" class="form-control" type="text" placeholder="" v-on:keyup.13="buscar_servicio_por_especialidad_editar">
                                                <typeahead v-model="modalespecialidad_editar" target="#id_especialidad_editar" :data="especialidades_editar" item-key="name"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">DNI Conserje:</label>
                                                <input type="text" class="form-control" v-model.trim="dni_busqueda_editar" v-on:keyup.13="buscar_archivero_dni_l_editar" id="dni_busqueda_id_editar">
                                            </div> 
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>

                                <div style="overflow:scroll;height: 300px;width: 100%;" v-if="servicios_editar.length > 0">
                                    <table class="tabla_servicios">
                                        <caption>Lista de Servicios</caption>
                                        <tr v-for="(servicio_editar,index) in servicios_editar" v-bind:index="index">
                                            <td v-text="servicio_editar.name"></td>
                                            <td align="center"><button class="btn btn-success btn-xs" v-on:click.prevent="agregar_temp_servicio_editar(index)"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <dl>
                                            <dt>Nombre de la ruta:</dt>
                                            <dd style="text-transform: uppercase;text-align: center;padding: 3%;" class="bg-info">
                                                <h1>{{ ruta_editar }}</h1>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-6">
                                        <table class="tabla_servicios" v-if="archiveros_lista_editar.length > 0">
                                            <caption>Conserjes para la nueva Ruta</caption>
                                            <tr v-for="(archivero_lista_editar,index) in archiveros_lista_editar" v-bind:index="index">
                                                <td v-text="archivero_lista_editar.Conserje"></td>
                                                <td align="center"><button class="btn btn-danger btn-xs" v-on:click.prevent="eliminar_conserje_editar(index)"><i class="fa fa-trash-o"></i></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <table class="tabla_servicios" v-if="servicios_editar_agregar.length > 0">
                                    <caption>Servicios para la Ruta a editar</caption>
                                    <tr v-for="(servicio_editar,index) in servicios_editar_agregar" v-bind:index="index">
                                        <td v-text="servicio_editar.Nombre"></td>
                                        <td align="center"><button class="btn btn-danger btn-xs" v-on:click.prevent="eliminar_servicio_agregar_editar(index)"><i class="fa fa-trash-o"></i></button></td>
                                    </tr>
                                </table>
                                <hr>
                                <div style="text-align: center;">
                                    <button class="btn btn-default" v-on:click.prevent="ocultar_agregar_rutas"><i class="fa fa-arrow-left" ></i> RETORNAR</button>
                                    <button type="" class="btn btn-primary" v-on:click.prevent="editar_nueva_ruta"><i class="fa fa-save"></i> ACTUALIZAR RUTA</button>
                                    <button type="" class="btn btn-default" v-on:click.prevent="ver_editar_ruta(id_ruta_editar)"><i class="fa fa-save"></i> CANCELAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="modal_detalle_ruta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ ruta_mostrar }}&nbsp;&nbsp;<i class="fa fa-map-marker"></i></h4>
                    </div>
                    <div class="modal-body">
                        <table v-if="conserjes_mostrar != 'Sin conserjes registrados'">
                            <caption style="text-decoration: underline;"><i class="fa fa-users"></i>&nbsp;CONSERJES</caption>
                            <tr v-for="(conserje_mostrar,index) in conserjes_mostrar" v-bind:index="index">
                                <td><i class="fa fa-user"></i>&nbsp;&nbsp;{{ conserje_mostrar.Conserje }}</td>
                            </tr>
                        </table>
                        <hr v-if="conserjes_mostrar != 'Sin conserjes registrados'">
                        <div class="row" v-for="(detalle,index) in detalle_mostrar" style="text-align: left;width: 100%;">
                            <p style="margin-left: 2%;text-transform: uppercase;"><i class="fa fa-check"></i>&nbsp;Tipo de Servicio: {{ detalle.TipoServicio }}</p>
                            <div class="col-xs-3 col-sm-4 col-xs-md-12" v-for="(especialidadm, index2) in detalle.Especialidades">
                                <blockquote>
                                    <table>
                                        <p><i class="fa fa-hospital-o"></i>&nbsp;{{ especialidadm.Especialidad }}</p>
                                        <footer v-for="(serviciosm, index3) in especialidadm.Servicios">
                                            {{ serviciosm.Servicio }} <br>
                                        </footer>
                                    </table>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                rutas_mostrar: [],
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
                archiveros_lista: [],
                NUM_RESULTS: 5,
                pag: 1,

                // datos para mostrar
                ruta_mostrar: '',
                detalle_mostrar: '',
                conserjes_mostrar: '',

                // variables para actualizar
                id_ruta_editar: '',
                ruta_editar: '',
                archiveros_lista_editar: [],
                servicios_editar_agregar: [],
                tiposervicios_editar:[],
                modaltiposervicio_editar: '',
                idtiposervicio_editar: '',
                especialidades_editar: [],
                modalespecialidad_editar: '',
                servicios_editar: [],
                dni_busqueda_editar: '',
            }
        },
        created: function() {
            this.cargar_tipo_servicio();
            this.cargar_rutas_mostrar();
        },
        methods: {
            eliminar_ruta: function(id_ruta) {
                var url = 'rutas/ruta_eliminar/' + id_ruta;
                axios.get(url).then(response => {
                    toastr.clear();
                    toastr.success('Ruta eliminada con éxito.','WebSigesa');
                    // this.cargar_tipo_servicio();
                    this.cargar_rutas_mostrar();
                }).catch(error => {
                    toastr.clear();
                    toastr.error('No se pudo eliminar, intentelo nuevamente.','WebSigesa');
                });
                // this.cargar_rutas_mostrar();
            },
            ver_editar_ruta: function(id_ruta) {
                this.limpiar_todo_agregar_ruta();
                this.id_ruta_editar = id_ruta;
                var url = 'rutas/ruta_detalle/' + id_ruta;
                var url2 = 'rutas/servicios/' + id_ruta;
                axios.get(url).then(response => {
                    this.archiveros_lista_editar = [];
                    this.ruta_editar = response.data.data.ruta;
                    var consej_temp = response.data.data.conserjes;

                    if (consej_temp == 'Sin conserjes registrados') {
                        this.archiveros_lista_editar = [];
                    } else {
                    
                        for(var i = 0; i < consej_temp.length; i++){
                            this.archiveros_lista_editar.push({
                                IdEmpleado: consej_temp[i]['IdEmpleado'],
                                Conserje: consej_temp[i]['Conserje'].toUpperCase()
                            });
                        }
                    }
                    // this.limpiar_todo_agregar_ruta();
                    $('#lista_rutas').hide();
                    $('#editar_rutas').fadeIn(400);
                    $('#nueva_ruta_id').focus();
                }).catch(error => {

                });

                axios.get(url2).then(response => {
                    // console.log(response.data.data);
                    this.servicios_editar_agregar = [];
                    var servicios_temp = response.data.data;
                    for(var i = 0; i < servicios_temp.length; i++){
                        this.servicios_editar_agregar.push({
                            IdServicio: servicios_temp[i]['IdServicio'],
                            Nombre: servicios_temp[i]['Nombre'].toUpperCase()
                        });
                    }
                }).catch(error => {

                });
            },
            ver_detalle_ruta: function(id_ruta) {
                var url = 'rutas/ruta_detalle/' + id_ruta;
                axios.get(url).then(response => {
                    // console.log(response.data.data);
                    this.ruta_mostrar = response.data.data.ruta;
                    this.conserjes_mostrar = response.data.data.conserjes;
                    this.detalle_mostrar = response.data.data.detalle;
                }).catch(error => {

                });
                $('#modal_detalle_ruta').modal('show');
                // console.log(id_ruta);
            },
            cargar_rutas_mostrar: function() {
                var url = 'rutas/listar';
                this.rutas_mostrar = [],
                axios.get(url).then(response => {
                    if (response.data.data == 'sindatos') {

                    } else {
                        var datos = response.data.data;
                        for (var i = datos.length - 1; i >= 0; i--) {
                            // console.log(datos[i]['Nombre']);
                            this.rutas_mostrar.push({
                                IdRuta: datos[i]['IdRuta'],
                                Nombre: datos[i]['Nombre']
                            })
                        }
                    }
                }).catch(error => {

                });
            },
            agregar_nueva_ruta: function() {
                var url = 'rutas/registrar_ruta';

                axios.post(url, {
                    'ruta_nombre': this.ruta_nueva,
                    'servicios': this.servicios_agregar,
                    'conserjes': this.archiveros_lista
                }).then(response => {
                    if (response.data.data == 'noseregistro') {
                        toastr.clear();
                        toastr.error('No se pudo registrar, intentelo nuevamente.','WebSigesa');
                    } else {
                        toastr.clear();
                        toastr.success('Se registraron los datos correctamente.','WebSigesa');
                        this.limpiar_todo_agregar_ruta();
                        this.cargar_rutas_mostrar();
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });
            },

            editar_nueva_ruta: function() {
                var url = 'rutas/editar_ruta';

                axios.post(url, {
                    'id_ruta': this.id_ruta_editar,
                    'servicios': this.servicios_editar_agregar,
                    'conserjes': this.archiveros_lista_editar
                }).then(response => {
                    if (response.data.data == 'noseregistro') {
                        toastr.clear();
                        toastr.error('No se pudo actualizar, intentelo nuevamente.','WebSigesa');
                    } else {
                        toastr.clear();
                        toastr.success('Se actualizaron los datos correctamente.','WebSigesa');
                        this.ver_editar_ruta(this.id_ruta_editar);
                        this.cargar_rutas_mostrar();
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });
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
                this.id_ruta_editar = '';
                this.ruta_editar = '';
                this.archiveros_lista_editar = [];
                this.servicios_editar_agregar = [];
                this.tiposervicios_edita = [];
                this.modaltiposervicio_editar = '';
                this.idtiposervicio_editar = '';
                this.especialidades_editar = [];
                this.modalespecialidad_editar = '';
                this.servicios_editar = [];
                this.dni_busqueda_editar = '';
            },
            pasar_tipo_servicio: function() {
                $('#id_tipo_especialidad').focus();
            },
            mostrar_agregar_rutas: function() {
                this.limpiar_todo_agregar_ruta();
                $('#lista_rutas').hide();
                $('#agregar_rutas').fadeIn(400);
                $('#nueva_ruta_id').focus();
            },
            ocultar_agregar_rutas: function()
            {
                this.limpiar_todo_agregar_ruta();
                $('#agregar_rutas').hide();
                $('#editar_rutas').hide();
                $('#lista_rutas').fadeIn(400);
            },
            cargar_tipo_servicio: function() {
                var url = 'sistema/tiposervicios';
                axios.get(url).then(response => {  
                    this.tiposervicios = response.data;  
                    this.tiposervicios_editar = response.data;                      
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

            buscar_especialidad_por_tipo_editar: function() {
                this.idtiposervicio_editar = this.modaltiposervicio_editar.id;
                
                var url = 'sistema/especialidad_tipo_servicio/' + this.idtiposervicio_editar;
                axios.get(url).then(response => {  
                    this.especialidades_editar = response.data; 
                    this.modalespecialidad_editar = '';
                    $('#id_especialidad_editar').focus();
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
            buscar_servicio_por_especialidad_editar: function() {
                this.idespecialidad_editar = this.modalespecialidad_editar.id;

                var url = 'sistema/servicios/' + this.idespecialidad_editar;
                axios.get(url).then(response => { 
                    console.log(response.data);
                    this.servicios_editar = response.data; 
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
            agregar_temp_servicio_editar: function(index) {
                for(var key in this.servicios_editar_agregar)
                {
                    if(this.servicios_editar_agregar[key]['IdServicio'] == this.servicios_editar[index]['id'])
                    {
                        toastr.clear();
                        toastr.warning('Este servicio ya esta agregado para ser registrado.','WebSigesa');
                        return false;
                    }
                }
                this.servicios_editar_agregar.push({
                    IdServicio: this.servicios_editar[index]['id'],
                    Nombre: this.servicios_editar[index]['name']
                });
            },
            eliminar_servicio_agregar: function(index) {
                this.servicios_agregar.splice(index,1);
            },
            eliminar_servicio_agregar_editar: function(index) {
                this.servicios_editar_agregar.splice(index,1);
            },
            eliminar_conserje: function(index) {
                this.archiveros_lista.splice(index,1);
            },
            eliminar_conserje_editar: function(index) {
                this.archiveros_lista_editar.splice(index,1);
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

            buscar_archivero_dni_l_editar: function() {
               if (this.dni_busqueda_editar.length <= 0) {
                    toastr.clear();
                    toastr.error('Debe ingresar un número de DNI.','WebSigesa');
                    this.dni_busqueda_editar = '';
                    $('#dni_busqueda_id_editar').focus();
                    return false;
                } else if (this.dni_busqueda_editar.length > 8) {
                    toastr.clear();
                    toastr.error('La cantidad de digitos no es valida para el DNI.','WebSigesa');
                    this.dni_busqueda_editar = '';
                    $('#dni_busqueda_id_editar').focus();
                    return false;
                }
                var url = 'Archivero/buscar/' + this.dni_busqueda_editar;
                axios.get(url).then(response => {
                    if (response.data.data == 'sindatos') { 
                        toastr.clear();
                        toastr.warning('No existe el empleado registrado en el sistema.','WebSigesa');
                        this.dni_busqueda_editar = '';
                        $('#dni_busqueda_id_editar').focus();
                    }
                    else {
                        var data = response.data.data;
                        for(var key in this.archiveros_lista_editar)
                        {
                            if(this.archiveros_lista_editar[key]['IdEmpleado'] == data.IDEMPLEADO)
                            {
                                toastr.clear();
                                toastr.warning('El conserje ya ha sido agregado para ser registrado.','WebSigesa');
                                this.dni_busqueda_editar = '';
                                // $('#id_digito_inicial').focus();
                                return false;
                            }
                        }
                        this.archiveros_lista_editar.push({
                            IdEmpleado: data.IDEMPLEADO,
                            Conserje:  data.PATERNO + ' ' + data.MATERNO + ' ' + data.NOMBRE
                        });
                        this.dni_busqueda_editar = '';
                        // $('#id_digito_inicial').focus();
                    }
                }).catch(error => {
                    toastr.clear();
                    toastr.warning('No se puede buscar el DNI.','WebSigesa');
                });
            },
        },

    }
</script>
