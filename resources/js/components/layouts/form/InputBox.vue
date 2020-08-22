<template>
    <div class="input_box">
        <input ref="input" :type="type" :value="value" @input="updateValue($event.target.value)" dir="auto" v-mask="mask" v-if="mask!=''">
        <input ref="input" :type="type" :value="value" @input="updateValue($event.target.value)" dir="auto" v-else>
        <span name="placeholder" :class="{'up':value}" @click="focus()">{{placeholder}}</span>
    </div>
</template>

<script>
    import {mask} from 'vue-the-mask'

    export default {
        name: "InputBox",
        props: {
            value: {
                type: String,
            },
            placeholder: {
                type: String,
            },
            type: {
                type: String,
            },
            mask: {
                default: '',
                type: String,
            },
            
        },
        data(){
            return {

            }
        },
        created(){

        },
        directives: {
            mask: mask,
        },
        methods:{
            updateValue(value){
                this.$emit('input',value);
            },

            focus(){
                this.$refs.input.focus();
            },
        }
    }
</script>

<style>

</style>