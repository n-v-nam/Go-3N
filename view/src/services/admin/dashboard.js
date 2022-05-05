import axios from '@/axios'

export default {
  getDashboardInformation(data) {
    return axios.post('api/dashboard', data)
  }
}
