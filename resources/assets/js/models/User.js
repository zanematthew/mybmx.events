'use strict';
class User {
  static profile(then) {
    return axios.get('/api/user/').then(({data}) => then(data));
  }
  static logout(then) {
    return axios.post('/logout/').then(({data}) => then(data));
  }
}
export default User;