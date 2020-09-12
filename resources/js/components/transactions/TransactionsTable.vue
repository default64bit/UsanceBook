<template>
    <div class="data_table transaction_table">
        <div class="toolbar">
            <search v-model="search" placeholder="Search Transactions" @search="getTransactions()"></search>
            <div class="flex">
                <select-box :value.sync="card_filter" placeholder="Cards" :options="card_filter_options"
                    :searchable="true" @search="searchCardList"
                    class="w-auto m-0 ml-4" @update:value="getTransactions()">
                    <template v-slot:option="{option}">
                        <span :value="option.value">{{option.name}}</span>
                    </template>
                </select-box>
                <select-box :value.sync="group_filter" placeholder="Groups" :options="group_filter_options"
                    :searchable="true" @search="searchGroupList"
                    class="w-auto m-0 ml-4" @update:value="getTransactions()">
                    <template v-slot:option="{option}">
                        <span :value="option.value">{{option.name}}</span>
                    </template>
                </select-box>
            </div>
            <button class="btn" @click="addClicked()">Add New Transaction</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>
                        <span>Transaction By</span>
                        <i class="fad fa-long-arrow-alt-up"></i><i class="fad fa-long-arrow-alt-down"></i>
                    </th>
                    <th>
                        <span>Name</span>
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
            <tbody v-if="transactions.length>0">
                <tr v-for="(transaction,i) in transactions" :key="i">
                    <td>
                        <div class="flex items-center">
                            <img class="avatar" :src="transaction.payed_by.avatar" alt="">
                            <span>{{transaction.payed_by.name}} {{transaction.payed_by.family}}</span>
                        </div>
                    </td>
                    <td>{{transaction.name}}</td>
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
            <div class="my-4 text-center" v-else>No Data</div>
        </table>
        <hr class="mb-4">
        <div class="loading flex flex-col justify-center items-center" v-if="pagination.next">
            <button class="btn bg-gray-700" @click="getTransactions(pagination.next,false)" v-if="!loading">Load More</button>
            <span class="text-5xl fad fa-spinner fa-spin" v-else></span>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import SelectBox from '../layouts/form/SelectBox'
    import Search from '../layouts/form/Search'

    export default {
        name: 'TransactionsTable',
        components: {
            'select-box': SelectBox,
            'search': Search,
        },
        data(){
            return {
                transactions: [],
                pagination: {},

                search: '',
                card_filter: { value:'', name:'All' },
                group_filter: { value:'', name:'All' },
                card_filter_options: {
                    '0': { value:'', name:'All' }
                },
                group_filter_options: {
                    '0': { value:'', name:'All' }
                },

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

            Promise.all([
                this.getCards(),
                this.getGroups(),
            ]).then(()=>{
                this.addOtherParams();
                this.getTransactions();
            });
        },
        methods: {
            toggle_menu(index,state){
                this.$set(this.transactions[index],'menu',state);
                this.$set(this.transactions[index],'can_close',state);
            },

            async getTransactions(url = '/api/v1/transaction', first_time = true){
                if(!url){ return 0; }

                if(first_time){ url += '?'; }
                if(this.search != null && this.search != undefined){
                    url += `&search=${this.search}`;
                }
                if(this.card_filter.value != '' && this.card_filter.value != null && this.card_filter.value != undefined){
                    url += `&card=${this.card_filter.value}`;
                }
                if(this.group_filter.value != '' && this.group_filter.value != null && this.group_filter.value != undefined){
                    url += `&group=${this.group_filter.value}`;
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

            async getCards(url = '/api/v1/cards'){
                await token.getToken().then((value)=>{ this.access_token = value; });
                // url += '?all=true';

                let done = false;
                await axios({
                    url: url,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.card_filter_options = { '0': { value:'', name:'All' } };
                    for(let i=0 ; i<response.data.data.length ; i++){
                        this.card_filter_options[response.data.data[i].id] = {
                            value:response.data.data[i].id, name:response.data.data[i].bank
                        };
                    }
                    done = true;
                }).catch(error=>{
                    done = true;
                });

                return new Promise((resolve,reject)=>{
                    if(done){resolve();}
                });
            },
            searchCardList(value){
                this.getCards('/api/v1/cards?search='+value);
            },

            async getGroups(url = '/api/v1/groups'){
                await token.getToken().then((value)=>{ this.access_token = value; });
                // url += '?all=true';

                let done = false;
                await axios({
                    url: url,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.group_filter_options = { '0': { value:'', name:'All' } };
                    for(let i=0 ; i<response.data.data.length ; i++){
                        this.group_filter_options[response.data.data[i].id] = {
                            value:response.data.data[i].id, name:response.data.data[i].name
                        };
                    }
                    done = true;
                }).catch(error=>{
                    done = true;
                });

                return new Promise((resolve,reject)=>{
                    if(done){resolve();}
                });
            },
            searchGroupList(value){
                this.getGroups('/api/v1/groups?search='+value);
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

            addOtherParams(){
                let url_bar = window.location.href;
                if(url_bar.indexOf('?') != -1){
                    url_bar = url_bar.substring(url_bar.indexOf('?')+1,url_bar.length);
                    let params = url_bar.split('&');
                    for(let i=0 ; i<params.length ; i++){
                        let param = params[i].split('=');

                        switch(param[0]){
                            case 'card': this.card_filter = this.card_filter_options[param[1]]; break;
                            case 'group': this.group_filter = this.group_filter_options[param[1]]; break;
                        }
                    }
                }
            },

        },
    }
</script>

<style>

</style>