<template>
    <div class="form transaction_edit">
        <div class="input_group">
            <input-box v-model="bank_name" type="text" placeholder="Bank Name"></input-box>
        </div>
        <div class="input_group">
            <input-box v-model="card_number" type="text" placeholder="Card Number" mask="#### #### #### ####"></input-box>
        </div>

        <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>

        <div class="loading flex flex-col justify-center items-start h-16">
            <i class="text-2xl fad fa-spinner fa-spin" v-if="loading"></i>
            <button class="btn" @click="editCard()" v-else>Edit Card</button>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "CardEdit",
        props: ['card'],
        components: {
            'input-box': InputBox,
        },
        data(){
            return {
                bank_name: '',
                card_number: '',

                access_token: '',
                error: '',
                loading: false,
            }
        },
        mounted(){

        },
        watch: { 
            card: function(newVal,oldVal) {
                this.getCard();
            }
        },
        methods: {

            async getCard(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: `/api/v1/cards/${this.card.data.id}`,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.bank_name = response.data.bank;
                    this.card_number = response.data.number;
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },

            async editCard(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('_method','put');
                form_data.append('bank_name',this.bank_name);
                form_data.append('card_number',this.card_number);

                this.loading = true;
                axios({
                    url: `/api/v1/cards/${this.card.data.id}`,
                    method: 'post',
                    data: form_data,
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$parent.$emit('close');
                    this.$parent.$emit('edit_value',{
                        index: this.card.index,
                        data: response.data,
                    });
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