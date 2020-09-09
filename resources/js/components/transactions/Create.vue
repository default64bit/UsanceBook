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
            <multi-select-box :values.sync="transaction_groups" placeholder="Transaction Group" :options="transaction_groups_options">
                <template v-slot:option="{option}">
                    <span :value="option.value">{{option.name}}</span>
                </template>
            </multi-select-box>
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

        <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>

        <div class="loading flex flex-col justify-center items-start h-16">
            <i class="text-2xl fad fa-spinner fa-spin" v-if="loading"></i>
            <button class="btn" @click="submitTransaction()" v-else>Submit Transaction</button>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import SelectBox from '../layouts/form/SelectBox'
    import MultiSelectBox from '../layouts/form/MultiSelectBox'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "TransactionCreate",
        components: {
            'select-box': SelectBox,
            'multi-select-box': MultiSelectBox,
            'input-box': InputBox,
        },
        data(){
            return {
                name: '',
                amount: '',
                type: {},
                date: '',
                transaction_groups: {},
                for_user: {},
                card: {},

                type_options: [
                    { value:'+', name:'Gain' },
                    { value:'-', name:'Loss' },
                ],
                transaction_groups_options: [
                    { value:'2', name:'Group1' },
                    { value:'4', name:'Group2' },
                    { value:'6', name:'Group31' },
                    { value:'8', name:'Group8' },
                    { value:'7', name:'Group7' },
                    { value:'64', name:'Group64' },
                    { value:'61', name:'Group322' },
                ],
                for_user_options: [
                    { value:null, name:'Nobody' },
                    { value:'3', name:'Gourge' },
                    { value:'2', name:'Fred' },
                ],
                card_options: [],

                access_token: '',
                error: '',
                loading: false,
            }
        },
        mounted(){
            this.getCards();
        },
        methods: {
            async getCards(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                axios({
                    url: '/api/v1/cards?all=true',
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.card_options = [
                        { value:null, name:'No Card' }
                    ];
                    for(let i=0; i<response.data.length; i++){
                        this.card_options.push({
                            value: response.data.id,
                            name: response.data.bank,
                        });
                    }
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                });
            },

            async submitTransaction(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('name',this.name);
                form_data.append('amount',this.amount);
                form_data.append('type',this.type.value);
                form_data.append('date',this.date);
                if(this.transaction_groups){
                    form_data.append('transaction_groups',JSON.stringify(this.transaction_groups));
                }
                if(this.for_user.value){
                    form_data.append('for_user',this.for_user.value);
                }
                if(this.card.value){
                    form_data.append('card',this.card.value);
                }

                this.loading = true;
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
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },
        }
    }
</script>

<style>

</style>