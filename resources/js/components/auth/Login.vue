<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="card card-login">
                <div class="card-header">Přihlášení</div>
                <div class="card-body">
                    <form @submit.prevent="authenticate">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail"
                                       class="form-control" name="email"
                                       placeholder="Email" v-model="form.email" required="required"
                                       autofocus="autofocus">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label for="inputPassword">Heslo</label>
                                <input type="password" id="inputPassword"
                                       class="form-control"
                                       name="password" placeholder="Password" v-model="form.password"
                                       required="required">
                            </div>
                        </div>

                        <div class="form-group" v-if="authError">
                            <div class="isa_error">
                                <font-awesome-icon icon="times-circle"/>
                                {{ authError }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="remember-me"
                                           name="remember" v-model="form.rememberMe">
                                    Zapamatovat si heslo
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-shadow"
                                    style="margin: 1.5em 0 0 0;">
                                Přihlásit se
                            </button>
                        </div>
                    </form>

                    <div class="form-group text-center" style="margin: 1em 0 0 0;">
                        Nemáš účet? <router-link to="/register" class="nav-link">Zaregistruj se!</router-link>
                    </div>

                </div>
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
                form: {
                    email: '',
                    password: '',
                    rememberMe: false
                },
                error: null
            }
        },
        methods: {
            authenticate() {
                this.$store.dispatch('login');

                login(this.$data.form)
                    .then((res) => {
                        this.$store.commit("loginSuccess", res);
                        this.$router.push({path: '/'});
                    })
                    .catch((error) => {
                        this.$store.commit("loginFailed", {error});
                    })
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

    .btn:hover {
        color: #212529;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #009688;
        border: 0;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #00796B;
    }

    .btn-shadow:hover {
        box-shadow: 0px 2px 4px 1px rgba(0, 0, 0, 0.3);
    }

    .btn-shadow {
        box-shadow: 0px 1px 2px 1px rgba(0, 0, 0, 0.2);
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
    }

    .error {
        background-color: tomato;
        padding: 0.5rem;
        border-radius: 10px;
    }

    .error  p {
        color: white;
        margin: 0;
    }

</style>