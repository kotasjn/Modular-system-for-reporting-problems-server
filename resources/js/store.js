export default {
    state: {
        welcomeMessage: "Welcome in my application!"
    },
    getters: {
        welcome(state) {
            return state.welcomeMessage;
        }
    },
    mutations: {},
    actions: {}
}