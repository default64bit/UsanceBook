<template>
    <div class="flex flex-col justify-center items-center">
        <div class="friends" v-if="!loading">
            <friends-table ref="friends_table"
                @add="is_add_dialog_open=true"
                @cancel="cancelCalled"
                @delete="deleteCalled"
            ></friends-table>
            <div class="flex flex-col justify-center items-center" style="margin-inline-start:1rem;">
                <friends-info></friends-info>
                <friends-requests></friends-requests>
            </div>
        </div>
        <loading v-else></loading>

        <pop-up-dialog :open.sync="is_add_dialog_open" @close="is_add_dialog_open=false" @new_value="new_value" title="Find New Friends">
            <find-new-friends slot="form"></find-new-friends>
        </pop-up-dialog>

        <pop-up-dialog :open.sync="is_cancel_dialog_open" @close="is_cancel_dialog_open=false" @cancel_value="cancel_value" title="Cancel Friend Request">
            <cancel-request slot="form" :friend="friend_to_cancel"></cancel-request>
        </pop-up-dialog>

        <pop-up-dialog :open.sync="is_delete_dialog_open" @close="is_delete_dialog_open=false" @delete_value="delete_value" title="Remove Friendship">
            <delete-friendship slot="form" :friend="friend_to_delete"></delete-friendship>
        </pop-up-dialog>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import {mapGetters,mapActions} from 'vuex'
    import Loading from '../layouts/Loading'
    import PopUpDialog from '../layouts/PopUpDialog'

    import FriendsInfo from './FriendsInfo'
    import FriendsRequests from './FriendsRequests'
    import FriendsTable from './FriendsTable'
    import FindNewFriends from './FindNewFriends'
    import CancelRequest from './CancelRequest'
    import DeleteFriendship from './DeleteFriendship'

    export default {
        name: 'Friends',
        components: {
            'loading': Loading,
            'pop-up-dialog': PopUpDialog,

            'friends-info': FriendsInfo,
            'friends-requests': FriendsRequests,
            'friends-table': FriendsTable,
            'find-new-friends': FindNewFriends,
            'cancel-request': CancelRequest,
            'delete-friendship': DeleteFriendship,
        },
        data(){
            return {
                loading: true,
                access_token: null,

                is_add_dialog_open: false,
                is_cancel_dialog_open: false,
                is_delete_dialog_open: false,

                friend_to_delete: {},
                friend_to_cancel: {},
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
            cancel_value(value){
                this.$emit('cancel_value',value);
            },
            delete_value(value){
                this.$emit('delete_value',value);
            },

            cancelCalled(friend){
                this.is_cancel_dialog_open = true;
                this.friend_to_cancel = friend;
            },
            deleteCalled(friend){
                this.is_delete_dialog_open = true;
                this.friend_to_delete = friend;
            },
        },
    }
</script>

<style>

</style>