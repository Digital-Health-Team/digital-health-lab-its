import OrderController from './OrderController'
import PortfolioController from './PortfolioController'
import UserProjectController from './UserProjectController'

const User = {
    OrderController: Object.assign(OrderController, OrderController),
    PortfolioController: Object.assign(PortfolioController, PortfolioController),
    UserProjectController: Object.assign(UserProjectController, UserProjectController),
}

export default User