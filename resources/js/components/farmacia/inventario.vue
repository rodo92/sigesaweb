<template>
	 <div>
	 	<section class="content-header">
			<h1>
				Farmacia
				<small>Inventario</small>
			</h1>
			<ol class="breadcrumb">
                <li><a href="restaurar"><i class="fa fa-dashboard"></i>Farmacia</a></li>
                <li class="active">Inventario</li>
            </ol>
		</section>	
		<section class="content container-fluid">
			<div id="lista_envio">
				<div class="box box-primary color-palette-box">
					<div class="box-body">
                        <table style="width: 100%">
                            <tr>
                            	<td width="15%" style="padding-right: 5px;">
                            		<label>Nro. Inventario</label>
                            		<div class="input-group">
                                       	<input class="form-control" type="text" placeholder="Nro. Inventario" readonly>
                                    </div>                  		
                            	</td>
                            	<td width="15%" style="padding-right: 5px;">
                            		<label>Fecha Registro</label>
                            		<div class="input-group">
                                       	<input class="form-control" type="text" placeholder="Fecha Registro" id="fecharegistro" readonly>
                                    </div>                  		
                            	</td>
                            	<td width="15%" style="padding-right: 5px;">
                                    <label>Almacen:</label>
                                    <div class="input-group">
                                        <select name="" id="id_almacen" class="form-control" v-model="almacenid">
                                            <option v-for="almacen in almacenes" :value="almacen.idAlmacen">
                                                {{ almacen.descripcion }}
                                            </option>
                                        </select>
                                    </div>
                                </td>	
                                <td width="15%" style="padding-right: 5px;">
                            		<label>Fecha Modificacion</label>
                            		<div class="input-group">
                                       	<input class="form-control" type="date" placeholder="Nro. Inventario" readonly>
                                    </div>                  		
                            	</td>	
                            	<td width="15%" style="padding-right: 5px;">
                            		<label>Tipo Inventario</label>
                            		<div class="input-group">
                                       	<select name="" id="id_almacen" class="form-control" v-model="tipoiid" disabled>
                                            <option v-for="tipoi in tiposi" :value="tipoi.idTipoInventario">
                                                {{ tipoi.Descripcion }}
                                            </option>
                                        </select>
                                    </div>                  		
                            	</td>
                            	<tr></tr>
                            </tr>                    
                        </table>
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
			return{
				almacenes: [],
				tiposi: [],
				almacenid: '',
				tipoiid: ''
			}
		},
		created: function() {
            this.loadData();
        },
        methods: {
        	// carga de datos
            loadData: function() {
                var url = 'farmacia/almacenes';        
                var url2 = 'inventario/tipos_inventarios';  

                var f = $("#fecharegistro").val();

                alert(f);                 
                // cargando almacenes
                axios.get(url).then(response => {                    
                    this.almacenes = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de almacenes');
                });    

                 // cargando tipos inventarios
                axios.get(url2).then(response => {                    
                    this.tiposi = response.data.data;
                }).catch(error => {
                    console.log('no hay datos de tipos de inventarios');
                });     
                
            }
        }
	}
</script>