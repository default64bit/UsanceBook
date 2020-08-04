<template>
    <div class="login">
        <h1 class="title">Register</h1>

        <button class="btn">
            <img src="/assets/google.svg" alt="">
            <span>Continue With Google</span>
        </button>

        <div class="spacer">OR</div>

        <form @submit.prevent="submit()">
            <input type="email" placeholder="Email Address">
            <input type="password" placeholder="Password">
            <br>
            <button class="btn">Register</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'Register',
        data(){
            return {

            }
        },

    }
</script>

<style>

</style>