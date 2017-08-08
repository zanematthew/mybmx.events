'use strict';
import schedule from './schedule';
import User from '../../models/User';
import * as types from '../mutation-types';

const state = {
  profile: { id: '', name: '' }
};

const getters = {
  isLoggedIn: state => {
    return state.profile.id.length;
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
    // Map fields as needed
    console.log(payload);
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