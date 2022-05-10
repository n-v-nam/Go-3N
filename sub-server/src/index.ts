const config = require('config')
import express from 'express'
const app = express();
const httpServer = require("http").createServer(app);
import { Server, Socket } from "socket.io";

// import knexInstance from './knexfile'
// import { Customer } from './models/Customer'
// import { Message } from './models/Message'
const cors = require('cors')
const bodyParser = require('body-parser')

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

app.use(cors(corsOptions))
app.use(express.json())
app.use(express.urlencoded({ extended: false }))

const socketIO = new Server(httpServer, {
  allowEIO3: true,
  cors: {
    origin: 'http://localhost:8080',
    credentials: true
  }
});

const users: any[] = []
socketIO.on('connection', (socket: Socket)=> {
  socket.on('connected', (userId: number)=> {
    console.log(`user ${userId} connected !`);
    users[userId] = socket.id
  })
  socket.on('send-report', async (data: any)=> {
        // const sender = await knexInstance<Customer>('user').where('id', data.userId).first()
        // if (sender) {
        //     const payload = {
        //       sender: sender,
        //       message: data.content
        //     }
        //     await knexInstance<Message>('notification').insert({sender_id: 1, receiver_id: 1, content: data.content})            
        //     socket.to(users).emit("receiveNotification", payload);
        // }
        socket.emit("report-message", 'here');
  })
})

const port = config.PORT || 8100


httpServer.listen(port, () => {
  console.log(`express app is started on port ${port}`)
})

export default httpServer
