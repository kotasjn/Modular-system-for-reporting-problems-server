<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="card" v-if="currentUser.isSuperAdmin">
        <div class="card-header">Nahlášené chyby</div>

        <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

        <v-data-table :items="bugs"
                      :headers="headers"
                      class="elevation table_margin_bottom">
            <template v-slot:items="props">
                <td class="text-xs-left" style="white-space: nowrap;">
                    {{ props.item.id }}
                </td>

                <td class="text-xs-center">
                    {{ props.item.description }}
                </td>

                <td class="text-xs-right">
                    {{ props.item.created_at }}
                </td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    export default {
        name: "BugsList",
        data() {
            return {
                isLoading: true,
                bugs: [],
                headers: [
                    {
                        text: 'ID',
                        value: 'id',
                        align: 'left',
                    },
                    {
                        text: 'Popis',
                        value: 'description',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Datum',
                        value: 'created_at',
                        align: 'center',
                    }
                ]
            }
        },
        mounted() {
            if (this.bugs.length) {
                this.isLoading = false;
                return;
            }

            if (this.currentUser.isSuperAdmin) {
                axios.get(`/api/bugs`)
                    .then(response => {
                        this.isLoading = false;
                        this.bugs = response.data.bugs;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        this.$dialog.notify.error('Nepodařilo se načíst chybová hlášení');
                    })
            }
        },
        computed: {
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

</style>