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
            <div v-if="!isLoggedIn">
                <router-link to="/register" v-if="$route.name=='login'">Register</router-link>
                <router-link to="/login" v-if="$route.name=='register'">Login</router-link>
                <!-- <a href="javascript:;" @click="login()" v-else>Login</a> -->
            </div>
            <div class="user" v-else>
                <span>{{userData.name}} {{userData.family}}</span>
                <img :src="userData.avatar" alt="">
            </div>
        </div>
    </header>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    
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
        async created(){
            let access_token = null;
            await token.getToken().then((value)=>{ access_token = value; });
            if(access_token != null){
                await this.getUser(access_token);
            }
        },
        mounted(){
            this.setDate();
        },
        computed: {
            ...mapGetters(['userData','isLoggedIn']),
        },
        methods: {
            ...mapActions(['getUser']),

            // getUser(){
            //     let access_token = this.$cookies.get('access_token');
            //     axios({
            //         url: '/api/v1/user',
            //         method: 'get',
            //         headers: {
            //             'Authorization': `Bearer ${access_token}`,
            //             'Content-Type': 'application/json'
            //         },
            //     }).then(response=>{
            //         this.logged_in = true;
            //         this.avatar = response.data.avatar;
            //         this.name = response.data.name;
            //         this.family = response.data.family;
            //     }).catch(error=>{});
            // },

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