<template>
    <div id="main">
        <Header/>
        <div id="custom-wrapper">
            <Sidebar/>
            <div class="content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import Header from "./Header";
    import Sidebar from "./Sidebar";

    export default {
        name: "main-app",
        components: {Sidebar, Header},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>


<style scoped>

    #custom-wrapper {
        display: flex;
    }

</style>
