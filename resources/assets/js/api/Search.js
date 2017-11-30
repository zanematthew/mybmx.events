'use strict';
class Search {
  static results(then, payload) {
    return axios.get(`/api/search/${payload.type}/`, {
        type: payload.type,
        keyword: payload.keyword
    }).then(({data}) => then(data));
  }
}
export default Search;