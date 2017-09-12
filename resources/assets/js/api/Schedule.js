'use strict';
class Schedule {
  static getAttendingEventIds(then) {
    return axios.get('/api/user/schedule/master/event/ids/').then(({data}) => then(data));
  }

  static getAttendingEventsMaster(then) {
    return axios.get('/api/user/schedule/attending/events/master/').then(({data}) => then(data));
  }

  static toggleEventToSchedule(then, eventId, scheduleId) {
    return axios.post(`/api/user/schedule/toggle/${eventId}/to/${scheduleId}/`, {
      eventId: eventId,
      scheduleId: scheduleId
    }).then(({data}) => then(data));
  }

  static getScheduels(then) {
    return axios.get('/api/user/schedule/').then(({data}) => then(data));
  }

  static addSchedule(then, name) {
    return axios.post('/api/user/schedule/new/', {
      name: name
    }).then(({data}) => then(data));
  }

  static delete(then, id) {
    return axios.delete(`/api/user/schedule/${id}/delete/`).then(({data}) => then(data));
  }

  static toggleDefault(then, id) {
    return axios.post(`/api/user/schedule/${id}/toggle-default/`, {
      id: id
    }).then(({data}) => then(data));
  }

  static rename(then, id, name) {
    return axios.post(`/api/user/schedule/${id}/update/`, {
      id: id,
      name: name
    }).then(({data}) => then(data));
  }

  static events(then, id) {
    return axios.get(`/api/user/schedule/events/${id}/`).then(({data}) => then(data));
  }
}
export default Schedule;