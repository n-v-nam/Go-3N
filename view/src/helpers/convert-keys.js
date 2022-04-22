function convertToCamelCase(obj) {
  const keys = Object.keys(obj)
  const result = keys.reduce((allFields, field) => {
    const newField = field
      .split('_')
      .map((key, index) => {
        if (index) {
          key = key.split('')
          key[0] = key[0].toUpperCase()
          key = key.join('')
        }
        return key
      })
      .join('')
    allFields[newField] = obj[field]
    return allFields
  }, {})
  return result
}

function convertToSnackCase(obj) {
  const keys = Object.keys(obj)
  const result = keys.reduce((allFields, field) => {
    const newField = field
      .split('')
      .map((key) => {
        let result = key
        if (key == key.toUpperCase()) result = '_' + result.toLowerCase()
        return result
      })
      .join('')
    allFields[newField] = obj[field]
    return allFields
  }, {})
  return result
}

export { convertToCamelCase, convertToSnackCase }
