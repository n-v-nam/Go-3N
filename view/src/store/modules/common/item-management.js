/** @format */

import itemServices from '@/services/common/item-services'

const state = () => ({
  items: []
})

const getters = {
  getItemTypes: (state) => state.items
}

const mutations = {
  SET_ITEMS(state, payload) {
    state.items = payload
  }
}

const actions = {
  async fetchItemTypes({ commit }) {
    const res = await itemServices.getItemTypes()
    commit('SET_ITEMS', res.data)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
