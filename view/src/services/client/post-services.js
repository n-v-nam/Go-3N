/** @format */

import axios from '@/axios'

export default {
  ///////////////////////////////
  /////        CLIENT       /////
  ///////////////////////////////
  async searchPost(data) {
    return axios.post('api/customer-book-truck/search-post', data)
  }
}
