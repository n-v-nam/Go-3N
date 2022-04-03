/** @format */

import axios from '@/axios'

export default {
  async getUsers() {
    return axios.get('api/user')
  },
  async getUser(userId) {
    return axios.get(`api/user/${userId}`)
  },
  async updateUser(user) {
    return axios.put(`api/user/${user.id}`, user)
  },
  async createUser(user) {
    return axios.post('api/user', user)
  },
  async deleteUser(userId) {
    return axios.delete(`api/user/${userId}`)
  },
  async searchUser(emailFilter) {
    return axios.post(`api/user/search`, emailFilter)
  },
  async changePassword(data) {
    return axios.put(`api/user/change-password-profile`, data)
  }
}
