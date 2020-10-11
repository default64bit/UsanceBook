import Vue from 'vue';
import VueRouter from 'vue-router';

// =================================

import Login from './components/login/Login';

import Register from './components/register/Register';

// =================================

import Dashboard from './components/dashboard/Dashboard';

// =================================

import Transactions from './components/transactions/Transactions';

// =================================

import Cards from './components/cards/Cards';
import CardsStatistics from './components/cards/Statistics';

// =================================

import Groups from './components/groups/Groups';
import GroupsStatistics from './components/groups/Statistics';

// =================================

import Friends from './components/friends/Friends';

// =================================

import Http404 from './components/Http404';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [

        { path: '/', component: Dashboard, name: 'dashboard' },

        // =================================

        { path: '/login', component: Login, name: 'login' },
        { path: '/register', component: Register, name: 'register' },

        // =================================

        { path: '/transactions', component: Transactions, name: 'transactions' },

        // =================================

        { path: '/cards', component: Cards, name: 'cards' },
        { path: '/cards/:card_id/statistics', component: CardsStatistics, name: 'cards' },

        // =================================

        { path: '/groups', component: Groups, name: 'groups' },
        { path: '/groups/:group_id/statistics', component: GroupsStatistics, name: 'groups' },

        // =================================

        { path: '/friends', component: Friends, name: 'friends' },

        // =================================

        { path: '*', component: Http404, name: '404' },

    ],
    scrollBehavior(to, from, savedPosition){
        document.querySelector('.main_body').scrollTop = 0;
        return { x: 0, y: 0 }
    },
});