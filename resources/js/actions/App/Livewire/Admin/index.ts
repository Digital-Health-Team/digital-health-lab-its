import GlobalSearch from './GlobalSearch'
import Dashboard from './Dashboard'
import User from './User'
import RawMaterial from './RawMaterial'
import Service from './Service'
import Product from './Product'
import Event from './Event'

const Admin = {
    GlobalSearch: Object.assign(GlobalSearch, GlobalSearch),
    Dashboard: Object.assign(Dashboard, Dashboard),
    User: Object.assign(User, User),
    RawMaterial: Object.assign(RawMaterial, RawMaterial),
    Service: Object.assign(Service, Service),
    Product: Object.assign(Product, Product),
    Event: Object.assign(Event, Event),
}

export default Admin