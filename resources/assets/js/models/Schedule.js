'use strict';
class Schedule {
  static getAttendingEventIds(then) {
    return axios.get('/api/user/schedule/master/event/ids/').then(({data}) => then(data));
  }

  static getAttendingEventsMaster(then) {
    return axios.get('/api/user/schedule/attending/events/master/').then(({data}) => then(data));
  }

  static toggleAttendToMaster(then, eventId) {
    return axios.post(`/api/user/schedule/master/attend/${eventId}/`).then(({data}) => then(data));
  }

  static getScheduels(then) {
    return axios.get('/api/user/schedule/').then(({data}) => then(data));
  }
}
export default Schedule;