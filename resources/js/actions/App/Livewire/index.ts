import Auth from './Auth'
import Settings from './Settings'
import SuperAdmin from './SuperAdmin'
import Gudang from './Gudang'
import Admin from './Admin'
import User from './User'

const Livewire = {
    Auth: Object.assign(Auth, Auth),
    Settings: Object.assign(Settings, Settings),
    SuperAdmin: Object.assign(SuperAdmin, SuperAdmin),
    Gudang: Object.assign(Gudang, Gudang),
    Admin: Object.assign(Admin, Admin),
    User: Object.assign(User, User),
}

export default Livewire