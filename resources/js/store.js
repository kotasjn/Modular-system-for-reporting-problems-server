import {getLocalUser} from "./helpers/auth";
import {getLocalTerritory} from "./helpers/territory";

const user = getLocalUser();

const territory = getLocalTerritory();

export default {
    state: {
        loading: false,
        isLoggedIn: !!user,
        auth_error: null,
        currentUser: user,
        currentTerritory: territory,
        reports: [],
        modules: []
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
        currentTerritory(state) {
            return state.currentTerritory;
        },
        territories(state) {
            return state.currentUser.territories;
        },
        reports(state) {
            return state.reports;
        },
        modules(state) {
            return state.modules;
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
            state.currentTerritory = Object.assign({}, payload.user.territories[0]);

            localStorage.setItem("user", JSON.stringify(state.currentUser));
            localStorage.setItem("territory", JSON.stringify(state.currentTerritory));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
            state.currentTerritory = null;
        },
        updateTerritory(state, payload) {
            state.currentTerritory = payload;
            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
        updateReports(state, payload) {
            state.reports = payload;
        },
        updateReport(state, newReport) {
            state.reports.forEach((report, index) => {
                if (report.id === newReport.id) {
                    state.reports[index] = newReport
                }
            });
        },
        updateModules(state, payload) {
            state.modules = payload;
        },
        updateModule(state, newModule) {
            state.modules.forEach((module, index) => {
                if (module.id === newModule.id) {
                    state.modules[index] = newModule
                }
            });
        },
        saveModule(state, newModule) {
            state.modules.push(newModule);
        },
        authError(state, bool) {
            state.auth_error = bool;
        },
        updateNumberOfWaitingReports(state, value) {
            state.currentTerritory.waiting_reports = value;
        },
        updateNumberOfAcceptedReports(state, value) {
            state.currentTerritory.accepted_reports = value;
        },
        updateNumberOfSolvedReports(state, value) {
            state.currentTerritory.solved_reports = value;
        },
        updateNumberOfRejectedReports(state, value) {
            state.currentTerritory.rejected_reports = value;
        },
    },
    actions: {
        login(context) {
            context.commit("login");
        },
        updateReport(context, newReport) {
            context.commit('updateReport', newReport)
        },
        getReports(context) {
            axios.get(`/api/territories/${context.state.currentTerritory.id}/reports/`)
                .then((response) => {
                    context.commit('updateReports', response.data.reports)
                })
        },
        getTerritory(context) {
            axios.get(`/api/territories/${context.state.currentTerritory.id}`)
                .then((response) => {
                    context.commit('updateTerritory', response.data.territory)
                })
        },
        getModules(context) {
            axios.get(`/api/territories/${context.state.currentTerritory.id}/modules/`)
                .then((response) => {
                    context.commit('updateModules', response.data.modules)
                })
        }
    }
}