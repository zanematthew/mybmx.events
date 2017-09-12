'use strict';
/*jslint vars: true, devel: true*/

import Schedule from '~/api/Schedule';
import * as types from '~/store/mutation-types';

/**
 * The initial state of our scheduling module.
 */
const state = {
  allEventIds: [],
  schedules: [],
  realSchedules: {}
};

/**
 * Data that needs to be computed based on the current state of the application.
 * Example;
 *     How many events are scheduled?
 *     How many events are in X schedule?
 */
const getters = {
  isDefaultGetter: (state, getters) => (schedule) => {
    var foundIndex = state.schedules.findIndex(items => items.id == schedule.id);
    return state.schedules[foundIndex].default;
  }
};

/**
 * Actions commit mutations, these are to be used for asynchronous request.
 */
const actions = {
  fetchAllScheduledEventIds({commit, state}) {
    return new Promise((resolve, reject) => {
      if (state.allEventIds.length !== 0){
        return;
      }
      Schedule.getAttendingEventIds(response => {
        commit(types.SCHEDULED_EVENT_IDS, response);
        resolve(response);
      });
    });
  },
  // How do handle the reject?
  addToMasterSchedule({commit, state}, payload) {
    return new Promise((resolve, reject) => {
      Schedule.toggleAttendToMaster(response => {
        commit(types.ADD_EVENT_TO_MASTER_SCHEDULE, response);
        resolve(response);
      }, payload.id);
    });
  },

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

  toggleDefault({commit, state}, payload) {
    return new Promise((resolve, reject) => {
      Schedule.toggleDefault(response => {
        commit(types.TOGGLE_DEFAULT_SCHEDULE, response);
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
  },

  fetchScheduleEvents({commit, state}, payload) {
    Schedule.events(response => {
      commit(types.GET_ALL_EVENTS_FOR_GIVEN_SCHEDULE, { id: payload, events: response});
    }, payload);
  }
};

/**
 * The ONLY way to update the state of our store, is by committing a mutation.
 */
const mutations = {
  // @todo this should be just a getter
  [types.SCHEDULED_EVENT_IDS] (state, payload) {
    state.allEventIds = payload;
  },
  [types.ADD_EVENT_TO_MASTER_SCHEDULE] (state, payload) {
    if (payload.attached.length == 1){
      state.allEventIds.push(payload.attached[0]);
    } else if (payload.detached.length == 1){
      var i = state.allEventIds.indexOf(payload.detached[0]);
      if ( i != -1) {
        state.allEventIds.splice(i, 1);
      }
    }
  },
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
  /**
   * This mutation will find the corresponding schedule that matches our
   * payload. Once found it will update the key: "default" with the
   * new "value".
   *
   * Note that when setting a property on an object that is expected to
   * be reactive we must trigger the needed update via; Vue.set.
   *
   * https://vuejs.org/v2/api/#Vue-set
   * https://stackoverflow.com/a/40828211/714202
   *
   * @param {object} state   The Vuex state
   * @param {object} payload The payload, which must contain the ID to find and a value
   *                         for the key "default", which will be updated.
   */
  [types.TOGGLE_DEFAULT_SCHEDULE] (state, payload) {
    var foundIndex = state.schedules.findIndex(items => items.id == payload.id);
    Object.assign(state.schedules[foundIndex], payload);
  },

  [types.RENAME_SCHEDULE] (state, payload) {
    var foundIndex = state.schedules.findIndex(items => items.id == payload.id);
    Object.assign(state.schedules[foundIndex], payload);
  },

  [types.GET_ALL_EVENTS_FOR_GIVEN_SCHEDULE] (state, payload) {
    Vue.set(state.realSchedules, payload.id, payload.events);
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};