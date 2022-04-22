/** @format */

import axios from '@/axios'

export default {
  getItemTypes() {
    return axios.get('api/item-type')
  }
}
