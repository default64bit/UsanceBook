<template>
    <div class="select_box multi_select_box" :class="{'z':open}" @mouseup="toggle_menu(true)">
        <div class="box">
            <span name="placeholder" :class="{'up':Object.keys(values).length>0}">{{placeholder}}</span>
            <div name="value" v-if="values">
                <span v-for="(value,i) in values" :key="i">
                    <b class="fas fa-times" @click="removeOption(value)"></b> {{value.name}}
                </span>
            </div>
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
        name: "MultiSelectBox",
        props: ['values','placeholder','options','searchable'],
        data(){
            return {
                open: false,
                can_close: true,

                timeout: '',
                search_query: '',
                selected_values: {},
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

            selectOption(option){
                if(this.selected_values[option.value]){
                    delete this.selected_values[option.value];
                }else{ this.selected_values[option.value] = option; }
                this.$emit('update:values',this.selected_values);
                this.open = false;
            },

            removeOption(option){
                if(this.selected_values[option.value]){
                    delete this.selected_values[option.value];
                }
                this.$emit('update:values',this.selected_values);
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