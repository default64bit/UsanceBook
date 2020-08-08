<template>
    <div class="loading flex flex-col justify-center items-center h-full">
        <i class="text-5xl fad fa-spinner fa-spin"></i>
        <h1 class="text-2xl mt-2">Loading</h1>
        <br>
        <!-- <a :href="`${this.base_url}/login`">go to login</a> -->
    </div>
</template>

<script>
    export default {
        name: 'LoginCallback',
        data(){
            return {
                base_url: document.querySelector('meta[name="base_url"]').getAttribute('content'),
                
                state: '',
                verifier: '',
                client_id: '1',
                redirect_uri: '',
                grant_type: 'authorization_code',
                code: '',
                
                error: '',
            }
        },
        created(){
            const urlParams = new URLSearchParams(window.location.search);

            this.state = urlParams.get('state');
            this.verifier = localStorage.getItem('verifier');
            this.redirect_uri = this.base_url+'/login/callback';
            this.code = urlParams.get('code');

            this.error = urlParams.get('error');
        },
        mounted(){
            if(this.state == localStorage.getItem('state')){
                this.getToken();
            }
        },
        methods: {
            getToken(){
                axios({
                    url: '/oauth/token',
                    method: 'post',
                    data: {
                        client_id: this.client_id,
                        redirect_uri: this.redirect_uri,
                        grant_type: this.grant_type,
                        scope: this.scope,
                        state: this.state,
                        code_verifier: this.verifier,
                        code: this.code,
                    }
                }).then(response=>{
                    // save the token and refresh_token
                    this.$cookies.set("access_token", response.data.access_token, 20*60);
                    this.$cookies.set("refresh_token", response.data.refresh_token, 5*24*60*60);
                    localStorage.removeItem('state');
                    localStorage.removeItem('verifier');

                    window.location.href = this.base_url;
                    
                }).catch(error=>{
                    console.log(error);
                    if(this.error == 'access_denied'){
                        window.location.href = this.base_url+'/login';
                    }else if(error.response != undefined && error.response.status != 500){
                        window.location.href = this.base_url+'/login?error=2';
                    }
                });
            },
        },
    }
</script>

<style>

</style>