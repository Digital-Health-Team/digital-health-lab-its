import Auth from './Auth'
import Settings from './Settings'
import Admin from './Admin'
import User from './User'

const Livewire = {
    Auth: Object.assign(Auth, Auth),
    Settings: Object.assign(Settings, Settings),
    Admin: Object.assign(Admin, Admin),
    User: Object.assign(User, User),
}

export default Livewire