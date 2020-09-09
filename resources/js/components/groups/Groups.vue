<template>
     <div class="flex flex-col justify-center items-center">
        <div class="groups" v-if="!loading">
            <groups-table ref="groups_table"
                @add="is_add_dialog_open=true"
                @edit="editCalled"
                @delete="deleteCalled"
            ></groups-table>
        </div>
        <loading v-else></loading>

        <pop-up-dialog :open.sync="is_add_dialog_open" @close="is_add_dialog_open=false" @new_value="new_value" title="Add New Group">
            <create slot="form"></create>
        </pop-up-dialog>

        <pop-up-dialog :open.sync="is_edit_dialog_open" @close="is_edit_dialog_open=false" @edit_value="edit_value" title="Edit Group">
            <edit slot="form" :group="group_to_edit"></edit>
        </pop-up-dialog>

        <pop-up-dialog :open.sync="is_delete_dialog_open" @close="is_delete_dialog_open=false" @delete_value="delete_value" title="Delete Group">
            <delete slot="form" :group="group_to_delete"></delete>
        </pop-up-dialog>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'
    import PopUpDialog from '../layouts/PopUpDialog'

    import GroupsTable from './GroupsTable'
    import Create from './Create'
    import Edit from './Edit'
    import Delete from './Delete'

    export default {
        name: 'Groups',
        components: {
            'loading': Loading,
            'pop-up-dialog': PopUpDialog,

            'groups-table': GroupsTable,
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

                group_to_delete: {},
                group_to_edit: {},
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

            editCalled(group){
                this.is_edit_dialog_open = true;
                this.group_to_edit = group;
            },
            deleteCalled(group){
                this.is_delete_dialog_open = true;
                this.group_to_delete = group;
            },
        },
    }
</script>

<style>

</style>