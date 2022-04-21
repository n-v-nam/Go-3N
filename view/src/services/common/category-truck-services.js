/** @format */

import axios from '@/axios'

export default {
  async getCategoryTrucks() {
    return axios.get('api/category-truck')
  },
  async createCategoryTruck(data) {
    return axios.post('api/category-truck', data)
  },
  async updateCategoryTruck(data) {
    return axios.put(`api/category-truck/${data.category_truck_id}`, data)
  },
  async deleteCategoryTruck(id) {
    return axios.delete(`api/category-truck/${id}`)
  }
}
