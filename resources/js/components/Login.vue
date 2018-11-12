<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Web</b>Sigesa</a>
        </div>
  
        <div class="login-box-body">
            <p class="login-box-msg">Ingrese sus datos para iniciar sesion</p>

            <form method="post" v-on:submit.prevent="postdata">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Usuario" v-model="usuario">
                    <span class="form-control-feedback">
                        <i class="fa fa-user"></i>
                    </span>
                    <label v-if="errores.usuario" class="text-danger">{{ errores.usuario[0] }}</label>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Contraseña" v-model="contrasenia">
                    <span class="form-control-feedback">
                        <i class="fa fa-lock"></i>
                    </span>
                    <label v-if="errores.contrasenia" class="text-danger">{{ errores.contrasenia[0] }}</label>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Recordar datos
                            </label>
                        </div>
                    </div>
        
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
    
    // importacion de librerias
    import axios from 'axios'

    export default {
        data() {
            return {
                usuario: '',
                contrasenia: '',
                errores: ''
            }
        },
        created: function() {

        },
        methods: {

            // envio de datos
            postdata: function() {
                var url = 'login';
                axios.post(url, {
                    'usuario': this.usuario,
                    'contrasenia': this.contrasenia
                }).then(response => {
                    
                }).catch(error => {
                    this.errores = error.response.data.errors;
                });
            }
        }
    }
</script>
