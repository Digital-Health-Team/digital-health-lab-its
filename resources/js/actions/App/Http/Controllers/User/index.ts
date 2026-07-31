import OrderController from './OrderController'
import UserProjectController from './UserProjectController'

const User = {
    OrderController: Object.assign(OrderController, OrderController),
    UserProjectController: Object.assign(UserProjectController, UserProjectController),
}

export default User