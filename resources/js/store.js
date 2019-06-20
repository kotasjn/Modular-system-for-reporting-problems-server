import {getLocalUser} from "./helpers/auth";
import {getLocalTerritory} from "./helpers/territory";

const user = getLocalUser();
const territory = getLocalTerritory();

export default {
    state: {
        loading: false,
        auth_error: null,
        currentUser: user,
        currentTerritory: territory,
        reports: [],
        modules: [],
        employees: {
            admin: [],
            approver: [],
            supervisors: [],
            problem_solvers: []
        }
    },
    getters: {
        isLoading(state) {
            return state.loading;
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
            return (state.currentUser) ? state.currentUser.territories : null;
        },
        reports(state) {
            return state.reports;
        },
        modules(state) {
            return state.modules;
        },
        employees(state) {
            return state.employees;
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
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
            state.currentTerritory = Object.assign({}, payload.user.territories[0]);
            state.currentUser.isAdmin = state.currentUser.id === state.currentTerritory.admin_id;

            localStorage.setItem("user", JSON.stringify(state.currentUser));
            localStorage.setItem("territory", JSON.stringify(state.currentTerritory));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");

            state.currentUser = null;
            state.currentTerritory = null;
        },
        updateTerritory(state, payload) {
            state.currentTerritory = payload;
            state.currentUser.isAdmin = state.currentUser.id === state.currentTerritory.admin_id;

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
        deleteReport(state, report) {
            state.reports.forEach((cur_report, index) => {
                if (cur_report.id === report.id) {
                    state.reports.splice(index, 1);
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
        updateEmployees(state, payload) {
            state.employees = payload;
        },
        saveEmployee(state, newEmployee, role) {
            if (role === 1) state.employees.approver[0] = newEmployee;
            else if (role === 2) state.employees.problem_solvers.push(newEmployee);
            else if (role === 3) state.employees.supervisors.push(newEmployee);
            state.currentTerritory.employees.push(newEmployee);
            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
        deleteEmployee(state, employee) {
            if (state.employees.approver.length !== 0 && state.employees.approver[0].id === employee.id) state.employees.approver[0] = null;

            if (state.employees.problem_solvers !== 0) {
                state.employees.problem_solvers.forEach((saved_employee, index) => {
                    if (saved_employee.id === employee.id) {
                        state.employees.problem_solvers.splice(index, 1);
                    }
                });
            }

            if(state.employees.supervisors.length !== 0) {
                state.employees.supervisors.forEach((saved_employee, index) => {
                    if (saved_employee.id === employee.id) {
                        state.employees.supervisors.splice(index, 1);
                    }
                });
            }

            if (state.currentTerritory.approver_id === employee.id) {
                state.currentTerritory.approver_id = null;
            }

            let empl = state.currentTerritory.employees;

            empl.forEach((saved_employee, index) => {
                if (saved_employee.id === employee.id) {
                    empl.splice(index, 1);
                }
            });

            state.currentTerritory.employees = empl;

            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
        authError(state, bool) {
            state.auth_error = bool;
        },
        updateNumberOfWaitingReports(state, value) {
            state.currentTerritory.waiting_reports = value;
            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
        updateNumberOfAcceptedReports(state, value) {
            state.currentTerritory.accepted_reports = value;
            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
        updateNumberOfSolvedReports(state, value) {
            state.currentTerritory.solved_reports = value;
            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
        updateNumberOfRejectedReports(state, value) {
            state.currentTerritory.rejected_reports = value;
            localStorage.setItem("currentTerritory", JSON.stringify(state.currentTerritory));
        },
    },
    actions: {
        login(context) {
            context.commit("login");
        },
        logout(context) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/auth/logout`)
                    .then(() => {
                        context.commit("logout");
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        updateReport(context, newReport) {
            context.commit('updateReport', newReport)
        },
        deleteReport(context, report) {
            return new Promise((resolve, reject) => {
                axios.delete(`/api/territories/${context.state.currentTerritory.id}/reports/${report.id}`)
                    .then(response => {
                        context.commit('deleteReport', report);

                        if (report.state === 0) context.commit("updateNumberOfWaitingReports", context.state.currentTerritory.waiting_reports - 1);
                        else if (report.state === 1) context.commit("updateNumberOfAcceptedReports", context.state.currentTerritory.accepted_reports - 1);
                        else if (report.state === 2) context.commit("updateNumberOfSolvedReports", context.state.currentTerritory.solved_reports - 1);
                        else if (report.state === 3) context.commit("updateNumberOfRejectedReports", context.state.currentTerritory.rejected_reports - 1);

                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        getReport(context, id) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/territories/${context.state.currentTerritory.id}/reports/${id}`)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        getReports(context) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/territories/${context.state.currentTerritory.id}/reports/`)
                    .then(response => {
                        context.commit('updateReports', response.data.reports);
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        getTerritory(context) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/territories/${context.state.currentTerritory.id}`)
                    .then(response => {
                        context.commit('updateTerritory', response.data.territory);
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        getModules(context) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/territories/${context.state.currentTerritory.id}/modules/`)
                    .then((response) => {
                        context.commit('updateModules', response.data.modules)
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        },
        getEmployees(context) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/territories/${context.state.currentTerritory.id}/employees/`)
                    .then((response) => {
                        context.commit('updateEmployees', response.data.employees)
                    })
                    .catch(error => {
                        reject(error);
                    })
            })
        }
    }
}