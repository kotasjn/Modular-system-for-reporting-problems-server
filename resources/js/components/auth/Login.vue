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
                        @input="dismissError"
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
                        @input="dismissError"
                        @click:append="show = !show"
                ></v-text-field>

                <v-checkbox
                        v-model="login.rememberMe"
                        label="Zůstat přihlášen"
                        color="teal"
                        class="no-margin-bottom-top"
                ></v-checkbox>

                <div class="form-group" v-if="authError">
                    <div class="isa_error">
                        <font-awesome-icon icon="times-circle"/>
                        {{ authError }}
                    </div>
                </div>

                <v-btn color="teal" class="white--text btn-full-width" @click="authenticate">Přihlásit se</v-btn>
            </v-form>

            <div class="form-group text-center" style="margin: 1em 0 0 0;">
                Nemáš účet?
                <router-link to="/register" class="nav-link">Zaregistruj se!</router-link>
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
                    validEmail: value => /.+@.+/.test(value) || 'E-mail musí být ve správném formátu.'
                }
            }
        },
        methods: {
            authenticate() {
                this.$store.dispatch('login');

                login(this.$data.login)
                    .then((res) => {
                        this.$store.commit("loginSuccess", res);
                        this.$router.push({path: '/'});
                    })
                    .catch((error) => {
                        this.$store.commit("loginFailed", {error});
                    })
            },
            dismissError() {
                this.$store.commit("authError", false);
            }
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