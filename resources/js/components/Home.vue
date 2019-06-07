<template>
    <div class="card">
        <div class="card-header">Example Component</div>
        <div class="card-body">
            {{ welcome }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "home",
        computed: {
            welcome() {
                return this.$store.getters.welcome
            }
        }
    }
</script>

<style scoped>


</style>