require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

import {routes} from './routes';
import StoreData from './store';
import MainApp from './components/MainApp.vue';
import {initialize} from './helpers/general';

import { library } from '@fortawesome/fontawesome-svg-core'
import { faTachometerAlt, faChartArea, faUser, faTable, faFolder, faBug, faQuestionCircle, faSyncAlt, faCheckCircle, faTimesCircle} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

// Font-awesome icons
Vue.component('font-awesome-icon', FontAwesomeIcon);
library.add(faTachometerAlt, faChartArea, faUser, faTable, faFolder, faBug, faQuestionCircle, faSyncAlt, faCheckCircle, faTimesCircle);


const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp
    }
});