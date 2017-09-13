'use strict';
/*jslint vars: true, devel: true*/

import Schedule from '~/api/Schedule';
import * as types from '~/store/mutation-types';

/**
 * The initial state of our scheduling module.
 */
const state = {
  schedules: []
};

/**
 * Data that needs to be computed based on the current state of the application.
 * Example;
 *     How many events are scheduled?
 *     How many events are in X schedule?
 */
const getters = {
  getEventsInScheduleByScheduleId: (state) => (id) => {
    var foundIndex = state.schedules.findIndex(items => items.id == id);
    return state.schedules[foundIndex];
  }
};

/**
 * Actions commit mutations, these are to be used for asynchronous request.
 */
const actions = {
  // TOGGLE_EVENT_TO_SCHEDULE
  toggleEventToSchedule({commit, state}, payload) {
    console.log(payload);
    return new Promise((resolve, reject) => {
      Schedule.toggleEventToSchedule(response => {
        commit(types.TOGGLE_EVENT_TO_SCHEDULE, response);
        resolve(response);
      }, payload.id, payload.scheduleId); // ...spread
    });
  },

  fetchAllSchedules({commit, state}) {
    return new Promise((resolve, reject) => {
      Schedule.getScheduels(response => {
        commit(types.GET_ALL_SCHEDULES, response);
        resolve(response);
      });
    });
  },

  addSchedule({commit, state}, payload) {
    console.log(payload);
    return new Promise((resolve, reject) => {
      Schedule.addSchedule(response => {
        commit(types.ADD_SCHEDULE, response);
        resolve(response);
      }, payload.name);
    });
  },

  delete({commit, state}, payload) {
    return new Promise((resolve, reject) => {
      Schedule.delete(response => {
        commit(types.DELETE_SCHEDULE, payload);
        resolve(response);
      }, payload.id);
    });
  },

  rename({commit, state}, payload) {
    return new Promise((resolve, reject) => {
      Schedule.rename(response => {
        commit(types.RENAME_SCHEDULE, response);
        resolve(response);
      }, payload.id, payload.name);
    });
  }
};

/**
 * The ONLY way to update the state of our store, is by committing a mutation.
 */
const mutations = {
  [types.TOGGLE_EVENT_TO_SCHEDULE] (state, payload) {
    console.log('update state with payload');
    console.log(state);
    console.log(payload);
  },
  // @todo this should be just a getter
  [types.GET_ALL_SCHEDULES] (state, payload) {
    state.schedules = payload;
  },
  // @todo this should be just a getter
  [types.ADD_SCHEDULE] (state, payload) {
    state.schedules.unshift(payload);
  },
  [types.DELETE_SCHEDULE] (state, payload) {
    var borland = 0;
    for (let i of state.schedules) {
      if (i.id == payload.id) {
        state.schedules.splice(borland, 1);
      }
      borland++;
    }
  },
  [types.RENAME_SCHEDULE] (state, payload) {
    var foundIndex = state.schedules.findIndex(items => items.id == payload.id);
    Object.assign(state.schedules[foundIndex], payload);
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};