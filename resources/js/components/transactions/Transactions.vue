<template>
     <div class="flex flex-col justify-center items-center">
        <div class="transactions" v-if="!loading">
            <transactions-info></transactions-info>
            <transactions-table @add="is_add_dialog_open=true"></transactions-table>
        </div>
        <loading v-else></loading>
        <pop-up-dialog :open.sync="is_add_dialog_open" title="Add New Transaction">
            <create slot="form"></create>
        </pop-up-dialog>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'
    import PopUpDialog from '../layouts/PopUpDialog'

    import TransactionsInfo from './TransactionsInfo'
    import TransactionsTable from './TransactionsTable'
    import Create from './Create'

    export default {
        name: 'Transactions',
        components: {
            'loading': Loading,
            'pop-up-dialog': PopUpDialog,

            'transactions-info': TransactionsInfo,
            'transactions-table': TransactionsTable,
            'create': Create,
        },
        data(){
            return {
                loading: true,
                access_token: null,

                is_add_dialog_open: false,
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