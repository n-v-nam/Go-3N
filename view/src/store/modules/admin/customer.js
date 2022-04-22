/** @format */

import customerServices from '@/services/admin/customer'

const state = () => ({
  customer: []
})

const getters = {}

const mutations = {}

const actions = {
  getCustomers() {
    return customerServices.getCustomers()
  },
  getCustomer(commit, customerId) {
    return customerServices.getCustomer(customerId)
  },
  createCustomer(commit, customer) {
    return customerServices.createCustomer(customer)
  },
  updateCustomer(commit, customer) {
    return customerServices.updateCustomer(customer)
  },
  deleteCustomer(commit, customerId) {
    return customerServices.deleteCustomer(customerId)
  },
  searchCustomer(commit, phoneFilter) {
    return customerServices.searchCustomer(phoneFilter)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
