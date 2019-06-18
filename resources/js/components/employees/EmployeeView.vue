<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="card">

        <div v-if="!edit">

            <div class="card-header">Detail zaměstnance</div>

            <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

            <div class="card-body">

                <img :src="employee.avatarURL" :alt="employee.name" class="avatar">

                <table>
                    <tr>
                        <th class="subheading">Zaměstnanec:</th>
                        <td class="body-1">{{ employee.name }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Role:</th>
                        <td class="body-1">{{ role() }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Email:</th>
                        <td class="body-1">{{ employee.email }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Telefon:</th>
                        <td class="body-1">{{ "+420" + employee.telephone }}</td>
                    </tr>
                    <tr v-if="role() === 'Řešitel'">
                        <th class="subheading">Kategorie:</th>
                        <td class="body-1">
                            <ul>
                                <li v-for="cat in employee.problem_solver.categories_assigned" :key="cat">
                                    {{ category(cat) }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <v-data-table v-if="employee.reports_assigned.length" :headers="headers"
                              :items="employee.reports_assigned"
                              class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.title }}</td>
                        <td class="text-xs-center" v-if="props.item.category_id === 1">Zeleň</td>
                        <td class="text-xs-center" v-else-if="props.item.category_id === 2">Odpad</td>
                        <td class="text-xs-center" v-else-if="props.item.category_id === 3">Doprava</td>
                        <td class="text-xs-center" v-else-if="props.item.category_id === 4">Mobiliář</td>
                        <td class="text-xs-center" v-else-if="props.item.category_id === 5">Veřejné osvětlení</td>
                        <td class="text-xs-center" v-else></td>

                        <td v-else class="text-xs-center">?</td>

                        <td class="text-xs-center">{{ props.item.created_at }}</td>
                        <td class="text-xs-center">

                            <font-awesome-icon v-if="props.item.state === 0" icon="question-circle"
                                               style="color: yellow"
                                               title="Čeká na schválení"/>
                            <font-awesome-icon v-if="props.item.state === 1" icon="sync-alt" style="color: mediumblue"
                                               title="Schváleno"/>
                            <font-awesome-icon v-if="props.item.state === 2" icon="check-circle"
                                               style="color: forestgreen"
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

                <div class="wrapper-button-bottom">
                    <v-btn @click="back">ZPĚT</v-btn>
                    <v-btn @click="editEmployee"
                           v-if="(currentTerritory.admin_id === currentUser.id) && (currentTerritory.admin_id !== employee.id)"
                           color="teal"
                           class="white--text float-right">
                        UPRAVIT
                    </v-btn>
                    <v-btn @click="deleteEmployee"
                           v-if="(currentTerritory.admin_id === currentUser.id) && (currentTerritory.admin_id !== employee.id)"
                           color="red darken-4"
                           class="white--text float-right">ODEBRAT
                    </v-btn>
                </div>
            </div>
        </div>

        <div v-if="edit && employee.isAdmin">

            <div class="card-header">Úprava zaměstnance</div>
            <div class="card-body">

                <EmployeeEdit v-bind:employee="employee" v-on:cancel-edit="editEmployee"
                              v-on:module-saved="saveEmployee"></EmployeeEdit>

            </div>
        </div>
    </div>
</template>

<script>
    import EmployeeEdit from "./EmployeeEdit";

    export default {
        name: "EmployeeView",
        data() {
            return {
                employee: {
                    id: null,
                    avatarURL: '',
                    name: '',
                    email: '',
                    telephone: null,
                    isAdmin: false,
                    isApprover: false,
                    problem_solver: {
                        categories_assigned: []
                    },
                    isSupervisor: false,
                    reports_assigned: []
                },
                edit: false,
                isLoading: true,
                headers: [
                    {
                        text: 'Podněty',
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
                ]
            }
        },
        created() {
            axios.get(`/api/territories/${this.$store.getters.currentTerritory.id}/employees/${this.$route.params.idEmployee}`)
                .then(response => {
                    this.employee = response.data.employee;
                })
                .catch(error => {
                    if (error.response.status === 404) {
                        this.$dialog.notify.error('Neexistující zaměstnanec');
                    } else {
                        this.$dialog.notify.error('Nepodařilo se načíst zaměstnance');
                    }
                    console.log(error);
                    this.back();
                });
        },
        methods: {
            editEmployee() {
                this.edit = !this.edit;
            },
            saveEmployee(newEmployee) {
                this.employee = newEmployee;
                this.$store.commit("updateEmployee", newEmployee);
                this.edit = !this.edit;
            },
            async deleteEmployee(index) {

                let res = await this.$dialog.warning({
                    text: 'Opravdu chcete odebrat zaměstnance?',
                    title: 'Varování',
                    actions: {
                        false: 'Zpět',
                        true: {
                            color: 'red darken-4',
                            text: 'Ano',
                            handle: () => {
                                return new Promise(resolve => {
                                    setTimeout(resolve, 100)
                                })
                            }
                        }
                    }
                });

                if (res) {

                    axios.delete(`/api/territories/${this.$store.getters.currentTerritory.id}/employees/${this.$route.params.idEmployee}`)
                        .then(response => {
                            console.log(response);
                            this.$dialog.notify.success('Zaměstnance byl úspěšně odebrán');
                            this.employees.splice(index, 1);
                            this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/employees`);
                        })
                        .catch(error => {
                            this.$dialog.notify.error('Zaměstnance se nepodařilo odebrat');
                            console.log(error);
                        });
                }
            },
            role: function () {
                if (this.employee.isAdmin) return "Administrátor";
                else if (this.employee.isApprover) return "Schvalovatel";
                else if (this.employee.problem_solver.categories_assigned.length) return "Řešitel";
                else if (this.employee.isSupervisor) return "Supervizor";
            },
            category: function (category) {
                if (category === 1) return "Zeleň";
                else if (category === 2) return "Odpad";
                else if (category === 3) return "Doprava";
                else if (category === 4) return "Mobiliář";
                else if (category === 5) return "Veřejné osvětlení";
            },
            showDetail(id) {
                this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/reports/${id}`);
            },
            back() {
                this.$router.go(-1);
            }
        },
        watch: {
            employee() {
                this.isLoading = false;
            }
        },
        computed: {
            employees() {
                this.isLoading = false;
                return this.$store.getters.employees;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        components: {
            EmployeeEdit
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
        border-radius: 50%;
        margin: .5em auto .5em auto;
    }

</style>