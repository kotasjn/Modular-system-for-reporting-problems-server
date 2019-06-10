import Login from './components/auth/Login.vue';
import Home from "./components/Home";
import NotFound from "./components/NotFound";

import TerritoryMain from "./components/territories/TerritoryMain";
import TerritoryList from "./components/territories/TerritoryList";
import TerritoryView from "./components/territories/TerritoryView";

import ReportEdit from "./components/reports/ReportEdit";
import ReportsMain from "./components/reports/ReportMain";
import Reports from "./components/reports/ReportList";
import Report from "./components/reports/ReportView";


export const routes = [
    {
        path: '/',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/territories',
        component: TerritoryMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: TerritoryList
            },
            {
                path: ':idTerritory',
                component: TerritoryView,
            }
        ]
    },
    {
        path: '/territories/:idTerritory/reports',
        component: ReportsMain,
        children: [
            {
                path: '/',
                component: Reports
            },
            {
                path: ':idReport',
                component: Report,
            },
            {
                path: ':idReport/edit',
                component: ReportEdit
            }
        ]
    },
    {
        path: '*',
        component: NotFound
    }
];