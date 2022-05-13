import express from 'express'
const app = express();
const httpServer = require("http").createServer(app);
import { Server, Socket } from "socket.io";

import knexInstance from './knexfile'
import { Customer } from './models/Customer'
import { Message } from './models/Message'

const cors = require('cors')
const bodyParser = require('body-parser')

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
    users[userId] ??= socket.id
  })
  socket.on('disconnected', (userId: number)=> {
    console.log(`user ${userId} connected !`);
    users[userId] = undefined
  })
  socket.on('admin', (adminId: number)=> {
    console.log(`admin ${adminId} connected !`);
    socket.join('admin')
  })
  socket.on('get-messages', async (data: any)=> {
    const { id, partner } = data
    
    const customer = await knexInstance<Customer>('customers').where('id', id).first()
    if (customer) {
        users[id] ??= socket.id

        const result = await knexInstance<Message>('messages')
          .where({ sender: id, receiver: partner })
          .orWhere({ receiver: id, sender: partner })
          .orderBy('id', 'desc')

        socket.emit("get-messages", result);    
    }
  })
  socket.on('search-customer', async (data: any)=> {
    const { phone, id } = data
    users[id] ??= socket.id

    const customer = await knexInstance<Customer>('customers')
      .where('phone', phone)
      .select('id', 'name', 'phone', 'avatar')
      .first()
    
    socket.emit("search-customer", customer);    
  })
  socket.on('get-customers', async (data: any)=> {
      const { id } = data
      users[id] ??= socket.id
      
      const sender = await knexInstance<Message>('messages')
        .where('sender', id)
        .leftJoin('customers', 'customers.id', 'messages.receiver')
        .groupBy('receiver')
        .select('customers.name', 'customers.phone', 'customers.avatar', 'customers.id')

      const receiver = await knexInstance<Message>('messages')
        .where('receiver', id)
        .leftJoin('customers', 'customers.id', 'messages.sender')
        .groupBy('sender')
        .select('customers.name', 'customers.phone', 'customers.avatar', 'customers.id')

      const result = [...sender, ...receiver].reduce((arr, current) => {
        const match = arr.find((obj: Customer) => obj.id === current.id)
        if(!match) return arr = [...arr, current]
        return arr
      }, [])
      
      socket.emit("get-customers", result)
  })
  socket.on('send-message', async (data: any)=> {
    const { sender, receiver } = data
    users[sender] ??= socket.id

    const result = await knexInstance<Message>('messages')
      .insert({...data})

    if(result) {
      socket.emit("send-message", { status: true });
      console.log(users[receiver])
      socket.to(users[receiver]).emit("receive-message");
      socket.to(users[receiver]).emit("notification-message");
    } else {
      socket.emit("send-message", { status: false });
    }
  })
})

const port = 8100


httpServer.listen(port, () => {
  console.log(`express app is started on port ${port}`)
})

export default httpServer
