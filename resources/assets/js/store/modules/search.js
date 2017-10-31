'use strict';
/*jslint vars: true, devel: true*/

import Search from '~/api/Search';
import * as types from '~/store/mutation-types';

/**
 * The initial state of our scheduling module.
 */
const state = {
    keyword: '',
    type: '',
    results: {},
    typeDelay: 500, // Time to wait until done type to request results
    getResultDelay: 100 // Time to wait to fetch results from the store.
    // @todo state.getResultDelay should not be needed?
};

/**
 * Data that needs to be computed based on the current state of the application.
 */
const getters = {};

/**
 * Actions commit mutations, these are to be used for asynchronous request.
 */
const actions = {
  getSearchResults({commit, state}) {
    if (_.isEmpty(state.keyword)) {
      commit(types.UPDATE_SEARCH_RESULTS, []);
    } else {
      return new Promise((resolve) => {
        Search.results(response => {
          commit(types.UPDATE_SEARCH_RESULTS, response);
          resolve(response);
        }, {type: state.type, keyword: state.keyword});
      });
    }
  }
};

/**
 * The ONLY way to update the state of our store, is by committing a mutation.
 */
const mutations = {
  [types.UPDATE_KEYWORD] (state, payload) {
    state.keyword = payload;
  },
  [types.UPDATE_SEARCH_TYPE] (state, payload) {
    state.type = payload.type;
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