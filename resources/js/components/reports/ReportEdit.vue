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
                :rules="[v => !!v || 'Tohle pole je povinné']"
                label="Stav"
                required
        ></v-select>

        <v-select
                v-model="editedReport.responsible_id"
                item-text="name"
                item-value="id"
                :items="employees"
                label="Řešitel"
        ></v-select>

        <v-text-field
                v-model="editedReport.userNote"
                :counter="255"
                :rules="noteRules"
                label="Poznámka uživatele"
                required
        ></v-text-field>

        <v-text-field
                v-model="editedReport.employeeNote"
                :counter="255"
                :rules="[v => (v.length <= 255) || 'Poznámka může mít maximálně 255 znaků.']"
                label="Poznámka zpracovatele"
        ></v-text-field>

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
                    this.snackbar = true
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
            updateReport() {

            }
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