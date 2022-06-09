const log4js = require('log4js')
const config = require('config')

log4js.configure({
  appenders: {
    stdout: {
      type: 'dateFile',
      filename: `log/agg-out-${config.logFileName}.txt`,
      daysToKeep: 7,
      keepFileExt: true,
      layout: {
        type: 'pattern',
        pattern: '%d{yyyy/MM/dd hh.mm.ss}【%p】 %m',
      },
    },
    stderr: {
      type: 'dateFile',
      filename: `log/agg-error-${config.logFileName}.txt`,
      daysToKeep: 7,
      keepFileExt: true,
      layout: {
        type: 'pattern',
        pattern: '%d{yyyy/MM/dd hh.mm.ss}【%p】 %m',
      },
    },
    _file: {
      type: 'dateFile',
      filename: `log/agg-app-${config.logFileName}.txt`,
      daysToKeep: 7,
      keepFileExt: true,
      layout: {
        type: 'pattern',
        pattern: '%d{yyyy/MM/dd hh.mm.ss}【%p】 %m',
      },
    },
    _info: {
      type: 'logLevelFilter',
      appender: 'stdout',
      level: 'debug',
      maxLevel: 'warn',
    },
    _error: { type: 'logLevelFilter', appender: 'stderr', level: 'error' },
  },
  categories: {
    default: { appenders: ['stdout'], level: 'info' },
    error: { appenders: ['stderr'], level: 'error' },
    app: { appenders: ['_info', '_error'], level: config.LOG_LEVEL || 'error' },
    appFile: { appenders: ['_file'], level: 'debug' },
  },
  pm2: true,
  disableClustering: true,
})
const logger = log4js.getLogger('app')
const fileLogger = log4js.getLogger('appFile')
const log = (msg) => logger.debug(msg)
const info = (msg) => logger.info(msg)
const error = function (msg) {
  logger.error(msg)
  error2File(`DID : ${msg}`)
}
const info2File = (msg) => fileLogger.info(msg)
const debug2File = (msg) => fileLogger.debug(msg)
const warn2File = (msg) => fileLogger.warn(msg)
const error2File = (msg) => fileLogger.error(msg)

function createTransaction(knex) {
  return new Promise((resolve) => {
    return knex.transaction(resolve)
  })
}

module.exports.log = log
module.exports.error = error
module.exports.info = info
module.exports.info2File = info2File
module.exports.debug2File = debug2File
module.exports.warn2File = warn2File
module.exports.error2File = error2File
module.exports.httpLogger = logger
module.exports.createTransaction = createTransaction
