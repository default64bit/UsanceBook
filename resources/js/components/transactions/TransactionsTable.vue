<template>
    <div class="transaction_table">
        <div class="toolbar">
            <search v-model="search" placeholder="Search Transactions" @search="doSomething()"></search>
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
                        <span>Payed By</span>
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
                    <td :type="transaction.type">
                        <span class="text-xl">{{transaction.type}}{{transaction.amount}}</span>
                        <b>{{transaction.unit}}</b>
                    </td>
                    <td>{{transaction.card}}</td>
                    <td>{{transaction.date}}</td>
                    <td class="actions">
                        <i class="fas fa-ellipsis-v" @mouseup="toggle_menu(i,true)"></i>
                        <ul :open="transaction.menu" @mouseenter="transaction.can_close=false" @mouseleave="transaction.can_close=true">
                            <li><button class="btn"><i class="far fa-newspaper mr-2"></i> Details</button></li>
                            <li><button class="btn"><i class="far fa-pencil-alt mr-2"></i> Edit</button></li>
                            <li><button class="btn"><i class="far fa-trash-alt mr-2"></i> Delete</button></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Search from '../layouts/form/Search'

    export default {
        name: 'TransactionsTable',
        components: {
            'search': Search
        },
        data(){
            return {
                transactions: [
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '+',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '-',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '+',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '+',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '-',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '+',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                    {
                        'name': 'Transaction Name',
                        'payed_by': {
                            'avatar': '/assets/user.svg',
                            'name': 'Kasra',
                            'family': 'Keshvardoost',
                        },
                        'amount': '23,450',
                        'unit': 'T',
                        'type': '+',
                        'card': 'From Wallet',
                        'date': '13 Sep, 2019',
                    },
                ],

                search: '',
            }
        },
        created(){

        },
        mounted(){
            document.getElementById('app').addEventListener('mousedown',()=>{
                for(let i=0; i<this.transactions.length; i++){
                    if(this.transactions[i].can_close){
                        this.$set(this.transactions[i],'menu',false);
                    }
                }
            });
        },
        methods: {
            toggle_menu(index,state){
                this.$set(this.transactions[index],'menu',state);
                this.$set(this.transactions[index],'can_close',state);
            },

            doSomething(){
                console.log(this.search);
            },

            addClicked(){
                this.$emit('add');
            },
        },
    }
</script>

<style>

</style>