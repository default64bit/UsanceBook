<template>
    <nav class="top_menu">
        <ul>
            <li :selected="$route.name=='dashboard'">
                <router-link to="/">
                    <span>Dashboard</span>
                </router-link>
            </li>
            <li :selected="$route.name=='transactions'">
                <router-link to="/Transactions">
                    <span>Transactions</span>
                </router-link>
            </li>
            <li :selected="$route.name=='cards'">
                <router-link to="/Cards">
                    <span>Cards</span>
                </router-link>
            </li>
            <li :selected="$route.name=='groups'">
                <router-link to="/Groups">
                    <span>Groups</span>
                </router-link>
            </li>
            <li :selected="$route.name=='friends'">
                <router-link to="/Friends">
                    <span>Friends</span>
                </router-link>
            </li>
            <li>
                <button>
                    <i class="fas fa-bell"></i>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'TopMenu',
        data(){
            return {
                
            }
        },
        mounted(){
            
        },
        methods:{
            
        }
    }
</script>

<style>
</style>