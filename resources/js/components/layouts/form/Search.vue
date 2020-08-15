<template>
    <div class="search" :class="{'open':open}"
        @mouseenter="can_close=false" @mouseleave="can_close=true">
        <input ref="search" type="text" :placeholder="placeholder" @input="updateValue($event.target.value)">
        <i class="far fa-search" @click="click()"></i>
    </div>
</template>

<script>
    export default {
        name: 'Search',
        props:['placeholder'],
        data(){
            return {
                open: false,
                can_close: false,
            }
        },
        created(){

        },
        mounted(){
            document.getElementById('app').addEventListener('mousedown',()=>{
                if(this.can_close){
                    this.open = false;
                }
            });
        },
        methods: {
            updateValue(value){
                this.$emit('input',value);
            },
            click(){
                if(this.open){
                    this.$emit('search');
                }else{
                    this.open = true;
                    this.$refs.search.focus();
                }
            },
        },
    }
</script>

<style>

</style>