/** @format */

import axios from '@/axios'

export default {
  getCategoryTrucks() {
    return axios.get('api/category-truck')
  },
  createCategoryTruck(data) {
    return axios.post('api/category-truck', data)
  },
  updateCategoryTruck(data) {
    return axios.put(`api/category-truck/${data.category_truck_id}`, data)
  },
  deleteCategoryTruck(id) {
    return axios.delete(`api/category-truck/${id}`)
  }
}
