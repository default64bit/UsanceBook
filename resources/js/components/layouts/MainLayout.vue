<template>
    <div class="main_body">

        <main-header v-if="showHeader"></main-header>
        <top-menu v-if="logged_in"></top-menu>
        <div class="main_content">
            <slot class="flex flex-col justify-center items-center h-full" name="content"></slot>
        </div>
        <main-footer v-if="showFooter"></main-footer>

    </div>
</template>

<script>
    import Header from './Header'
    import Footer from './Footer'
    import TopMenu from './TopMenu'

    export default {
        name: "MainLayout",
        props:['header','footer'],
        components: {
            'main-header': Header,
            'main-footer': Footer,
            'top-menu': TopMenu,
        },
        data(){
            return{
                showHeader: true,
                showFooter: true,
                logged_in: false,
            }
        },
        mounted(){
            this.showHeader = this.header==false ? false : true;
            this.showFooter = this.footer==false ? false : true;
        },
    }
</script>

<style scoped>

</style>