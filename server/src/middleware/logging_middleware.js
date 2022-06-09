const app = require('../utils/app-utils')
const url = require('url')

module.exports = {
  loggingRequest(req, res, next) {
    var path = url.parse(req.url).pathname
    var params = JSON.stringify(req.query)
    if (req.method != 'GET') {
      params = JSON.stringify(req.params)
    }
    app.info2File(`${path}開始：${params}`)
    next()
    app.info2File(`${path}終了：${params}`)
  },
}
