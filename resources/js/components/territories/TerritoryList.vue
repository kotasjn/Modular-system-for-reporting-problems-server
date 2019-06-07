<template>
    
</template>

<script>
    export default {
        name: 'TerritoryList',
        computed: {
            territories() {
                return this.$store.getters.territories;
            }
        }

    }
</script>

<style scoped>

</style>