<template>

    <v-form ref="form"
            v-model="valid"
            lazy-validation>
        <v-text-field
                v-model="editedReport.title"
                :counter="80"
                :rules="[v => !!v || 'Tohle pole je povinné']"
                label="Titulek"
                required
        ></v-text-field>

        <v-select
                v-model="editedReport.category_id"
                item-text="name"
                item-value="id"
                :items="categories"
                :rules="[v => !!v || 'Tohle pole je povinné']"
                label="Kategorie"
                v-on:change="categoryChanged"
                required
        ></v-select>

        <v-select
                v-model="editedReport.state"
                item-text="name"
                item-value="id"
                :items="states"
                :rules="[v => (0 <= v && v <= 3) || 'Tohle pole je povinné']"
                label="Stav"
                required
        ></v-select>

        <v-select
                v-model="editedReport.responsible_user_id"
                item-text="name"
                item-value="id"
                :items="employees"
                label="Řešitel"
        ></v-select>

        <v-textarea
                v-model="editedReport.userNote"
                :counter="255"
                auto-grow
                rows="1"
                :rules="noteRules"
                label="Poznámka uživatele"
                required
        ></v-textarea>

        <v-textarea
                v-model="editedReport.employeeNote"
                :counter="255"
                auto-grow
                rows="1"
                :rules="[v => (v.length <= 255) || 'Poznámka může mít maximálně 255 znaků.']"
                label="Poznámka zpracovatele"
        ></v-textarea>

        <div class="wrapper-button-bottom">
            <div class="leftcolumn">
                <v-btn @click="$emit('cancel-edit', null)">ZRUŠIT</v-btn>
            </div>
            <div class="rightcolumn">
                <v-btn :disabled="!valid" @click="validate" color="teal" class="white--text">ODESLAT</v-btn>
            </div>
        </div>

    </v-form>

</template>

<script>

    export default {
        name: "ReportEdit",
        props: ['report'],
        created() {
            this.getEmployees(this.$props.report.category_id)
        },
        methods: {
            validate() {
                if (this.$refs.form.validate()) {
                    axios.put(`/api/territories/${this.$store.getters.currentTerritory.id}/reports/${this.$props.report.id}`, {
                        title: this.editedReport.title,
                        category_id: this.editedReport.category_id,
                        state: this.editedReport.state,
                        responsible_user_id: this.editedReport.responsible_user_id,
                        userNote: this.editedReport.userNote,
                        employeeNote: this.editedReport.employeeNote
                    })
                        .then(response => {
                            console.log(response);
                            this.$emit('report-saved', response.data.report);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            },
            getEmployees($category_id) {
                axios.get(`/api/territories/${this.$store.getters.currentTerritory.id}/employees`, {
                    params: {
                        category_id: $category_id
                    }
                })
                    .then((response) => {
                        this.employees = response.data.employees
                    })
            },
            categoryChanged()
            {
                this.getEmployees(this.report.category_id)
            },
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
                states: [
                    {
                        name: 'Čeká na schválení',
                        id: 0
                    },
                    {
                        name: 'Schváleno',
                        id: 1
                    },
                    {
                        name: 'Vyřešeno',
                        id: 2
                    },
                    {
                        name: 'Zamítnuto',
                        id: 3
                    }
                ],
                valid: true,
                employees: [],
                editedReport: JSON.parse(JSON.stringify(this.$props.report)),
                titleRules: [
                    v => !!v || 'Tohle pole je povinné',
                    v => (v && v.length <= 80) || 'Titulek může mít maximálně 80 znaků.'
                ],
                noteRules: [
                    v => !!v || 'Tohle pole je povinné',
                    v => (v && v.length <= 255) || 'Poznámka může mít maximálně 255 znaků.'
                ]
            }
        },
        computed: {
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
        }
    }
</script>

<style scoped>

</style>