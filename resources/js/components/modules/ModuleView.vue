<template>

    <div class="card">

        <div v-if="!edit">

            <div class="card-header">Detail modulu</div>

            <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

            <div class="card-body">

                <table>
                    <tr>
                        <th class="subheading">Název modulu:</th>
                        <td class="body-1">{{ module.name }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Stav:</th>
                        <td class="body-1">{{ module.active ? "Aktivní" : "Neaktivní"}}</td>
                    </tr>
                </table>

                <v-divider></v-divider>

                <div v-for="(input, index) in module.inputs" :key="index">

                    <table>
                        <tr>
                            <th class="subheading">Název inputu:</th>
                            <td class="body-1">{{ input.title }}</td>
                        </tr>

                        <tr>
                            <th class="subheading">Typ inputu:</th>
                            <td v-if="input.inputType === 'spinner'" class="body-1">Výběr z několika položek</td>
                            <td v-else-if="input.inputType === 'number'" class="body-1">Číslo</td>
                            <td v-else class="body-1">Text</td>
                        </tr>

                        <tr v-if="(input.characters != null) && (input.inputType === 'string')">
                            <th class="subheading">Počet znaků:</th>
                            <td class="body-1">{{ input.characters }}</td>
                        </tr>

                        <tr v-if="input.inputType === 'spinner'">
                            <th class="subheading">Seznam položek:</th>
                            <td class="body-1">
                                <ul>
                                    <li v-for="item in input.items">
                                        {{ item.text }}
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr v-if="input.hint != null">
                            <th class="subheading">Nápověda:</th>
                            <td class="body-1">{{ input.hint }}</td>
                        </tr>

                    </table>

                    <v-divider v-if="index + 1 < module.inputs.length" :key="`divider-${index}`"></v-divider>

                </div>

                <div class="wrapper-button-bottom">
                    <div class="leftcolumn">
                        <v-btn @click="back">ZPĚT</v-btn>
                    </div>
                    <div class="rightcolumn">
                        <v-btn @click="editModule" color="teal" class="white--text">UPRAVIT</v-btn>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="edit">

            <div class="card-header">Úprava modulu</div>
            <div class="card-body">

                <ModuleEdit v-bind:module="module" v-on:cancel-edit="editModule"
                            v-on:module-saved="saveModule" v-on:module-deleted="deleteModule"></ModuleEdit>

            </div>
        </div>
    </div>

</template>

<script>
    import ModuleEdit from "./ModuleEdit";

    export default {
        name: "ModuleView",
        data() {
            return {
                module: {
                    name: '',
                    active: 0,
                    category_id: null,
                    inputs: [{
                        title: '',
                        inputType: '',
                        characters: null,
                        hint: null,
                        items: [{
                            text: ''
                        }]
                    }]
                },
                edit: false,
                isLoading: true
            }
        },
        created() {
            axios.get(`/api/territories/${this.$store.getters.currentTerritory.id}/modules/${this.$route.params.idModule}`)
                .then((response) => {
                    this.module = response.data.module;
                });
        },
        methods: {
            editModule() {
                this.edit = !this.edit;
            },
            saveModule(newModule) {
                this.module = newModule;
                this.$store.commit("updateModule", newModule);
                this.edit = !this.edit;
            },
            deleteModule(index) {
                this.modules.splice(index,1);
                this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/modules`);
            },
            back() {
                this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/modules`);
            }
        },
        watch: {
            module() {
                this.isLoading = false;
            }
        },
        computed: {
            modules() {
                this.isLoading = false;
                return this.$store.getters.modules;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
        },
        components: {
            ModuleEdit
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

</style>