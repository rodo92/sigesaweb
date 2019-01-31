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
        		<div class="row" style="margin-top:10%;height: 200px;">
        			<div class="col-xs-6 col-xs-offset-3 text-center" style="">
        				<button class="btn btn-default btn-app btn-lg text-center" v-on:click.prevent="seleccionar_modo('1')" style="width: 30%;height: 30%; padding: 5% 8% 5% 8%;"><i class="fa fa-user-o" aria-hidden="true"></i>ARCHIVERO</button>
        				<button class="btn btn-default btn-app btn-lg text-center" v-on:click.prevent="seleccionar_modo('2')" style="width: 30%;height: 30%; padding: 5% 8% 5% 8%;"><i class="fa fa-user-o" aria-hidden="true"></i>CONSERJE</button>
        				<button class="btn btn-default btn-app btn-lg text-center" v-on:click.prevent="seleccionar_modo('3')" style="width: 30%;height: 30%; padding: 5% 8% 5% 8%;"><i class="fa fa-retweet" aria-hidden="true"></i>ENRUTADO</button>
        			</div>
        		</div>
        	</div>
        	<div class="box box-primary color-palette-box" style="display: none;" id="box_archivero">
                <div class="box-body" style="padding:2%;">
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
                		<div class="col-xs-3 text-center" >
                            <button class="btn btn-default" v-on:click.prevent="ver_archiveros_citados_dia" id="boton_citados_del_dia_conserje"><i class="fa fa-stethoscope"></i>&nbsp;CITADOS DEL DIA</button>         
                            <button class="btn btn-default" v-on:click.prevent="traer_lista_historias_archivero" id="boton_citados_del_dia_conserje"><i class="fa fa-refresh"></i></button>
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
            	<div class="box-body" style="padding:2%;">
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
            	<div class="box-body" style="padding:2%;">
            		<div class="row">
	            		<div class="col-xs-4">
	            			<h4>CONSERJE: {{ conserje_mostrar }}</h4>

	            			<table>
	            				<tr>
	            					<td>
	            						<div class="checkbox">
    										<button class="btn btn-default" v-on:click.prevent="dar_salida_todos_conserje"><i class="fa fa-check"></i>&nbsp;Todos Salieron</button>
  										</div>
	            					</td>
	            					<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	            					<td>
	            						<div class="checkbox">
    										<button class="btn btn-default" v-on:click.prevent="dar_recepcion_todos_conserje"><i class="fa fa-check"></i>&nbsp;Todos Regresaron</button>
  										</div>
	            					</td>
	            				</tr>
	            			</table>
	            		</div>
	            		<div class="col-xs-4 text-center">
                                     
                        </div>
	            		<div class="col-xs-4 text-right">
	            			<button class="btn btn-default" v-on:click.prevent="generar_lista_conserje"><i class="fa fa-file-excel-o"></i>&nbsp;EXPORTAR LISTADO</button>
	            			<button class="btn btn-default" v-on:click.prevent="seleccionar_modo('4')"><i class="fa fa-bars"></i>&nbsp;MEN&Uacute; PRINCIPAL</button>
	            		</div>
	            	</div>
	            	<hr>
	            	<div class="row">
	            		<div class="col-xs-12" style="overflow:scroll;height: 500px;">
	            			<table class="tabla_servicios">
	            				<thead>
	            					<tr>
	            						<th width="2%" style="text-align: center;">#</th>
	            						<th width="6%" style="text-align: center;">HISTORIA</th>
	            						<th width="20%" style="text-align: left;">PACIENTE</th>
	            						<th width="5%" style="text-align: center;">RUTA</th>
	            						<th width="20%" style="text-align: left;">ESPECIALIDAD</th>
	            						<th width="20%" style="text-align: left;">CONSULTORIO</th>
	            						<th width="5%" style="text-align: center;">ESTADO</th>
	            						<th width="5%" style="text-align: center;">SALIDA</th>
	            						<th width="7%" style="text-align: center;">ESTADO</th>
	            						<th width="7%" style="text-align: center;">RECEPCION</th>
                                        <th width="5%" style="text-align: left;">OBSERVACIONES</th>
	            					</tr>
	            				</thead>
	            				<tbody>
	            					<tr v-for="(historia_conserje,index) in historias_conserje" v-bind:index="index">
	            						<td align="center" v-text="index + 1"></td>
	            						<td align="center" v-text="historia_conserje.NroHistoriaClinica"></td>
	            						<td v-text="historia_conserje.Paciente"></td>
	            						<td align="center" v-text="historia_conserje.Ruta"></td>
	            						<td align="left" v-text="historia_conserje.Especialidad"></td>
	            						<td align="left" v-text="historia_conserje.Consultorio"></td>

	            						<td class="bg-success" align="center" v-if="historia_conserje.SalidaConserje == '1'">Salio</td>
	            						<td class="bg-danger" align="center" v-else-if="historia_conserje.SalidaConserje == '0'">No Salio</td>

	            						<td align="center" v-if="historia_conserje.SalidaConserje == '0'" v-on:click.prevent="dar_salida_conserje(historia_conserje.IdHistoriaSolicitada)"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></td>
	            						<td align="center" v-if="historia_conserje.SalidaConserje == '1'" v-on:click.prevent="no_dar_salida_conserje(historia_conserje.IdHistoriaSolicitada)"><button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>

	            						<td class="bg-success" align="center" v-if="historia_conserje.RecepcionConserje == '1'" >Regreso</td>
	            						<td class="bg-danger" align="center" v-else-if="historia_conserje.RecepcionConserje == '0'">No Regreso</td>

	            						<td align="center" v-if="historia_conserje.RecepcionConserje == '0'"><button class="btn btn-success btn-xs" v-on:click.prevent="dar_recepcion_conserje(historia_conserje.NroHistoriaClinica)"><i class="fa fa-check"></i></button></td>
	            						<td align="center" v-if="historia_conserje.RecepcionConserje == '1'"><button class="btn btn-danger btn-xs" v-on:click.prevent="no_dar_recepcion_conserje(index)"><i class="fa fa-times"></i></button></td>
                                        <td align="left" style="padding: 0px;">
                                            <input type="text" v-text="historia_conserje.ObservacionNoRecepcion" style="margin:0px; width: 100%; height: 100%; border: none;">
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
                    $('#boton_citados_del_dia_conserje').removeClass();
                    $('#boton_citados_del_dia_conserje').addClass('btn');
                    $('#boton_citados_del_dia_conserje').addClass('btn-default');
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
        			this.historias_conserje = '';
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
        			this.traer_lista_historias_archivero();
        		}).catch(error => {

        		});
        	},
        	encontrado_archivero: function(idhistoriasolicitada)
        	{
        		var url = 'MovimientoHistoria/encontradohistoria/' + idhistoriasolicitada;
        		axios.get(url).then(response => {
        			toastr.clear();
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
        	},
        	dar_salida_todos_conserje: function()
        	{
        		var idhistorias = [];
        		for (var i = 0; i < this.historias_conserje.length; i++) {
        			idhistorias.push(this.historias_conserje[i]['IdHistoriaSolicitada']);
        		}
        		var url = 'MovimientoHistoria/salidatodosconserje';
        		axios.post(url, {
        			'idhistorias': idhistorias
        		}).then(response => {
                    this.traer_lista_historias_conserjes();
        		}).catch(error => {

        		});
                
        	},
            dar_recepcion_todos_conserje: function()
            {
                var idhistorias = [];
                for (var i = 0; i < this.historias_conserje.length; i++) {
                    idhistorias.push(this.historias_conserje[i]['IdHistoriaSolicitada']);
                }
                var url = 'MovimientoHistoria/recepciontodosconserje';
                axios.post(url, {
                    'idhistorias': idhistorias
                }).then(response => {
                    this.traer_lista_historias_conserjes();
                }).catch(error => {

                });
                
            },
        	dar_salida_conserje: function(idhistoria)
        	{
        		var url = 'MovimientoHistoria/darsalidaconserje/' + idhistoria;

        		axios.get(url).then(response => {
        			this.traer_lista_historias_conserjes();
        		}).catch(error => {

        		});
        	},
            no_dar_salida_conserje: function(idhistoria)
            {
                var url = 'MovimientoHistoria/nosalidaconserje/' + idhistoria;

                axios.get(url).then(response => {
                    this.traer_lista_historias_conserjes();
                }).catch(error => {

                });
            },
            dar_recepcion_conserje: function(idhistoria)
            {
                var url = 'MovimientoHistoria/darrecepcionconserje/' + idhistoria;

                axios.get(url).then(response => {
                    this.traer_lista_historias_conserjes();
                }).catch(error => {

                });
            },

            no_dar_recepcion_conserje: function(idhistoria)
            {
                /*console.log(this.historias_conserje[index]['idhistoria']);
                console.log(this.historias_conserje[index]['ObservacionNoRecepcion']);*/
                // console.log(this.historias_conserje[index]['ObservacionNoRecepcion']);
                // return false;
                var url = 'MovimientoHistoria/norecepciontodosconserje/' + idhistoria;

                axios.get(url).then(response => {
                    this.traer_lista_historias_conserjes();
                }).catch(error => {

                });
            },
            generar_lista_conserje: function() {
                window.open('MovimientoHistoria/generarlistadosconserje','_blank');
            },
            ver_archiveros_citados_dia: function()
            {
                var url = 'MovimientoHistoria/listadoarchiverocitadosdia';
                axios.get(url).then(response => {
                    this.historias_archiveros = response.data.data;
                    this.nombre_archivero_mostrar = response.data.data[0]['archivero'].toUpperCase();
                    this.digito_inicial_mostrar = response.data.data[0]['dinicial'];
                    this.digito_fin_mostrar = response.data.data[0]['dfinal'];
                    $('#boton_citados_del_dia_conserje').removeClass();
                    $('#boton_citados_del_dia_conserje').addClass('btn');
                    $('#boton_citados_del_dia_conserje').addClass('btn-warning');
                }).catch(error => {
                });
                
            }
        }
    }
</script>