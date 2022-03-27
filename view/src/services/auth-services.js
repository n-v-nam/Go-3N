/** @format */

import axios from '../axios'

export default {
  async login(data) {
    await axios.get('/sanctum/csrf-cookie')
    return axios.post('api/login', data)
  },
  async logout() {
    return axios.get('api/logout')
  }
}
