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
                    <tr>
                        <th class="subheading">Kategorie:</th>

                        <td class="body-1" v-if="module.category_id === 1">Zeleň</td>
                        <td class="body-1" v-else-if="module.category_id === 2">Odpad</td>
                        <td class="body-1" v-else-if="module.category_id === 3">Doprava</td>
                        <td class="body-1" v-else-if="module.category_id === 4">Mobiliář</td>
                        <td class="body-1" v-else-if="module.category_id === 5">Veřejné osvětlení</td>
                        <td class="body-1" v-else></td>

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
                        <v-btn @click="back">ZPĚT</v-btn>
                        <v-btn @click="editModule" color="teal" class="white--text float-right">UPRAVIT</v-btn>
                        <v-btn @click="activateModule" color="orange" class="white--text float-right">{{ module.active ? "DEAKTIVOVAT" : "AKTIVOVAT"}}</v-btn>
                        <v-btn @click="deleteModule" color="red darken-4" class="white--text float-right">ODSTRANIT</v-btn>
                </div>
            </div>
        </div>

        <div v-if="edit">

            <div class="card-header">Úprava modulu</div>
            <div class="card-body">

                <ModuleEdit v-bind:module="module" v-on:cancel-edit="editModule"
                            v-on:module-saved="saveModule"></ModuleEdit>

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
            async deleteModule(index) {

                let res = await this.$dialog.warning({
                    text: 'Opravdu chcete odstranit modul? Pokud tak učiníte, přijdete o všechna dosud uložená data tohoto modulu.',
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

                    axios.delete(`/api/territories/${this.$store.getters.currentTerritory.id}/modules/${this.$route.params.idModule}`)
                        .then(response => {
                            console.log(response);
                            this.$dialog.notify.success('Modul byl úspěšně smazán');
                            this.modules.splice(index, 1);
                            this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/modules`);
                        })
                        .catch(error => {
                            this.$dialog.notify.error('Modul se nepodařilo smazat');
                            console.log(error);
                        });
                }
            },
            async activateModule() {

                let message = (this.module.active) ? "Opravdu chcete deaktivovat modul? Pokud tak učiníte, modul již nebude viditelný pro ostatní uživatele." : "Opravdu chcete aktivovat modul? Pokud tak učiníte, modul bude viditelný pro všechny uživatele.";

                let res = await this.$dialog.warning({
                        text: message,
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

                    axios.put(`/api/territories/${this.$store.getters.currentTerritory.id}/modules/${this.$route.params.idModule}/activate`)
                        .then(response => {
                            console.log(response);
                            if(this.module.active) {
                                this.$dialog.notify.success('Modul byl úspěšně deaktivován');
                                this.module.active = false;
                            } else {
                                this.$dialog.notify.success('Modul byl úspěšně aktivován');
                                this.module.active = true;
                            }
                        })
                        .catch(error => {
                            this.$dialog.notify.error('Modul se nepodařilo aktivovat');
                            console.log(error);
                        });
                }
            },
            back() {
                this.$router.go(-1);
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