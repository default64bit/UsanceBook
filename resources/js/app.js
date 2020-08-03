import Vue from 'vue';
import router from './router';
import App from './components/App';
import VueCookies from 'vue-cookies';
import Http404 from './components/Http404';
import store from './store';

require('./bootstrap');

Vue.use(VueCookies);

const app = new Vue({
    el: '#app',
    components: {App,Http404},
    router,store
});
