/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import store from './store'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(Vuetify);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('home-component', require('./components/Home.vue').default);
Vue.component('subject-component', require('./components/subject/Main.vue').default);
Vue.component('invoice-component', require('./components/invoice/Main.vue').default);
Vue.component('calendar-component', require('./components/calendar/Main.vue').default);
Vue.component('gantto-component', require('./components/gantto/Main.vue').default);
Vue.component('master-component', require('./components/master/Main.vue').default);
Vue.component('setting-component', require('./components/setting/Main.vue').default);
Vue.component('print-component', require('./components/print/Main.vue').default);
Vue.component('bill-component', require('./components/bill/Main.vue').default);
Vue.component('pay-component', require('./components/pay/Main.vue').default);

Vue.component('user-component', require('./components/User.vue').default);

Vue.component('test-component', require('./components/test.vue').default);
Vue.component('hoge-component', require('./components/hoge.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    store,
});
