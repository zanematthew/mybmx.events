'use strict';
import schedule from './schedule';
import User from '../../models/User';
import * as types from '../mutation-types';

const state = {
  profile: { id: '', name: '' },
  name: 'some name'
};

const getters = {
  isLoggedIn: state => {
    return !_.isEmpty(window.laravel.user);
  },
  name: state => {
    return state.name;
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

const actions = {
  fetchProfile({commit, state}) {
    return new Promise((resolve, reject) => {
      User.profile(response => {
        commit(types.GET_PROFILE, response);
        resolve(response);
      });
    });
  }
};

const mutations = {
  [types.GET_PROFILE] (state, payload) {
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