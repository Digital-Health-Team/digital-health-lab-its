import LandingPageController from './LandingPageController'
import ProfileController from './ProfileController'
import DashboardController from './DashboardController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    DashboardController: Object.assign(DashboardController, DashboardController),
}

export default Controllers