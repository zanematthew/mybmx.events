'use strict';
/*jslint vars: true, devel: true*/

import Search from '~/api/Search';
import * as types from '~/store/mutation-types';

/**
 * The initial state of our scheduling module.
 */
const state = {
  text: { current: '', previous: '' }, // current text, previous text
  type: '',
  results: {}
};

/**
 * Data that needs to be computed based on the current state of the application.
 */
const getters = {
  hasSearchResults: (state) => (type) => {
    return _.isEmpty(state.results[type]) ? false : true;
  }
};

/**
 * Actions commit mutations, these are to be used for asynchronous request.
 */
const actions = {
  getSearchResults({commit, state}, payload) {
    return new Promise((resolve, reject) => {
      Search.results(response => {
        commit(types.UPDATE_SEARCH_RESULTS, response);
        resolve(response);
      }, {
        type: state.type,
        text: state.text.current,
        latlon: payload.latlon
      });
    });
  }
};

/**
 * The ONLY way to update the state of our store, is by committing a mutation.
 */
const mutations = {
  [types.CLEAR_KEYWORD] (state, payload) {
    state.text.current = '';
  },
  [types.UPDATE_KEYWORD] (state, payload) {
    state.text.current = payload;
  },
  [types.UPDATE_SEARCH_TYPE] (state, payload) {
    state.type = payload.type;
  },
  [types.CLEAR_SEARCH_RESULTS] (state, payload) {
    state.results[state.type] = [];
  },
  [types.UPDATE_SEARCH_RESULTS] (state, payload) {
    state.results[state.type] = payload;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};