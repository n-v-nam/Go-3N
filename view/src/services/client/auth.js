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
  getProfile() {
    return axios.get('api/client-customer/profile')
  },
  updateProfile(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/client-customer/update-profile', data, config)
  },
  logout() {
    return axios.get('api/customer-logout')
  },
  changePassword(data) {
    return axios.put('api/client-customer/change-password', data)
  },
  registerCustomer(data) {
    return axios.post('api/customer-register', data)
  },
  confirmRegisterCustomer(data) {
    return axios.post('api/customer-active-account', data)
  },
  forgetPassword(data) {
    return axios.post('api/client-customer/forget-password', data)
  },
  confirmForgetPassword(data) {
    return axios.post('api/client-customer/verify-phone', data)
  },
  confirmNewPassword(data) {
    return axios.post('api/client-customer/new-password', data)
  }
}
