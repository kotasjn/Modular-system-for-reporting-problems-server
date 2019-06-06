import {getLocalUser} from "./helpers/auth";

const user = getLocalUser();

export default {
    state: {
        loading: false,
        isLoggedIn: !!user,
        auth_error: null,
        currentUser: user,
        reports: []
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        authError(state) {
            return state.auth_error;
        },
        currentUser(state) {
            return state.currentUser;
        },
        reports(state) {
            return state.reports;
        }

    },
    mutations: {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.loading = false;
            state.auth_error = null;
            state.isLoggedIn = true;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

            localStorage.setItem("user", JSON.stringify(state.currentUser));

        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        }
    },
    actions: {
        login(context) {
            context.commit("login");
        }
    }
}