<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center" v-if="!loading">
            <div class="text-green-400" v-if="isLoggedIn">Logged In</div>
            <div class="text-red-400" v-if="!isLoggedIn">Not Logged In</div>
            <br>
            <button class="btn bg-gray-200 py-1 px-4" @click="logout()">Logout</button>
        </div>

        <loading v-else></loading>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'

    export default {
        name: 'Dashboard',
        components: {
            'loading': Loading,
        },
        data(){
            return {
                loading: true,
                access_token: null,
            }
        },
        async created(){
            await token.getToken().then((value)=>{ this.access_token = value; });
            if(this.access_token == null){
                this.$router.push('/login');
            }
            this.loading = false;
        },
        computed: {
            ...mapGetters(['userData','isLoggedIn']),
        },
        watch: {
            isLoggedIn(newValue,oldValue){
                if(!newValue){
                    this.$router.push('/login');
                }else{ this.loading = false; }
            },
        },
        methods: {
            ...mapActions(['getUser']),

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
                    this.$router.push('/login');
                }).catch(error=>{
                    if(error.response != undefined && error.response.status != 500){
                        this.$router.push('/login');
                    }
                });
            },

        },
    }
</script>

<style>

</style>