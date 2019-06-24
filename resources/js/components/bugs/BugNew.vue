<template>

    <div class="card">
        <div class="card-header">Chybové hlášení</div>

        <div class="card-body">

            <v-form ref="form"
                    v-model="valid"
                    lazy-validation>

                <v-textarea
                        v-model="bug.description"
                        :counter="255"
                        auto-grow
                        rows="1"
                        :rules="[v => !!v || 'Tohle pole je povinné']"
                        label="Popis chybového stavu"
                ></v-textarea>

                <div class="wrapper-button-bottom">
                    <div class="rightcolumn">
                        <v-btn :disabled="!valid" @click="saveBug" color="teal" class="white--text">ODESLAT</v-btn>
                    </div>
                </div>

            </v-form>
        </div>
    </div>

</template>

<script>
    export default {
        name: "BugNew",
        data() {
            return {
                valid: true,
                bug: {
                    description: null,
                }
            }
        },
        methods: {
            saveBug() {
                if (this.$refs.form.validate()) {

                    axios.post(`/api/bugs`, {
                        bug: this.bug
                    }).then(response => {
                        this.$dialog.notify.success('Chybové hlášení bylo úspěšně odesláno');
                        this.bug.description = "";
                        this.$refs.form.reset()
                    }).catch(error => {
                        this.$dialog.notify.error('Chybové hlášení se nepodařilo odeslat');
                    });
                }
            },
        },
    }
</script>

<style scoped>

</style>