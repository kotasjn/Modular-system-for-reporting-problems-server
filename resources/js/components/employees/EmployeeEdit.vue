<template>

    <v-form ref="form"
            v-model="valid"
            lazy-validation>

        <v-text-field
                v-model="newEmployee.name"
                label="Jméno"
                required
                readonly
        ></v-text-field>

        <v-text-field
                v-model="newEmployee.email"
                label="Email"
                required
                readonly
        ></v-text-field>

        <v-select
                v-model="newEmployee.role"
                :items="items"
                item-text="name"
                item-value="id"
                :rules="[v => !!v || 'Tohle pole je povinné']"
                label="Role zaměstnance"
                required
        ></v-select>

        <v-list v-if="newEmployee.role === 2" one-line subheader>
            <v-list-tile v-for="(category, index) in categories" :key="index" avatar>
                <v-list-tile-action>
                    <v-checkbox v-model="responsibilities[index]"
                                color="indigo accent-2"></v-checkbox>
                </v-list-tile-action>
                <v-list-tile-content>
                    <v-list-tile-title>{{ category.name }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>

        <div class="wrapper-button-bottom">
            <div class="leftcolumn">
                <v-btn @click="$emit('cancel-edit', null)">ZRUŠIT</v-btn>
            </div>
            <div class="rightcolumn">
                <v-btn :disabled="!valid" @click="saveEmployee" color="teal" class="white--text">ULOŽIT</v-btn>
            </div>
        </div>

    </v-form>

</template>

<script>
    export default {
        name: "EmployeeNew",
        props: ['employee'],
        data() {
            return {
                categories: [
                    {
                        name: 'Zeleň',
                        id: 1
                    },
                    {
                        name: 'Odpad',
                        id: 2
                    },
                    {
                        name: 'Doprava',
                        id: 3
                    },
                    {
                        name: 'Mobiliář',
                        id: 4
                    },
                    {
                        name: 'Veřejné osvětlení',
                        id: 5
                    }
                ],
                roles: [
                    {
                        name: 'Schvalovatel',
                        id: 1
                    },
                    {
                        name: 'Řešitel',
                        id: 2
                    },
                    {
                        name: 'Supervizor',
                        id: 3
                    }
                ],
                valid: true,
                newEmployee: JSON.parse(JSON.stringify(this.$props.employee)),
                responsibilities: this.parseResponsibilities(),
            }
        },
        methods: {
            saveEmployee() {
                if (this.$refs.form.validate()) {
                    this.newEmployee.responsibilities = [];
                    if (this.newEmployee.role === 2)
                        this.responsibilities.forEach(this.addResponsibility);

                    axios.put(`/api/territories/${this.$store.getters.currentTerritory.id}/employees/${this.newEmployee.id}`, {
                        employee: this.newEmployee
                    }).then(response => {
                        this.$store.commit("deleteEmployee", response.data.employee);
                        this.$store.commit("saveEmployee", response.data.employee, this.employee.role);
                        this.$dialog.notify.success('Zaměstnanec byl úspěšně uložen');
                        this.$emit('employee-saved', response.data.employee);
                    }).catch(error => {
                        this.$dialog.notify.error('Zaměstnance se nepodařilo uložit');
                        console.log(error);
                    });
                }
            },
            parseResponsibilities() {
                let resp = [];
                if (typeof this.$props.employee.responsibilities !== 'undefined') {
                    let index;
                    for (index = 0; index < 5; index++) {
                        if (this.$props.employee.responsibilities.includes(index + 1)) resp.push(true);
                        else resp.push(false);
                    }
                }
                return resp;
            },
            addResponsibility(item, index) {
                if (item) this.newEmployee.responsibilities.push(index + 1);
            }
        },
        watch: {},
        computed: {
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
            items() {
                if (this.currentTerritory.approver_id != null && this.employee.role !== 1) return this.roles.slice(1, 3);
                else return this.roles.slice(0, 3);
            }
        }
    }
</script>

<style scoped>

</style>