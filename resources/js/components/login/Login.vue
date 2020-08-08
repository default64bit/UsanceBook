<template>
    <div class="login">
        <h1 class="title">Login</h1>

        <button class="btn">
            <img src="/assets/google.svg" alt="">
            <span>Continue With Google</span>
        </button>

        <div class="spacer">OR</div>

        <form @submit.prevent>
            <input type="hidden" name="_token" :value="csrf_token">
            <input type="email" name="email" placeholder="Email Address" v-model="email">
            <input type="password" name="password" placeholder="Password" v-model="password">
            <br>
            <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>
            <br>
            <button class="btn" @click="submit()">Login</button>
        </form>
    </div>
</template>

<script>
    import token from '../../auth/token'

    export default {
        name: 'Login',
        data(){
            return {
                email: '',
                password: '',

                base_url: document.querySelector('meta[name="base_url"]').getAttribute('content'),
                csrf_token: document.querySelector('meta[name="csrf_token"]').getAttribute('content'),

                error: '',
            }
        },
        async created(){
            await token.isTokenValid().then(()=>{
                window.location.href = this.base_url;
            }).catch((err)=>{ console.log(err); });

            // const urlParams = new URLSearchParams(window.location.search);
            // this.error = urlParams.get('error');
            // switch(this.error){
            //     case '1': this.error = 'Invalid Credentials'; break;
            //     case '2': this.error = 'Something Went Wrong, Try Again'; break;
            //     default: this.error = '';
            // }
        },
        mounted(){

        },
        methods: {
            submit(){
                let access_token = this.$cookies.get('access_token');
                axios({
                    url: '/login',
                    method: 'post',
                    data: {
                        email: this.email,
                        password: this.password,
                    }
                }).then(response=>{
                    this.$cookies.set("access_token", response.data.access_token, 20*60, '/');
                    this.$router.push('/');
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