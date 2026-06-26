import LandingPageController from './LandingPageController'
import PameranController from './PameranController'
import ProfileController from './ProfileController'
import DashboardController from './DashboardController'
import TrainingController from './TrainingController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    PameranController: Object.assign(PameranController, PameranController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    DashboardController: Object.assign(DashboardController, DashboardController),
    TrainingController: Object.assign(TrainingController, TrainingController),
}

export default Controllers