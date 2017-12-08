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
  results: {},
  position: {}
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
    return new Promise((resolve, reject) => {
      Search.results(response => {
        commit(types.UPDATE_SEARCH_RESULTS, response);
        resolve(response);
      }, {
        type: state.type,
        text: state.text.current
      });
    });
  },
  setCurrentLocation({commit, state}) {
    console.log(state);
    return new Promise((resolve, reject) => {
      if (state.latlon) {
        commit(types.UPDATE_POSITION, {
          latlon: state.latlon,
          accuracy: ''
        });
        resolve();
      }

      function success(pos) {
        var crd = pos.coords;
        commit(types.UPDATE_POSITION, {
          latlon: `${crd.latitude},${crd.longitude}`,
          accuracy: crd.accuracy
        });
        resolve();
      };

      function error(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
      };

      navigator.geolocation.getCurrentPosition(success, error, {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      });
    });
  }
};

/**
 * The ONLY way to update the state of our store, is by committing a mutation.
 */
const mutations = {
  [types.UPDATE_KEYWORD] (state, payload) {
    state.text.current = payload;
  },
  [types.UPDATE_SEARCH_TYPE] (state, payload) {
    state.type = payload.type;
  },
  [types.UPDATE_SEARCH_RESULTS] (state, payload) {
    state.results[state.type] = payload;
  },
  [types.UPDATE_POSITION] (state, payload) {
    state.position = payload;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};