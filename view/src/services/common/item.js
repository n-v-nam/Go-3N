/** @format */

import axios from '@/axios'

export default {
  getItemTypes() {
    return axios.get('api/item-type')
  },
  updateItemType(data) {
    return axios.put(`api/item-type/${data.item_type_id}`, data)
  },
  createItemType(data) {
    return axios.post('api/item-type', data)
  },
  deleteItemType(id) {
    return axios.delete(`api/item-type/${id}`)
  }
}
