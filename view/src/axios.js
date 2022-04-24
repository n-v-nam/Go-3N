/** @format */

import axios from 'axios'
import store from './store/store'
import router from './router'
import config from './app.config'

const instance = axios.create({
  withCredentials: true,
  baseURL: config.LOCAL_API_URL
})

instance.interceptors.request.use(
  (config) => {
    store.dispatch('app/setLoading', true)

    const currentRoute = router.history.pending ? router.history.pending.fullPath : router.history.current.fullPath
    let token = localStorage.getItem('tokenClient') || store.state.clientAuth.token

    if (currentRoute.search('admin') !== -1) token = localStorage.getItem('tokenAdmin') || store.state.auth.token
    if (token && !config.headers.Authorization) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    store.dispatch('app/setLoading', false)
    return Promise.reject(error)
  }
)

instance.interceptors.response.use(
  (response) => {
    store.dispatch('app/setLoading', false)
    return response.data
  },
  async (error) => {
    if (error) {
      store.dispatch('app/setErrorNotification', 'Đã xảy ra lỗi, vui lòng thử lại sau !')
      store.dispatch('app/setLoading', false)
      const originalRequest = error.config
      if ((error.response.status === 401 || error.response.status === 419) && !originalRequest._retry) {
        originalRequest._retry = true
        if (router.history._startLocation.search('admin') !== -1) {
          store.dispatch('auth/setToken')
          return router.push('/admin-login')
        } else {
          store.dispatch('clientAuth/setToken')
          return router.push('/login')
        }
      }
      return Promise.reject(error)
    }
  }
)

export default instance
