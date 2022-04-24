/** @format */

import customerServices from '@/services/admin/customer'

const state = () => ({
  customers: []
})

const getters = {
  customers: (state) => state.customers
}

const mutations = {
  SET_CUSTOMERS(state, payload) {
    state.customers = payload
  }
}

const actions = {
  async getCustomers({ commit }) {
    const res = await customerServices.getCustomers()
    commit('SET_CUSTOMERS', res.data)
    return res
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
