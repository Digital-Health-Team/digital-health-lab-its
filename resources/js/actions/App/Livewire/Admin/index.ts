import GlobalSearch from './GlobalSearch'
import Dashboard from './Dashboard'
import User from './User'

const Admin = {
    GlobalSearch: Object.assign(GlobalSearch, GlobalSearch),
    Dashboard: Object.assign(Dashboard, Dashboard),
    User: Object.assign(User, User),
}

export default Admin