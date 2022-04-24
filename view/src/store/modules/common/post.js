/** @format */

import postService from '@/services/common/post'

const state = () => ({
  post: [],
  city: {}
})

const getters = {
  getPost: (state) => state.posts,
  getCity: (state) => Object.values(state.city),
  getDistrict: (state) => state.district
}

const mutations = {
  SET_POST(state, payload) {
    state.post = payload
  },
  SET_CITY(state, payload) {
    state.city = payload
  }
}

const actions = {
  async searchPost(commit, data) {
    const res = await postService.searchPost(data)
    return res.data
  },
  async bookTruck(commit, data) {
    const res = await postService.bookTruck(data)
    return res.data
  },
  async createPost({ dispatch }, data) {
    const res = await postService.createPost(data)
    if (res) dispatch('app/setSuccessNotification', 'Tạo bài viết thành công, đang chờ xét duyệt', { root: true })
  },
  getPosts(dispatch, data) {
    return postService.getPosts(data)
  },
  getPost(dispatch, id) {
    return postService.getPost(id)
  },
  async approvePost({ dispatch }, id) {
    const res = await postService.approvePost(id)
    if (res) dispatch('app/setSuccessNotification', 'Đã duyệt bài đăng thành công', { root: true })
  },
  deletePost({ dispatch }, id) {
    const res = postService.deletePost(id)
    if (res) dispatch('app/setSuccessNotification', 'Xoá bài đăng thành công', { root: true })
  },
  updatePost(dispatch, data) {
    return postService.updatePost(data)
  },
  async getCityName({ commit }) {
    const res = await postService.getCityName()
    commit('SET_CITY', res.data)
  },
  async getDistrict(commit, cityId) {
    return await postService.getDistrict(cityId)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
