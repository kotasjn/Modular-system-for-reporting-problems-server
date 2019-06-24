<template>
    <div class="row justify-content-center">
        <div class="col-md-12 no-padding">

            <bug-new></bug-new>
            <bugs-list></bugs-list>

        </div>
    </div>
</template>

<script>
    import BugNew from "./BugNew";
    import BugsList from "./BugList";
    export default {
        name: "BugMain",
        components: {BugsList, BugNew}
    }
</script>

<style scoped>

</style>