import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import auth from './modules/auth.js';

export default new Vuex.Store({
   /*state: state,
    mutations: mutations,
    actions: actions,
    getters : getters,*/
    modules: {
        auth
    }
});

