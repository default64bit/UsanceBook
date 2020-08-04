<template>
    <header class="header">
        <div class="left_side">
            <div class="date">
                <span>
                    <b class="day_number">{{day_number}}</b>
                </span>
                <span>
                    <b class="day_name">{{day_name}}</b>
                    <b class="month">{{month}},</b>
                    <b class="year">{{year}}</b>
                </span>
            </div>
        </div>
        <div class="logo">
            <h2>Usance Book</h2>
        </div>
        <span class="btn theme_switch" @click="toggleTheme()">
            <i class="fas" :class="{'fa-moon dark':theme=='light', 'fa-sun-haze light':theme=='dark'}"></i>
        </span>
        <div class="right_side">
            <router-link to="/register" v-if="$route.name=='login'">Register</router-link>
            <router-link to="/login" v-else>Login</router-link>
        </div>
    </header>
</template>

<script>
    export default {
        name: "Header",
        data(){
            return {
                theme: 'light',

                day_name: '',
                day_number: '',
                month: '',
                year: '',
            }
        },
        created(){

        },
        mounted(){
            this.setDate();
        },
        methods: {
            toggleTheme(){
                this.theme = this.theme=='dark' ? 'light' : 'dark';
            },

            setDate(){
                var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                var today = new Date();

                this.day_name = days[today.getDay()];
                this.day_number = String(today.getDate()).padStart(2,'0');
                this.month = months[today.getMonth()];
                this.year = today.getFullYear();
            },
        },
    }
</script>

<style>

</style>