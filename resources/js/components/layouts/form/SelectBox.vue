<template>
    <div class="select_box" :class="{'z':open}" @mouseup="toggle_menu(true)">
        <div class="box">
            <span name="placeholder" :class="{'up':value.name}">{{placeholder}}</span>
            <span name="value" v-if="value">{{value.name}}</span>
        </div>
        <i class="fas fa-chevron-down"></i>
        <ul class="list" v-if="open" @mouseenter="closeable(false)" @mouseleave="closeable(true)">
            <div name="search" v-if="searchable">
                <input type="text" placeholder="Search" v-model="search_query" @input="search()">
            </div>
            <li v-for="(option,i) in options" :key="i" @click="selectOption(option)">
                <slot name="option" :option="option"></slot>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "SelectBox",
        props: ['value','placeholder','options','searchable'],
        data(){
            return {
                open: false,
                can_close: true,

                timeout: '',
                search_query: '',
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

            this.timeout = setTimeout(()=>{},0);
        },
        methods: {
            toggle_menu(state){
                this.open = state;
            },

            selectOption(value){
                this.$emit('update:value',value);
                this.open = false;
            },

            closeable(state){
                this.can_close = state;
            },

            search(){
                clearTimeout(this.timeout);
                this.timeout = setTimeout(()=>{
                    this.$emit('search',this.search_query);
                },2000);
            },
        }
    }
</script>

<style>

</style>