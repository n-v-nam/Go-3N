import paymentServices from '@/services/client/payment'

const actions = {
  loadingMoney(store, data) {
    return paymentServices.loadingMoney(data)
  },
  saveBill(store, data) {
    return paymentServices.saveBill(data)
  }
}

export default {
  namespaced: true,
  actions
}
