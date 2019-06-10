<template>
    <div>
        <div class="card" v-if="!edit">
            <div class="card-header">Detail podnětu</div>
            <div class="card-body">
                <table>
                    <tr>
                        <th>ID</th>
                        <td>{{ report.id }}</td>
                    </tr>
                    <tr>
                        <th>Titulek</th>
                        <td>{{ report.title }}</td>
                    </tr>
                    <tr>
                        <th>Kategorie</th>
                        <td>{{ report.category_id }}</td>
                    </tr>
                    <tr>
                        <th>Řešitel</th>
                        <td>{{ report.responsible_id }}</td>
                    </tr>
                    <tr>
                        <th>Poznámka uživatele</th>
                        <td>{{ report.userNote }}</td>
                    </tr>
                </table>

                <div>
                    <img class="image" v-for="(photo, i) in report.photos" :src="photo" :key="i" @click="index = i">
                    <vue-gallery-slideshow :images="report.photos" :index="index"
                                           @close="index = null"></vue-gallery-slideshow>
                </div>

                <div id="wrapper">
                    <div id="leftcolumn">
                        <router-link :to="`/territories/${currentTerritory.id}/reports`">Zpět</router-link>
                    </div>
                    <div id="rightcolumn">
                        <button v-on:click="editReport">Upravit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" v-if="edit">
            <div class="card-header">Detail podnětu</div>
            <div class="card-body">

                <ReportEdit v-bind:report="report" v-on:cancel-edit="editReport"></ReportEdit>

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
            axios.get(`/api/territories/${this.$store.getters.currentTerritory.id}/reports/${this.$route.params.idReport}`)
                .then((response) => {
                    this.report = response.data.report;
                });
        },
        data() {
            return {
                report: {
                    title: '',
                    state: 0,
                    category_id: 0,
                    responsible_id: null,
                    userNote: '',
                    employeeNote: ''
                },
                index: null,
                edit: false
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
        },
        components: {
            VueGallerySlideshow,
            ReportEdit
        }
    }
</script>

<style scoped>

    .image {
        width: 100px;
        height: 100px;
        background-size: cover;
        cursor: pointer;
        margin: 5px;
        border-radius: 3px;
        border: 1px solid lightgray;
        object-fit: contain;
    }

    .wrapper {
        width: 100%;
    }

    .leftcolumn {
        width: 50%;
        float: left;
    }

    .rightcolumn {
        width: 50%;
        float: right;
        text-align: right;
    }

</style>