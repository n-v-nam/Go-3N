import { Request } from 'express'
import knexInstance from '../knexfile'
import { User } from '../models/User'
import crypto = require('crypto')
import randomstring = require('randomstring')
import transporter from '../nodemailer'
import { LoginRequest } from '../domain/requests/user/LoginRequest'

const tableName = {
    USER: 'user'
} as const

const handleLogin = async (req: Request<LoginRequest>) => {
    try {
        const query = await knexInstance<User>(tableName.USER).where(req.body).first()
        const token = crypto.randomBytes(64).toString('hex')
        const now = new Date()
        
        const expiration_date = new Date(`${now.getMonth()}-${now.getDate()}-${now.getFullYear() + 1}`);
        
        if (query) {
            await knexInstance<User>(tableName.USER).where(req.body).andWhere('status', 1).update({ token, expiration_date })
            return {
                id: query.id,
                token,
                name: query.name,
                expiration_date
            }
        } else return Promise.reject('Incorrect account !!!')
    } catch (err) {
        return Promise.reject('Login failed, please try again !')
    }
}

export { handleLogin }
