import { Request, Response } from 'express'

const app = require('../../utils/app-utils')

const { check } = require('express-validator')

import {
    handleLogin
} from '../repositories/UserRepository'
import { ResponseData } from '../responses/Response'
import { LoginRequest } from '@/src/domain/requests/user/LoginRequest'
import { LoginResponse } from '@/src/domain/responses/user/LoginResponse'

const login = async (req: Request<LoginRequest>, res: Response<ResponseData<LoginResponse>>) => {
    try {
        return handleLogin(req)
            .then(function (data: any) {
                return res.status(200).send(new ResponseData<LoginResponse>(true, 'Login Success !', data))
            })
            .catch(function (error: any) {
                app.error(error)
                return res.status(400).send(new ResponseData<LoginResponse>(false, error || 'Login Failed', null))
            })
    } catch (err: any) {
        app.error(err)
        return res.status(500).send(new ResponseData<LoginResponse>(false, 'An error has occurred', null))
    }
}

export { login }

