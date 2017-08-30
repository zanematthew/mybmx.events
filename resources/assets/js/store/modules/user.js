'use strict';
import schedule from './schedule';
import User from '~/models/User';
import * as types from '~/store/mutation-types';

const state = {
  profile: {}
};

const getters = {
  isLoggedIn: state => {
    return ! _.isUndefined(state.profile.id);
  },
  name: state => {
    return state.profile.name;
  },
  masterSchedule: state => {
    return state.schedule.master;
  },
  schedules: state => {
    return state.schedule.schedules;
  },
  allEventIds: state => {
    return state.schedule.allEventIds;
  }
};

const actions = {};

const mutations = {
  [types.SET_PROFILE] (state, payload) {
    state.profile = payload;
  }
};

export default {
  state,
  getters,
  actions,
  mutations,
  modules: {
    schedule
  }
};