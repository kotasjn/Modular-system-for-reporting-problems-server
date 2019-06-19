<template>

    <div class="card">
        <div class="card-header">Nový zaměstnanec</div>

        <div class="card-body">

            <v-form ref="form"
                    v-model="valid"
                    lazy-validation>

                <v-text-field
                        v-model="employee.name"
                        label="Jméno"
                        required
                        readonly
                ></v-text-field>

                <v-text-field
                        v-model="employee.email"
                        label="Email"
                        required
                        readonly
                ></v-text-field>

                <v-select
                        v-model="employee.role"
                        :items="items"
                        item-text="name"
                        item-value="id"
                        :rules="[v => !!v || 'Tohle pole je povinné']"
                        label="Role zaměstnance"
                        required
                ></v-select>

                <v-list v-if="employee.role === 2" one-line subheader>
                    <v-list-tile v-for="(category, index) in categories" :key="index" avatar>
                        <v-list-tile-action>
                            <v-checkbox v-model="responsibilities[category.id]"
                                        color="indigo accent-2"></v-checkbox>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ category.name }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>

                <div class="wrapper-button-bottom">
                    <div class="leftcolumn">
                        <v-btn @click="back">ZRUŠIT</v-btn>
                    </div>
                    <div class="rightcolumn">
                        <v-btn :disabled="!valid" @click="saveEmployee" color="teal" class="white--text">ULOŽIT</v-btn>
                    </div>
                </div>

            </v-form>
        </div>
    </div>

</template>

<script>
    export default {
        name: "EmployeeNew",
        props: ['user'],
        created () {
            this.checkApprover();
        },
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
                employee: {
                    user_id: this.$props.user.user_id,
                    avatarURL: this.$props.user.avatarURL,
                    email: this.$props.user.email,
                    name: this.$props.user.name,
                    role: null,
                    responsibilities: [],
                },
                responsibilities: [],
                titleRules: [
                    v => !!v || 'Tohle pole je povinné',
                    v => (v && v.length <= 80) || 'Maximální délka je 80 znaků.'
                ],
                hintRules: [
                    v => (null || v.length <= 255) || 'Poznámka může mít maximálně 255 znaků.'
                ]
            }
        },
        methods: {
            saveEmployee() {
                if (this.$refs.form.validate()) {
                    this.responsibilities.forEach(this.parseResponsibility);

                    axios.post(`/api/territories/${this.$store.getters.currentTerritory.id}/employees`, {
                        employee: this.employee
                    }).then(response => {
                        console.log(response);
                        this.$store.commit("saveEmployee", response.data.employee, this.employee.role);
                        this.$dialog.notify.success('Zaměstnanec byl úspěšně uložen');
                        this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/employees/${response.data.employee.id}`);
                    }).catch(error => {
                        this.$dialog.notify.error('Zaměstnanece se nepodařilo uložit');
                        console.log(error);
                    });
                }
            },
            parseResponsibility(item, index) {
                if (item) this.employee.responsibilities.push(index);
            },
            checkApprover() {
                if(this.currentTerritory.approver_id != null) {
                    this.roles.slice(0, 1);
                    console.log('jsem tu');
                }

            },
            back() {
                this.$router.go(-1);
            }
        },
        watch: {},
        computed: {
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
            items() {
                if(this.currentTerritory.approver_id != null) return this.roles.slice(1, 3);
                else return this.roles.slice(0, 3);
            }
        }
    }
</script>

<style scoped>

</style>