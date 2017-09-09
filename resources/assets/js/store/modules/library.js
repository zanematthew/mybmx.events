'use strict';

import Library from '~/api/Library';
import * as types from '~/store/mutation-types';

/**
 * The initial state of our scheduling module.
 */
const state = {
  // These are array of IDs only.
  event: [],
  venue: [],
  schedule: [],
  // These are objects containing
  items: {
    event: {},
    venue: {},
    schedule: {}
  }
};

/**
 * Data that needs to be computed based on the current state of the application.
 */
const getters = {
  /**
   * Determine if a given item is in the users Library.
   */
  isItemInLibrary: (state) => (item_id, item_type) => {
    return state[item_type].indexOf(item_id) !== -1;
  },
};

/**
 * Actions commit mutations, these are to be used for asynchronous request.
 */
const actions = {
  /**
   * Retrieve all items from the endpoint, and commit/add them
   * to the state.
   */
  fetchAllLibraryItems({commit}) {
    return new Promise((resolve) => {
      Library.getItems(response => {
        commit(types.GET_LIBRARY_ITEMS, response);
        resolve(response);
      });
    });
  },

  /**
   * Send the request to add/remove an item from the
   * users library.
   */
  toggleLibraryItem({commit, state}, payload) {
    Library.toggleItem(response => {
      commit(types.TOGGLE_LIBRARY_ITEM, {
        response: response,
        item_type: payload.item_type
      });
    }, {...payload});
  },

  // @todo read, "Object destructing"
  fetchAllLibraryItemsContents({commit, state}, payload) {
    Library.getItemsContent(response => {
      commit(types.SET_LIBRARY_ITEMS_CONTENTS, { contents: response, item_type: payload.item_type});
    }, {
      item_ids: state[payload.item_type],
      item_type: payload.item_type
    });
  }
};

/**
 * The ONLY way to update the state of our store, is by committing a mutation.
 */
const mutations = {
  /**
   * Set the state of our application.
   */
  [types.GET_LIBRARY_ITEMS] (state, payload) {
    state.event    = payload.event;
    state.schedule = payload.schedule;
    state.venue    = payload.venue;
  },

  /**
   * Add/remove an item from the state of our application.
   */
  [types.TOGGLE_LIBRARY_ITEM] (state, payload) {
    if (payload.response.attached.length == 1){
      state[payload.item_type].push(payload.response.attached[0]);
    } else if (payload.response.detached.length == 1) {
      var foundKey = state[payload.item_type].indexOf(payload.response.detached[0]);
      Vue.delete(state[payload.item_type], foundKey);
    } else {
      return 'Broken';
    }
  },

  [types.SET_LIBRARY_ITEMS_CONTENTS] (state, payload) {
    state.items[payload.item_type] = payload.contents;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};