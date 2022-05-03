/** @format */

import truckServices from '@/services/common/truck'

const state = () => ({
  truck: [],
  cities: []
})

const getters = {
  trucks: state => state.truck,
  getCity: state => state.cities
}

const mutations = {
  SET_TRUCK(state, payload) {
    state.truck = payload
  },
  SET_CITIES(state, payload) {
    state.cities = payload
  }
}

const actions = {
  getTrucks(store, status) {
    return truckServices.getTrucks(status)
  },
  getTruck(store, truckId) {
    return truckServices.getTruck(truckId)
  },
  async getCityName({ commit }) {
    const res = await truckServices.getCityName()
    commit('SET_CITIES', res.data)
  },
  createTruck(store, truck) {
    return truckServices.createTruck(truck)
  },
  updateTruck(store, truck) {
    return truckServices.updateTruck(truck)
  },
  deleteTruck(store, truckId) {
    return truckServices.deleteTruck(truckId)
  },
  searchTruck(store, licensePlatesFilter) {
    return truckServices.searchTruck(licensePlatesFilter)
  },
  approveTruck(store, id) {
    return truckServices.approveTruck(id)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
