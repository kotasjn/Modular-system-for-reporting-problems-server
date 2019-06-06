<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 no-padding">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        {{ welcome }}
                    </div>
                </div>
            </div>
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