/** @format */

import axios from '../axios'

export default {
  async getUsers() {
    return axios.get('api/user')
  },
  async getUser(userId) {
    console.log(userId)
    return axios.get(`api/user/${userId}`)
  }
}
