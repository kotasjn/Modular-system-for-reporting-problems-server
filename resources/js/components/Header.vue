<template>
    <nav class="navbar navbar-expand bg-dark static-top">
        <router-link class="navbar-brand" to="/">Bakalářka</router-link>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <template v-if="!currentUser">
                    <li>
                        <router-link to="/login" class="nav-link">Login</router-link>
                    </li>
                    <li>
                        <router-link to="/register" class="nav-link">Register</router-link>
                    </li>
                </template>
                <template v-else>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            {{ currentUser.name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

    </nav>
</template>

<script>
    export default {
        name: "app-header",
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>

<style scoped>

    a {
        color: white;
    }

    .bg-dark {
        background-color: #00796B !important;
    }

    .navbar {
        position: fixed;
        width: 100%;
        height: 55px;
        z-index: 1;
        box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.3);
    }

    #navbarSupportedContent {
        width:100%;
    }

    #navbarSupportedContent {
        text-align: right;
    }

    #navbarSupportedContent li {
        display: inline-block;
        text-align: center;
    }

    .dropdown-menu {
        min-width: auto;
    }

    .dropdown-menu a {
        color: #212121;
    }


</style>