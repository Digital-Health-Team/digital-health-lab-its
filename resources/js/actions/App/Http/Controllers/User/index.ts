import OrderController from './OrderController'
import PublishController from './PublishController'

const User = {
    OrderController: Object.assign(OrderController, OrderController),
    PublishController: Object.assign(PublishController, PublishController),
}

export default User