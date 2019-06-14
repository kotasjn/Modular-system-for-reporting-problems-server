<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="card">
        <div class="card-header">Podněty</div>

        <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

        <v-data-table :headers="headers"
                      :items="reports"
                      class="elevation-1">
            <template v-slot:items="props">
                <td class="text-xs-left">{{ props.item.title }}</td>
                <td class="text-xs-center" v-if="props.item.category_id === 1">Zeleň</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 2">Odpad</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 3">Doprava</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 4">Mobiliář</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 5">Veřejné osvětlení</td>
                <td class="text-xs-center" v-else></td>

                <td v-if="props.item.responsible_user_id != null" class="text-xs-center">{{
                    getName(props.item.responsible_user_id) }}
                </td>
                <td v-else class="text-xs-center">?</td>

                <td class="text-xs-center">{{ props.item.created_at }}</td>
                <td class="text-xs-center">

                    <font-awesome-icon v-if="props.item.state === 0" icon="question-circle" style="color: yellow"
                                       title="Čeká na schválení"/>
                    <font-awesome-icon v-if="props.item.state === 1" icon="sync-alt" style="color: mediumblue"
                                       title="Schváleno"/>
                    <font-awesome-icon v-if="props.item.state === 2" icon="check-circle" style="color: forestgreen"
                                       title="Vyřešeno"/>
                    <font-awesome-icon v-if="props.item.state === 3" icon="times-circle" style="color: red"
                                       title="Zamítnuto"/>

                </td>
                <td class="text-xs-center">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>
    </div>

</template>

<script>
    export default {
        name: 'ReportList',
        data() {
            return {
                isLoading: true,
                headers: [
                    {
                        text: 'Titulek',
                        align: 'left',
                        sortable: false,
                        value: 'title'
                    },
                    {
                        text: 'Kategorie',
                        value: 'category_id',
                        align: 'center'
                    },
                    {
                        text: 'Zodpovědnost',
                        value: 'responsible_user_id',
                        align: 'center'
                    },
                    {
                        text: 'Vytvořeno',
                        value: 'created_at',
                        align: 'center'
                    },
                    {
                        text: 'Stav',
                        value: 'state',
                        align: 'center'
                    },
                    {
                        text: 'Detail',
                        align: 'center',
                        sortable: false
                    }
                ],
            }
        },
        mounted() {
            if (this.reports.length) {
                this.isLoading = false;
                return;
            }

            this.$store.dispatch('getReports');
        },
        methods: {
            showDetail(id) {
                this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/reports/${id}`);
            },
            getName(id) {

                let employees = this.currentTerritory.employees;

                for (let i = 0; i < employees.length; i++) {
                    if (employees[i].id === id) return employees[i].name;
                }
            }
        },
        watch: {
            reports() {
                this.isLoading = false;
            },
        },
        computed: {
            reports() {
                return this.$store.getters.reports;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
        }
    }
</script>

<style scoped>


</style>
