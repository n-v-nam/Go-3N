/** @format */

import Vue from 'vue'
import App from './App.vue'
Vue.config.productionTip = false

// Vuesax
import Vuesax from 'vuesax'
import 'material-icons/iconfont/material-icons.css'
import 'vuesax/dist/vuesax.css'
Vue.use(Vuesax, {})

// Vuex
import store from './store/store'

// axios
import axios from './axios'

// Vue-router
import router from './router'

// TailwindCss
import './assets/css/main.css'
import './assets/scss/main.scss'

// Global Components
import './global-components'

// Filters
import filter from '@/filters/filter'

// SocketIO
import VueSocketIO from 'vue-socket.io'
Vue.use(
  new VueSocketIO({
    debug: true,
    connection: 'http://localhost:8100',
    withCredentials: true
  })
)

//vee-validate
import VeeValidate, { Validator } from 'vee-validate'
import vi from 'vee-validate/dist/locale/vi'
Validator.localize({ vi: vi })
Vue.use(VeeValidate, { locale: 'vi' })

//scroll
Vue.directive('scroll', {
  inserted: function (el, binding) {
    let f = function (evt) {
      if (binding.value(evt, el)) {
        window.removeEventListener('scroll', f)
      }
    }
    window.addEventListener('scroll', f)
  }
})

new Vue({
  axios,
  store,
  router,
  filter,
  render: h => h(App)
}).$mount('#app')
