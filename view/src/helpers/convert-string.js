function convertPhone(phone) {
  const numbers = phone.split('')
  if (numbers[0] == 0) numbers[0] = '+84'

  return numbers.join('')
}

export { convertPhone }
