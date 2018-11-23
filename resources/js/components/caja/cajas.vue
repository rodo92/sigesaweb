<style type="text/css" media="screen">
    table#tabla_datos{
        border-spacing: 5px;
        border-collapse: separate;
    }
</style>
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

            <div class="box box-default" id="cuerpo_factura" >
                <div class="box-body" style="padding: 3%;">
                    <div class="row">
                        <div class="col-xs-8">
                            <table style="width: 100%" id="tabla_datos">
                                <tr>
                                    <td width="15%">
                                        <input type="text" class="form-control" placeholder="RUC" v-on:keyup.13="buscar_proveedor" v-model="ruc" id="ruc_bus" maxlength="11">
                                    </td>
                                    <td width="15%">
                                        <label for="">RAZON&Oacute;N SOCIAL:</label>                                        
                                    </td>                    
                                    <td width="40%" class="bg-warning">
                                        {{ razonsocial }}
                                    </td>
                                    <td width="10%">
                                        <label> R.U.C.:</label>                                        
                                    </td>
                                   <td width="20%">
                                       {{ direccion }}
                                   </td>
                                </tr>
                                <tr>
                                    <td width="15%">
                                        
                                    </td>
                                    <td width="15%">
                                        <label for="">DIRECCI&Oacute;N :</label>                                        
                                    </td>
                                    <td width="%">
                                        {{ rucv }}
                                    </td>
                                    <td width="10%">
                                        <label>FECHA: </label>
                                        
                                    </td>
                                    <td width="20%">
                                        {{ new Date().getDate() + "/" + (new Date().getMonth() +1) + "/" + new Date().getFullYear() }}
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
                                    <td width="10%">
                                        <label> DOCUMENTO:</label>                                        
                                    </td>
                                    <td width="20%" class="bg-warning">
                                       {{ comprobante }}
                                   </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-4 text-right">
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
                                        <th class="text-center">DOCUMENTO</th>
                                        <th class="text-center">CODIGO</th>
                                        <th>DESCRIPCION</th>
                                        <th class="text-center">CANTIDAD</th>
                                        <th class="text-center">PRECIO</th>
                                        <th class="text-center">TOTAL</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in productos">
                                        <td class="text-center">{{ producto.Comprobante }}</td>
                                        <td class="text-center">{{ producto.Codigo }}</td>
                                        <td>{{ producto.Producto }}</td>
                                        <td class="text-center">{{ producto.Cantidad }}</td>
                                        <td class="text-right">{{ producto.Precio }}</td>
                                        <td class="text-right">{{ producto.TotalUnitario }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
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
                                        <td class="warning text-right">I.G.V.</td>
                                        <td class="text-right">{{ sumigv }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
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
                dni: '',
                paciente: '',
                seguro: '',
                errores: '',
                serie: '',
                sumsubtotal: 0,
                sumigv: 0,
                sumtotal: 0,
                comprobante: '',
                productos: [],
                ndocumento: '',
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
                        $('#dni_bus').focus();
                        
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
                        this.paciente = datos.ApellidoPaterno + ' ' + datos.ApellidoMaterno + ' ' + datos.PrimerNombre;
                        $('#btn_buscar_productos').focus();
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });
            },
            ver_modal: function() {
                $('#modal_ingresos_productos').modal('show');
            },
            buscar_boleta: function() {
                var url = 'cajas/detalle_boleta/' + this.serie + '/' + this.ndocumento;

                axios.get(url).then(response => {
                    var datos = response.data.data;
                    

                    if (datos == 'sindatos') {
                        toastr.error('No se encontraron datos asociados a este numero de documento', 'WebSigesa');
                    }
                    else{
                        this.serie = '';
                        this.ndocumento = '';
                        $('#serie_boleta').focus();
                        this.paciente = datos.paciente;                        
                        this.comprobante = datos.comprobante;

                        
                        this.sumtotal = parseFloat(datos.total) + parseFloat(this.sumtotal);
                        
                        for (var i = 0; i < datos.productos.length; i++) {
                            this.productos.push(datos.productos[i]);
                        }
                    }
                    
                }).catch(error => {
                    console.log(error.response.data);
                });
            }
        },
        mounted() {
            $('#serie_boleta').keyup(function() {
                var cantidad = $(this).val().length;
                if (cantidad == 4) {
                    $('#ndocumento_boleta').focus();
                }
            });
        }
    }
</script>