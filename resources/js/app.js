require('./bootstrap');
window.Vue = require('vue');


/**
 * Componentes
 */
Vue.component('login', require('./components/Login.vue'));
Vue.component('headerlte', require('./components/Header.vue'));
Vue.component('menulte', require('./components/Menu.vue'));
Vue.component('footerlte', require('./components/Footer.vue'));

const app = new Vue({
    el: '#app'
});
