export interface User{
    id: number
    name: string
    username: string
    password: string
    email: string
    code: string | null
    status: 0 | 1
    token: string
    expiration_date: Date
    created_at: string
}