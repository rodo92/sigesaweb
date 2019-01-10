<style>
    table.tabla_archiveros_lista {
        width: 100%;
        border-collapse: collapse;
    }

    table.tabla_archiveros_lista td,table.tabla_archiveros_lista th {
        border: 1px solid #ddd;
        padding: 6px;
    }
</style>

<template>
    <div>
        <section class="content-header">
            <h1>
                Archiveros
                <small>Archivo</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Archivo</a></li>
                <li class="active">Archiveros</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
            <div id="lista_archiveros">
                <div class="box box-primary color-palette-box">
                    <div class="box-body">
                        <table style="width: 100%">
                            <tr>
                                <td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">DNI</label>
                                        <input type="text" class="form-control" v-model.trim="dni_busqueda" v-on:keyup.13="buscar_archivero_dni_l" id="dni_busqueda_id">
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
                                    <button class="btn btn-primary" v-on:click.prevent="mostrar_agregar_archiveros"><i class="fa fa-plus"></i> Agregar</button>
                                    <button class="btn btn-default"><i class="fa fa-search" v-on:click.prevent="buscar_archivero_dni_l"></i> Buscar</button>
                                    <a class="btn btn-success" href="Archivero"><i class="fa fa-refresh"></i> Limpiar</a>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table class="tabla_archiveros_lista">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">#</th>
                                    <th style="text-align:center;">DNI</th>
                                    <th>ARCHIVERO</th>
                                    <th style="text-align:center;">DIGITO<br>INICIAL</th>
                                    <th style="text-align:center;">DIGITO<br>FIN</th>
                                    <th style="text-align:center;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(arch_lista,index) in archiveros_lista" v-bind:index="index" v-show="(pag - 1) * NUM_RESULTS <= index  && pag * NUM_RESULTS > index">
                                    <td align="center">{{ index + 1 }}</td>
                                    <td align="center" v-text="arch_lista.DNI"></td>
                                    <td v-text="arch_lista.EMPLEADO"></td>
                                    <td align="center" v-text="arch_lista.DIGITOINICIAL"></td>
                                    <td align="center" v-text="arch_lista.DIGITOFINAL"></td>
                                    <td align="center">
                                        <button class="btn btn-danger btn-sm" v-on:click.prevent="eliminar_archivero(index)"><i class="fa fa-trash-o"></i></button>
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
                                    <a href="#" aria-label="Next" v-show="pag * NUM_RESULTS / archiveros_lista.length < 1" @click.prevent="pag += 1">
                                        Siguiente <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div id="agregar_archiver" style="display: none;">
                <div class="box box-primary color-palette-box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4" style="padding-bottom: 5%;">
                                <h3>Nuevo Archivero</h3>
                                <hr>
                                <div class="form-group">
                                    <label for="">DNI:</label>
                                    <input type="text" class="form-control" placeholder="NUMERO DE DNI" v-on:keyup.13="buscar_archivero" v-model.trim="dni" id="id_numero_dni">
                                </div>
                                <div class="form-group">
                                    <label for="">Datos del Empleado</label>
                                    <p class="bg-warning">Nombres: {{ nombres_mostrar }}</p>
                                    <p class="bg-warning">Apellidos: {{ paterno_mostrar + ' ' + materno_mostrar }}</p>
                                </div>
                                <table width="100%">
                                    <tr>
                                        <td style="padding-right: 5px;">
                                            <div class="form-group">
                                                <label for="">D&iacute;gito Inicial:</label>
                                                <input type="text" class="form-control" placeholder="DIGITO INICIAL" id="id_digito_inicial" v-on:keyup.13="pasar_digito_final" v-model.trim="dt_inicio_registrar">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label for="">D&iacute;gito Final:</label>
                                                <input type="text" class="form-control" placeholder="DIGITO FINAL" id="id_digito_final" v-on:keyup.13="registrar_archivero_digito_terminal" v-model.trim="dt_fin_registrar">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <div style="text-align: center;">
                                    <button class="btn btn-default" v-on:click.prevent="registrar_archivero_digito_terminal"><i class="fa fa-save"></i> Registrar</button>
                                    <button class="btn btn-default" v-on:click.prevent="limpiar_campos_registro"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
                                    <button class="btn btn-default" v-on:click.prevent="ocultar_agregar_archiveros"><i class="fa fa-arrow-left" ></i> Retornar</button>
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

    export default {
        data() {
            return {
                dni: '',
                dni_busqueda: '',
                paterno_mostrar: '',
                materno_mostrar: '',
                nombres_mostrar: '',
                id_empleado_grabar: '',
                dt_inicio_registrar: '',
                dt_fin_registrar: '',
                archiveros_lista: [],
                NUM_RESULTS: 5,
                pag: 1,
            }
        },
        created: function() {
            this.cargar_datos();
        },
        methods: {
            eliminar_archivero: function(index) {
                // console.log(this.archiveros_lista[index]['IDARCHIVODIGITOTERMINAL']);
                var url = 'Archivero/eliminar/' + this.archiveros_lista[index]['IDARCHIVODIGITOTERMINAL'];
                toastr.remove();
                axios.get(url).then(response => {
                    if (response.data.data == 'eliminado') {
                        toastr.success('Se elimino correctamente.','WebSigesa');
                        this.cargar_datos();
                    } else if (response.data.data == 'no eliminado') {
                        toastr.warning('No se elimino, intentelo nuevamente.','WebSigesa');
                    }
                }).catch(error => {

                });
            },
            buscar_archivero_dni_l: function() {
                if (this.dni_busqueda.length <= 0) {
                    toastr.error('Debe ingresar un número de DNI.','WebSigesa');
                    this.dni_busqueda = '';
                    $('#dni_busqueda_id').focus();
                    return false;
                } else if (this.dni_busqueda.length > 8) {
                    toastr.error('La cantidad de digitos no es valida para el DNI.','WebSigesa');
                    this.dni_busqueda = '';
                    $('#dni_busqueda_id').focus();
                    return false;
                }
                var url = 'Archivero/listar_dni/' + this.dni_busqueda;

                axios.get(url).then(response => {
                    if (response.data.data == 'sindatos') { 
                        this.archiveros_lista = [];
                        toastr.warning('No se encontraron registros.','WebSigesa');
                    } else {
                        this.archiveros_lista = [];
                        this.archiveros_lista.push(response.data.data);
                        this.dni_busqueda = '';
                        // this.archiveros_lista = response.data.data;
                    }
                    // console.log(response.data.data);
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            cargar_datos: function() {
                var url = 'Archivero/listar';

                axios.get(url).then(response => {
                    if (response.data.data == 'sindatos') { 
                        this.archiveros_lista = [];
                        toastr.warning('No se encontraron registros.','WebSigesa');
                    } else {
                        this.archiveros_lista = response.data.data;
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });

            },
            mostrar_agregar_archiveros: function() {
                $('#lista_archiveros').hide();
                $('#agregar_archiver').fadeIn(400);
                $('#id_numero_dni').focus();
            },
            ocultar_agregar_archiveros: function()
            {
                $('#agregar_archiver').hide();
                $('#lista_archiveros').fadeIn(400);
            },
            limpiar_campos_registro: function() {
                this.dni = '';
                this.paterno_mostrar = '';
                this.materno_mostrar = '';
                this.nombres_mostrar = '';
                this.id_empleado_grabar = '';
                this.dt_inicio_registrar = '';
                this.dt_fin_registrar = '';
                $('#id_numero_dni').focus();
            },
            buscar_archivero: function()
            {
                if (this.dni.length <= 0) {
                    toastr.error('Debe ingresar un número de DNI.','WebSigesa');
                    this.dni = '';
                    $('#id_numero_dni').focus();
                    return false;
                } else if (this.dni.length > 8) {
                    toastr.error('La cantidad de digitos no es valida para el DNI.','WebSigesa');
                    this.dni = '';
                    $('#id_numero_dni').focus();
                    return false;
                }
                var url = 'Archivero/buscar/' + this.dni;
                axios.get(url).then(response => {
                    if (response.data.data == 'sindatos') { 
                        toastr.warning('No existe el empleado registrado en el sistema.','WebSigesa');
                        this.dni = '';
                        this.paterno_mostrar = '';
                        this.materno_mostrar = '';
                        this.nombres_mostrar = '';
                        this.id_empleado_grabar = '';
                        $('#id_numero_dni').focus();
                    }
                    else {
                        var data = response.data.data;
                        this.paterno_mostrar = data.PATERNO;
                        this.materno_mostrar = data.MATERNO;
                        this.nombres_mostrar = data.NOMBRE;
                        this.id_empleado_grabar = data.IDEMPLEADO;
                        $('#id_digito_inicial').focus();
                    }
                }).catch(error => {
                    toastr.warning('No se puede buscar el DNI.','WebSigesa');
                });
            },
            pasar_digito_final: function()
            {
                $('#id_digito_final').focus();
            },
            registrar_archivero_digito_terminal: function()
            {
                if(this.dt_inicio_registrar.length <= 0) {
                    toastr.error('Debe ingresar un Dígito Inicial.','WebSigesa');
                    // return false;
                } else  if(this.dt_fin_registrar.length <= 0) {
                    toastr.error('Debe ingresar un Dígito Final.','WebSigesa');
                    // return false;
                } else {
                    var url = 'Archivero/nuevodigitoterminal/' + this.dt_inicio_registrar + '/' + this.dt_fin_registrar + '/' +  this.id_empleado_grabar;

                    axios.get(url).then(response => {
                        if (response.data.data == 'sindatos') {
                            toastr.warning('No se pudo registrar.','WebSigesa');
                        } else {
                            this.dni = '';
                            this.paterno_mostrar = '';
                            this.materno_mostrar = '';
                            this.nombres_mostrar = '';
                            this.id_empleado_grabar = '';
                            this.dt_inicio_registrar = '';
                            this.dt_fin_registrar = '';
                            $('#id_numero_dni').focus();
                            toastr.success('Se registro exitosamente.','WebSigesa');
                            this.cargar_datos();
                        }
                    }).catch(error => {
                        console.log(error.response.data);
                    });
                }
            }
        },
        mounted() {
        }
    }    
</script>