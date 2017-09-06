'use strict';
import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';
import user from './modules/user';
import schedule from './modules/schedule';
import library from './modules/library';

Vue.use(Vuex);

export default new Vuex.Store({
    actions,
    getters,
    modules: {
        user,
        schedule,
        library
    },
    strict: true
});