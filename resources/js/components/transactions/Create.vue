<template>
    <div class="form transaction_create">
        <div class="input_group">
            <input-box v-model="name" type="text" placeholder="Transaction Name"></input-box>
        </div>
        <div class="input_group">
            <input-box v-model="amount" type="text" placeholder="Amount (Toman)"></input-box>
            <select-box class="w-40" :value.sync="type" placeholder="Type" :options="type_options">
                <template v-slot:option="{option}">
                    <span :value="option.value">{{option.name}}</span>
                </template>
            </select-box>
        </div>
        <div class="input_group">
            <input-box v-model="date" type="text" placeholder="Date (yyyy/mm/dd hh:mm:ss)" mask="####/##/## ##:##:##"></input-box>
        </div>
        <div class="input_group">
            <select-box :value.sync="transaction_group" placeholder="Transaction Group" :options="transaction_group_options">
                <template v-slot:option="{option}">
                    <span :value="option.value">{{option.name}}</span>
                </template>
            </select-box>
        </div>
        <div class="input_group">
            <select-box :value.sync="for_user" placeholder="For User" :options="for_user_options">
                <template v-slot:option="{option}">
                    <span :value="option.value">{{option.name}}</span>
                </template>
            </select-box>
            <select-box :value.sync="card" placeholder="Card" :options="card_options">
                <template v-slot:option="{option}">
                    <span :value="option.value">{{option.name}}</span>
                </template>
            </select-box>
        </div>
        <button class="btn" @click="submitTransaction()">Submit Transaction</button>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mask} from 'vue-the-mask'
    import SelectBox from '../layouts/form/SelectBox'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "TransactionCreate",
        components: {
            'select-box': SelectBox,
            'input-box': InputBox,
        },
        data(){
            return {
                name: '',
                amount: '',
                type: {},
                date: '',
                transaction_group: {},
                for_user: {},
                card: {},

                type_options: [
                    { value:'+', name:'Gain' },
                    { value:'-', name:'Loss' },
                ],
                transaction_group_options: [
                    { value:null, name:'No Group' },
                    { value:'2', name:'Group1' },
                ],
                for_user_options: [
                    { value:null, name:'Nobody' },
                    { value:'3', name:'Gourge' },
                    { value:'2', name:'Fred' },
                ],
                card_options: [
                    { value:null, name:'No Card' },
                    { value:'23', name:'Bank Mellat' },
                    { value:'13', name:'Bank Sepah' },
                ],

                access_token: '',
            }
        },
        directives: {mask},
        methods: {
            async submitTransaction(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('name',this.name);
                form_data.append('amount',this.amount);
                form_data.append('type',this.type.value);
                form_data.append('date',this.date);
                if(this.transaction_group.value){
                    form_data.append('transaction_group',this.transaction_group.value);
                }
                if(this.for_user.value){
                    form_data.append('for_user',this.for_user.value);
                }
                if(this.card.value){
                    form_data.append('card',this.card.value);
                }

                axios({
                    url: '/api/v1/transaction',
                    method: 'post',
                    data: form_data,
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$parent.$emit('close');
                    this.$parent.$emit('new_value',response.data);
                }).catch(error=>{
                    
                });
            },
        }
    }
</script>

<style>

</style>