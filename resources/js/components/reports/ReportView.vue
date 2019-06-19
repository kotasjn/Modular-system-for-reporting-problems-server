<template>
    <div class="card">
        <div v-if="!edit">
            <div class="card-header">Detail podnětu</div>

            <v-progress-linear :indeterminate="true" height="5" v-show="isLoading"></v-progress-linear>

            <div class="card-body" v-if="!isLoading">

                <table>
                    <tr>
                        <th class="subheading">Titulek:</th>
                        <td class="body-1">{{ report.title }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Kategorie:</th>
                        <td v-if="report.category_id === 1" class="body-1">Zeleň</td>
                        <td v-else-if="report.category_id === 2" class="body-1">Odpad</td>
                        <td v-else-if="report.category_id === 3" class="body-1">Doprava</td>
                        <td v-else-if="report.category_id === 4" class="body-1">Mobiliář</td>
                        <td v-else-if="report.category_id === 5" class="body-1">Veřejné osvětlení</td>
                        <td v-else class="body-1"></td>
                    </tr>
                    <tr>
                        <th class="subheading">Stav:</th>
                        <td v-if="report.state === 0" class="body-1">Čeká na schválení
                            <font-awesome-icon v-if="report.state === 0" icon="question-circle" style="color: yellow"
                                               title="Čeká na schválení"/>
                        </td>
                        <td v-else-if="report.state === 1" class="body-1">Schváleno
                            <font-awesome-icon v-if="report.state === 1" icon="sync-alt" style="color: mediumblue"
                                               title="Schváleno"/>
                        </td>
                        <td v-else-if="report.state === 2" class="body-1">Vyřešeno
                            <font-awesome-icon v-if="report.state === 2" icon="check-circle" style="color: forestgreen"
                                               title="Vyřešeno"/>
                        </td>
                        <td v-else-if="report.state === 3" class="body-1">Zamítnuto
                            <font-awesome-icon v-if="report.state === 3" icon="times-circle" style="color: red"
                                               title="Zamítnuto"/>
                        </td>
                        <td v-else class="body-1"></td>
                    </tr>
                    <tr>
                        <th class="subheading">Zadavatel:</th>
                        <td class="body-1">{{ report.user.name }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Řešitel:</th>
                        <td v-if="report.responsible != null" class="body-1">{{ report.responsible.name }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Vytvořeno:</th>
                        <td class="body-1">{{ report.created_at }}</td>
                    </tr>
                    <tr>
                        <th class="subheading">Poznámka uživatele:</th>
                        <td class="body-1">{{ report.userNote }}</td>
                    </tr>

                    <tr>
                        <th class="subheading">Poznámka řešitele:</th>
                        <td class="body-1">{{ report.employeeNote }}</td>
                    </tr>

                    <tr v-if="report.moduleData.length > 0">
                        <th class="subheading font-weight-bold">Data modulů:</th>
                    </tr>

                    <tr v-for="module in report.moduleData">
                        <th class="subheading">{{ module.name }}</th>
                        <td class="body-1">
                            <ul>
                                <li v-for="inputData in module.inputData">
                                    {{ inputData.title + ": " + inputData.data }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <div style="margin-bottom: 1em">
                    <img class="image" v-for="(photo, i) in report.photos" :src="photo" :key="i" @click="index = i">
                    <vue-gallery-slideshow :images="report.photos" :index="index"
                                           @close="index = null"></vue-gallery-slideshow>
                </div>


                <gmap-map :center="report.location"
                          :zoom="15"
                          style="width:100%;  height: 300px;">
                    <gmap-marker :position="report.location"></gmap-marker>
                </gmap-map>

                <div class="wrapper-button-bottom">
                    <v-btn @click="back">ZPĚT</v-btn>
                    <v-btn @click="editReport" color="teal" class="white--text float-right">UPRAVIT</v-btn>
                    <v-btn @click="deleteReport" color="red darken-4" class="white--text float-right">ODSTRANIT</v-btn>
                </div>

            </div>
        </div>

        <div v-if="edit">
            <div class="card-header">Úprava podnětu</div>
            <div class="card-body">

                <ReportEdit v-bind:report="report" v-on:cancel-edit="editReport"
                            v-on:report-saved="saveReport"></ReportEdit>

            </div>
        </div>
    </div>
</template>

<script>

    import VueGallerySlideshow from 'vue-gallery-slideshow';
    import ReportEdit from './ReportEdit';

    export default {
        name: "Report",
        created() {
            this.$store.dispatch('getReport', this.$route.params.idReport).then(response => {
                this.report = response.data.report;
            }, error => {
                if(error.response.status === 404) {
                    this.$dialog.notify.error('Neexistující podnět');
                } else {
                    this.$dialog.notify.error('Nepodařilo se načíst podnět');
                }
                console.log(error);
                this.back();
            })
        },
        data() {
            return {
                report: {
                    title: '',
                    state: null,
                    category_id: null,
                    location: {
                        lat: 0,
                        lng: 0
                    },
                    responsible_user_id: null,
                    responsible: {
                        name: '',
                    },
                    user: {
                        name: '',
                    },
                    userNote: '',
                    employeeNote: '',
                    moduleData: [{
                        id: null,
                        name: '',
                        inputData: [{
                            id: null,
                            title: '',
                            data: ''
                        }]
                    }]
                },
                index: null,
                edit: false,
                isLoading: true
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            currentTerritory() {
                return this.$store.getters.currentTerritory;
            },
        },
        methods: {
            editReport() {
                this.edit = !this.edit;
            },
            saveReport(newReport) {
                if (this.report.state !== newReport.state)
                    this.changeCountOfReports(this.report.state, newReport.state);

                this.report = newReport;
                this.$store.commit("updateReport", newReport);
                this.$dialog.notify.success('Podnět úspěšně uložen');
                this.edit = !this.edit;
            },
            deleteReport() {
                this.$store.dispatch("deleteReport", this.report).then(response => {
                    this.$dialog.notify.success('Podnět byl úspěšně smazán');
                    this.back();
                }, error => {
                    this.$dialog.notify.error('Podnět se nepodařilo odstranit');
                    console.log(error);
                })
            },
            changeCountOfReports(oldState, newState) {
                if (oldState === 0) this.$store.commit("updateNumberOfWaitingReports", this.currentTerritory.waiting_reports - 1);
                else if (oldState === 1) this.$store.commit("updateNumberOfAcceptedReports", this.currentTerritory.accepted_reports - 1);
                else if (oldState === 2) this.$store.commit("updateNumberOfSolvedReports", this.currentTerritory.solved_reports - 1);
                else if (oldState === 3) this.$store.commit("updateNumberOfRejectedReports", this.currentTerritory.rejected_reports - 1);

                if (newState === 0) this.$store.commit("updateNumberOfWaitingReports", this.currentTerritory.waiting_reports + 1);
                else if (newState === 1) this.$store.commit("updateNumberOfAcceptedReports", this.currentTerritory.accepted_reports + 1);
                else if (newState === 2) this.$store.commit("updateNumberOfSolvedReports", this.currentTerritory.solved_reports + 1);
                else if (newState === 3) this.$store.commit("updateNumberOfRejectedReports", this.currentTerritory.rejected_reports + 1);
            },
            back() {
                this.$router.go(-1);
            }
        },
        watch: {
            report() {
                this.isLoading = false;
            }
        },
        components: {
            VueGallerySlideshow,
            ReportEdit
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

    .image {
        width: 100px;
        height: 100px;
        background-size: cover;
        cursor: pointer;
        margin: 5px;
        border-radius: 3px;
        border: 1px solid lightgray;
        object-fit: cover;
    }

</style>