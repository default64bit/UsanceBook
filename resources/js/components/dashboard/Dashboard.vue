<template>
    
</template>

<script>
    export default {
        name: 'Dashboard',
        data(){
            return {
                logged_in: false,
            }
        },
        created(){
            if(!this.logged_in){
                this.$router.push('/login');
            }
        },
        methods: {

        },
    }
</script>

<style>

</style>