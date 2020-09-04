<template>
    <div class="form find_new_friends">
        <div class="input_group">
            <input-box v-model="search" type="text" placeholder="Email Address" @input="searchUsers()"></input-box>
            <button class="search_button btn bg-gray-100"><i class="fas fa-search"></i></button>
        </div>
        <hr>
        <div class="data_table users_list" v-if="!loading">
            <table v-if="users.length>0">
                <tbody>
                    <tr v-for="(user,i) in users" :key="i">
                        <td>
                            <div class="flex items-center">
                                <img class="avatar w-16 h-16" :src="user.avatar" alt="">
                                <div class="flex flex-col">
                                    <b class="text-lg">{{user.name}} {{user.family}}</b>
                                    <span class="opacity-75 text-sm">{{user.email}}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="text-2xl fad fa-spinner fa-spin" v-if="user.add_state=='loading'"></i>
                            <i v-if="user.add_state=='added'">Request Sent</i>
                            <button class="btn" @click="addFriend(user,i)" v-else>Add</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="loading flex flex-col justify-center items-center h-16" v-else>
            <i class="text-2xl fad fa-spinner fa-spin"></i>
        </div>

        <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mask} from 'vue-the-mask'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "FindNewFriends",
        components: {
            'input-box': InputBox,
        },
        data(){
            return {
                search: '',
                timeout: '',
                users: [],

                access_token: '',
                error: '',
                loading: false,
            }
        },
        mounted(){
            this.timeout = setTimeout(()=>{},0);
        },
        methods: {
            searchUsers(){
                clearTimeout(this.timeout);
                this.timeout = setTimeout(()=>{
                    this.getUsers();
                },2000);
            },

            async getUsers(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: `/api/v1/users_list?search=${this.search}`,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.users = response.data;
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },

            async addFriend(user,index){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('email',user.email);

                this.$set(this.users[index],'add_state','loading');
                axios({
                    url: `/api/v1/friends`,
                    method: 'post',
                    data: form_data,
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$parent.$emit('new_value',user);
                    this.$set(this.users[index],'add_state','added');
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.$set(this.users[index],'add_state','');
                });
            },

        }
    }
</script>

<style>

</style>