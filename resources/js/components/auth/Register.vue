<template>
    <div class="card card-login">
        <div class="card-header">Registrace</div>
        <div class="card-body">

            <v-form ref="form" lazy-validation>
                <v-text-field
                        v-model="register.name"
                        :rules="[rules.required]"
                        label="Jméno"
                        color="teal"
                        required
                ></v-text-field>

                <v-text-field
                        v-model="register.email"
                        :rules="[rules.required, rules.validEmail]"
                        label="Email"
                        color="teal"
                        required
                ></v-text-field>

                <v-text-field
                        v-model="register.password"
                        :append-icon="show_password ? 'visibility' : 'visibility_off'"
                        :rules="[rules.required, rules.password]"
                        :type="show_password ? 'text' : 'password'"
                        name="input-10-1"
                        label="Heslo"
                        color="teal"
                        @click:append="show_password = !show_password"
                ></v-text-field>

                <v-text-field
                        v-model="register.password_confirmation"
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
                register: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    telephone: null
                },
                confirmation: false,
                show_password: false,
                show_password_confirmation: false,
                rules: {
                    required: v => !!v || 'Povinné pole',
                    validEmail: v => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail musí být ve správném formátu',
                    password: v => v.length >= 8 || 'Heslo musí mít alespoň 8 znaků',
                    passwordConfirm: v => v === this.register.password || 'Zadaná hesla se neshodují',
                    checkbox: v => v || 'Pro odeslání registrace musíte souhlasit s podmínkami'
                }
            }
        },
        methods: {
            register() {

                register(this.$data.register)
                    .then((res) => {
                        this.$dialog.notify.success('Registrace proběhla úspěšně');
                        this.$router.push({path: '/login'});
                    })
                    .catch((error) => {
                        this.$dialog.notify.error('Registrace se nezdařila');
                        console.log(error);
                    })
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

</style>