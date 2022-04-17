/** @format */

import axios from '@/axios'

export default {
  async getItemTypes() {
    return axios.get('api/item-type')
  }
}
