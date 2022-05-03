import Vue from 'vue'
import { CUSTOMER_TYPE, SEX_TYPE } from '@/constants/customer'

const customerType = Vue.filter('customerType', value => {
  if (!value && value !== 0) return ''
  return CUSTOMER_TYPE[value]
})
const sexType = Vue.filter('sexType', value => {
  if (!value && value !== 0) return ''
  return SEX_TYPE[value]
})
const toCurrency = Vue.filter('toCurrency', value => {
  if (!value) {
    return 0
  }
  const formatted = value.toLocaleString('vi-VN', {
    style: 'currency',
    currency: 'VND'
  })
  return formatted
})

export default { customerType, sexType, toCurrency }
