const config = require('config')
import express from 'express'
const app = express()

import { router } from './routers/Router'
const log4js = require('log4js')
const cors = require('cors')
const bodyParser = require('body-parser')
const appUtils = require('./utils/app-utils')

const dotenv = require('dotenv')
dotenv.config()

const corsOptions: any = {} // eslint-disable prettier-ignore
var enableCors = process.env.ENABLE_CORS
if (enableCors + '' == 'true') {
  corsOptions.origin = '*' //Configures the Access-Control-Allow-Origin CORS header
  corsOptions.methods = '*' //Configures the Access-Control-Allow-Methods CORS header
  corsOptions.allowedHeaders = '*' //Configures the Access-Control-Allow-Headers CORS header
  corsOptions.exposedHeaders =
    'authorization, login_type_key, Content-Disposition, content-disposition' //Configures the Access-Control-Expose-Headers CORS header
  corsOptions.credentials = false //Configures the Access-Control-Allow-Credentials CORS header
  //corsOptions.maxAge = 5;                           //Configures the Access-Control-Max-Age CORS header
  corsOptions.preflightContinue = false //Pass the CORS preflight response to the next handler
  corsOptions.optionsSuccessStatus = 200 //Provides a status code to use for successful OPTIONS requests, since some legacy browsers (IE11, various SmartTVs) choke on 204.
} else {
  corsOptions.exposedHeaders =
    'authorization, login_type_key, Content-Disposition, content-disposition'
}

app.use(bodyParser.json({ limit: '11mb' }))

app.use(log4js.connectLogger(appUtils.httpLogger, { level: 'info' }))
app.use(cors(corsOptions))
app.use(express.json())
app.use(express.urlencoded({ extended: false }))

app.use('/api', router)

const port = config.PORT || 8000


app.listen(port, () => {
  console.log(`express app is started on port ${port}`)
})

export default app
