<template>
    <div class="card">
        <div class="card-header">Detail území</div>

        <div class="card-body">

            <img :src="currentTerritory.avatarURL" :alt="currentTerritory.name" class="avatar">

            <table>
                <tr>
                    <th class="subheading">Název:</th>
                    <td class="body-1">{{ currentTerritory.name }}</td>
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
                    <td class="body-1">{{ currentTerritory.waiting_reports }}</td>
                </tr>
                <tr>
                    <th class="subheading">Schválené podněty:</th>
                    <td class="body-1">{{ currentTerritory.accepted_reports }}</td>
                </tr>
                <tr>
                    <th class="subheading">Vyřešené podněty:</th>
                    <td class="body-1">{{ currentTerritory.solved_reports }}</td>
                </tr>
                <tr>
                    <th class="subheading">Zamítnuté podněty:</th>
                    <td class="body-1">{{ currentTerritory.rejected_reports }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TerritoryView",
        computed: {
            admin() {
                for (let i = 0; i < this.currentTerritory.employees.length; i++) {
                    if (this.currentTerritory.employees[i].id === this.currentTerritory.admin_id)
                        return this.currentTerritory.employees[i].name;
                }
            },
            approver() {
                if (this.currentTerritory.approver_id) {
                    for (let i = 0; i < this.currentTerritory.employees.length; i++) {
                        if (this.currentTerritory.employees[i].id === this.currentTerritory.approver_id)
                            return this.currentTerritory.employees[i].name;
                    }
                }
            },
            currentUser() {
                return this.$store.getters.currentUser;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
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