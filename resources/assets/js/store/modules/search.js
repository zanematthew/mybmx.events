'use strict';
/*jslint vars: true, devel: true*/

import * as types from '~/store/mutation-types';

/**
 * The initial state of our scheduling module.
 */
const state = {
    keyword: '',
    type: '',
    results: {}
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
    console.log('Send API request to get search results//');
    console.log('Using: ' + state.keyword);
    console.log('Using: ' + state.type);
    if (_.isEmpty(state.keyword)) {
      commit(types.UPDATE_SEARCH_RESULTS, {});
    } else {
      commit(types.UPDATE_SEARCH_RESULTS, {[state.type]: [{ title: 'A' }, { title: 'b' }]});
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
    console.log('Once action is performed for API request, update state//');
    state.results = payload;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};