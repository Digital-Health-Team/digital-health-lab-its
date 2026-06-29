import {
    Home,
    FolderOpen,
    Wrench,
    ShoppingBag,
    User,
    GraduationCap,
    BookOpen,
    Briefcase,
} from "lucide-react";
import { type NavItem } from "../Types/sidebar.type";

export const sidebarNavItems: NavItem[] = [
    { id: "home", label: "Home", href: "/dashboard", icon: Home, match: "/dashboard" },
    { id: "training", label: "Training", href: "/training", icon: GraduationCap },
    { id: "projects", label: "Projects", href: "/projects", icon: FolderOpen },
    { id: "publications", label: "Publications", href: "/publications", icon: BookOpen },
    { id: "services", label: "Services", href: "/services", icon: Wrench },
    { id: "products", label: "Products", href: "/products", icon: ShoppingBag },
    { id: "profile", label: "Profile", href: "/profile", icon: User },
    { id: "portfolio", label: "My Portfolio", href: "/portfolio", icon: Briefcase },
];
