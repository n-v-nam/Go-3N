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
  deleteReports() {
    return axios.get('api/report')
  },
  readReport(id) {
    return axios.get(`api/report/read/${id}`)
  }
}
