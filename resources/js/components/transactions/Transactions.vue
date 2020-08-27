<template>
     <div class="flex flex-col justify-center items-center">
        <div class="transactions" v-if="!loading">
            <transactions-info></transactions-info>
            <transactions-table ref="transactions_table"
                @add="is_add_dialog_open=true"
                @edit="editCalled"
                @delete="deleteCalled"
            ></transactions-table>
        </div>
        <loading v-else></loading>

        <pop-up-dialog :open.sync="is_add_dialog_open" @close="is_add_dialog_open=false" @new_value="new_value" title="Add New Transaction">
            <create slot="form"></create>
        </pop-up-dialog>

        <pop-up-dialog :open.sync="is_edit_dialog_open" @close="is_edit_dialog_open=false" @edit_value="edit_value" title="Edit Transaction">
            <edit slot="form" :transaction="transaction_to_edit"></edit>
        </pop-up-dialog>

        <pop-up-dialog :open.sync="is_delete_dialog_open" @close="is_delete_dialog_open=false" @delete_value="delete_value" title="Delete Transaction">
            <delete slot="form" :transaction="transaction_to_delete"></delete>
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
    import Edit from './Edit'
    import Delete from './Delete'

    export default {
        name: 'Transactions',
        components: {
            'loading': Loading,
            'pop-up-dialog': PopUpDialog,

            'transactions-info': TransactionsInfo,
            'transactions-table': TransactionsTable,
            'create': Create,
            'edit': Edit,
            'delete': Delete,
        },
        data(){
            return {
                loading: true,
                access_token: null,

                is_add_dialog_open: false,
                is_edit_dialog_open: false,
                is_delete_dialog_open: false,

                transaction_to_delete: {},
                transaction_to_edit: {},
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
            new_value(value){
                this.$emit('new_value',value);
            },
            edit_value(value){
                this.$emit('edit_value',value);
            },
            delete_value(value){
                this.$emit('delete_value',value);
            },

            editCalled(transaction){
                this.is_edit_dialog_open = true;
                this.transaction_to_edit = transaction;
            },
            deleteCalled(transaction){
                this.is_delete_dialog_open = true;
                this.transaction_to_delete = transaction;
            },
        },
    }
</script>

<style>

</style>