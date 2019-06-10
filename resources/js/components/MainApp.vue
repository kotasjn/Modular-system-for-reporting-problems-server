<template>
    <v-app>
        <div id="main">
            <Header/>
            <div id="custom-wrapper">
                <Sidebar/>
                <div id="content">

                    <router-view></router-view>

                </div>
            </div>
        </div>
    </v-app>
</template>

<script>
    import Header from "./Header";
    import Sidebar from "./Sidebar";

    export default {
        name: "main-app",
        components: {
            Sidebar,
            Header,
            currentUser() {
                return this.$store.getters.currentUser
            }
        },
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
        position: absolute;
        left: 225px;
        right: 0;
        margin-top: 55px;
        padding: 1rem;
    }

</style>
