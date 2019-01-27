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
        	<div class="box box-primary container-fluid" id="box_menu">
        		<div class="row" style="height: 500px;">
        			<div class="col-xs-4 col-xs-offset-4" style="padding: 5%;">
        				<button class="btn btn-default btn-lg btn-block" v-on:click.prevent="seleccionar_modo('1')">ARCHIVERO</button><br><br>
        				<button class="btn btn-default btn-lg btn-block" v-on:click.prevent="seleccionar_modo('2')">CONSERJE</button><br><br>
        				<button class="btn btn-default btn-lg btn-block" v-on:click.prevent="seleccionar_modo('3')">ENRUTADO</button>
        			</div>
        		</div>
        	</div>
        	<div class="box box-primary color-palette-box" style="display: none;" id="box_archivero">
                <div class="box-body">
                	<div class="row">
                		<div class="col-xs-4 bg-info" >
                			<table>
                				<tr>
                					<th>ARCHIVERO</th>
                					<td>&nbsp;&nbsp;&nbsp;</td>
                					<td v-text="nombre_archivero_mostrar"></td>
                				</tr>
                				<tr>
                					<th>D&Iacute;GITO INICIAL</th>
                					<td>&nbsp;&nbsp;&nbsp;</td>
                					<td v-text="digito_inicial_mostrar"></td>
                				</tr>
                				<tr>
                					<th>D&Iacute;GITO FINAL</th>
                					<td>&nbsp;&nbsp;&nbsp;</td>
                					<td v-text="digito_fin_mostrar"></td>
                				</tr>
                			</table>
                		</div>
                		<div class="col-xs-3" >
                			
                		</div>
                		<div class="col-xs-4 text-right">
                			<button class="btn btn-default" v-on:click.prevent="imprimir_listado"><i class="fa fa-print"></i>&nbsp;IMPRIMIR LISTADO</button>
                			<button class="btn btn-default" v-on:click.prevent="seleccionar_modo('4')"><i class="fa fa-bars"></i>&nbsp;MEN&Uacute; PRINCIPAL</button>
                		</div>
                	</div>
                	<div class="row" id="tabla_para_archiveros">
                		<div class="col-xs-12" style="overflow:scroll;height: 500px;">
                			<table class="tabla_servicios">
                				<caption>Lista de Historias Clínicas por tu Dígito Terminal</caption>
		                		<thead>
		                			<tr class="">
			                			<th width="4%" style="text-align: center;">#</th>
			                			<th width="8%" style="text-align: center;">H. C.</th>
			                			<th width="25%">PACIENTE</th>
			                			<th width="5%" style="text-align: center;">SERIE</th>
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
		                				<td class="bg-warning" align="center" v-if="historia_archiveros.historia" v-text="historia_archiveros.historia"></td>
		                				<td align="center" v-else></td>
		                				<td v-text="historia_archiveros.paciente"></td>
		                				<td class="bg-warning" align="center" v-if="historia_archiveros.seriehc" v-text="historia_archiveros.seriehc"></td>
		                				<td align="center" v-else></td>
		                				<td v-text="historia_archiveros.cupo" align="center"></td>
		                				<td v-text="historia_archiveros.horainicio" align="center"></td>
		                				<td v-text="historia_archiveros.horafin" align="center"></td>
		                				<td v-text="historia_archiveros.consultorio"></td>
		                				<td class="bg-success" align="center" v-if="historia_archiveros.salidaarchivo == '1'">Encontrado</td>
		                				<td class="bg-danger" align="center" v-else-if="historia_archiveros.salidaarchivo == '0'">No Encontrado</td>
		                				<td align="center" v-else>&nbsp;</td>
		                				<td align="center">
		                					<button class="btn btn-xs btn-default" v-on:click.prevent="encontrado_archivero(historia_archiveros.idhistoriasolicitada)"><i class="fa fa-check"></i></button>
		                					<button class="btn btn-xs btn-default" v-on:click.prevent="no_encontrado_archivero(historia_archiveros.idhistoriasolicitada)"><i class="fa fa-times"></i></button>
		                				</td>
		                			</tr>
		                		</tbody>
		                	</table>
                		</div>
                	</div>
                </div>
            </div>

            <div class="box box-primary color-palette-box" style="display: none;" id="box_enrutador">
            	<div class="box-body">
            		<div class="row">
	            		<div class="col-xs-4"></div>
	            		<div class="col-xs-4"></div>
	            		<div class="col-xs-4 text-right">
	            			<button class="btn btn-default" v-on:click.prevent="imprimir_listado_historias_enrutadas"><i class="fa fa-file-excel-o"></i>&nbsp;EXPORTAR LISTADO</button>
	            			<button class="btn btn-default" v-on:click.prevent="seleccionar_modo('4')"><i class="fa fa-bars"></i>&nbsp;MEN&Uacute; PRINCIPAL</button>
	            		</div>
	            	</div>
	            	<hr>
	            	<div class="row">
	            		<div class="col-xs-12" style="overflow:scroll;height: 500px;">
	            			<table class="tabla_servicios">
	            				<thead>
	            					<tr>
	            						<th width="4%" style="text-align: center;">#</th>
	            						<th width="8%" style="text-align: center;">HISTORIA</th>
	            						<th width="23%" style="text-align: left;">PACIENTE</th>
	            						<th width="6%" style="text-align: center;">RUTA</th>
	            						<th width="18%" style="text-align: center;">ESPECIALIDAD</th>
	            						<th width="16%" style="text-align: center;">CONSULTORIO</th>
	            						<th width="23%" style="text-align: left;">CONSERJE</th>
	            					</tr>
	            				</thead>
	            				<tbody>
	            					<tr v-for="(historia_enrutadas,index) in historias_enrutadas" v-bind:index="index">
	            						<td align="center" v-text="index + 1"></td>
	            						<td align="center" v-text="historia_enrutadas.NroHistoriaClinica"></td>
	            						<td v-text="historia_enrutadas.Paciente"></td>
	            						<td align="center" v-text="historia_enrutadas.Ruta"></td>
	            						<td align="center" v-text="historia_enrutadas.Especialidad"></td>
	            						<td align="center" v-text="historia_enrutadas.Consultorio"></td>
	            						<td v-text="historia_enrutadas.Conserje"></td>
	            					</tr>
	            				</tbody>
	            			</table>
	            		</div>
	            	</div>
            	</div>
            </div>

            <div class="box box-primary color-palette-box" style="display: none;" id="box_conserje">
            	<div class="box-body">
            		<div class="row">
	            		<div class="col-xs-6">
	            			<h4>CONSERJE: {{ conserje_mostrar }}</h4>
	            		</div>
	            		<div class="col-xs-2"></div>
	            		<div class="col-xs-4 text-right">
	            			<button class="btn btn-default" v-on:click.prevent=""><i class="fa fa-file-excel-o"></i>&nbsp;EXPORTAR LISTADO</button>
	            			<button class="btn btn-default" v-on:click.prevent="seleccionar_modo('4')"><i class="fa fa-bars"></i>&nbsp;MEN&Uacute; PRINCIPAL</button>
	            		</div>
	            	</div>
	            	<hr>
	            	<div class="row">
	            		<div class="col-xs-12" style="overflow:scroll;height: 500px;">
	            			<table class="tabla_servicios">
	            				<thead>
	            					<tr>
	            						<th width="4%" style="text-align: center;">#</th>
	            						<th width="8%" style="text-align: center;">HISTORIA</th>
	            						<th width="32%" style="text-align: left;">PACIENTE</th>
	            						<th width="6%" style="text-align: center;">RUTA</th>
	            						<th width="25%" style="text-align: center;">ESPECIALIDAD</th>
	            						<th width="25%" style="text-align: center;">CONSULTORIO</th>
	            					</tr>
	            				</thead>
	            				<tbody>
	            					<tr v-for="(historia_conserje,index) in historias_conserje" v-bind:index="index">
	            						<td align="center" v-text="index + 1"></td>
	            						<td align="center" v-text="historia_conserje.NroHistoriaClinica"></td>
	            						<td v-text="historia_conserje.Paciente"></td>
	            						<td align="center" v-text="historia_conserje.Ruta"></td>
	            						<td align="center" v-text="historia_conserje.Especialidad"></td>
	            						<td align="center" v-text="historia_conserje.Consultorio"></td>
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
                // tipo_modo: '',
                historias_archiveros: [],
                nombre_archivero_mostrar: '',
                digito_inicial_mostrar: '',
                digito_fin_mostrar: '',

                // enrutado
                historias_enrutadas: [],

                // conserje
                historias_conserje: [],
                conserje_mostrar: '',
            }
        },
        created: function() {
        },
        methods: {
        	seleccionar_modo: function(tipo_modo)
        	{
        		switch(tipo_modo) {
  					case '1':
  						$('#box_menu').hide();
  						$('#box_enrutador').hide();
  						$('#box_conserje').hide();
  						$('#box_archivero').fadeIn(200);
    					this.traer_lista_historias_archivero();
    					break;
  					case '2':
    					$('#box_archivero').hide();
    					$('#box_menu').hide();
    					$('#box_enrutador').hide();
    					$('#box_conserje').fadeIn(200);
    					this.traer_lista_historias_conserjes();
    					break;
  					case '3':
    					$('#box_archivero').hide();
    					$('#box_menu').hide();
    					$('#box_conserje').hide();
    					$('#box_enrutador').fadeIn(200);
    					this.traer_lista_historias_enrutadas();
    					break;
    				case '4':
  						$('#box_archivero').hide();
  						$('#box_enrutador').hide();
  						$('#box_conserje').hide();
  						$('#box_menu').fadeIn(200);
    					this.traer_lista_historias_archivero();
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
        			this.nombre_archivero_mostrar = response.data.data[0]['archivero'].toUpperCase();
        			this.digito_inicial_mostrar = response.data.data[0]['dinicial'];
					this.digito_fin_mostrar = response.data.data[0]['dfinal'];
        		}).catch(error => {

        		});
        	},
        	traer_lista_historias_enrutadas: function()
        	{
        		var url = 'MovimientoHistoria/historiasenrutadas';
        		axios.get(url).then(response => {
        			this.historias_enrutadas = response.data.data;
            		}).catch(error => {

        		});
        	},
        	traer_lista_historias_conserjes: function()
        	{
        		var url = 'MovimientoHistoria/listadoconserje';
        		axios.get(url).then(response => {
        			this.historias_conserje = response.data.data;
        			this.conserje_mostrar = response.data.data[0]['Conserje'].toUpperCase();
            		}).catch(error => {

        		});
        	},
        	no_encontrado_archivero: function(idhistoriasolicitada)
        	{
        		var url = 'MovimientoHistoria/noencontradohistoria/' + idhistoriasolicitada;
        		axios.get(url).then(response => {
        			toastr.clear();
        			// toastr.success('')
        			this.traer_lista_historias_archivero();
        		}).catch(error => {

        		});
        	},
        	encontrado_archivero: function(idhistoriasolicitada)
        	{
        		var url = 'MovimientoHistoria/encontradohistoria/' + idhistoriasolicitada;
        		axios.get(url).then(response => {
        			toastr.clear();
        			// toastr.success('')
        			this.traer_lista_historias_archivero();
        		}).catch(error => {

        		});
        	},
        	imprimir_listado: function()
        	{
        		var url = 'MovimientoHistoria/imprimirlistadoarchivero';
                window.open(url);
        	},
        	imprimir_listado_historias_enrutadas: function()
        	{
        		var url = 'MovimientoHistoria/historiasenrutadasexcel';
                window.open(url);
        	}
        }
    }
</script>