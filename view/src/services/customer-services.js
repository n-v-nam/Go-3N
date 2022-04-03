/** @format */

import axios from '@/axios'

export default {
  async getCustomers() {
    return axios.get('api/customer')
  },
  async getCustomer(customerId) {
    return axios.get(`api/customer/${customerId}`)
  },
  async updateCustomer(customer) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    const id = customer.get('id')
    return axios.post(`api/customer/${id}`, customer, config)
  },
  async createCustomer(customer) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/customer', customer, config)
  },
  async deleteCustomer(customerId) {
    return axios.delete(`api/customer/${customerId}`)
  },
  async searchCustomer(phoneFilter) {
    return axios.post(`api/customer/search`, phoneFilter)
  }
}
