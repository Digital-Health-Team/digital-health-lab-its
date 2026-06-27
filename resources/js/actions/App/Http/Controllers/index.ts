import LandingPageController from './LandingPageController'
import PameranController from './PameranController'
import ProfileController from './ProfileController'
import User from './User'
import DashboardController from './DashboardController'
import TrainingController from './TrainingController'
import ProjectsController from './ProjectsController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    PameranController: Object.assign(PameranController, PameranController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    User: Object.assign(User, User),
    DashboardController: Object.assign(DashboardController, DashboardController),
    TrainingController: Object.assign(TrainingController, TrainingController),
    ProjectsController: Object.assign(ProjectsController, ProjectsController),
}

export default Controllers