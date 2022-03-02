import Vue from 'vue'
import App from './App.vue'

Vue.config.productionTip = false

// Vuesax
import Vuesax from 'vuesax'
import 'material-icons/iconfont/material-icons.css';
import 'vuesax/dist/vuesax.css'

Vue.use(Vuesax, {
  // options here
})

// Vuex
import store from './store/store'

// axios
import axios from 'axios'

// Vue-router
import router from './router'

// TailwindCss
import "./assets/css/main.css"
import "./assets/scss/main.scss"

new Vue({
  axios,
  store,
  router,
  render: h => h(App),
}).$mount('#app')
