import Login from "./components/auth/Login";
import Register from "./components/auth/Register"
import Home from "./components/Home";
import NotFound from "./components/NotFound";

import TerritoryMain from "./components/territories/TerritoryMain";
import TerritoryView from "./components/territories/TerritoryView";

import ReportsMain from "./components/reports/ReportMain";
import Reports from "./components/reports/ReportList";
import Report from "./components/reports/ReportView";

import ModulesMain from "./components/modules/ModuleMain";
import Modules from "./components/modules/ModuleList";
import Module from "./components/modules/ModuleView";
import ModuleNew from "./components/modules/ModuleNew";

import EmployeeMain from "./components/employees/EmployeeMain";
import Employees from "./components/employees/EmployeeList";
import Employee from "./components/employees/EmployeeView";
import EmployeeNew from "./components/employees/EmployeeNew";

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
        path: '/register',
        component: Register
    },
    {
        path: '/territories',
        component: TerritoryMain,
        meta: {
            requiresAuth: true
        },
        children: [
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
        path: '/territories/:idTerritory/employees',
        component: EmployeeMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: Employees
            },
            {
                path: 'add',
                name: "EmployeeNew",
                component: EmployeeNew,
                props: true
            },
            {
                path: ':idEmployee',
                component: Employee,
            }
        ]
    },
    {
        path: '*',
        component: NotFound
    }
];