import Login from './Login'
import ForgotPassword from './ForgotPassword'
import ResetPassword from './ResetPassword'
import Register from './Register'
import VerifyEmail from './VerifyEmail'

const Auth = {
    Login: Object.assign(Login, Login),
    ForgotPassword: Object.assign(ForgotPassword, ForgotPassword),
    ResetPassword: Object.assign(ResetPassword, ResetPassword),
    Register: Object.assign(Register, Register),
    VerifyEmail: Object.assign(VerifyEmail, VerifyEmail),
}

export default Auth