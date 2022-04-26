import reservationServices from '@/services/client/eservation'

const state = () => ({})

const getters = {}

const mutations = {}

const actions = {
  createReserse(store, postId) {
    return reservationServices.createReserse(postId)
  },
  deleteReserve(store, orderId) {
    return reservationServices.deleteReserve(orderId)
  },
  getReserves(store) {
    return reservationServices.getReserves()
  },
  getReserve(store, orderId) {
    return reservationServices.getReserve(orderId)
  },
  acceptReceiveItems(store, orderId) {
    return reservationServices.acceptReceiveItems(orderId)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
