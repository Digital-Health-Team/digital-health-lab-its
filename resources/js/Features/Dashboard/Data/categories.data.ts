import { type Category } from "../Types/category.type";

export const categories: Category[] = [
    { id: "3d-designs", label: "3D Designs", href: "/shop?cat=3d-designs", icon: "/assets/images/categories/3d_designs.png", accent: "primary" },
    { id: "prosthetics", label: "Prosthetics", href: "/shop?cat=prosthetics", icon: "/assets/images/categories/prosthetics.png", accent: "secondary" },
    { id: "aid-bands", label: "Aid Bands", href: "/shop?cat=aid-bands", icon: "/assets/images/categories/aid_bands.png" },
    { id: "educational", label: "Educational Mannequin", href: "/shop?cat=educational", icon: "/assets/images/categories/educational.png" },
    { id: "papers", label: "Papers", href: "/publications?type=paper", icon: "/assets/images/categories/papers.png" },
    { id: "journals", label: "Journals", href: "/publications?type=journal", icon: "/assets/images/categories/journals.png" },
    { id: "projects", label: "Projects", href: "/projects", icon: "/assets/images/categories/projects.png" },
    { id: "services", label: "Services", href: "/services", icon: "/assets/images/categories/services.png" },
    { id: "training", label: "Training", href: "/training", icon: "/assets/images/categories/training.png" },
    { id: "events", label: "Event", href: "/events", icon: "/assets/images/categories/events.png" },
];

