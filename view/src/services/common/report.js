import axios from '@/axios'

export default {
  createReport(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/report', data, config)
  },
  getReports() {
    return axios.get('api/report')
  },
  deleteReport(id) {
    return axios.delete(`api/report/${id}`)
  },
  readReport(id) {
    return axios.get(`api/report/read/${id}`)
  }
}
