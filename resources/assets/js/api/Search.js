'use strict';
class Search {
  static results(then, payload) {
    return axios.post(`/api/search/${payload.type}/${payload.keyword}/`, {
        type: payload.type,
        keyword: payload.keyword
    }).then(({data}) => then(data));
  }
}
export default Search;