'use strict';
class User {
  static logout(then) {
    return axios.post('/logout/').then(({data}) => then(data));
  }
}
export default User;