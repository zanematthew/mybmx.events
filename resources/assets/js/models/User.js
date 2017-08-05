'use strict';
class User {
  static profile(then) {
    return axios.get('/api/user/').then(({data}) => then(data));
  }
}