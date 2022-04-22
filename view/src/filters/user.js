import Vue from 'vue'
import { USER_TYPE } from '@/constants/user'

const userRole = Vue.filter('userRole', (value) => {
  if (!value) return ''
  return USER_TYPE[value]
})

export default { userRole }
