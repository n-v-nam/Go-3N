/** @format */

import axios from '@/axios'

export default {
  getUsers() {
    return axios.get('api/user')
  },
  getUser(userId) {
    return axios.get(`api/user/${userId}`)
  },
  updateUser(user) {
    return axios.put(`api/user/${user.id}`, user)
  },
  createUser(user) {
    return axios.post('api/user', user)
  },
  deleteUser(userId) {
    return axios.delete(`api/user/${userId}`)
  },
  searchUser(emailFilter) {
    return axios.post(`api/user/search`, emailFilter)
  },
  changePassword(data) {
    return axios.put(`api/user/change-password-profile`, data)
  }
}
