<template>
    <div id="main">
        <Header/>
        <div id="custom-wrapper">
            <Sidebar/>
            <div id="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 no-padding">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
