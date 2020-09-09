<template>
    <div class="form transaction_delete">
        <div class="input_group">
            <h3 class="text-2xl" v-if="group.data">
                Do you want to delete this group? <br>
                <span class="text-gray-700 bg-gray-200 px-1">{{group.data.name}}</span>
            </h3>
        </div>

        <b class="error text-lg text-red-500" v-if="error!=''">{{error}}</b>

        <div class="loading flex flex-col justify-center items-start h-16">
            <i class="text-2xl fad fa-spinner fa-spin" v-if="loading"></i>
            <div v-else>
                <button class="btn delete" @click="submitDelete()">Delete Group</button>
                <button class="btn" @click="close()">No</button>
            </div>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import InputBox from '../layouts/form/InputBox'

    export default {
        name: "GroupDelete",
        props: ['group'],
        components: {
            'input-box': InputBox,
        },
        data(){
            return {
                name: '',
                amount: '',

                access_token: '',
                error: '',
                loading: false,
            }
        },
        methods: {
            async submitDelete(){
                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: `/api/v1/groups/${this.group.data.id}`,
                    method: 'delete',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    this.$parent.$emit('close');
                    this.$parent.$emit('delete_value',this.group);
                    this.loading = false;
                }).catch(error=>{
                    if(error.response){
                        this.error = error.response.data.error
                    }
                    this.loading = false;
                });
            },

            close(){
                this.$parent.$emit('close');
            },
        }
    }
</script>

<style>

</style>