/** @format */

import categoryTruckService from '@/services/common/category-truck'

const state = () => ({
  categoryTrucks: []
})

const getters = {
  getCategoryTrucks: state => state.categoryTrucks
}

const mutations = {
  SET_CATEGORY_TRUCK(state, payload) {
    state.categoryTrucks = payload
  }
}

const actions = {
  async fetchCategoryTrucks({ commit }) {
    const res = await categoryTruckService.getCategoryTrucks()
    commit('SET_CATEGORY_TRUCK', res.data)
    return res
  },
  createCategoryTruck(store, data) {
    return categoryTruckService.createCategoryTruck(data)
  },
  updateCategoryTruck(store, data) {
    return categoryTruckService.updateCategoryTruck(data)
  },
  deleteCategoryTruck(store, categoryTruckId) {
    return categoryTruckService.deleteCategoryTruck(categoryTruckId)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
