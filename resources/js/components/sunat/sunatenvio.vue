<template>
	 <div>
	 	<section class="content-header">
			<h1>
				Sunat
				<small>Envio de Información</small>
			</h1>
			<ol class="breadcrumb">
                <li><a href="restaurar"><i class="fa fa-dashboard"></i> Sunat</a></li>
                <li class="active">Envio de Informacion</li>
            </ol>
		</section>
		<!-- Cuerpo del contenido -->
		<section class="content container-fluid">
			<div id="lista_envio">
				<div class="box box-primary color-palette-box">
					<div class="box-body">
                        <table style="width: 100%">
                            <tr>
                            	<td width="15%" style="padding-right: 5px;">
                                    <div class="input-group">
                                        <label for="">Fecha de Envio</label>
                                        <div class="input-group date">
	                                        <div class="input-group-addon">
	                                            <i class="fa fa-calendar"></i>
	                                        </div>
                                        	<input type="text" class="form-control pull-right" id="fecha" :value="fecha">
                                    </div>
                                    </div>
                                </td>
                                <td width="15%" class="text-center">
                                	<button type="button" class="btn btn-info" v-on:click.prevent="postData"><i class="fa fa-upload"></i>&nbsp;&nbsp;	&nbsp;Envio de Informacion</button>               
                                </td>
                            </tr>
                            <tr>
                            	<td>
                            		<label v-if="errores.inicio" class="text-danger">{{ errores.inicio[0] }}</label>
                            	</td>
                            </tr>

                        </table>
                    </div>
				</div>
			</div>
		</section>

	 </div>
</template>

<script>
	import datepicker from 'bootstrap-datepicker'
	import toastr from 'toastr'
	
	export default{
		data(){
			return {
				fecha: '',
				errores: ''
			}
		},

		created: function(){

		},

		methods: {
			postData: function(){
				
				if (this.fecha == '') { toastr.error('Debe seleccionar una fecha','WebSigesa');return false; }

				var alerta_espera = toastr.info('Enviando Informacion','WebSigesa', { 
                    timeOut: 0,
                    extendedTimeOut: 0
                });

				var url = '/senvio/envio_informacion/'+this.fecha;

				axios.get(url).then(response=> {
					console.log(response.data);
				}).catch(error => {
                    toastr.clear();
                    this.errores = error.response.data.errors;
                }); 
			}
		},

		mounted(){
			$('#fecha').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es'             
            }).on(
            "changeDate", () => {this.fecha = $('#fecha').val()}
            );
		}
	}
</script>