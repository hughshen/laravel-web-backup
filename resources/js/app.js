import Vue from 'vue'
import ElementUI from 'element-ui'
import VueRouter from 'vue-router';
import axios from 'axios';
import router from './routes.js';
import Api from './api.js';
import Auth from './auth.js';

// Components
import App from './views/App.vue';

// Element UI
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';

// Sass
import '../sass/app.scss';

// Globals
window.Vue = Vue;
window.Event = new Vue;
window.axios = axios;
window.api = new Api();
window.auth = new Auth();

Vue.use(ElementUI,  { locale });
Vue.use(VueRouter);

// Settings
window.axios.defaults.headers.common['Accept'] = 'application/json';

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});