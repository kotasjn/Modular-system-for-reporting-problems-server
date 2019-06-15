<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="card">
        <div class="card-header">Moduly</div>

        <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

        <v-btn flat icon @click="$router.push(`/territories/${currentTerritory.id}/modules/create`)" color="teal" class="add_item">
            <v-icon>add</v-icon>
            Přidat modul
        </v-btn>


        <v-data-table :headers="headers"
                      :items="modules"
                      class="elevation-1">
            <template v-slot:items="props">
                <td class="text-xs-left">{{ props.item.name }}</td>

                <td class="text-xs-center" v-if="props.item.category_id === 1">Zeleň</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 2">Odpad</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 3">Doprava</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 4">Mobiliář</td>
                <td class="text-xs-center" v-else-if="props.item.category_id === 5">Veřejné osvětlení</td>
                <td class="text-xs-center" v-else></td>

                <td class="text-xs-center"> {{ props.item.inputs.length }}</td>

                <td class="text-xs-center">
                    <font-awesome-icon v-if="props.item.active" icon="check-circle" style="color: forestgreen"
                                       title="Aktivní"/>
                    <font-awesome-icon v-else icon="times-circle" style="color: red" title="Neaktivní"/>
                </td>

                <td class="text-xs-center">
                    <v-btn flat icon @click="showDetail(props.item.id)" color="indigo accent-2">
                        <v-icon>remove_red_eye</v-icon>
                    </v-btn>
                </td>
            </template>
        </v-data-table>

    </div>
</template>

<script>
    export default {
        name: "ModuleList",
        data() {
            return {
                isLoading: true,
                headers: [
                    {
                        text: 'Název',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Kategorie',
                        value: 'category_id',
                        align: 'center'
                    },
                    {
                        text: 'Počet inputů',
                        value: 'inputs.length',
                        align: 'center'
                    },
                    {
                        text: 'Aktivní',
                        value: 'active',
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
        mounted() {
            if (this.modules.length) {
                this.isLoading = false;
                return;
            }

            this.$store.dispatch('getModules');
        },
        methods: {
            showDetail(id) {
                this.$router.push(`/territories/${this.$store.getters.currentTerritory.id}/modules/${id}`);
            }
        },
        watch: {
            modules() {
                this.isLoading = false;
            },
        },
        computed: {
            modules() {
                return this.$store.getters.modules;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
        }
    }
</script>

<style scoped>

    .elevation-1 {
        box-shadow: none;
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