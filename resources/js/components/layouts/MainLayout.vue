<template>
    <div class="main_body">

        <main-header v-if="showHeader"></main-header>
        <div class="wrapper">
            <div class="main_content">
                <slot name="content"></slot>
            </div>
        </div>
        <main-footer v-if="showFooter"></main-footer>

    </div>
</template>

<script>
    import Header from './Header'
    import Footer from './Footer'

    export default {
        name: "MainLayout",
        props:['header','footer'],
        components: {
            'main-header': Header,
            'main-footer': Footer,
        },
        mounted(){
            this.showHeader = this.header==false ? false : true;
            this.showFooter = this.footer==false ? false : true;
        },
        data(){
            return{
                showHeader: true,
                showFooter: true,
            }
        },
    }
</script>

<style scoped>

</style>