<template>
    <div class="card card-login">
        <div class="card-header">Přihlášení</div>
        <div class="card-body">

            <v-form ref="form" lazy-validation>
                <v-text-field
                        v-model="login.email"
                        :rules="[rules.required, rules.validEmail]"
                        label="Email"
                        color="teal"
                        required
                ></v-text-field>

                <v-text-field
                        v-model="login.password"
                        :append-icon="show ? 'visibility' : 'visibility_off'"
                        :rules="[rules.required]"
                        :type="show ? 'text' : 'password'"
                        name="input-10-1"
                        label="Heslo"
                        color="teal"
                        @click:append="show = !show"
                ></v-text-field>

                <v-checkbox
                        v-model="login.rememberMe"
                        label="Zůstat přihlášen"
                        color="teal"
                        class="no-margin-bottom-top"
                ></v-checkbox>


                <v-btn color="teal" class="white--text btn-full-width" @click="authenticate">Přihlásit se</v-btn>
            </v-form>

            <div class="form-group text-center" style="margin: 1em 0 0 0;">
                Nemáte účet?
                <router-link to="/register" class="nav-link">Zaregistrujte se!</router-link>
            </div>

        </div>
    </div>
</template>

<script>
    import {login} from '../../helpers/auth';

    export default {
        name: "Login",
        data() {
            return {
                login: {
                    email: '',
                    password: '',
                    rememberMe: false
                },
                show: false,
                rules: {
                    required: value => !!value || 'Povinné pole.',
                    validEmail: value => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value) || 'E-mail musí být ve správném formátu.'
                }
            }
        },
        methods: {
            authenticate() {
                this.$store.dispatch('login');

                login(this.$data.login)
                    .then((res) => {
                        this.$store.commit("loginSuccess", res);
                        this.$dialog.notify.success('Přihlášení proběhlo úspěšně');
                        this.$router.push({path: '/'});
                    })
                    .catch((error) => {
                        this.$dialog.notify.error('Přihlášení se nezdařilo\nNeexistující kombinace přihlašovacích údajů');
                        this.$store.commit("loginFailed", {error});
                    })
            },
        },
        computed: {
            authError() {
                return this.$store.getters.authError
            }
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

</style>