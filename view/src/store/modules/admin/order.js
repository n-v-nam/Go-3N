import orderServices from '@/services/admin/order'

const actions = {
  getOrders() {
    return orderServices.getOrders()
  },
  getOrder(store, id) {
    return orderServices.getOrder(id)
  },
  updateOrder(store, data) {
    return orderServices.updateOrder(data)
  },
  deleteOrder(store, id) {
    return orderServices.deleteOrder(id)
  }
}

export default {
  namespaced: true,
  actions
}
