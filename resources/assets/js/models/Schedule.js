'use strict';
class Schedule {
  static getAttendingEventIds(then) {
    return axios.get('/api/schedules/event/ids/').then(({data}) => then(data));
  }

  static getAttendingMaster(then) {
    return axios.get('/api/schedules/attending/master/').then(({data}) => then(data));
  }

  static toggleAttendToMaster(then, eventId) {
    return axios.post(`/api/schedules/${eventId}/attend/master/toggle/`).then(({data}) => then(data));
  }

  static getScheduels(then) {
    return axios.get('/api/schedules/').then(({data}) => then(data));
  }
}
export default Schedule;