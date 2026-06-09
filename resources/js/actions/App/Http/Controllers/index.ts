import LandingPageController from './LandingPageController'
import ProfileController from './ProfileController'

const Controllers = {
    LandingPageController: Object.assign(LandingPageController, LandingPageController),
    ProfileController: Object.assign(ProfileController, ProfileController),
}

export default Controllers