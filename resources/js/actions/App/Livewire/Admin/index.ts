import GlobalSearch from './GlobalSearch'
import Dashboard from './Dashboard'
import User from './User'
import RawMaterial from './RawMaterial'
import Service from './Service'
import Product from './Product'
import Event from './Event'
import OpenSourceProject from './OpenSourceProject'
import OrderCenter from './OrderCenter'
import Cms from './Cms'

const Admin = {
    GlobalSearch: Object.assign(GlobalSearch, GlobalSearch),
    Dashboard: Object.assign(Dashboard, Dashboard),
    User: Object.assign(User, User),
    RawMaterial: Object.assign(RawMaterial, RawMaterial),
    Service: Object.assign(Service, Service),
    Product: Object.assign(Product, Product),
    Event: Object.assign(Event, Event),
    OpenSourceProject: Object.assign(OpenSourceProject, OpenSourceProject),
    OrderCenter: Object.assign(OrderCenter, OrderCenter),
    Cms: Object.assign(Cms, Cms),
}

export default Admin