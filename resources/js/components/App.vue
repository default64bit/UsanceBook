<template>
    <main-layout>
        <div slot="content">
            <router-view></router-view>
        </div>
    </main-layout>
</template>

<script>
    import MainLayout from './layouts/MainLayout'

    export default {
        name: "App",
        components:{
            'main-layout': MainLayout,
        },
    }
</script>

<style scoped>

</style>