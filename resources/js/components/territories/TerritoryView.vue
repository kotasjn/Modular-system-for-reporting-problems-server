<template>
    <div class="card">
        <div class="card-header">Detail území</div>

        <div class="card-body" v-if="territory">

            <img :src="territory.avatarURL" :alt="territory.name" class="avatar">

            <table>
                <tr>
                    <th class="subheading">Název:</th>
                    <td class="body-1">{{ territory.name }}</td>
                </tr>
                <tr>
                    <th class="subheading">Admin:</th>
                    <td class="body-1">{{ admin }}</td>
                </tr>
                <tr v-if="approver">
                    <th class="subheading">Schvalovatel:</th>
                    <td class="body-1">{{ approver }}</td>
                </tr>
                <tr>
                    <th class="subheading">Čekající podněty:</th>
                    <td class="body-1">{{ territory.waiting_reports }}</td>
                </tr>
                <tr>
                    <th class="subheading">Schválené podněty:</th>
                    <td class="body-1">{{ territory.accepted_reports }}</td>
                </tr>
                <tr>
                    <th class="subheading">Vyřešené podněty:</th>
                    <td class="body-1">{{ territory.solved_reports }}</td>
                </tr>
                <tr>
                    <th class="subheading">Zamítnuté podněty:</th>
                    <td class="body-1">{{ territory.rejected_reports }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TerritoryView",
        created() {
            this.getTerritory();
        },
        data() {
            return {
                territory: null,
                isLoading: true,
            };
        },
        methods: {
            getTerritory() {
                axios.get(`/api/territories/${this.$route.params.idTerritory}`)
                    .then((response) => {
                        this.territory = response.data.territory;
                    })
                    .catch((error) => {
                        if (error.response.status === 403) {
                            this.$dialog.notify.error('Nemáte oprávnění k zobrazení detailů obce');
                        } else if (error.response.status === 404) {
                            this.$dialog.notify.error('Neexistující obec');
                        } else {
                            this.$dialog.notify.error('Nepodařilo se načíst detail obce');
                        }

                    });
            }
        },
        watch: {
            '$route.params.idTerritory': function () {
                this.getTerritory()
            }
        },
        computed: {
            admin() {
                if (this.territory) {
                    for (let i = 0; i < this.territory.employees.length; i++) {
                        if (this.territory.employees[i].id === this.territory.admin_id)
                            return this.territory.employees[i].name;
                    }
                }
            },
            approver() {
                if (this.territory && this.territory.approver_id) {
                    for (let i = 0; i < this.territory.employees.length; i++) {
                        if (this.territory.employees[i].id === this.territory.approver_id)
                            return this.territory.employees[i].name;
                    }
                }
            },
            currentUser() {
                return this.$store.getters.currentUser;
            }
        }
    }
</script>

<style scoped>

    table > tr > td {
        padding-top: 0.5em;
        padding-bottom: 0.5em;
    }

    table > tr > th {
        padding-right: 0.5em;
        padding-top: 0.5em;
        padding-bottom: 0.5em;
        vertical-align: top;
    }

    .avatar {
        display: block;
        width: 150px;
        height: 150px;
        border-radius: 10%;
        margin: .5em auto .5em auto;
    }

</style>