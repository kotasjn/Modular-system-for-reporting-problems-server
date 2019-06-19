<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="card">
        <div class="card-header">Zaměstnanci</div>

        <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

        <v-btn v-if="!search && (currentUser.id === currentTerritory.admin_id)" flat icon @click="search = !search"
               color="teal"
               class="add_item">
            <v-icon>add</v-icon>
            Přidat zaměstnance
        </v-btn>

        <v-form v-if="search && (currentUser.id === currentTerritory.admin_id)"
                ref="form"
                class="form">
            <v-text-field type="text"
                          v-model.lazy="email"
                          debounce="500"
                          :rules="[rules.validEmail]"
                          color="teal"
                          label="Zadejte email uživatele">
            </v-text-field>
        </v-form>

        <v-data-table v-if="users.length"
                      :items="users"
                      class="elevation table_margin_bottom"
                      hide-headers
                      hide-actions>
            <template v-slot:items="props">
                <td class="text-xs-left">
                    <v-avatar style="margin: 5px">
                        <img :src="props.item.avatarURL">
                    </v-avatar>
                    {{ props.item.name }}
                </td>

                <td class="text-xs-center"> {{ props.item.email }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="createEmployee" color="indigo accent-2"
                           style="margin-right: 0">
                        <v-icon>add</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>


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

                <td class="text-xs-center"> {{ "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>

        <v-data-table v-if="employees.approver.length"
                      :headers="headers_approver"
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

                <td class="text-xs-center"> {{ "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>


        <v-data-table v-if="employees.problem_solvers.length"
                      :headers="headers_problem_solvers"
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

                <td class="text-xs-center"> {{ "+420" + props.item.telephone }}</td>

                <td class="text-xs-right">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2" style="margin-right: 0">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>


        <v-data-table v-if="employees.supervisors.length"
                      :headers="headers_supervisor"
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

                <td class="text-xs-center"> {{ "+420" + props.item.telephone }}</td>

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
                search: false,
                email: null,
                users: [],
                rules: {
                    validEmail: value => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value) || 'E-mail není ve správném formátu.'
                },
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
            },
            searchUser() {
                this.isLoading = true;
                axios.get(`/api/territories/${this.$store.getters.currentTerritory.id}/search/`, {params: {email: this.email}})
                    .then(response => {
                        this.users = response.data;
                        this.isLoading = false;
                        if (!this.users.length)
                            this.$dialog.notify.error('Nepodařilo se vyhledat uživatele pro daný email');
                    })
                    .catch(error => {
                        if (error.response.status === 403) {
                            this.$dialog.notify.error('Nemáte oprávnění k vyhledávání uživatelů');
                            this.$router.push(`/`);
                        } else {
                            this.$dialog.notify.error('Nepodařilo se vyhledat uživatele');
                        }
                        this.isLoading = false;
                    });
            },
            createEmployee() {
                let user = this.users[0];
                this.$router.push({ name: 'EmployeeNew', params: { user } });
            },
            exist(email) {
                let i;
                for (i = 0; i < this.currentTerritory.employees.length; i++) {
                    if (this.currentTerritory.employees[i].email === email) return true;
                }
                return false;
            }
        },
        watch: {
            employees() {
                this.isLoading = false;
            },
            email(after, before) {
                if (this.$refs.form.validate()) {
                    if(!this.exist(this.email)) {
                        this.searchUser();
                    } else {
                        this.$dialog.notify.error('Tento uživatel již je zaměstnancem dané obce');
                    }
                }
            }
        },
        computed: {
            employees() {
                return this.$store.getters.employees;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
            currentUser() {
                return this.$store.getters.currentUser;
            }
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

    .form {
        margin: 1.25em 1.25em 0 1.25em;
    }

</style>