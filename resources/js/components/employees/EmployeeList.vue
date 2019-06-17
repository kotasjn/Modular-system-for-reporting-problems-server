<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="card">
        <div class="card-header">Zaměstnanci</div>

        <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

        <v-btn flat icon @click="$router.push(`/territories/${currentTerritory.id}/employees/add`)" color="teal"
               class="add_item">
            <v-icon>add</v-icon>
            Přidat zaměstnance
        </v-btn>

        <v-data-table :headers="headers_admin"
                      :items="employees.admin"
                      class="elevation table_margin_bottom"
                      hide-actions>
            <template v-slot:items="props">
                <td class="text-xs-left">
                    <v-avatar style="margin: 5px">
                        <img :src="props.item.avatarURL">
                    </v-avatar>
                    {{ props.item.name }}
                </td>

                <td class="text-xs-center"> {{ props.item.email }}</td>

                <td class="text-xs-center"> {{  "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>

        <v-data-table :headers="headers_approver"
                      :items="employees.approver"
                      class="elevation table_margin_bottom"
                      hide-actions>
            <template v-slot:items="props">
                <td class="text-xs-left">
                    <v-avatar style="margin: 5px">
                        <img :src="props.item.avatarURL">
                    </v-avatar>
                    {{ props.item.name }}
                </td>

                <td class="text-xs-center"> {{ props.item.email }}</td>

                <td class="text-xs-center"> {{  "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>


        <v-data-table :headers="headers_problem_solvers"
                      :items="employees.problem_solvers"
                      class="elevation table_margin_bottom"
                      hide-actions>
            <template v-slot:items="props">
                <td class="text-xs-left">
                    <v-avatar style="margin: 5px">
                        <img :src="props.item.avatarURL">
                    </v-avatar>
                    {{ props.item.name }}
                </td>

                <td class="text-xs-center"> {{ props.item.email }}</td>

                <td class="text-xs-center"> {{  "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>


        <v-data-table :headers="headers_supervisor"
                      :items="employees.supervisors"
                      class="elevation table_margin_bottom"
                      hide-actions>
            <template v-slot:items="props">
                <td class="text-xs-left">
                    <v-avatar style="margin: 5px">
                        <img :src="props.item.avatarURL">
                    </v-avatar>
                    {{ props.item.name }}
                </td>

                <td class="text-xs-center"> {{ props.item.email }}</td>

                <td class="text-xs-center"> {{  "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>

    </div>
</template>

<script>
    export default {
        name: "EmployeeList",
        data() {
            return {
                isLoading: true,
                headers_admin: [
                    {
                        text: 'Administrátor',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Email',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Telefon',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Detail',
                        align: 'right',
                        sortable: false
                    }
                ],
                headers_approver: [
                    {
                        text: 'Schvalovatel',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Email',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Telefon',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Detail',
                        align: 'right',
                        sortable: false
                    }
                ],
                headers_problem_solvers: [
                    {
                        text: 'Řešitelé',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Email',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Telefon',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Detail',
                        align: 'right',
                        sortable: false
                    }
                ],
                headers_supervisor: [
                    {
                        text: 'Supervizoři',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Email',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Telefon',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Detail',
                        align: 'right',
                        sortable: false
                    }
                ]
            }
        },
        mounted() {
            if (this.employees.length) {
                this.isLoading = false;
                return;
            }

            this.$store.dispatch('getEmployees').then(() => {
            }, error => {
                if (error.response.status === 403) {
                    this.$dialog.notify.error('Nemáte oprávnění spravovat zaměstnance obce');
                    this.$router.push(`/`);
                } else {
                    this.$dialog.notify.error('Nepodařilo se načíst zaměstnance obce');
                }
                console.log(error);
            });
        },
        methods: {
            showDetail(id) {
                this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/employees/${id}`);
            }
        },
        watch: {
            employees() {
                this.isLoading = false;
            },
        },
        computed: {
            employees() {
                return this.$store.getters.employees;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
        }
    }
</script>

<style scoped>

    .elevation {
        box-shadow: none;
    }

    .table_margin_bottom {
        margin-bottom: 1.25em;
    }

    div.wrapper-button-top button {
        margin: 0;
    }

    .add_item {
        width: calc(100% - 2.5rem);
        margin-top: 1.25rem;
        margin-right: 1.25rem;
        margin-left: 1.25rem;
    }

</style>