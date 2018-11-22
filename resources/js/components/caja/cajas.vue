
<template>    
    <div>
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
                                <select class="form-control" v-model="idTIpoDocumento">
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

            <div class="box box-default" id="cuerpo_factura" style="display: none;">
                <div class="box-body" style="padding: 3%;">
                    <div class="row">
                        <div class="col-xs-6">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" placeholder="RUC" v-on:keyup.13="buscar_proveedor" v-model="ruc" id="ruc_bus">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="">Raz&oacute;n Social:</label>
                                        {{ razonsocial }}
                                    </td>                    
                                    
                                    <td>
                                        <label>Direcci&oacute;n :</label>
                                        {{ direccion }}
                                    </td>
                                   
                                </tr>
                                <tr>
                                    <td>
                                        <label for="">R.U.C. :</label>
                                        {{ rucv }}
                                    </td>
                                    
                                    <td>
                                        <label>Fecha de Emisi&oacute;n: </label>
                                        {{ new Date().getDate() + "/" + (new Date().getMonth() +1) + "/" + new Date().getFullYear() }}
                                    </td>
                                    
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button type="" class="btn btn-info" v-on:click.prevent="ver_modal" id="btn_buscar_productos"><i class="fa fa-search"></i> AGREGAR<br>PRODUCTOS</button>
                            <button type="" class="btn btn-success"><i class="fa fa-save"></i> GENERAR<br>FACTURA</button>
                            <button type="" class="btn btn-warning"><i class="fa fa-close"></i> CERRAR<br>CAJA</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th>COD</th>
                                        <th>DESCRIPCION</th>
                                        <th>CANTIDAD</th>
                                        <th>PRECIO</th>
                                        <th>TOTAL</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="warning">SUBTOTAL <i class="fa fa-usd" aria-hidden="true"></i></td>
                                        <td>205</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="warning">I.G.V.</td>
                                        <td>45</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="warning">TOTAL <i class="fa fa-usd" aria-hidden="true"></i></td>
                                        <td>250</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="">Observaciones</label>
                            <textarea class="form-control" rows="3" placeholder="Observaciones ..."></textarea>
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
                                <input type="text" class="form-control" placeholder="NOMBRE O CODIGO">
                            </div>
                            <div class="col-xs-4">
                                <button class="btn btn-default"><i class="fa fa-search"></i> BUSCAR</button>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 2%;">
                            <div class="col-xs-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-warning">
                                            <th>CODIGO</th>
                                            <th>DESCRIPCION</th>
                                            <th class="text-center">CANTIDAD</th>
                                            <th class="text-center">PRECIO</th>
                                            <th class="text-center">AGREGAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                errores: '',
            }
        },
        created: function() {
            this.loadData();
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
                    } else {
                        toastr.error('Hubo un error en la apertura de caja. Intentelo nuevamente.', 'WebSigesa');
                    }                  
                }).catch(error => {
                    this.errores = error.response.data.errors;
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
                        $('#btn_buscar_productos').focus();
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            ver_modal: function() {
                $('#modal_ingresos_productos').modal('show');
            }
        }
    }
</script>