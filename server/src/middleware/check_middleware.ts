import knexInstance from '../knexfile'
import { User } from '../models/User'
import { ResponseData } from '../responses/Response'
const app = require('../utils/app-utils')

const checkTokenUser = async (req: any, res: any, next: any) => {
    try {
        const { authorization } = req.headers
        const token = authorization.split(' ')[1]
        const query = await knexInstance<User>('user').where('token', token).andWhere('status', 1).first()

        if (query) {
            const now = new Date()
            const expiration_date = new Date(query.expiration_date)
            
            if (expiration_date > now) {
                req.body.username = query.username
                req.params.username = query.username
                next()
            } else {
                app.error('Token expired!')
                return res.status(403).send(new ResponseData<null>(false, 'Token expired!', null))
            }
        } else return res.status(403).send(new ResponseData<null>(false, 'User not found!', null))
    } catch (err) {
        app.error(err)
        return res.status(403).send(new ResponseData<null>(false, 'Error with token, please try again !', null))
    }
}

export { checkTokenUser }
