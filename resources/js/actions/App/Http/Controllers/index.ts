import LandingPageController from './LandingPageController'
import PameranController from './PameranController'
import ProfileController from './ProfileController'
import User from './User'
import DashboardController from './DashboardController'
import TrainingController from './TrainingController'
import ProjectsController from './ProjectsController'
import ServicesController from './ServicesController'
import ProductsController from './ProductsController'
import PublicationsController from './PublicationsController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    PameranController: Object.assign(PameranController, PameranController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    User: Object.assign(User, User),
    DashboardController: Object.assign(DashboardController, DashboardController),
    TrainingController: Object.assign(TrainingController, TrainingController),
    ProjectsController: Object.assign(ProjectsController, ProjectsController),
    ServicesController: Object.assign(ServicesController, ServicesController),
    ProductsController: Object.assign(ProductsController, ProductsController),
    PublicationsController: Object.assign(PublicationsController, PublicationsController),
}

export default Controllers