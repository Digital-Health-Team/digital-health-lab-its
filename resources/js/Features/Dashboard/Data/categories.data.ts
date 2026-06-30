import {
    ThreeDDesignsIcon,
    ProstheticsIcon,
    AidBandsIcon,
    EducationalMannequinIcon,
    PapersIcon,
    JournalsIcon,
    ProjectsIcon,
    ServicesIcon,
    TrainingIcon,
    EventsIcon,
} from "../Components/CategoryQuickAccess/CategoryIcons";
import { type Category } from "../Types/category.type";

export const categories: Category[] = [
    { id: "3d-designs", label: "3D Designs", href: "/shop?cat=3d-designs", icon: ThreeDDesignsIcon, accent: "primary" },
    { id: "prosthetics", label: "Prosthetics", href: "/shop?cat=prosthetics", icon: ProstheticsIcon, accent: "secondary" },
    { id: "aid-bands", label: "Aid Bands", href: "/shop?cat=aid-bands", icon: AidBandsIcon },
    { id: "educational", label: "Educational Mannequin", href: "/shop?cat=educational", icon: EducationalMannequinIcon },
    { id: "papers", label: "Papers", href: "/publications?type=paper", icon: PapersIcon },
    { id: "journals", label: "Journals", href: "/publications?type=journal", icon: JournalsIcon },
    { id: "projects", label: "Projects", href: "/projects", icon: ProjectsIcon },
    { id: "services", label: "Services", href: "/services", icon: ServicesIcon },
    { id: "training", label: "Training", href: "/training", icon: TrainingIcon },
    { id: "events", label: "Event", href: "/events", icon: EventsIcon },
];

