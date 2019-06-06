<template>
    <div id="main">
        <Header/>
        <div id="custom-wrapper">
            <Sidebar/>
            <div id="content">
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

    #content {
        width: 100%;
        margin-top: 55px;
        padding: 1rem;
    }


</style>
