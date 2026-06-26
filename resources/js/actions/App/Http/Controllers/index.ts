import LandingPageController from './LandingPageController'
import PameranController from './PameranController'
import ProfileController from './ProfileController'
import DashboardController from './DashboardController'
import TrainingController from './TrainingController'
import ProjectsController from './ProjectsController'
import ServicesController from './ServicesController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    PameranController: Object.assign(PameranController, PameranController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    DashboardController: Object.assign(DashboardController, DashboardController),
    TrainingController: Object.assign(TrainingController, TrainingController),
    ProjectsController: Object.assign(ProjectsController, ProjectsController),
    ServicesController: Object.assign(ServicesController, ServicesController),
}

export default Controllers