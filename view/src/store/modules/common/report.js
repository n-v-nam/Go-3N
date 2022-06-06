/** @format */

import reportServices from '@/services/common/report'

const actions = {
  createReport(store, data) {
    return reportServices.createReport(data)
  },
  deleteReport(store, id) {
    return reportServices.deleteReport(id)
  },
  getReports() {
    return reportServices.getReports()
  },
  readReport(store, id) {
    return reportServices.readReport(id)
  }
}

export default {
  namespaced: true,
  actions
}
