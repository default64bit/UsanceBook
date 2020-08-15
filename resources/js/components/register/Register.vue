<template>
    <div class="flex flex-col justify-center items-center" style="height:calc(100vh - 100px)">
        <div class="login" v-if="!loading">
            <h1 class="title">Register</h1>

            <button class="btn">
                <img src="/assets/google.svg" alt="">
                <span>Continue With Google</span>
            </button>

            <div class="spacer">OR</div>

            <form @submit.prevent>
                <input type="hidden" name="_token" :value="csrf_token">
                <input type="text" name="name" placeholder="First Name" v-model="name">
                <input type="text" name="family" placeholder="Last Name" v-model="family">
                <input type="email" name="email" placeholder="Email Address" v-model="email">
                <input type="password" name="password" placeholder="Password" v-model="password">
                <br>
                <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>
                <br>
                <button class="btn" @click="submit()">Register</button>
            </form>
        </div>

        <loading v-else></loading>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'

    export default {
        name: 'Register',
        components: {
            'loading': Loading,
        },
        data(){
            return {
                name: '',
                family: '',
                email: '',
                password: '',

                base_url: document.querySelector('meta[name="base_url"]').getAttribute('content'),
                csrf_token: document.querySelector('meta[name="csrf_token"]').getAttribute('content'),
                access_token: null,

                error: '',
                loading: true,
            }
        },
        async created(){
            await token.getToken().then((value)=>{ this.access_token = value; });
            if(this.access_token != null){
                this.$router.push('/');
            }
            this.loading = false;
        },
        mounted(){

        },
        computed: {
            ...mapGetters(['userData','isLoggedIn']),
        },
        watch: {
            isLoggedIn(newValue,oldValue){
                if(newValue){
                    this.$router.push('/');
                }else{ this.loading = false; }
            },
        },
         methods: {
            ...mapActions(['getUser']),

            submit(){
                axios({
                    url: '/register',
                    method: 'post',
                    data: {
                        name: this.name,
                        family: this.family,
                        email: this.email,
                        password: this.password,
                    }
                }).then(response=>{
                    this.$cookies.set("access_token", response.data.access_token, 20*60, '/');
                    window.location.reload();
                }).catch(error=>{
                    if(error.response != undefined && error.response.status != 500){
                        this.error = error.response.data.error;
                    }
                });
            },

        },

    }
</script>

<style>

</style>