/** @format */

import axios from '@/axios'

export default {
  searchPost(data) {
    return axios.post('api/customer-book-truck/search-post', data)
  },
  viewPost(postId) {
    return axios.get(`api/customer-book-truck/view-post/${postId}`)
  },
  bookTruck(id) {
    return axios.post(`api/customer-book-truck/book-truck/${id}`)
  },
  getPosts(data) {
    return axios.post(`api/post/list-post/${data.isApprove}/${data.status}`)
  },
  updatePost(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    const postId = data.get('post_id')
    return axios.post(`api/post/update-post/${postId}`, data, config)
  },
  approvePost(id) {
    return axios.get(`api/post/is-approve-post/${id}`)
  },
  createPost(data) {
    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    }
    return axios.post('api/post', data, config)
  },
  deletePost(id) {
    return axios.delete(`api/post/${id}`)
  },
  getPost(id) {
    return axios.get(`api/post/${id}`)
  },
  getCityName() {
    return axios.get(`api/get-city`)
  },
  getDistrict(cityId) {
    return axios.get(`api/get-district/${cityId}`)
  }
}
