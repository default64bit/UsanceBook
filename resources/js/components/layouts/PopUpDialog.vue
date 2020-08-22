<template>
    <div class="dialog" :class="{'hidden':!open}">
        <div class="dialog_box">
            <div class="header">
                <h3>{{title}}</h3>
                <button @click="closeDialog()"><i class="far fa-times"></i></button>
            </div>
            <slot name="form"></slot>
        </div>
        <div class="overlay" @click="closeDialog()"></div>
    </div>
</template>

<script>
    export default {
        name: "PopUpDialog",
        props: ['title','open'],
        data(){
            return {
                
            }
        },
        mounted(){

        },
        methods: {
            closeDialog(){
                this.$emit('update:open',false);
            },
        },
    }
</script>

<style>

</style>