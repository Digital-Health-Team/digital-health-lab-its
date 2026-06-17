import LandingPageController from './LandingPageController'
import ProfileController from './ProfileController'
import User from './User'
import DashboardController from './DashboardController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    User: Object.assign(User, User),
    DashboardController: Object.assign(DashboardController, DashboardController),
}

export default Controllers