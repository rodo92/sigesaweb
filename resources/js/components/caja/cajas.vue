
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
            <div class="box box-primary color-palette-box" id="cabecera_factura">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table style="width: 70%; text-align: left;">
                                <tr>
                                    <td width="30%" style="padding-right: 1%;">
                                        <div class="form-group">
                                            <label>Seleccione un caja</label>
                                            <select class="form-control">
                                                <option value=""></option>
                                                <option v-for="caja in cajas" :value="caja.IdCaja">
                                                    {{ caja.Descripcion }}
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="30%" style="padding-right: 1%;">
                                        <div class="form-group">
                                            <label>Tipo de Documento</label>
                                            <select class="form-control">
                                                <option value=""></option>
                                                <option v-for="tipdoc in tipo_documento" :value="tipdoc.IdTipoComprobante">
                                                    {{ tipdoc.Descripcion }}
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="20%" >
                                        <div class="radio">
                                            <button class="btn btn-success" v-on:click.prevent="btn_af">APERTURA<br>DE CAJA</button>
                                        </div>  
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-primary" id="cuerpo_factura" style="display: none;">
                <div class="box-body">
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
                                    <td></td>
                                    <td>
                                        <label>Direcci&oacute;n :</label>
                                        
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="">R.U.C. :</label>
                                        {{ rucv }}
                                    </td>
                                    <td></td>
                                    <td>
                                        <label>Fecha de Emisi&oacute;n: </label>
                                        {{ new Date().getDate() + "/" + (new Date().getMonth() +1) + "/" + new Date().getFullYear() }}
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button type="" class="btn btn-info"><i class="fa fa-plus"></i> AGREGAR PRODUCTOS</button>
                            <button type="" class="btn btn-success"><i class="fa fa-print"></i> GENERAR</button>
                            <button type="" class="btn btn-warning"><i class="fa fa-close"></i> CERRAR CAJA</button>
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
                                        <td>25280</td>
                                        <td>Alargamiento o acortamiento de tendones</td>
                                        <td>1</td>
                                        <td>250</td>
                                        <td>250</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-default"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                            <button class="btn btn-sm btn-default"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
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
    </div>
</template>

<script>
    import axios from 'axios'


    export default {
        data() {
            return {
                cajas: [],
                tipo_documento: [],
                ruc: '',
                rucv: '',
                razonsocial: '',
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

            btn_af: function() {
                $('#cabecera_factura').addClass('collapsed-box');
                $('#cuerpo_factura').show();
            },
            buscar_proveedor: function() {
                var url = 'sistema/proveedor/' + this.ruc;
                axios.get(url).then(response => {
                    var datos = response.data;
                    this.razonsocial = datos.RAZONSOCIAL;
                    this.rucv = datos.RUC;
                    $('#ruc_bus').val('');
                }).catch(error => {
                    console.log(error.response.data);
                });
            }
        }
    }
</script>