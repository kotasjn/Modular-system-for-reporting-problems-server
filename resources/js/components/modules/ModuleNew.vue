<template>

    <div class="card">
        <div class="card-header">Vytvoření nového modulu</div>

        <div class="card-body">

        <v-form ref="form"
                v-model="valid"
                lazy-validation>

            <v-text-field
                    v-model="newModule.name"
                    :counter="80"
                    :rules="titleRules"
                    label="Název modulu"
                    required
            ></v-text-field>

            <v-select
                    v-model="newModule.category_id"
                    item-text="name"
                    item-value="id"
                    :items="categories"
                    :rules="[v => !!v || 'Tohle pole je povinné']"
                    label="Kategorie"
                    required
            ></v-select>

            <v-divider></v-divider>

            <div v-for="(input, inputIndex) in newModule.inputs" :key="inputIndex">

                <v-text-field
                        v-model="input.title"
                        :counter="80"
                        :rules="titleRules"
                        label="Název inputu"
                        required
                ></v-text-field>

                <v-select
                        v-model="input.inputType"
                        item-text="name"
                        item-value="id"
                        :items="inputTypes"
                        :rules="[v => !!v || 'Tohle pole je povinné']"
                        label="Typ inputu"
                        required
                ></v-select>

                <div v-if="input.inputType === 'spinner'" class="spinner_items">

                    <div v-for="(item, itemIndex) in input.items" :key="itemIndex" class="spinner_item">

                        <v-text-field
                                v-model="item.text"
                                :counter="80"
                                :rules="titleRules"
                                label="Text položky"
                                class="item_field"
                                required
                        ></v-text-field>

                        <v-btn flat icon @click="deleteItem(inputIndex, itemIndex)" color="red darken-4"
                               class="delete-icon">
                            <v-icon>close</v-icon>
                        </v-btn>

                    </div>

                    <div class="spinner_item">
                        <v-btn flat icon @click="addItem(inputIndex)" color="teal" class="item_field">
                            <v-icon>add</v-icon>
                            Přidat položku
                        </v-btn>
                    </div>

                </div>

                <v-textarea
                        v-model="input.hint"
                        :counter="255"
                        auto-grow
                        rows="1"
                        :rules="hintRules"
                        label="Nápověda"
                ></v-textarea>

                <v-btn flat icon @click="deleteInput(inputIndex)" color="red darken-4" class="add_item">
                    <v-icon>close</v-icon>
                    Odebrat input
                </v-btn>

                <v-divider></v-divider>

            </div>

            <v-btn flat icon @click="addInput" color="teal" class="add_item">
                <v-icon>add</v-icon>
                Přidat input
            </v-btn>

            <div class="wrapper-button-bottom">
                <div class="leftcolumn">
                    <v-btn @click="$router.go(-1)">ZRUŠIT</v-btn>
                </div>
                <div class="rightcolumn">
                    <v-btn :disabled="!valid" @click="saveModule" color="teal" class="white--text">ULOŽIT</v-btn>
                </div>
            </div>

        </v-form>

        </div>
    </div>

</template>

<script>
    export default {
        name: "ModuleNew",
        methods: {
            async saveModule() {
                if (this.$refs.form.validate()) {

                    axios.post(`/api/territories/${this.$store.getters.currentTerritory.id}/modules`, {
                        module: this.newModule
                    }).then(response => {
                        console.log(response);
                        this.$store.commit("saveModule", response.data.module);
                        this.$dialog.notify.success('Modul byl úspěšně uložen');
                        this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/modules/${response.data.module.id}`);
                    })
                        .catch(error => {
                            this.$dialog.notify.error('Modul se nepodařilo uložit');
                            console.log(error);
                        });
                }
            },
            addItem(inputIndex) {
                this.newModule.inputs[inputIndex].items.push({
                    text: ''
                });
            },
            deleteItem(inputIndex, itemIndex) {
                this.newModule.inputs[inputIndex].items.splice(itemIndex, 1);
            },
            addInput() {
                this.newModule.inputs.push({
                    title: '',
                    inputType: null,
                    characters: null,
                    hint: '',
                    items: []
                });
            },
            deleteInput(inputIndex) {
                this.newModule.inputs.splice(inputIndex, 1);
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
                inputTypes: [
                    {
                        name: 'Výběr z několika položek',
                        id: 'spinner',
                    },
                    {
                        name: 'Číslo',
                        id: 'number',
                    },
                    {
                        name: 'Text',
                        id: 'string',
                    }
                ],
                valid: true,
                newModule: {
                    name: '',
                    category_id: null,
                    inputs: [{
                        title: '',
                        inputType: null,
                        characters: '',
                        hint: '',
                        items: []
                    }]
                },
                titleRules: [
                    v => !!v || 'Tohle pole je povinné',
                    v => (v && v.length <= 80) || 'Maximální délka je 80 znaků.'
                ],
                hintRules: [
                    v => (v.length <= 255) || 'Poznámka může mít maximálně 255 znaků.'
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

    .spinner_items {
        width: calc(100% - 1.25rem);
        align-items: flex-start;
        display: flex;
        flex: 1 1 auto;
        font-size: 16px;
        margin-left: 1.5rem;
        flex-wrap: wrap;
        flex-direction: column;
        text-align: left;
    }

    .spinner_item {
        width: 100%;
    }

    .item_field {
        float: left;
        width: calc(100% - 4.5em);
    }

    .delete-icon {
        float: right;
        width: 3em;
    }

    .add_item {
        width: 100%;
        margin-right: 0;
        margin-left: 0;
    }

</style>