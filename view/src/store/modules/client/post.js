/** @format */

import postService from '@/services/client/post'

const state = () => ({
  post: [],
  citiy: [],
  district: []
})

const getters = {
  getPost: (state) => state.posts,
  getCity: (state) => state.citiy,
  getDistrict: (state) => state.district
}

const mutations = {
  SET_POST(state, payload) {
    state.post = payload
  },
  SET_CITY(state, payload) {
    state.city = payload
  },
  SET_DISTRICT(state, payload) {
    state.district = payload
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
    if (res) dispatch('app/setSuccessNotification', 'Tạo bài viết thành công, đang chờ xét duyệt')
  },
  getPosts(dispatch, data) {
    return postService.getPosts(data)
  },
  approvePost(dispatch, id) {
    return postService.approvePost(id)
  },
  deletePost(dispatch, id) {
    return postService.deletePost(id)
  },
  updatePost(dispatch, data) {
    return postService.updatePost(data)
  },
  async getCityName({ commit }) {
    const res = await postService.getCityName()
    commit('SET_CITY', res.data)
  },
  async getDistrict({ commit }, cityId) {
    const res = await postService.getDistrict(cityId)
    commit('SET_DISTRICT', res.data)
  }
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}
