/** @format */

import axios from '@/axios'

export default {
  async getCategoryTrucks() {
    return axios.get('api/category-truck')
  }
}
