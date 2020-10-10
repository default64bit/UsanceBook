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
            <li :selected="$route.name=='profile'">
                <router-link to="/Profile">
                    <span>Profile</span>
                </router-link>
            </li>
            <li>
                <button>
                    <i class="fas fa-bell"></i>
                </button>
                <button @click="logout()" title="Logout">
                    <i class="fad fa-door-open"></i>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'

    export default {
        name: 'TopMenu',
        data(){
            return {
                access_token: null,
            }
        },
        mounted(){
            
        },
        methods:{

            async logout(){
                await token.getToken().then((value)=>{ this.access_token = value; });
                if(this.access_token == null){ this.$router.push('/login'); }
                axios({
                    url: '/api/v1/logout',
                    method: 'post',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$cookies.remove("access_token");
                    window.location.reload();
                }).catch(error=>{
                    if(error.response != undefined && error.response.status != 500){
                        this.$router.push('/login');
                    }
                });
            },
        }
    }
</script>

<style>
</style>