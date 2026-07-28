import LandingPageController from './LandingPageController'
import LocaleController from './LocaleController'
import PameranController from './PameranController'
import SwitchRoleController from './SwitchRoleController'
import ProfileController from './ProfileController'
import User from './User'
import Admin from './Admin'
import DashboardController from './DashboardController'
import GlobalSearchController from './GlobalSearchController'
import TrainingController from './TrainingController'
import ProjectsController from './ProjectsController'
import ServicesController from './ServicesController'
import ProductsController from './ProductsController'
import PublicationsController from './PublicationsController'
import NewsController from './NewsController'
import TeamMemberController from './TeamMemberController'
import ScanMaterialController from './ScanMaterialController'
import ScanToolController from './ScanToolController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    LocaleController: Object.assign(LocaleController, LocaleController),
    PameranController: Object.assign(PameranController, PameranController),
    SwitchRoleController: Object.assign(SwitchRoleController, SwitchRoleController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    User: Object.assign(User, User),
    Admin: Object.assign(Admin, Admin),
    DashboardController: Object.assign(DashboardController, DashboardController),
    GlobalSearchController: Object.assign(GlobalSearchController, GlobalSearchController),
    TrainingController: Object.assign(TrainingController, TrainingController),
    ProjectsController: Object.assign(ProjectsController, ProjectsController),
    ServicesController: Object.assign(ServicesController, ServicesController),
    ProductsController: Object.assign(ProductsController, ProductsController),
    PublicationsController: Object.assign(PublicationsController, PublicationsController),
    NewsController: Object.assign(NewsController, NewsController),
    TeamMemberController: Object.assign(TeamMemberController, TeamMemberController),
    ScanMaterialController: Object.assign(ScanMaterialController, ScanMaterialController),
    ScanToolController: Object.assign(ScanToolController, ScanToolController),
}

export default Controllers