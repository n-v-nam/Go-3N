import { ResponseData } from '../responses/Response'

const { validationResult } = require('express-validator')

const handleValidationResult = (req, res, next) => {
  const errors = validationResult(req) // Finds the validation errors in this request and wraps them in an object with handy functions
  if (!errors.isEmpty()) {
    const errs = errors.array()

    const messages = errs.map((item) => item.msg)
    return res
      .status(422)
      .send(new ResponseData(false, messages.join(', '), null))
  }

  next()
}

module.exports = {
  handleValidationResult,
}
