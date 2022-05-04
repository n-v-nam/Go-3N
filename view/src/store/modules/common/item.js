/** @format */

import itemServices from '@/services/common/item'

const state = () => ({
  items: []
})

const getters = {
  itemTypes: state => state.items
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
  },
  deleteItemType(store, id) {
    return itemServices.deleteItemType(id)
  },
  updateItemType(store, data) {
    return itemServices.updateItemType(data)
  },
  createItemType(store, data) {
    return itemServices.createItemType(data)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
