import Vue from 'vue'
import { CUSTOMER_TYPE, SEX_TYPE } from '@/constants/customer'

const customerType = Vue.filter('customerType', (value) => {
  if (!value) return ''
  return CUSTOMER_TYPE[value]
})
const sexType = Vue.filter('sexType', (value) => {
  if (!value) return ''
  return SEX_TYPE[value]
})

export default { customerType, sexType }
