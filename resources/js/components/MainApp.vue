<template>
    <v-app>
        <div id="main">
            <Header></Header>
            <div id="custom-wrapper">
                <Sidebar/>
                <div id="content" v-bind:class="[ currentUser ? 'isLogged' : 'isNotLogged' ]">

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
        mounted() {
            console.log('Component mounted.')
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        components: {
            Sidebar,
            Header
        }
    }
</script>


<style scoped>

    #custom-wrapper {
        display: flex;
    }

    #content {
        margin-top: 55px;
        padding: 1rem;
    }

    #content.isLogged {
        position: absolute;
        left: 225px;
        right: 0;
    }

    #content.isNotLogged {
        position: relative;
        margin: 55px auto;
    }

</style>
