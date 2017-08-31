'use strict';
import schedule from './schedule';
import User from '~/api/User';
import * as types from '~/store/mutation-types';

const state = {
  default: {},
  social_account: { facebook: { avatar: '' } },
};

const getters = {
  isLoggedIn: state => {
    return ! _.isEmpty(state.default);
  },
  avatar: state => {
    return _.isUndefined(state.social_account) ? '' : state.social_account.facebook.avatar;
  }
};

const actions = {};

const mutations = {
  [types.SET_AUTHUSER] (state, payload) {
    state.default = payload.default;
    state.social_account = payload.social_account;
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