<template>
    
    <div>
        <section class="content-header">
            <h1>
                {{ nombre_modulo }}
                <small>Panel de Control</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Panel de Control</li>
            </ol>
        </section>

    <!-- Cuerpo del contenido -->
        <section class="content container-fluid">
            <div class="box box-default color-palette-box text-center" id="">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-2 text-center" style="margin: 1%;" v-for="item in menu">
                            <button class="btn btn-app btn-lg text-center" style="width: 80%;height: 80%; padding: 5% 12% 5% 12%;" v-on:click="newMenu(item.IdListGrupo)">
                                <img v-bind:src="'/svg/IconosWeb/' + item.Icono + '.png'" alt="" class="img-responsive"> <br>{{ item.Texto }}
                            </button>
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
                menu: [],
                nombre_modulo: '',
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
                        this.menu = response.data.menu;
                        this.nombre_modulo = 'Módulos';
                    }
                    if (response.data.con_mp == false) {
                        this.menu = '';
                        this.nombre_modulo = response.data.menu[0].Texto;

                    }
                    
                }).catch(error => {
                    console.log('no hay datos iniciados');
                });
            },
            newMenu: function(idlistgrupo) {
                var url = 'menu/' + idlistgrupo;
                axios.get(url).then(response => {
                    window.location = 'inicio';
                }).catch(error => {

                });
            }
        }
    }    
</script>