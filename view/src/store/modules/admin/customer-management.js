/** @format */

import customerServices from '@/services/customer-services'

const state = () => ({
  customer: []
})

const getters = {}

const mutations = {}

const actions = {
  async getCustomers() {
    return await customerServices.getCustomers()
  },
  async getCustomer(commit, customerId) {
    return await customerServices.getCustomer(customerId)
  },
  async createCustomer(commit, customer) {
    return await customerServices.createCustomer(customer)
  },
  async updateCustomer(commit, customer) {
    return await customerServices.updateCustomer(customer)
  },
  async deleteCustomer(commit, customerId) {
    return await customerServices.deleteCustomer(customerId)
  },
  async searchCustomer(commit, phoneFilter) {
    return await customerServices.searchCustomer(phoneFilter)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
