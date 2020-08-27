<template>
    <div class="transaction_table">
        <div class="toolbar">
            <search v-model="search" placeholder="Search Transactions" @search="searchInTransactions()"></search>
            <button class="btn" @click="addClicked()">Add New Transaction</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>
                        <span>Name</span>
                        <i class="fad fa-long-arrow-alt-up"></i><i class="fad fa-long-arrow-alt-down"></i>
                    </th>
                    <th>
                        <span>Transaction By</span>
                        <i class="fad fa-long-arrow-alt-up"></i><i class="fad fa-long-arrow-alt-down"></i>
                    </th>
                    <th>
                        <span>Amount</span>
                        <i class="fad fa-long-arrow-alt-up"></i><i class="fad fa-long-arrow-alt-down"></i>
                    </th>
                    <th>
                        <span>Card</span>
                        <i class="fad fa-long-arrow-alt-up"></i><i class="fad fa-long-arrow-alt-down"></i>
                    </th>
                    <th>
                        <span>Date</span>
                        <i class="fad fa-long-arrow-alt-up"></i><i class="fad fa-long-arrow-alt-down"></i>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(transaction,i) in transactions" :key="i">
                    <td>{{transaction.name}}</td>
                    <td>
                        <div class="flex items-center">
                            <img class="avatar" :src="transaction.payed_by.avatar" alt="">
                            <span>{{transaction.payed_by.name}} {{transaction.payed_by.family}}</span>
                        </div>
                    </td>
                    <td :type="transaction.type.value">
                        <span class="text-xl">{{transaction.type.value}}{{transaction.amount}}</span>
                        <b>{{transaction.unit}}</b>
                    </td>
                    <td>{{transaction.card.name}}</td>
                    <td>{{transaction.date}}</td>
                    <td class="actions">
                        <i class="fas fa-ellipsis-v" @mouseup="toggle_menu(i,true)"></i>
                        <ul :open="transaction.menu" @mouseenter="transaction.can_close=false" @mouseleave="transaction.can_close=true">
                            <li><button class="btn" @click="toggle_menu(i,false)"><i class="far fa-newspaper mr-2"></i> Details</button></li>
                            <li><button class="btn" @click="editClicked(i)"><i class="far fa-pencil-alt mr-2"></i> Edit</button></li>
                            <li><button class="btn" @click="deleteClicked(i)"><i class="far fa-trash-alt mr-2"></i> Delete</button></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr class="my-4">
        <div class="loading flex flex-col justify-center items-center" v-if="pagination.next">
            <button class="btn bg-gray-700" @click="getTransactions(pagination.next,false)" v-if="!loading">Load More</button>
            <span class="text-5xl fad fa-spinner fa-spin" v-else></span>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import Search from '../layouts/form/Search'

    export default {
        name: 'TransactionsTable',
        components: {
            'search': Search
        },
        data(){
            return {
                transactions: [],
                pagination: {},

                search: '',

                loading: false,
                access_token: '',
            }
        },
        created(){
            this.$parent.$on('new_value',(value)=>{
                this.transactions.unshift(value);
            });
            this.$parent.$on('edit_value',(value)=>{
                this.$set(this.transactions,value.index,value.data);
            });
            this.$parent.$on('delete_value',(value)=>{
                this.transactions.splice(value.index,1);
            });
        },
        mounted(){
            document.getElementById('app').addEventListener('mousedown',()=>{
                for(let i=0; i<this.transactions.length; i++){
                    if(this.transactions[i].can_close){
                        this.$set(this.transactions[i],'menu',false);
                    }
                }
            });

            this.getTransactions();
        },
        methods: {
            toggle_menu(index,state){
                this.$set(this.transactions[index],'menu',state);
                this.$set(this.transactions[index],'can_close',state);
            },

            async getTransactions(url = '/api/v1/transaction', first_time = true){
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
                        this.transactions = response.data.data;
                    }else{
                        this.transactions.push(...response.data.data);
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

            searchInTransactions(){
                let url = '/api/v1/transaction';
                if(this.search != null || this.search != undefined){
                    url += `?search=${this.search}`;
                }
                this.getTransactions(url);
            },

            addClicked(){
                this.$emit('add');
            },
            editClicked(index){
                this.$emit('edit',{
                    index: index,
                    data: this.transactions[index],
                });
                this.toggle_menu(index,false);
            },
            deleteClicked(index){
                this.$emit('delete',{
                    index: index,
                    data: this.transactions[index],
                });
                this.toggle_menu(index,false);
            },

        },
    }
</script>

<style>

</style>