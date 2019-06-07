<template>

    <div class="card">
        <div class="card-header">Podněty</div>
        <div class="card-body">

            <table class="table">
                <thead>
                <tr>
                    <th class="centered-cell">ID</th>
                    <th>Titulek</th>
                    <th class="centered-cell">Kategorie</th>
                    <th class="centered-cell">Zodpovědnost</th>
                    <th class="centered-cell">Stav</th>
                    <th class="centered-cell">Detail</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="!reports.length">
                    <tr>
                        <td colspan="6" class="text-center">Nebyly nalezeny žádné záznamy</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="report in reports" :key="report.id">
                        <td class="centered-cell">{{ report.id }}</td>
                        <td>{{ report.title }}</td>
                        <td class="centered-cell">{{ report.category_id }}</td>
                        <td class="centered-cell">{{ report.responsible_id }}</td>
                        <td class="centered-cell">
                            <font-awesome-icon v-if="report.state === '0'" icon="question-circle" style="color: yellow" title="Čeká na schválení"/>
                            <font-awesome-icon v-if="report.state === '1'" icon="sync-alt" style="color: mediumblue" title="Schváleno"/>
                            <font-awesome-icon v-if="report.state === '2'" icon="check-circle" style="color: forestgreen" title="Vyřešeno"/>
                            <font-awesome-icon v-if="report.state === '3'" icon="times-circle" style="color: red" title="Zamítnuto"/>
                        </td>
                        <td class="centered-cell">
                            <router-link :to="`/territories/${currentTerritory.id}/reports/${report.id}`">Zobrazit</router-link>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script>
    export default {
        name: 'ReportList',
        mounted() {
            if (this.reports.length) {
                return;
            }

            this.$store.dispatch('getReports');
        },
        computed: {
            reports() {
                return this.$store.getters.reports;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            }
        }
    }
</script>

<style scoped>

    .centered-cell {
        text-align: center;
    }

</style>
