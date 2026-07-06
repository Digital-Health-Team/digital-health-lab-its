import GlobalSearch from './GlobalSearch'
import Dashboard from './Dashboard'
import OrderCenter from './OrderCenter'
import Service from './Service'
import Product from './Product'
import Event from './Event'
import OpenSourceProject from './OpenSourceProject'
import Publication from './Publication'
import Training from './Training'
import Inventory from './Inventory'
import Material from './Material'
import Tool from './Tool'
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
    Publication: Object.assign(Publication, Publication),
    Training: Object.assign(Training, Training),
    Inventory: Object.assign(Inventory, Inventory),
    Material: Object.assign(Material, Material),
    Tool: Object.assign(Tool, Tool),
    User: Object.assign(User, User),
    CMS: Object.assign(CMS, CMS),
}

export default Admin