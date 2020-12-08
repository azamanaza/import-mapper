require('./bootstrap');

import Vue from 'vue';

import vuetify from './src/vuetify';

//Main pages
import App from './src/app.vue';


const app = new Vue({
    el: '#app',
    vuetify,
    components: { App }
});