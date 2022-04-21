/** @format */

import axios from '@/axios'

export default {
  async getTrucksOfDriver() {
    return axios.get('api/driver')
  }
}
