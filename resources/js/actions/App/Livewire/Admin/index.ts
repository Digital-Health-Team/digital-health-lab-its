import GlobalSearch from './GlobalSearch'
import Dashboard from './Dashboard'
import OrderCenter from './OrderCenter'
import Service from './Service'
import Product from './Product'
import Event from './Event'
import OpenSourceProject from './OpenSourceProject'
import RawMaterial from './RawMaterial'
import MasterData from './MasterData'
import User from './User'
import CMS from './CMS'

const Admin = {
    GlobalSearch: Object.assign(GlobalSearch, GlobalSearch),
    Dashboard: Object.assign(Dashboard, Dashboard),
    OrderCenter: Object.assign(OrderCenter, OrderCenter),
    Service: Object.assign(Service, Service),
    Product: Object.assign(Product, Product),
    Event: Object.assign(Event, Event),
    OpenSourceProject: Object.assign(OpenSourceProject, OpenSourceProject),
    RawMaterial: Object.assign(RawMaterial, RawMaterial),
    MasterData: Object.assign(MasterData, MasterData),
    User: Object.assign(User, User),
    CMS: Object.assign(CMS, CMS),
}

export default Admin