<template>
    <div class="card" v-if="report">
        <div class="card-header">Detail podnětu</div>
        <div class="card-body">
            <table>
                <tr>
                    <th>ID</th>
                    <td>{{ report.id }}</td>
                </tr>
                <tr>
                    <th>Titulek</th>
                    <td>{{ report.title }}</td>
                </tr>
                <tr>
                    <th>Kategorie</th>
                    <td>{{ report.category_id }}</td>
                </tr>
                <tr>
                    <th>Řešitel</th>
                    <td>{{ report.responsible_id }}</td>
                </tr>
                <tr>
                    <th>Poznámka uživatele</th>
                    <td>{{ report.userNote }}</td>
                </tr>
            </table>

            <router-link to="/reports">Zpět</router-link>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Report",
        created() {
            axios.get(`/api/territories/${this.$store.getters.currentTerritory.id}/reports/${this.$route.params.id}`)
                .then((response) => {
                    this.report = response.data.report
                })
        },
        data() {
            return {
                report: null
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        methods: {
            onClick(i) {
                this.index = i;
            }
        }
    }
</script>

<style scoped>

</style>