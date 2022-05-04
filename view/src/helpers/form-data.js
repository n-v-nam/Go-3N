function createFormData(data, PUT = false) {
  const formData = new FormData()
  if (PUT) formData.append('_method', 'PUT')

  Object.keys(data).forEach((key) => {
    formData.append(key, data[key])
  })
  return formData
}
export { createFormData }
