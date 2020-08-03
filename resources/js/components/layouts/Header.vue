<template>
    <header class="header">
        <div class="left_side">
            <div class="date">
                <span>
                    <b class="day_number">25</b>
                </span>
                <span>
                    <b class="day_name">Friday</b>
                    <b class="month">March</b>
                    <b class="year">2020</b>
                </span>
            </div>
        </div>
        <div class="logo">
            <h1>Usance Book</h1>
        </div>
        <span class="btn theme_switch" @click="toggleTheme()">
            <i class="fas" :class="{'fa-moon dark':theme=='light', 'fa-sun-haze light':theme=='dark'}"></i>
        </span>
        <div class="right_side">
            <router-link to="/register">Register</router-link>
        </div>
    </header>
</template>

<script>
    export default {
        name: "Header",
        data(){
            return {
                theme: 'light',
            }
        },
        created(){

        },
        mounted(){
            
        },
        methods: {
            toggleTheme(){
                this.theme = this.theme=='dark' ? 'light' : 'dark';
            }
        },
    }
</script>

<style>

</style>