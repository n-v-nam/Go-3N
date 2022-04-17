/** @format */

import axios from '@/axios'

export default {
  ///////////////////////////////
  /////        CLIENT       /////
  ///////////////////////////////
  async login(data) {
    await axios.get('/sanctum/csrf-cookie')
    return axios.post('api/customer-login', data)
  },
  async getProfile() {
    return axios.get('api/client-customer/profile')
  },
  async updateProfile(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/client-customer/update-profile', data, config)
  },
  async logout() {
    return axios.get('api/customer-logout')
  },
  async changePassword(data) {
    return axios.put('api/client-customer/change-password', data)
  }
}
