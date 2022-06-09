/* eslint use-isnan: "off", no-useless-escape: "off" */
const isEmpty = (str) => {
  if (typeof str != 'undefined') {
    str = str + ''
  }
  if (
    typeof str == 'undefined' ||
    !str ||
    str.length === 0 ||
    str === '' ||
    str === null ||
    str === 'null' ||
    str === 'NaN' ||
    str === 'nan' ||
    str === NaN ||
    !/[^\s]/.test(str) ||
    /^\s*$/.test(str) ||
    str.replace(/\s/g, '') === ''
  ) {
    return true
  } else {
    return false
  }
}

const isDecimalUnit1decimal3 = (number) => {
  return /^\d{0,1}(\.\d{0,3})?$/.test(number)
}

const isDecimalUnit2decimal2 = (number) => {
  return /^\d{0,2}(\.\d{0,2})?$/.test(number)
}

const isDecimalUnit2decimal13 = (number) => {
  return /^\d{0,2}(\.\d{0,13})?$/.test(number)
}

const isDecimalUnit3decimal1 = (number) => {
  return /^\d{0,3}(\.\d{0,1})?$/.test(number)
}

const isDecimalUnit3decimal2 = (number) => {
  return /^\d{0,3}(\.\d{0,2})?$/.test(number)
}

const isDecimalUnitNegativeNumbersAllowed3decimal2 = (number) => {
  return /^-?\d{0,3}(\.\d{0,2})?$/.test(number)
}

const isDecimalUnit3decimal12 = (number) => {
  return /^\d{0,3}(\.\d{0,12})?$/.test(number)
}
const isDecimalUnit7decimal1 = (number) => {
  return /^\d{0,6}(\.\d{0,1})?$/.test(number)
}

const isUnicode = (str) => {
  return /^[a-zA-Z0-9,.<>?\/:'"`~!@#$%^&*()-_=+[{}}\\|*s]+$/.test(str)
}

const fieldIsValid = (str, min, max, isNumber) => {
  let isValid = true
  str = str + ''
  if (isEmpty(str + '') || str.length > max || str.length < min) {
    isValid = false
  }
  if (isNumber && isNaN(str) == true) {
    isValid = false
  }
  return isValid
}

module.exports = {
  isEmpty,
  fieldIsValid,
  isUnicode,
  isDecimalUnit1decimal3,
  isDecimalUnit2decimal2,
  isDecimalUnit2decimal13,
  isDecimalUnit3decimal1,
  isDecimalUnit3decimal2,
  isDecimalUnitNegativeNumbersAllowed3decimal2,
  isDecimalUnit3decimal12,
  isDecimalUnit7decimal1,
}
