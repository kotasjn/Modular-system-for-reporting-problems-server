<template>
    <ul class="sidebar navbar-nav">
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
            <a class="nav-link" href="/">
                <font-awesome-icon icon="tachometer-alt"/>
                <span>Přehled</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/reports">
                <font-awesome-icon icon="table"/>
                <span>Podněty</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/statistics">
                <font-awesome-icon icon="chart-area"/>
                <span>Statistiky</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/users">
                <font-awesome-icon icon="user"/>
                <span>Uživatelé</span></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <font-awesome-icon icon="folder"/>
                <span>Moduly</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                <h6 class="dropdown-header"></h6>
                <a class="dropdown-item" href="">Modul 1</a>
                <a class="dropdown-item" href="">Modul 2</a>
                <a class="dropdown-item" href="">Modul 3</a>
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">Ostatní:</h6>
                <a class="dropdown-item" href="">Nastavení</a>
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
        name: "Sidebar"
    }
</script>

<style scoped>

    a {
        color: white;
    }

    .sidebar {
        width: 225px !important;
        background-color: #00796B;
    }

    navbar-nav {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none
    }

    .navbar-nav .nav-link {
        padding-right: 0;
        padding-left: 0
    }

    .navbar-nav .dropdown-menu {
        position:static;
        float:none
    }

    .sidebar .nav-item .nav-link {
        display: block;
        width: 100%;
        text-align: left;
        padding: 1rem;
        color: rgba(255, 255, 255, 0.5);
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

</style>