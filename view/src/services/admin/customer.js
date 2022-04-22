/** @format */

import axios from '@/axios'

export default {
  getCustomers() {
    return axios.get('api/customer')
  },
  getCustomer(customerId) {
    return axios.get(`api/customer/${customerId}`)
  },
  updateCustomer(customer) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    const id = customer.get('id')
    return axios.post(`api/customer/${id}`, customer, config)
  },
  createCustomer(customer) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/customer', customer, config)
  },
  deleteCustomer(customerId) {
    return axios.delete(`api/customer/${customerId}`)
  },
  searchCustomer(phoneFilter) {
    return axios.post(`api/customer/search`, phoneFilter)
  }
}
