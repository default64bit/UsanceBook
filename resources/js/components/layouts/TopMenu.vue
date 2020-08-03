<template>
    <nav ref="menu" class="menu">
        <ul class="main_menu">
            <li>
                <router-link to="/">
                    <span>Dashboard</span>
                </router-link>
            </li>
            <li>
                <router-link to="/">
                    <span>Transactions</span>
                </router-link>
            </li>
            <li>
                <router-link to="/">
                    <span>Cards</span>
                </router-link>
            </li>
            <li>
                <router-link to="/">
                    <span>Groups</span>
                </router-link>
            </li>
            <li>
                <router-link to="/">
                    <span>Friends</span>
                </router-link>
            </li>
            <li>
                <router-link to="/">
                    <i class="fas fa-bell"></i>
                </router-link>
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