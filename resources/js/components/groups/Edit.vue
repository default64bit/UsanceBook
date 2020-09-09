<template>
    <div class="form transaction_edit">
        <div class="input_group">
            <input-box v-model="name" type="text" placeholder="Name"></input-box>
        </div>
        <div class="input_group">
            <select-box :value.sync="who_can_see" placeholder="Who Can See" :options="who_can_options">
                <template v-slot:option="{option}">
                    <i :class="option.icon" class="w-6"></i> <span :value="option.value">{{option.name}}</span>
                </template>
            </select-box>
            <select-box :value.sync="who_can_pay" placeholder="Who Can Pay" :options="who_can_options">
                <template v-slot:option="{option}">
                    <i :class="option.icon" class="w-6"></i> <span :value="option.value">{{option.name}}</span>
                </template>
            </select-box>
        </div>

        <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>

        <div class="loading flex flex-col justify-center items-start h-16">
            <i class="text-2xl fad fa-spinner fa-spin" v-if="loading"></i>
            <button class="btn" @click="editGroup()" v-else>Edit Group</button>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import SelectBox from '../layouts/form/SelectBox'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "GroupEdit",
        props: ['group'],
        components: {
            'select-box': SelectBox,
            'input-box': InputBox,
        },
        data(){
            return {
                name: '',
                who_can_see: {},
                who_can_pay: {},

                who_can_options: [
                    { value:'everyone', name:'Everyone', icon:'far fa-globe-europe' },
                    { value:'friends', name:'Friends', icon:'far fa-users' },
                    { value:'only_me', name:'Only Me', icon:'far fa-user' },
                ],

                access_token: '',
                error: '',
                loading: false,
            }
        },
        mounted(){

        },
        watch: { 
            group: function(newVal,oldVal) {
                this.getGroup();
            }
        },
        methods: {

            async getGroup(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: `/api/v1/groups/${this.group.data.id}`,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.name = response.data.name
                    switch(response.data.who_can_see){
                        case 'Everyone': this.who_can_see = { value:'everyone', name:'Everyone', icon:'far fa-globe-europe' }; break;
                        case 'Friends': this.who_can_see = { value:'friends', name:'Friends', icon:'far fa-users' }; break;
                        case 'Only Me': this.who_can_see = { value:'only_me', name:'Only Me', icon:'far fa-user' }; break;
                    }
                    switch(response.data.who_can_pay){
                        case 'Everyone': this.who_can_pay = { value:'everyone', name:'Everyone', icon:'far fa-globe-europe' }; break;
                        case 'Friends': this.who_can_pay = { value:'friends', name:'Friends', icon:'far fa-users' }; break;
                        case 'Only Me': this.who_can_pay = { value:'only_me', name:'Only Me', icon:'far fa-user' }; break;
                    }
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },

            async editGroup(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('_method','put');
                form_data.append('name',this.name);
                form_data.append('who_can_see',this.who_can_see.value);
                form_data.append('who_can_pay',this.who_can_pay.value);

                this.loading = true;
                axios({
                    url: `/api/v1/groups/${this.group.data.id}`,
                    method: 'post',
                    data: form_data,
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$parent.$emit('close');
                    this.$parent.$emit('edit_value',{
                        index: this.group.index,
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