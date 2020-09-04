<template>
    <div class="data_table friend_requests">
        <hr class="mt-4">
        <div class="flex justify-between py-1">
            <h3 class="text-lg"><b>Friend Requests</b></h3>
            <ins>{{friends.length}}</ins>
        </div>
        <hr>
        <table>
            <tbody v-if="friends.length>0">
                <tr v-for="(friend,i) in friends" :key="i">
                    <td>
                        <div class="flex items-center">
                            <img class="avatar" :src="friend.avatar" alt="">
                            <div class="flex flex-col">
                                <b class="text-lg">{{friend.name}} {{friend.family}}</b>
                                <span class="opacity-75 text-sm">{{friend.email}}</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex" v-if="!friend.loading">
                            <button class="btn accept" @click="accept(i)">Accept</button>
                            <button class="btn reject" @click="reject(i)">Reject</button>
                        </div>
                        <span class="text-3xl fad fa-spinner fa-spin" v-else></span>
                    </td>
                </tr>
            </tbody>
            <div class="my-4 text-center" v-else>No Friend Request</div>
        </table>
        <hr class="mb-4">
        <div class="loading flex flex-col justify-center items-center" v-if="pagination.next">
            <button class="btn bg-gray-700" @click="getFriends(pagination.next,false)" v-if="!loading">Load More</button>
            <span class="text-5xl fad fa-spinner fa-spin" v-else></span>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'

    export default {
        name: 'FriendRequests',
        components: {
            
        },
        data(){
            return {
                friends: [],
                pagination: {},

                loading: false,
                access_token: '',
            }
        },
        created(){
            
        },
        mounted(){
            this.getFriends();
        },
        methods: {
            async getFriends(url = '/api/v1/friends/request_list', first_time = true){
                if(!url){ return 0; }

                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: url,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    if(first_time){
                        this.friends = response.data.data;
                    }else{
                        this.friends.push(...response.data.data);
                    }
                    this.pagination = {
                        current_page:response.data.meta.current_page,
                        first:response.data.links.first,
                        last:response.data.links.last,
                        next:response.data.links.next,
                        prev:response.data.links.prev,
                    }
                    this.loading = false;
                }).catch(error=>{
                    this.loading = false;
                });
            },

            async accept(index){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.$set(this.friends[index],'loading',true);
                let email = this.friends[index].email;
                axios({
                    url: `api/v1/friends/request/${email}/accept`,
                    method: 'post',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$set(this.friends[index],'loading',false);
                    this.friends.splice(index,1);
                }).catch(error=>{
                    this.$set(this.friends[index],'loading',false);
                });
            },
            async reject(index){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.$set(this.friends[index],'loading',true);
                let email = this.friends[index].email;
                axios({
                    url: `api/v1/friends/request/${email}/reject`,
                    method: 'post',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$set(this.friends[index],'loading',false);
                    this.friends.splice(index,1);
                }).catch(error=>{
                    this.$set(this.friends[index],'loading',false);
                });
            },

        },
    }
</script>

<style>

</style>