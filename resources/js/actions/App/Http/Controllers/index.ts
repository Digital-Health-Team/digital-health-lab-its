import LandingPageController from './LandingPageController'
import PameranController from './PameranController'
import ProfileController from './ProfileController'
import DashboardController from './DashboardController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    PameranController: Object.assign(PameranController, PameranController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    DashboardController: Object.assign(DashboardController, DashboardController),
}

export default Controllers