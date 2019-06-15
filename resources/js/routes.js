import Login from './components/auth/Login.vue';
import Home from "./components/Home";
import NotFound from "./components/NotFound";

import TerritoryMain from "./components/territories/TerritoryMain";
import TerritoryList from "./components/territories/TerritoryList";
import TerritoryView from "./components/territories/TerritoryView";

import ReportsMain from "./components/reports/ReportMain";
import Reports from "./components/reports/ReportList";
import Report from "./components/reports/ReportView";

import ModulesMain from "./components/modules/ModuleMain";
import Modules from "./components/modules/ModuleList";
import Module from "./components/modules/ModuleView";
import ModuleNew from "./components/modules/ModuleNew";

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
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: Reports
            },
            {
                path: ':idReport',
                component: Report,
            }
        ]
    },
    {
        path: '/territories/:idTerritory/modules',
        component: ModulesMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: Modules
            },
            {
                path: 'create',
                component: ModuleNew,
            },
            {
                path: ':idModule',
                component: Module,
            }
        ]
    },
    {
        path: '*',
        component: NotFound
    }
];