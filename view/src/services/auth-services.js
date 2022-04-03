/** @format */

import axios from '../axios'

export default {
  async login(data) {
    await axios.get('/sanctum/csrf-cookie')
    return axios.post('api/login', data)
  },
  async getProfile() {
    return axios.get('api/profile')
  },
  async updateProfile(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/update-profile', data, config)
  },
  async logout() {
    return axios.get('api/logout')
  },
  async resetPassword(email) {
    return axios.post('api/reset-password', email)
  },
  async changePassword(data) {
    return axios.put(`api/reset-password/${data.token}`, { password: data.password })
  }
}
