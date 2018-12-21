require('./bootstrap');
window.Vue = require('vue');
Vue.config.productionTip = false

/**
 * Componentes
 */

// Layouts
Vue.component('login', require('./components/Login.vue'));
Vue.component('headerlte', require('./components/Header.vue'));
Vue.component('menulte', require('./components/Menu.vue'));
Vue.component('footerlte', require('./components/Footer.vue'));

// Inicio
Vue.component('inicioindex', require('./components/Inicio.Index.vue'));

// Farmacia
Vue.component('reportegestion', require('./components/farmacia/reportegestion.vue'));
Vue.component('reporalmacen', require('./components/farmacia/reporalmacen.vue'));

// Caja
Vue.component('caja', require('./components/caja/cajas.vue'));
Vue.component('cajacentral', require('./components/caja/cajasc.vue'));
Vue.component('cajafarmacia', require('./components/caja/cajasf.vue'));

// Consulta externa
Vue.component('admision', require('./components/consultaextena/admision.vue'));

const app = new Vue({
    el: '#app'
});
