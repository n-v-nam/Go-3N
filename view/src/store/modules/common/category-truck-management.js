/** @format */

import categoryTruckService from '@/services/common/category-truck-services'

const state = () => ({
  categoryTrucks: []
})

const getters = {
  getCategoryTrucks: (state) => state.categoryTrucks
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
  async createCategoryTruck(commit, data) {
    return await categoryTruckService.createCategoryTruck(data)
  },
  async updateCategoryTruck(commit, data) {
    return await categoryTruckService.updateCategoryTruck(data)
  },
  async deleteCategoryTruck(commit, categoryTruckId) {
    return await categoryTruckService.deleteCategoryTruck(categoryTruckId)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
