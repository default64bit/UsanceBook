<template>
    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col justify-center items-center" v-if="!loading">
            <div class="text-green-400" v-if="logged_in">Logged In</div>
            <div class="text-red-400" v-if="!logged_in">Not Logged In</div>
            <br>
            <button class="btn bg-gray-200 py-1 px-4" @click="logout()">Logout</button>
        </div>
        <div class="loading flex flex-col justify-center items-center h-full" v-else>
            <i class="text-5xl fad fa-spinner fa-spin"></i>
            <h1 class="text-2xl mt-2">Loading</h1>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import login from '../../auth/login'

    export default {
        name: 'Dashboard',
        data(){
            return {
                logged_in: false,
                loading: true,
            }
        },
        async created(){
            await token.isTokenValid().then(()=>{
                this.logged_in = true;
            }).catch((err)=>{
                login.goToLoginPage();
            });
            this.loading = false;
        },
        methods: {
            logout(){
                let access_token = this.$cookies.get('access_token');
                axios({
                    url: '/api/v1/logout',
                    method: 'post',
                    headers: {
                        'Authorization': `Bearer ${access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$cookies.remove("access_token");
                    this.$cookies.remove("refresh_token");
                    login.goToLoginPage('0');
                    // window.location.reload();
                }).catch(error=>{});
            },
        },
    }
</script>

<style>

</style>