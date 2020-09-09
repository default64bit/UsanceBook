<template>
    <div class="data_table groups_table">
        <div class="toolbar">
            <search v-model="search" placeholder="Search Groups" @search="searchInGroups()"></search>
            <button class="btn" @click="addClicked()">Add New Group</button>
        </div>
        <hr>
        <table>
            <tbody v-if="groups.length>0">
                <tr v-for="(group,i) in groups" :key="i">
                    <td class="text-4xl mr-10">{{group.name}}</td>
                    <td class="my-4" :type="group.type">
                        <span class="text-3xl" v-if="group.total!=0">{{group.type}}{{group.total}} {{group.unit}}</span>
                        <span class="text-gray-500" v-else>No Transaction</span>
                    </td>
                    <td class="">
                        <ul class="flex">
                            <li v-for="(payer,j) in group.other_payers" :key="j">
                                <img :src="payer.avatar" class="w-8" alt="">
                            </li>
                        </ul>
                    </td>
                    <td class="actions">
                        <i class="fas fa-list" @mouseup="toggle_menu(i,true)"></i>
                        <ul :open="group.menu" @mouseenter="group.can_close=false" @mouseleave="group.can_close=true">
                            <li><button class="btn" @click="editClicked(i)"><i class="far fa-pencil-alt mr-2"></i> Edit</button></li>
                            <li><button class="btn" @click="deleteClicked(i)"><i class="far fa-trash-alt mr-2"></i> Delete</button></li>
                            <li><button class="btn" @click="goToTransactions(i)"><i class="far fa-newspaper mr-2"></i> Transactions</button></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
            <div class="my-4 text-center" v-else>No Data</div>
        </table>
        <hr class="mb-4">
        <div class="loading flex flex-col justify-center items-center" v-if="pagination.next">
            <button class="btn bg-gray-700" @click="getGroups(pagination.next,false)" v-if="!loading">Load More</button>
            <span class="text-5xl fad fa-spinner fa-spin" v-else></span>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import Search from '../layouts/form/Search'

    export default {
        name: 'GroupsTable',
        components: {
            'search': Search
        },
        data(){
            return {
                groups: [],
                pagination: {},

                search: '',

                loading: false,
                access_token: '',
            }
        },
        created(){
            this.$parent.$on('new_value',(value)=>{
                this.groups.unshift(value);
            });
            this.$parent.$on('edit_value',(value)=>{
                this.$set(this.groups,value.index,value.data);
            });
            this.$parent.$on('delete_value',(value)=>{
                this.groups.splice(value.index,1);
            });
        },
        mounted(){
            document.getElementById('app').addEventListener('mousedown',()=>{
                for(let i=0; i<this.groups.length; i++){
                    if(this.groups[i].can_close){
                        this.$set(this.groups[i],'menu',false);
                    }
                }
            });

            this.getGroups();
        },
        methods: {
            toggle_menu(index,state){
                this.$set(this.groups[index],'menu',state);
                this.$set(this.groups[index],'can_close',state);
            },

            async getGroups(url = '/api/v1/groups', first_time = true){
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
                        this.groups = response.data.data;
                    }else{
                        this.groups.push(...response.data.data);
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

            searchInGroups(){
                let url = '/api/v1/groups';
                if(this.search != null || this.search != undefined){
                    url += `?search=${this.search}`;
                }
                this.getGroups(url);
            },

            addClicked(){
                this.$emit('add');
            },
            editClicked(index){
                this.$emit('edit',{
                    index: index,
                    data: this.groups[index],
                });
                this.toggle_menu(index,false);
            },
            deleteClicked(index){
                this.$emit('delete',{
                    index: index,
                    data: this.groups[index],
                });
                this.toggle_menu(index,false);
            },

            goToTransactions(index){
                let id = this.groups[index].id;
                this.$router.push(`/Transactions?group=${id}`);
            },

        },
    }
</script>

<style>

</style>