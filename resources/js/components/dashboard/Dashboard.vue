<template>
    <div class="flex flex-col justify-center items-center">
        <div class="dashboard" v-if="!loading">
            <chart></chart>
            <cards></cards>
            <transactions></transactions>
            <groups></groups>
            <div style="grid-area:right;">
                <friends></friends>
                <div name="ad">Your Ad Here</div>
                <div name="subscription">
                    <h3>Never Miss Any News</h3>
                    <input type="text" placeholder="Email Address">
                    <button class="btn">Subscribe To Newsletter</button>
                </div>
            </div>
        </div>

        <loading v-else></loading>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'

    import Chart from './Chart'
    import Cards from './Cards'
    import Transactions from './Transactions'
    import Groups from './Groups'
    import Friends from './Friends'

    export default {
        name: 'Dashboard',
        components: {
            'loading': Loading,
            'chart': Chart,
            'cards': Cards,
            'transactions': Transactions,
            'groups': Groups,
            'friends': Friends,
        },
        data(){
            return {
                loading: true,
                access_token: null,
            }
        },
        async created(){
            await token.getToken().then((value)=>{ this.access_token = value; });
            if(this.access_token == null){
                this.$router.push('/login');
            }
            this.loading = false;
        },
        computed: {
            ...mapGetters(['isLoggedIn']),
        },
        watch: {
            isLoggedIn(newValue,oldValue){
                if(!newValue){
                    this.$router.push('/login');
                }else{ this.loading = false; }
            },
        },
        methods: {

        },
    }
</script>

<style>

</style>