<template>
    <div class="friend_info">
        <div class="" name="friends">
            <h3>Friends</h3>
            <b>{{friends_count}}</b>
        </div>
        <div class="" name="pending">
            <h3>Pending</h3>
            <b>{{pending_count}}</b>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'

    export default {
        name: 'FriendInfo',
        data(){
            return {
                friends_count: 0,
                pending_count: 0,

                error: '',
                loading: true,
                access_token: null,
            }
        },
        mounted(){
            this.getInfo();
        },
        methods: {
            async getInfo(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: `/api/v1/friends/info`,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.friends_count = response.data.friends;
                    this.pending_count = response.data.pending;
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },
        },
    }
</script>

<style>

</style>