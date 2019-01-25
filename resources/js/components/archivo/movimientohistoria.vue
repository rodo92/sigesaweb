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
                Movimiento de Historia
                <small>Archivo</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Archivo</a></li>
                <li class="active">Movimiento de Historia</li>
            </ol>
        </section>

        <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
        	<div class="box box-primary color-palette-box">
                <div class="box-body">
                	<div class="row">
                		<div class="col-xs-6">
                			<table>
		                		<tr>
		                			<td>
		                				<div class="form-group">
		                					<label for="cmb_modo">Modo:</label>
											<select name="" id="cmb_modo" class="form-control" @change="seleccionar_modo()" v-model="tipo_modo">
												<option value="1">Archivero</option>
												<option value="2">Conserje</option>
												<option value="3">Enrutador</option>
											</select>
		                				</div>
		                			</td>
		                		</tr>
		                	</table>
                		</div>
                		<div class="col-xs-6">
                			<h5>Archivero: {{ nombre_archivero_mostrar }}</h5>
                			<p class="text-muted">Dígito Inicial: {{ digito_inicial_mostrar }}
                				<br>Dígito Final: {{ digito_fin_mostrar }}</p>
                		</div>
                	</div>
                	<div class="row" style="display: none;" id="tabla_para_archiveros">
                		<div class="col-xs-12" style="overflow:scroll;height: 500px;">
                			<table class="tabla_servicios">
                				<caption>Lista de Historias Clínicas por tu Dígito Terminal</caption>
		                		<thead>
		                			<tr>
			                			<th width="4%" style="text-align: center;">#</th>
			                			<th width="8%" style="text-align: center;">H. C.</th>
			                			<th width="30%">PACIENTE</th>
			                			<th width="5%" style="text-align: center;">CUPO</th>
			                			<th width="5%" style="text-align: center;">INICIO</th>
			                			<th width="5%" style="text-align: center;">FIN</th>
			                			<th width="20%">CONSULTORIO</th>
			                			<th width="10%" style="text-align: center;">ESTADO</th>
			                			<th width="10%" style="text-align: center;"><i class="fa fa-cogs"></i></th>
			                		</tr>
		                		</thead>
		                		<tbody>
		                			<tr v-for="(historia_archiveros,index) in historias_archiveros" v-bind:index="index">
		                				<td v-text="index + 1" style="text-align: center;"></td>
		                				<td align="center" v-if="historia_archiveros.historia" v-text="historia_archiveros.historia"></td>
		                				<td align="center" v-else></td>
		                				<td v-text="historia_archiveros.Paciente"></td>
		                				<td v-text="historia_archiveros.cupo" align="center"></td>
		                				<td v-text="historia_archiveros.horainicio" align="center"></td>
		                				<td v-text="historia_archiveros.horafin" align="center"></td>
		                				<td v-text="historia_archiveros.consultorio"></td>
		                				<td align="center"  v-if="historia_archiveros.SalidaArchivo == 1"><span class="label label-success">Encontrador</span></td>
		                				<td align="center"  v-else><span class="label label-danger">No Encontrado</span></td>
		                				<td align="center">
		                					<button class="btn btn-xs btn-default"><i class="fa fa-check"></i></button>
		                					<button class="btn btn-xs btn-default"><i class="fa fa-times"></i></button>
		                				</td>
		                			</tr>
		                		</tbody>
		                	</table>
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
                tipo_modo: '',
                historias_archiveros: [],
                nombre_archivero_mostrar: '',
                digito_inicial_mostrar: '',
                digito_fin_mostrar: '',
            }
        },
        created: function() {
        },
        methods: {
        	seleccionar_modo: function()
        	{
        		switch(this.tipo_modo) {
  					case '1':
    					$('#tabla_para_archiveros').fadeIn(200);
    					this.traer_lista_historias_archivero();
    					break;
  					case '2':
    					$('#tabla_para_archiveros').hide();
    					break;
  					case '3':
    					$('#tabla_para_archiveros').hide();
    					break;
    				default:
    					break;
				}
        	},
        	traer_lista_historias_archivero: function()
        	{
        		var url = 'MovimientoHistoria/listado_historias_archivero';
        		axios.get(url).then(response => {
        			this.historias_archiveros = response.data.data;
        			this.nombre_archivero_mostrar = response.data.data[0]['Archivero'];
        			this.digito_inicial_mostrar = response.data.data[0]['DI'];
					this.digito_fin_mostrar = response.data.data[0]['DF'];
        			console.log(this.historias_archiveros);
        		}).catch(error => {

        		});
        	}
        }
    }
</script>