<template>
    <ul class="sidebar navbar-nav" v-if="currentUser">
        <li class="nav-item territory" v-if="currentTerritory">
            <router-link class="nav-link" :to="`/territories/${currentTerritory.id}`">
                <div class="text-center">
                    <img :src="`${currentTerritory.avatarURL}`" alt="Avatar" class="avatar"/>
                    <div class="territory-name">{{currentTerritory.name}}</div>

                    <div class="reports">
                        <div class="item">
                            <div class="item-header">
                                ČEKAJÍCÍ
                            </div>
                            <div class="item-number">
                                {{countOfWaitingReports}}
                            </div>
                        </div>
                        <div class="item">
                            <div class="item-header">
                                VYŘEŠENO
                            </div>
                            <div class="item-number">
                                {{countOfSolvedReports}}
                            </div>
                        </div>
                        <div class="item">
                            <div class="item-header">
                                CELKEM
                            </div>
                            <div class="item-number">
                                {{totalCountOfReports}}
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </li>

        <!--
        <li class="nav-item active">
            <router-link class="nav-link" to="/">
                <font-awesome-icon icon="tachometer-alt"/>
                <span>Přehled</span>
            </router-link>
        </li>
        -->

        <li class="nav-item">
            <router-link class="nav-link" :to="`/territories/${currentTerritory.id}/reports`">
                <font-awesome-icon icon="table"/>
                <span>Podněty</span>
            </router-link>
        </li>
        <!--
        <li class="nav-item">
            <router-link class="nav-link" to="/statistics">
                <font-awesome-icon icon="chart-area"/>
                <span>Statistiky</span>
            </router-link>
        </li>
        -->
        <li class="nav-item">
            <router-link class="nav-link" :to="`/territories/${currentTerritory.id}/employees`">
                <font-awesome-icon icon="user"/>
                <span>Zaměstnanci</span>
            </router-link>
        </li>
        <li class="nav-item" v-if="currentUser.isAdmin">
            <router-link class="nav-link" :to="`/territories/${currentTerritory.id}/modules`">
                <font-awesome-icon icon="folder"/>
                <span>Moduly</span>
            </router-link>
        </li>
        <li class="nav-item last">
            <router-link class="nav-link" to="/bugs">
                <font-awesome-icon icon="bug"/>
                <span>Nahlásit chybu</span>
            </router-link>
        </li>

    </ul>
</template>

<script>
    export default {
        name: "Sidebar",
        mounted() {
            if (this.currentTerritory !== null && this.currentTerritory.length) {
                return;
            }

            if(this.currentUser) {
                this.$store.dispatch('getTerritory').then(() => {}, error => {
                    this.$dialog.notify.error('Nepodařilo se načíst data obce');
                    console.log(error);
                });
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory
            },
            countOfWaitingReports() {
                return this.$store.getters.currentTerritory.waiting_reports + 0
            },
            countOfSolvedReports() {
                return this.$store.getters.currentTerritory.solved_reports + 0
            },
            totalCountOfReports() {
                const territory = this.$store.getters.currentTerritory;

                return territory.waiting_reports + territory.accepted_reports + territory.solved_reports + territory.rejected_reports;
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
        position: fixed;
        min-width: 225px !important;
        height: calc(100vh - 55px);
        background-color: #00796B;
        margin-top: 55px;
        z-index: 1;
        overflow-y: scroll;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
        scrollbar-width: none;
    }

    .navbar-nav .nav-link {
        padding-right: 0;
        padding-left: 0
    }

    .sidebar .nav-item {
        font-size: 14px;
    }

    .sidebar .nav-item .nav-link {
        display: block;
        width: 100%;
        text-align: left;
        padding: .7rem 1rem .7rem 1rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .sidebar .nav-item .nav-link:hover {
        color: #fff
    }

    .sidebar .nav-item .nav-link span {
        margin-left: .5rem;
        display: inline;
    }

    .sidebar .nav-item:last-child {
        padding-bottom: 30px;
    }

    .territory {
        background-color: #009688;
        margin-bottom: .5rem;
    }

    .avatar {
        vertical-align: middle;
        width: 100px;
        height: 100px;
        border-radius: 10%;
        margin: .5em 0 .5em 0;
    }

    .territory-name {
        margin-bottom: .5em;
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