export function initialize(store, router) {

    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.state.currentUser;

        if(requiresAuth && !currentUser) {
            next('/login');
        } else if (to.path === '/login' && currentUser){
            next('/');
        } else {
            next();
        }
    });

    axios.interceptors.response.use(null, (error) => {
        store.commit('logout');
        router.push('/login')
    });

}