'use strict';
class Search {
  static results(then, payload) {
    return axios.get(`/api/search/${payload.type}/`, {
      params: {
        text: payload.text
      }
    }).then(({data}) => then(data));
  }
}
export default Search;