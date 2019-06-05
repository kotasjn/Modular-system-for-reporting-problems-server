import Login from './components/auth/Login.vue';
import Home from "./components/Home";

export const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/login',
        component: Login
    }
];