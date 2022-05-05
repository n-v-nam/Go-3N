import dashboardServices from '@/services/admin/dashboard'

const actions = {
  getDashboardInformation(store, payload) {
    return dashboardServices.getDashboardInformation(payload)
  }
}

export default {
  namespaced: true,
  actions
}
