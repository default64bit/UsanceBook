<template>
    <div class="date_range_picker select_box" :class="{'z':open}" @mouseup="toggle_menu(true)">
        <div class="box">
            <span name="placeholder" :class="{'up':value.from_date}">{{placeholder}}</span>
            <span name="value" v-if="value.from_date">{{value.from_date}} - {{value.to_date}}</span>
        </div>
        <i class="fas fa-chevron-down"></i>
        <div class="list" v-if="open" @mouseenter="closeable(false)" @mouseleave="closeable(true)">
            <div class="px-2 flex justify-center items-center" name="from">
                <label class="text-sm">From Date :</label>
                <input type="text" placeholder="YYYY/MM/DD" v-mask="mask" v-model="from_date">
            </div>
            <div class="px-2 flex justify-center items-center" name="to">
                <label class="text-sm">To Date :</label>
                <input type="text" placeholder="YYYY/MM/DD" v-mask="mask" v-model="to_date">
            </div>
            <button class="btn" @click="set()">Set</button>
        </div>
    </div>
</template>

<script>
    import {mask} from 'vue-the-mask'

    export default {
        name: "SelectBox",
        props: ['value','placeholder'],
        data(){
            return {
                open: false,
                can_close: true,

                from_date: this.value.from_date,
                to_date: this.value.to_date,
                mask: '####/##/##',
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
        directives: {
            mask: mask,
        },
        methods: {
            toggle_menu(state){
                this.open = state;
            },

            set(){
                let value = {
                    from_date: this.from_date,
                    to_date: this.to_date,
                };
                this.$emit('update:value',value);
                this.open = false;
            },

            closeable(state){
                this.can_close = state;
            },
        }
    }
</script>

<style>

</style>