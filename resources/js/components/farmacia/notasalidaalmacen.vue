<template>
	<div>
		<section class="content-header">
            <h1>
                Farmacia
                <small>Nota Salida Almacén</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="restaurar"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Nota Salida Almacén</li>
            </ol>
        </section>
        <section class="content container-fluid">
        	<form>
			  	<div class="row form-group">
			  		<div class="col-xs-2">
			  			<input type="text" class="form-control" placeholder="N° Nota Salida" readonly>
			  		</div>
			  		<div class="col-xs-4"></div>
			  		<div class="col-xs-2">
			  			<input type="text" class="form-control" placeholder="F. Registro" readonly>
			  		</div>
			  		<div class="col-xs-1">
			  			<input type="text" class="form-control" placeholder="Hora" readonly>
			  		</div>
			  		<div class="col-xs-1"></div>
					<div class="col-xs-2">
			  			<input type="text" class="form-control" placeholder="Estado" readonly>
			  		</div>

			  	</div>
			  	<div class="row form-group">
			  		<div class="col-xs-5">
					    <select class="form-control">
					    	<option value="" disabled selected>Almacen Origen</option>
					      	<option>1</option>
					      	<option>2</option>
					      	<option>3</option>
					      	<option>4</option>
					      	<option>5</option>
					    </select>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-xs-5">
					    <select class="form-control">
					    	<option value="" disabled selected>Concepto</option>
					      	<option>1</option>
					      	<option>2</option>
					      	<option>3</option>
					      	<option>4</option>
					      	<option>5</option>
					    </select>
					</div>
					<div class="col-xs-1"></div>
					<div class="col-xs-5">
						<select class="form-control">
					    	<option value="" disabled selected>Destino</option>
					      	<option>1</option>
					      	<option>2</option>
					      	<option>3</option>
					      	<option>4</option>
					      	<option>5</option>
					    </select>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-xs-5">
					    <select class="form-control">
					    	<option value="" disabled selected>Tipo Documento</option>
					      	<option>1</option>
					      	<option>2</option>
					      	<option>3</option>
					      	<option>4</option>
					      	<option>5</option>
					    </select>
					</div>
					<div class="col-xs-1"></div>
					<div class="col-xs-3">
			  			<input type="text" class="form-control" placeholder="N° Documento">
			  		</div>
				</div>

				<div class="row form-group">
					<div class="col-xs-5">
			  			<input type="text" class="form-control" placeholder="Observaciones"	>
			  		</div>
			  		<div class="col-xs-2"></div>
					<div class="col-xs-2">
						<button type="" class="btn btn-info" v-on:click.prevent="ver_modal" id="btn_buscar_productos"><i class="fa fa-search"></i> AGREGAR<br>PRODUCTOS</button>
					</div>
				</div>

			</form>
        </section>

        <!-- Busqueda de Productos -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_ingresos_productos">
        	<div class="modal-dialog modal-lg" role="document">
        		<div class="modal-content">
        			<div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Buscar medicamentos</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="row">
                    		<div class="col-xs-8">
                    			<input type="text" class="form-control" placeholder="NOMBRE O CODIGO" v-model="masletras">
                    		</div>
                    	</div>
                    </div>
        		</div>
        	</div>
        </div>
	</div>
</template>

<script>
	export default{
		data() {
			return{
			 	masletras: ''
			}
		},
		methods:{
			ver_modal: function() {
            	alert();
            }
		}
	}
</script>