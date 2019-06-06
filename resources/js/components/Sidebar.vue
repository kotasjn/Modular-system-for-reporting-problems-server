<template>
    <ul class="sidebar navbar-nav" v-if="currentUser">
        <li class="nav-item territory">
            <router-link class="nav-link" to="/">
                <div class="text-center">
                    <img src="/storage/images/default-profile.png" alt="Avatar" class="avatar"/>
                    <div class="territory-name">Kroměříž</div>

                    <div class="reports">
                        <div class="item">
                            <div class="item-header">
                                ČEKAJÍCÍ
                            </div>
                            <div class="item-number">
                                5
                            </div>
                        </div>
                        <div class="item">
                            <div class="item-header">
                                VYŘEŠENO
                            </div>
                            <div class="item-number">
                                42
                            </div>
                        </div>
                        <div class="item">
                            <div class="item-header">
                                CELKEM
                            </div>
                            <div class="item-number">
                                105
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </li>

        <li class="nav-item active">
            <router-link class="nav-link" to="/">
                <font-awesome-icon icon="tachometer-alt"/>
                <span>Přehled</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link" to="/reports">
                <font-awesome-icon icon="table"/>
                <span>Podněty</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link" to="/statistics">
                <font-awesome-icon icon="chart-area"/>
                <span>Statistiky</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link" to="/users">
                <font-awesome-icon icon="user"/>
                <span>Uživatelé</span>
            </router-link>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <font-awesome-icon icon="folder"/>
                <span>Moduly</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                <router-link class="nav-link" to="">Modul 1</router-link>
                <router-link class="nav-link" to="">Modul 2</router-link>
                <router-link class="nav-link" to="">Modul 3</router-link>
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Ostatní:</h6>
                <router-link class="nav-link" to="">Nastavení</router-link>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/bugs">
                <font-awesome-icon icon="bug"/>
                <span>Nahlásit chybu</span></a>
        </li>


    </ul>
</template>

<script>
    export default {
        name: "Sidebar",
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

    .sidebar {
        width: 225px !important;
        min-width: 225px !important;
        background-color: #00796B;
        margin-top: 55px;
    }

    .navbar-nav .nav-link {
        padding-right: 0;
        padding-left: 0
    }

    .navbar-nav .dropdown-menu {
        position: static;
        float: none
    }

    .sidebar .nav-item .nav-link {
        display: block;
        width: 100%;
        text-align: left;
        padding: 1rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .sidebar .nav-item .nav-link:hover {
        color: #fff
    }

    .sidebar .nav-item .nav-link span {
        font-size: 1rem;
        margin-left: 0.5rem;
        display: inline;
    }

    .territory {
        background-color: #009688;
    }

    .avatar {
        vertical-align: middle;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        margin: 0.5em 0 0.5em 0;
    }

    .territory-name {
        margin-bottom: 0.5em;
        font-size: 24px;
        color: #fff;
    }

    .reports {
        display: inline;
    }

    .reports .item {
        display: inline-block;
        width: 32%;
        color: #fff;
    }

    .item .item-header {
        font-size: 12px;
    }

    .item .item-number {
        font-size: 14px;
    }

    .dropdown-menu {
        background-color: #009688;
    }

    .dropdown-item {
        color: rgba(255, 255, 255, 0.7);
    }

    .dropdown-header {
        color: rgba(255, 255, 255, 0.7);
    }

    .dropdown-item:hover {
        color: #fff;
        background-color: #009688;
    }

    .dropdown-divider {
        border-color: rgba(255, 255, 255, 0.7);
    }

</style>