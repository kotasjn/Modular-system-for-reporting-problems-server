<template>
    <div class="card">
        <div class="card-header">Detail území</div>
        <div class="card-body">

            necoaasdasd

        </div>
    </div>
</template>

<script>
    export default {
        name: "TerritoryView",
        created() {
            if (this.territories.length) {
                this.territory = this.territories.find((territory) => territory.id === this.$route.params.id);
            } else {
                axios.get(`/api/territories/${this.$route.params.id}`)
                    .then((response) => {
                        this.territory = response.data.territory
                    });
            }
        },
        data() {
            return {
                territory: null
            };
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            territories () {
                return this.$store.getters.territories;
            }
        }
    }
</script>

<style scoped>

</style>