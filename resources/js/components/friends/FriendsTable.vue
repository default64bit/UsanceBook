<template>
    <div class="data_table friends_table">
        <div class="toolbar">
            <search v-model="search" placeholder="Search Friends" @search="searchInFriends()"></search>
            <button class="btn" @click="addClicked()">Find New Friends</button>
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
                        <button class="btn remove" @click="deleteFriend(i)" v-if="friend.accepted">Remove</button>
                        <button class="btn cancel" @click="cancelRequest(i)" v-else
                            @mouseover="cancel_text='Cancel Request'" @mouseout="cancel_text='Pending'">
                            {{cancel_text}}
                        </button>
                    </td>
                </tr>
            </tbody>
            <div class="my-4 text-center" v-else>No Data</div>
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
    import Search from '../layouts/form/Search'

    export default {
        name: 'FriendsTable',
        components: {
            'search': Search
        },
        data(){
            return {
                friends: [],
                pagination: {},

                search: '',

                cancel_text: 'Pending',

                loading: false,
                access_token: '',
            }
        },
        created(){
            this.$parent.$on('new_value',(value)=>{
                this.friends.unshift(value);
            });
            this.$parent.$on('cancel_value',(value)=>{
                this.friends.splice(value.index,1);
            });
            this.$parent.$on('delete_value',(value)=>{
                this.friends.splice(value.index,1);
            });
        },
        mounted(){
            this.getFriends();
        },
        methods: {
            async getFriends(url = '/api/v1/friends', first_time = true){
                if(!url){ return 0; }

                if(!first_time){
                    if(this.search != null || this.search != undefined){
                        url += `&search=${this.search}`;
                    }
                }

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

            searchInFriends(){
                let url = '/api/v1/friends';
                if(this.search != null || this.search != undefined){
                    url += `?search=${this.search}`;
                }
                this.getFriends(url);
            },

            addClicked(){
                this.$emit('add');
            },
            cancelRequest(index){
                this.$emit('cancel',{
                    index: index,
                    data: this.friends[index],
                });
            },
            deleteFriend(index){
                this.$emit('delete',{
                    index: index,
                    data: this.friends[index],
                });
            },

        },
    }
</script>

<style>

</style>