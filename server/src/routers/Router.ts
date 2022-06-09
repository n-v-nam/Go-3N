import express from 'express'
// import { checkTokenUser }  from '../middleware/check_middleware'
import { login } from '../controllers/UserController'
const router = express.Router()

const utils_middleware = require('../middleware/utils_middleware')
const logging_middleware = require('../middleware/logging_middleware')

/* eslint no-undef: "off" */
router.post<any, any>(
  '/user/login',
  [
    logging_middleware.loggingRequest,
    utils_middleware.handleValidationResult
  ],
  login
)


export { router }
