<template>
    <div class="form card_create">
        <div class="input_group">
            <input-box v-model="bank_name" type="text" placeholder="Bank Name"></input-box>
        </div>
        <div class="input_group">
            <input-box v-model="card_number" type="text" placeholder="Card Number" mask="#### #### #### ####"></input-box>
        </div>

        <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>

        <div class="loading flex flex-col justify-center items-start h-16">
            <i class="text-2xl fad fa-spinner fa-spin" v-if="loading"></i>
            <button class="btn" @click="addCard()" v-else>Add Card</button>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "CardCreate",
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
        methods: {

            async addCard(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('bank_name',this.bank_name);
                form_data.append('card_number',this.card_number);

                this.loading = true;
                axios({
                    url: '/api/v1/cards',
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