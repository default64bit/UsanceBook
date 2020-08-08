<template>
    <div class="login">
        <h1 class="title">Login</h1>

        <button class="btn">
            <img src="/assets/google.svg" alt="">
            <span>Continue With Google</span>
        </button>

        <div class="spacer">OR</div>

        <form action="/login" method="post">
            <input type="hidden" name="_token" :value="csrf_token">
            <input type="email" name="email" placeholder="Email Address">
            <input type="password" name="password" placeholder="Password">
            <br>
            <b class="error text-lg text-red-500" v-if="error!=''">! {{error}} !</b>
            <br>
            <button class="btn">Login</button>
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

            const urlParams = new URLSearchParams(window.location.search);
            this.error = urlParams.get('error');
            switch(this.error){
                case '1': this.error = 'Invalid Credentials'; break;
                case '2': this.error = 'Something Went Wrong, Try Again'; break;
                default: this.error = '';
            }
            
            // if(document.referrer == `${this.base_url}/login`){
            //     window.location.href = this.base_url;
            // }
        },
        mounted(){
            // make STATE and CODE_VERIFIER ####DONE
            // has the CODE_VERIFIER with sha256 then convert to base64 url safe and clll it CHALLANGE ####DONE
            // save the STATE and CODE_VERIFIER ####DONE
            // make Get request to back-end with parametres ( client_id, redirect_uri, response_type, scope, state, challenge ) ####DONE
            // the user redirects to back-end made Login page ####DONE
            // user submit it's credentials and back-end validates it and redirect to SPA
            // back-end redirect to SPA with CODE and STATE
            // SPA checks the STATE from back-end and it's own STATE
            // if STATEs are the same SPA make another request with parametres ( grant_type, client_id, redirect_uri, code_verifier, code )
            // bacl-end will response with a token
        },
        methods: {

        },
    }
</script>

<style>

</style>