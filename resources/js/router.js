import Vue from 'vue';
import VueRouter from 'vue-router';

// =================================

import Login from './components/login/Login';

// =================================

import Http404 from './components/Http404';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [

        { path: '/', component: Login, name: 'Login' },

        // =================================

        // { path: '/user/login', component: Login, name: 'login', props: true },
        // { path: '/user/login/callback', component: LoginCallback },

        // { path: '/user/register', component: Register, name: 'register', props: true },
        // { path: '/user/register/callback', component: RegisterCallback },

        // =================================

        { path: '*', component: Http404, name: '404' },

    ],
    scrollBehavior(to, from, savedPosition){
        document.querySelector('.main_body').scrollTop = 0;
        return { x: 0, y: 0 }
    },
});