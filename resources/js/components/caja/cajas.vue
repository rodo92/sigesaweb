<style type="text/css" media="screen">
    table.tabla_datos{
        border-spacing: 5px;
        border-collapse: separate;
    }
    #serie_boleta {
        text-transform: uppercase;
    }
</style>
<template>    
    <div>
        <div>
                        <frame id="pdf_cuerpo" name="pdf_cuerpo"></frame>
                    </div>
        <section class="content-header">
            <h1>
                Cajas
                <small>Caja</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="restaurar"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Caja</li>
            </ol>
        </section>

    <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
            <div class="row" id="cabecera_factura" style="margin-top: 10%;">
                <div class="col-xs-4 col-xs-offset-4">
                    <div class="box box-default color-palette-box" >
                        <div class="box-body" style="padding: 10%;">
                            <div class="form-group">
                                <label>Seleccione un caja</label>
                                <select class="form-control" v-model="idCaja">
                                    <option value=""></option>
                                    <option v-for="caja in cajas" :value="caja.IdCaja" >
                                        {{ caja.Descripcion }}
                                    </option>
                                </select>
                                <label v-if="errores.idcaja" class="text-danger">{{ errores.idcaja[0] }}</label>
                            </div>
                             <div class="form-group">
                                <label>Tipo de Documento</label>
                                <select class="form-control" v-model="idTIpoDocumento" id="tipo_documento_id">
                                    <option value=""></option>
                                    <option v-for="tipdoc in tipo_documento" :value="tipdoc.IdTipoComprobante">
                                        {{ tipdoc.Descripcion }}
                                    </option>
                                </select>
                                <label v-if="errores.tipodocumento" class="text-danger">{{ errores.tipodocumento[0] }}</label>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-block" v-on:click.prevent="btn_ac"><i class="fa fa-print"></i> APERTURA DE CAJA</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-default" id="cuerpo_factura" style="display: none;"><!-- style="display: none;" -->
                <div class="box-body" style="padding: 1%;">
                    <div class="row">
                        <div class="col-xs-8">
                            <table style="width: 100%" class="tabla_datos">
                                <tr>
                                    <td width="15%">
                                        <input type="text" class="form-control" placeholder="RUC" v-on:keyup.13="buscar_proveedor" v-model="ruc" id="ruc_bus" maxlength="11">
                                    </td>
                                    <td width="15%">
                                        <label for="">RAZON&Oacute;N SOCIAL:</label>                                        
                                    </td>                    
                                    <td width="40%" class="bg-warning"  colspan="3">
                                        {{ razonsocial }}
                                    </td>
                                   
                                </tr>
                                <tr>
                                    <td width="15%">
                                        
                                    </td>
                                     <td width="10%">
                                        <label>DIRECCI&Oacute;N :</label>                                        
                                    </td>
                                    <td width="20%" colspan="3" class="bg-warning">
                                       {{ direccion }}
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td>
                                        
                                    </td>
                                    <td>
                                         <label>RUC: </label>
                                    </td>
                                    <td class="bg-warning">
                                        {{ rucv }}
                                    </td>
                                    <td width="10%">
                                        <label>FECHA: </label>
                                        
                                    </td>
                                    <td width="20%" class="bg-warning">
                                        {{ new Date().getDate() + "/" + (new Date().getMonth() +1) + "/" + new Date().getFullYear() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                       <input type="text" id="id_cuenta" class="form-control"  placeholder="N° CUENTA" v-model="cuenta" v-on:keyup.13="buscar_boleta_cuenta"> 
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%">
                                        <input type="text" class="form-control" placeholder="DNI" v-on:keyup.13="buscar_paciente" v-model="dni" id="dni_bus" maxlength="8">
                                    </td>
                                    <td width="15%">
                                        <label for="">PACIENTE:</label>                                        
                                    </td>                    
                                    <td width="40%"  class="bg-warning">
                                        {{ paciente }}
                                    </td>
                                    <td width="10%">
                                        <label> SEGURO:</label>                                        
                                    </td>
                                    <td width="20%" class="bg-warning">
                                       {{ seguro }}
                                   </td>
                                </tr>
                                <tr>
                                    <td width="15%">
                                        <input type="text" id="serie_boleta" class="form-control"  placeholder="SERIE" v-model="serie" >
                                    </td>
                                    <td width="15%" colspan="2">
                                        <input type="text" id="ndocumento_boleta"  class="form-control" placeholder="N° DOCUMENTO" v-on:keyup.13="buscar_boleta" v-model="ndocumento">
                                    </td>
                                    <!-- <td width="10%">
                                        <label> DOCUMENTO:</label>                                        
                                    </td>
                                    <td width="20%" class="bg-warning">
                                       {{ comprobante }}
                                   </td> -->
                                </tr>
                                <!-- <tr>
                                    <td width="15%">
                                        <input type="text" id="id_orden" class="form-control"  placeholder="N° ORDEN" v-model="idorden" v-on:keyup.13="buscar_boleta_id_orden">
                                    </td>                                    
                                </tr> -->
                            </table>
                        </div>
                        <div class="col-xs-4 text-right">
                            <button type="" class="btn btn-info" v-on:click.prevent="ver_modal" id="btn_buscar_productos"><i class="fa fa-search"></i> AGREGAR<br>PRODUCTOS</button>
                            <button type="" class="btn btn-success" v-on:click.prevent="registrarfactura"><i class="fa fa-save"></i> GENERAR<br>FACTURA</button>
                            <button type="" class="btn btn-default" v-on:click.prevent="facturar_clasificador"><i class="fa fa-save"></i> FACTURA<br>CLASIFICADOR</button>
                            <button  class="btn btn-warning" v-on:click.prevent="cerrar_caja"><i class="fa fa-close"></i> CERRAR<br>CAJA</button>
                            <br>
                            <div>
                                <div  style="text-transform: uppercase;">
                                    <h4>{{ cadena_tipo_documento }} ELECTR&Oacute;NICA</h4>
                                    <h4>{{ nroserie_grabar }} - {{ nrodocumento_grabar}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-center">DOCUMENTO</th>
                                        <th class="text-center">CODIGO</th>
                                        <th>DESCRIPCION</th>
                                        <th class="text-center">CANTIDAD</th>
                                        <th class="text-right">I.G.V.</th>
                                        <th class="text-right">PRECIO</th>
                                        <th class="text-right">TOTAL</th>
                                        <th style="display: none;">SUBTOTAL</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(producto,index) in productos" v-bind:index="index">
                                        <td class="text-center" v-text="producto.Comprobante"></td>
                                        <td class="text-center" v-text="producto.Codigo"></td>
                                        <td v-text="producto.Producto"></td>
                                        <td class="text-center" v-text="producto.Cantidad"></td>
                                        <td class="text-right" v-text="producto.Impuesto"></td>
                                        <td class="text-right" v-text="producto.Precio"></td>
                                        <td class="text-right" v-text="producto.TotalUnitario"></td>
                                        <td v-text="producto.Subtotal" style="display: none;"></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-danger" v-on:click.prevent="eliminarRegistrov(index)">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="warning text-right">SUBTOTAL <i class="fa fa-usd" aria-hidden="true"></i></td>
                                        <td class="text-right">{{ sumsubtotal }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="warning text-right">I.G.V.</td>
                                        <td class="text-right">{{ sumigv }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="warning text-right">TOTAL <i class="fa fa-usd" aria-hidden="true"></i></td>
                                        <td class="text-right">{{ sumtotal }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table style="width: 100%;" class="tabla_datos">
                                <tr >
                                    <td width="33%">
                                        <textarea name="" class="form-control" rows="5" style="width: 100%;text-transform: uppercase;" v-model="conceptos" placeholder="CONCEPTO"></textarea>
                                    </td>
                                    <td width="33%">
                                        <textarea name="" class="form-control" rows="5" style="width: 100%;text-transform: uppercase;" v-model="observacion_uno" placeholder="OBSERVACIÓN 1"></textarea>
                                    </td>
                                    <td width="33%">
                                        <textarea name="" class="form-control" rows="5" style="width: 100%;text-transform: uppercase;" v-model="observacion_dos" placeholder="OBSERVACIÓN 2"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- busqueda de poductas -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_ingresos_productos">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Buscar medicamentos, servicios, insumos ...</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-8">
                                <input type="text" class="form-control" placeholder="NOMBRE O CODIGO" id="paramatro_busqueda" v-on:keyup="buscar_item" v-model="txt_busqueda">
                            </div>
                            <div class="col-xs-4">
                                <button class="btn btn-default"><i class="fa fa-search"></i> BUSCAR</button>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 2%;">
                            <div class="col-xs-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center col-xs-2">CODIGO</th>
                                            <th  class="col-xs-6">DESCRIPCION</th>
                                            <th class="text-center col-xs-1">CANTIDAD</th>
                                            <th class="text-center col-xs-2">PRECIO</th>
                                            <th class="text-center col-xs-1">AGREGAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="productos_temp" v-for="producto_temp in productos_temp">
                                            <td class="text-center col-xs-2">{{ producto_temp.Codigo }}</td>
                                            <td class="text-left col-xs-6">{{ producto_temp.Nombre }}</td>
                                            <td class="text-center col-xs-1">
                                                <input type="number" v-bind:id="'codigo_'+producto_temp.Codigo" value="0">
                                            </td>
                                            <td class="text-center col-xs-2">{{ producto_temp.Subtotal }}</td>
                                            <td class="text-center col-xs-1">
                                                
                                                <button class="btn btn-sm btn-primary" v-on:click.prevent="agregarItenm(producto_temp.Codigo,producto_temp.Nombre,producto_temp.Precio,producto_temp.IdPartida,producto_temp.Impuesto,producto_temp.Subtotal)">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                    </div>
                    
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    import axios from 'axios'
    import toastr from 'toastr'

    export default {
        data() {
            return {
                cajas: [],
                tipo_documento: [],
                ruc: '',
                rucv: '',
                razonsocial: '',
                direccion:'',
                idCaja: '',
                idTIpoDocumento: '',
                dni: '',
                paciente: '',
                idpaciente: '',
                seguro: '',
                errores: '',
                serie: null,
                sumsubtotal: 0,
                sumigv: 0,
                sumtotal: 0,
                comprobante: '',
                productos: [],
                productos_temp: [],
                txt_busqueda: '',
                idTipoFinanciamiento: '',
                ndocumento: null,
                idorden: null,
                cuenta: '',
                cuenta_grabar: '',
                observacion_uno: '',
                observacion_dos: '',
                conceptos: '',
                nroserie_grabar: '',
                nrodocumento_grabar: '',
                cadena_tipo_documento: '',
                IdGestionCaja: '',
                totalcobrado: 0,
                imprimir_clasificador: 0,
            }
        },
        created: function() {
            this.loadData();

        },
        mounted() {
            $('#serie_boleta').keyup(function() {
                var cantidad = $(this).val().length;
                if (cantidad == 4) {
                    $('#ndocumento_boleta').focus();
                }
            });
        },
        methods: {

            // carga de datos
            loadData: function() {
                var url = 'cajas/listar'; 
                var url2 = 'cajas/listar_tipo_documento';
                // cargando cajas
                axios.get(url).then(response => {                    
                    this.cajas = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de cajas');
                });

                axios.get(url2).then(response => {                    
                    this.tipo_documento = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de tipos de documentos');
                });                
            },

            eliminarRegistrov: function(index) {
                var clave = this.productos[index]['Comprobante'];
                var aborrar = [];
                for (var i = 0; i < this.productos.length; i++) {
                    if (this.productos[i]['Comprobante'] == clave) {
                        aborrar.push(i);
                    }
                }

                if (aborrar.length > 1) {
                    var indice;
                    var impus = 0;
                    for (var i = 0; i < aborrar.length; i++) {
                        indice = aborrar[i];
                        this.restarmontos(this.productos[indice]['Subtotal'],this.productos[indice]['Impuesto'],this.productos[indice]['TotalUnitario']);
                        /*impus = parseFloat(this.productos[indice]['Impuesto']) + parseFloat(impus);
                        impus = Math.round(impus * 100) / 100;*/
                        //console.log(this.productos[indice]['TotalUnitario']);
                    }  
                    this.productos.splice(aborrar[0],aborrar.length);
                    
                    /*this.sumsubtotal = parseFloat(this.sumsubtotal) + parseFloat(impus);
                    this.sumsubtotal = Math.round(this.sumsubtotal * 100) / 100 ;*/
                }
                else
                {
                    var indice = aborrar[0];
                    this.restarmontos(this.productos[indice]['Subtotal'],this.productos[indice]['Impuesto'],this.productos[indice]['TotalUnitario']);
                    this.productos.splice(aborrar[0],1);
                }
            },

            btn_ac: function() {                
                var url = 'cajas/aperturar_caja';
                axios.post(url, {
                    'idcaja' : this.idCaja,
                    'tipodocumento' : this.idTIpoDocumento
                }).then(response => {
                    if (response.data.data == true) {
                        toastr.success('Apertura de caja correcta.', 'WebSigesa');
                        $('#cabecera_factura').hide();
                        $('#cuerpo_factura').fadeIn(400);
                        $('#ruc_bus').focus();
                        this.cadena_tipo_documento = $('#tipo_documento_id option:selected').text().trim();
                        this.nroserie_grabar = response.data.data_documento[0].NroSerie;
                        this.nrodocumento_grabar = response.data.data_documento[0].NroDocumento.trim();
                        this.IdGestionCaja = response.data.data_documento[0].IdGestionCaja;
                        // console.log(this.nrodocumento_grabar);
                        // console.log(this.nrodocumento_grabar);
                        // console.log(this.nroserie_grabar);
                        // console.log(this.nrodocumento_grabar);
                    } else {
                        toastr.error('Hubo un error en la apertura de caja. Intentelo nuevamente.', 'WebSigesa');
                    } 
                    // console.log(response.data);
                }).catch(error => {
                    this.errores = error.response.data.errors;
                });
            },
            nuevo_correlativo: function()
            {
                var url = 'cajas/nuevo_correlativo/' + this.idCaja + '/' + this.idTIpoDocumento;
                axios.get(url).then(response => {
                    this.nroserie_grabar = response.data.data[0].NroSerie;
                    this.nrodocumento_grabar = response.data.data[0].NroDocumento.trim();
                    $('#ruc_bus').focus();
                }).catch({

                });
            },
            buscar_proveedor: function() {
                var url = 'sistema/proveedor/' + this.ruc;
                axios.get(url).then(response => {
                    toastr.clear();
                    if (response.data == false) {
                        toastr.warning('No existen datos asociados a ese número de RUC.', 'WebSigesa');
                        this.ruc = '';
                        $('#ruc_bus').focus();
                    }
                    else {
                        this.ruc = '';
                        var datos = response.data;
                        this.razonsocial = datos.RAZONSOCIAL;
                        this.rucv = datos.RUC;
                        this.direccion = datos.DIRECCION;
                        $('#id_cuenta').focus();
                        
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            buscar_paciente: function() {
                var url = 'cajas/tipo_seguro_paciente/' + this.dni;
                axios.get(url).then(response => {
                    toastr.clear();
                    if (response.data == false) {
                        toastr.warning('No existen datos asociados a ese número de DNI.', 'WebSigesa');
                        this.dni = '';
                        $('#dni_bus').focus();

                    }
                    else {
                        this.dni = '';
                        var datos = response.data.data[0];
                        this.seguro = datos.Financiamiento;
                        this.idTipoFinanciamiento = datos.idFuenteFinanciamiento;
                        this.paciente = datos.ApellidoPaterno + ' ' + datos.ApellidoMaterno + ' ' + datos.PrimerNombre;
                        this.idpaciente = datos.IdPaciente;
                        $('#btn_buscar_productos').focus();
                    }
                    // console.log(response.data);
                }).catch(error => {
                    // console.log(error.response.data);
                    toastr.warning('No existen datos asociados a ese número de DNI.', 'WebSigesa');
                    this.dni = '';
                    $('#dni_bus').focus();
                });
            },
            ver_modal: function() {
                this.productos_temp = [];
                this.txt_busqueda = '';
                $('#modal_ingresos_productos').modal('show');
                $('#modal_ingresos_productos').on('shown.bs.modal', function() {
                    $('#paramatro_busqueda').focus();
                });
            },
            buscar_boleta: function() {
                var url = 'cajas/detalle_boleta/' + this.serie + '/' + this.ndocumento + '/' + '';

                axios.get(url).then(response => {
                    var datos = response.data.data;
                    

                    if (datos == 'sindatos') {
                        toastr.error('No se encontraron datos asociados a este numero de documento', 'WebSigesa');
                        this.idorden = '';
                        this.serie = '';
                        this.ndocumento = '';
                        $('#serie_boleta').focus();
                    }
                    else{
                        this.idorden = '';
                        this.serie = '';
                        this.ndocumento = '';
                        $('#serie_boleta').focus();
                        this.paciente = datos.paciente;   
                        this.idpaciente = datos.idpaciente;                     
                        this.comprobante = datos.comprobante;

                        
                        
                        
                        for (var i = 0; i < datos.productos.length; i++) {
                            this.productos.push(datos.productos[i]);
                        }

                        this.sumarmontos(datos.subtotal,datos.igv,datos.total);
                    }
                    
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            buscar_boleta_cuenta: function() {
                var url = 'cajas/detalle_cuenta/' + this.cuenta;

                axios.get(url).then(response => {
                    var datos = response.data.data;
                    

                    if (datos == 'sindatos') {
                        toastr.error('No se encontraron datos asociados a este numero de cuenta', 'WebSigesa');
                        this.cuenta = '';
                        $('#id_cuenta').focus();
                    }
                    else if (datos == 'yafacturada') {
                        toastr.error('Esta cuenta ya se esta facturada.', 'WebSigesa');
                        this.cuenta = '';
                        $('#id_cuenta').focus();
                    }
                    else{
                        this.paciente = datos.paciente;
                        this.idpaciente = datos.idpaciente;
                        this.idTipoFinanciamiento = datos.idseguro;
                        this.seguro = datos.seguro;
                        this.cuenta_grabar = this.cuenta;
                        this.cuenta = '';
                        $('#id_cuenta').focus();

                        for (var i = 0; i < datos.productos.length; i++) {
                            this.productos.push({
                                Comprobante: datos.productos[i].Comprobante,
                                Codigo: datos.productos[i].Codigo,
                                Producto: datos.productos[i].Producto,
                                Cantidad: datos.productos[i].Cantidad,
                                Impuesto: datos.productos[i].Impuesto,
                                IdPartida: datos.productos[i].IdPartida,
                                Precio: datos.productos[i].Precio,
                                Subtotal: datos.productos[i].SubTotal,
                                TotalUnitario: datos.productos[i].TotalUnitario
                            });
                            this.sumarmontos(datos.productos[i].SubTotal,datos.productos[i].Impuesto,datos.productos[i].TotalUnitario);
                        }
                    }
                    
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            /*buscar_boleta_id_orden: function() {
                var url = 'cajas/detalle_boleta/' + null + '/' + null + '/' + this.idorden;
                // console.log(url);

                axios.get(url).then(response => {
                    var datos = response.data.data;
                    // console.log(datos)

                    if (datos == 'sindatos') {
                        toastr.error('No se encontraron datos asociados a este numero de orden', 'WebSigesa');
                        this.idorden = '';
                        this.serie = '';
                        this.ndocumento = '';
                        $('#id_orden').focus();
                    }
                    else{
                        this.idorden = '';
                        this.serie = '';
                        this.ndocumento = '';
                        $('#id_orden').focus();
                        this.paciente = datos.paciente;                        
                        // this.comprobante = datos.comprobante;

                        
                        // console.log(datos.productos[0].Codigo);
                        
                        for (var i = 0; i < datos.productos.length; i++) {
                            this.productos.push({
                                Comprobante: datos.productos[i].Codigo,
                                Codigo: datos.productos[i].Codigo,
                                Producto: datos.productos[i].Producto,
                                Cantidad: datos.productos[i].Cantidad,
                                Precio: datos.productos[i].Precio,
                                TotalUnitario: datos.productos[i].TotalUnitario
                            });
                            
                        }
                        this.sumarmontos(datos.subtotal,datos.igv,datos.total);
                        
                    }
                    
                }).catch(error => {
                    console.log(error.response.data);
                });
            },*/
            sumarmontos: function(subtotal,igv,total) {
                this.sumsubtotal = parseFloat(subtotal) + parseFloat(this.sumsubtotal);
                this.sumigv = parseFloat(igv) + parseFloat(this.sumigv);
                this.sumtotal = parseFloat(total) + parseFloat(this.sumtotal);

                // this.sumsubtotal = Math.round(this.sumsubtotal * 100) / 100;
                // this.sumigv = Math.round(this.sumigv * 100) / 100;
                // this.sumtotal = Math.round(this.sumtotal * 100) / 100;
            },
            restarmontos: function(subtotal,igv,total) {
                this.sumsubtotal = parseFloat(this.sumsubtotal) - parseFloat(subtotal) ;
                this.sumigv = parseFloat(this.sumigv) - parseFloat(igv) ;
                this.sumtotal = parseFloat(this.sumtotal) - parseFloat(total) ;

                // this.sumsubtotal = Math.round(this.sumsubtotal * 100) / 100;
                // this.sumigv = Math.round(this.sumigv * 100) / 100;
                // this.sumtotal = Math.round(this.sumtotal * 100) / 100;
            },
            buscar_item: function() {
                if (this.txt_busqueda.length > 2) {
                   // hacer busqueda
                    if (this.idTipoFinanciamiento == '') {
                        this.idTipoFinanciamiento = 1;
                        console.log(this.idTipoFinanciamiento);
                    }
                    var url = 'cajas/servicios_medicamentos/' + this.idTipoFinanciamiento + '/' + this.txt_busqueda; 
                    console.log(this.idTipoFinanciamiento);
                    axios.get(url).then(response => {
                        if (response.data.data == 'sindatos') {

                        }
                        else{
                            this.productos_temp = [];
                            var datos = response.data.data;
                            for (var i = 0; i < datos.length; i++) {
                                this.productos_temp.push(datos[i]);
                            }
                            //this.txt_busqueda = '';

                        }
                    }).catch(response => {

                    });
                }
                else{
                    console.log('aun no');
                }
            },
            agregarItenm: function(codigo,nombre,precio,idpartida,impuesto,subtotal)
            {   
                var cantidad = $('#codigo_'+codigo).val();
                if (cantidad <= 0) {
                    toastr.warning('Debe ingresar una cantidad válida.','WebSigesa');
                }

                else{
                    var totalunitario = parseFloat(cantidad) * parseFloat(subtotal);
                    // totalunitario = Math.round(totalunitario * 100) / 100;

                    // var subtotalunitario = parseFloat(cantidad) * parseFloat(preciounitario);
                    // subtotalunitario = Math.round(subtotalunitario * 100) / 100;

                    var impuestounitario = parseFloat(cantidad) * parseFloat(impuesto);
                    // impuestounitario = Math.round(impuestounitario * 100) / 100;

                    this.productos.push({
                        Comprobante: codigo,
                        Codigo: codigo,
                        Producto: nombre,
                        Cantidad: cantidad,
                        Subtotal: subtotal,
                        Impuesto: impuestounitario,
                        IdPartida: idpartida,
                        Precio: precio,
                        TotalUnitario: totalunitario
                    });
                    this.txt_busqueda = '';
                    $('#paramatro_busqueda').focus();

                    this.sumarmontos(subtotal,impuestounitario,totalunitario);
                }
            },
            facturar_clasificador: function()
            {
                this.imprimir_clasificador = 1;
                this.registrarfactura();
            },
            registrarfactura: function()
            {
                if (this.rucv.length <= 0) {
                    toastr.error('Debe ingresar un RUC');
                    return false;
                }

                var url = 'cajas/registro_factura';
                axios.post(url, {
                    'NroSerie': this.nroserie_grabar,
                    'NroDocumento': this.nrodocumento_grabar,
                    'Ruc': this.rucv,
                    'RazonSocial': this.razonsocial,
                    'IdTipoComprobante': this.idTIpoDocumento,
                    'Subtotal': this.sumsubtotal,
                    'IGV': this.sumigv,
                    'Total': this.sumtotal,
                    'IdPaciente': this.idpaciente,
                    'Observacion1': this.observacion_uno,
                    'Observacion2': this.observacion_dos,
                    'Concepto': this.conceptos,
                    'IdCuentaAtencion': this.cuenta_grabar,
                    'productos': this.productos,
                    'idcaja': this.idCaja
                }).then(response => {
                    // console.log(response.data); return false;
                    if (response.data.data == 'correcto') {
                        toastr.success('Factura generada con éxito.', 'WebSigesa');
                        this.nuevo_correlativo();
                        this.imprimir(response.data.idcabecera);

                        //suma total cobrado
                        this.totalcobrado = parseFloat(this.totalcobrado) + parseFloat(this.sumtotal);
                    }
                    this.ruc = '';
                    this.rucv = '';
                    this.razonsocial = '';
                    this.direccion ='';
                    this.dni = '';
                    this.paciente = '';
                    this.idpaciente = '';
                    this.seguro = '';
                    this.serie = null;
                    this.sumsubtotal = 0;
                    this.sumigv = 0;
                    this.sumtotal = 0;
                    this.comprobante = '';
                    this.productos = [];
                    this.productos_temp = [];
                    this.txt_busqueda = '';
                    this.idTipoFinanciamiento = '';
                    this.ndocumento = null;
                    this.idorden = null;
                    this.cuenta = '';
                    this.cuenta_grabar = '';
                    this.observacion_uno = '';
                    this.observacion_dos = '';
                    this.conceptos = '';
                    this.imprimir_clasificador = 0;
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            imprimir: function(idorder)
            {
                if (this.imprimir_clasificador == 0) {
                    var url = 'cajas/generar_pdf/' + idorder;
                }

                else if(this.imprimir_clasificador == 1) {
                    var url = 'cajas/generar_pdf_partida/' + idorder;
                }

                
                // console.log(url);
                window.open(url,'_blank');
                /*axios({
                    url: urls,
                    method: 'GET',
                    responseType: 'blob', // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    window.open(url,'WebSigesa', 'width=500,height=700,toolbar=0')
                    const link = document.createElement('a');
                    // const iframe = document.createElement('frame')
                    link.href = url;
                    // iframe.src = url;
                    link.setAttribute('target','_blank');
                    // link.setAttribute('onclick', "w=window.open('" + url + "'); w.print(); w.close();"); //or any other extension
                    link.appendChild(iframe);
                    document.body.appendChild(link);
                    // w.print();
                    link.click();

                });*/
            },
            cerrar_caja: function()
            {
                var url = 'cajas/cerrar_caja/' + this.IdGestionCaja + '/C/fecha/' + this.totalcobrado;

                axios.get(url).then(response => {
                    $('#cuerpo_factura').hide();
                    $('#cabecera_factura').fadeIn(400);
                    this.cadena_tipo_documento = '';
                    this.nroserie_grabar = '';
                    this.nrodocumento_grabar = '';
                    this.sumsubtotal = 0;
                    this.sumigv = 0;
                    this.sumtotal = 0;
                    toastr.clear();
                    toastr.success('Caja cerrada con exito.','WebSigesa');
                }).catch(error => {
                    toastr.clear();
                    toastr.error('No se puede cerrar la caja,intentelo nuevamente.','WebSigesa');
                });
            }
        }
       
    }
</script>