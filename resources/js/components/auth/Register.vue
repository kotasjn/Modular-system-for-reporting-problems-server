<template>
    <div class="card card-login">
        <div class="card-header">Registrace</div>
        <div class="card-body">

            <v-form ref="form"
                    v-model="valid"
                    lazy-validation>
                <v-text-field
                        v-model="form.name"
                        :rules="[rules.required]"
                        label="Jméno"
                        color="teal"
                        required
                ></v-text-field>

                <v-text-field
                        v-model="form.email"
                        :rules="[rules.required, rules.validEmail]"
                        label="Email"
                        type="email"
                        color="teal"
                        required
                ></v-text-field>

                <div class="input_row">
                    <div class="phone">

                        <span class="subheading">+420</span>

                        <v-text-field
                                v-model="form.telephone"
                                :rules="[rules.required, rules.telephone, rules.number, rules.number_positive]"
                                label="Telefon"
                                autocomplete="new-password"
                                color="teal"
                                class="phone_input"
                                required
                        ></v-text-field>
                    </div>
                </div>

                <v-text-field
                        v-model="form.password"
                        :append-icon="show_password ? 'visibility' : 'visibility_off'"
                        :rules="[rules.required, rules.password]"
                        :type="show_password ? 'text' : 'password'"
                        autocomplete="new-password"
                        label="Heslo"
                        color="teal"
                        @click:append="show_password = !show_password"
                ></v-text-field>

                <v-text-field
                        v-model="form.password_confirmation"
                        :append-icon="show_password_confirmation ? 'visibility' : 'visibility_off'"
                        :rules="[rules.required, rules.passwordConfirm]"
                        :type="show_password_confirmation ? 'text' : 'password'"
                        name="input-10-1"
                        label="Potvrzení hesla"
                        color="teal"
                        @click:append="show_password_confirmation = !show_password_confirmation"
                ></v-text-field>

                <v-checkbox
                        v-model="confirmation"
                        :rules="[rules.checkbox]"
                        label="Souhlasím se zpracováním osobních údajů a s obecnými podmínkami aplikace XYZ."
                        color="teal"
                        class="margin-bottom"
                ></v-checkbox>

                <v-btn color="teal" class="white--text btn-full-width" @click="register">Registrovat se</v-btn>

            </v-form>

            <div class="form-group text-center" style="margin: 1em 0 0 0;">
                Již máte účet?
                <router-link to="/login" class="nav-link">Přihlašte se!</router-link>
            </div>

        </div>
    </div>
</template>

<script>
    import {register} from '../../helpers/auth';

    export default {
        name: "Register",
        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    telephone: ''
                },
                confirmation: false,
                show_password: false,
                show_password_confirmation: false,
                valid: true,
                rules: {
                    required: v => !!v || 'Povinné pole',
                    validEmail: v => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail musí být ve správném formátu',
                    password: v => v.length >= 8 || 'Heslo musí mít alespoň 8 znaků',
                    passwordConfirm: v => v === this.form.password || 'Zadaná hesla se neshodují',
                    checkbox: v => v || 'Pro odeslání registrace musíte souhlasit s podmínkami',
                    telephone: v => v.length === 9 || 'Telefoní číslo musí mít 9 číslic',
                    number: v => /\d/.test(v) || 'Nejedná se o číslo',
                    number_positive: v => v > 0 || 'Číslo musí být kladné'
                }
            }
        },
        methods: {
            register() {

                if (this.$refs.form.validate()) {

                    register(this.$data.form)
                        .then(() => {
                            this.$dialog.notify.success('Registrace proběhla úspěšně');
                            this.$router.push({path: '/login'});
                        })
                        .catch(error => {
                            if(error.response.status === 422) {
                                this.$dialog.notify.error(`Registrace se nezdařila.\nÚčet pro daný email již existuje`);
                            } else {
                                this.$dialog.notify.error(`Registrace se nezdařila.`);
                            }
                            console.log(error);
                        })
                }
            },
        }
    }
</script>

<style scoped>

    .form-label-group > label {
        font-size: 12px;
        margin-bottom: 0;
    }

    .error p {
        color: white;
        margin: 0;
    }

    .btn-full-width {
        width: 100%;
        margin: 0
    }

    .no-margin-bottom-top {
        margin-top: 0;
        margin-bottom: 0;
    }

    .margin-bottom {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .input_row {
        width: 100%;
        align-items: flex-start;
        display: flex;
        flex: 1 1 auto;
        font-size: 16px;
        flex-wrap: wrap;
        flex-direction: column;
        text-align: left;
    }

    .phone {
        position: relative;
        width: 100%;
    }

    .phone span {
        position: absolute;
        width: 2.5em;
        left: 0;
        top: 48%;
        transform: translateY(-52%);
    }

    .phone .phone_input {
        float: right;
        width: calc(100% - 2.5em);
    }

</style>