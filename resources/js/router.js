import Vue from 'vue';
import VueRouter from 'vue-router';

// =================================

import Login from './components/login/Login';
import Register from './components/register/Register';

// =================================

import Dashboard from './components/dashboard/Dashboard';

// =================================

import Http404 from './components/Http404';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [

        { path: '/', component: Dashboard, name: 'dashboard' },

        // =================================

        { path: '/login', component: Login, name: 'login' },
        // { path: '/user/login/callback', component: LoginCallback },

        { path: '/register', component: Register, name: 'register' },
        // { path: '/user/register/callback', component: RegisterCallback },

        // =================================

        { path: '*', component: Http404, name: '404' },

    ],
    scrollBehavior(to, from, savedPosition){
        document.querySelector('.main_body').scrollTop = 0;
        return { x: 0, y: 0 }
    },
});