<template>
    <div class="form card_create">
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
            <button class="btn" @click="addGroup()" v-else>Create Group</button>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import SelectBox from '../layouts/form/SelectBox'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "GroupCreate",
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
        methods: {

            async addGroup(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                let form_data = new FormData();
                form_data.append('name',this.name);
                form_data.append('who_can_see',this.who_can_see.value);
                form_data.append('who_can_pay',this.who_can_pay.value);

                this.loading = true;
                axios({
                    url: '/api/v1/groups',
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