<template>
	<!-- Left side column. contains the logo and sidebar -->
  	<aside class="main-sidebar">

    	<!-- sidebar: style can be found in sidebar.less -->
    	<section class="sidebar">

      		<!-- Sidebar user panel (optional) -->
      		<div class="user-panel">
        		<div class="pull-left image">
          			<img src="img-dlte/user2-160x160.jpg" class="img-circle" alt="User Image">
        		</div>
        		<div class="pull-left info">
          			<p>{{ nombre_corto }}</p>
          			<!-- Status -->
          			<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        		</div>
      		</div>

      		<!-- Sidebar Menu -->
      		<ul class="sidebar-menu" data-widget="tree">
                <li class="header">
                    M&Oacute;DULOS
                </li>
                <li><a href="restaurar"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
                <li v-for="item in menu" class="active treeview">
                    <a href="">
                        <i class="fa fa-link"></i> <span>{{ item.Texto }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                   <ul class="treeview-menu">
                        <li v-for="items in item.Submenu">
                            <a v-bind:href="items.Clave">
                                <i class="fa fa-circle-o"></i>&nbsp;{{ items.Texto }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href=""><i class="fa fa-book"></i> <span>Documentaci&oacute;n</span></a></li>
            </ul>

      		<!-- /.sidebar-menu -->
    	</section>
    	<!-- /.sidebar -->
  	</aside>
</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                nombre_corto: '',
                menu: [],
            }
        },
        created: function() {
            this.loadData();
        },
        methods: {

            // envio de datos
            loadData: function() {
                var url = 'sistema/get_data_session';
                axios.get(url).then(response => {                    

                    if (response.data.con_mp == true) {
                        $("body").addClass('sidebar-collapse');
                        this.menu = null;
                    }
                    if (response.data.con_mp == false) {
                        this.nombre_corto = response.data.nombre_corto;
                        this.menu = response.data.menu;
                    }
                    
                }).catch(error => {
                    console.log('no hay datos iniciados');
                });
            }
        }
    }    
</script>