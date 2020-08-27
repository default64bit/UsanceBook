<template>
    <div class="transaction_info">
        <div class="total">
            <h4>Total Number Of Transactions</h4>
            <b>{{info.total_number_of_transactions}}</b>
            <span type="+">Positive : <b>{{info.total_positive_transactions}}</b></span>
            <span type="-">Negative : <b>{{info.total_negative_transactions}}</b></span>
        </div>
        <div class="top" type="+">
            <h4>{{info.top_positive.name}}</h4>
            <span>{{info.top_positive.date}}</span>
            <b>+{{info.top_positive.amount}} {{info.top_positive.unit}}</b>
            <div class="avatar">
                <img :src="info.top_positive.payed_by.avatar" alt="">
                <span>{{info.top_positive.payed_by.name}} {{info.top_positive.payed_by.family}}</span>
            </div>
        </div>
        <div class="top" type="-">
            <h4>{{info.top_negative.name}}</h4>
            <span>{{info.top_negative.date}}</span>
            <b>-{{info.top_negative.amount}} {{info.top_negative.unit}}</b>
            <div class="avatar">
                <img :src="info.top_negative.payed_by.avatar" alt="">
                <span>{{info.top_negative.payed_by.name}} {{info.top_negative.payed_by.family}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'

    export default {
        name: "TransactionInfo",
        data(){
            return {
                info: {
                    total_number_of_transactions: '0',
                    total_positive_transactions: '0',
                    total_negative_transactions: '0',
                    top_positive: {
                        name: '',
                        date: '',
                        amount: '',
                        unit: 'T',
                        payed_by: {
                            avatar: '',
                            name: '',
                            family: '',
                        },
                    },
                    top_negative: {
                        name: '',
                        date: '',
                        amount: '',
                        unit: 'T',
                        payed_by: {
                            avatar: '',
                            name: '',
                            family: '',
                        },
                    },
                },

                loading: false,
                access_token: '',
            }
        },
        created(){

        },
        mounted(){
            this.getTransactionInfo();
        },
        methods: {
            async getTransactionInfo(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: '/api/v1/transaction/top_info',
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.info = response.data;
                    this.loading = false;
                }).catch(error=>{
                    this.loading = false;
                });
            },
        },
    }
</script>

<style>

</style>