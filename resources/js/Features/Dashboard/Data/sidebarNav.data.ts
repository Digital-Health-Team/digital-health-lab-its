import {
    Home,
    FolderOpen,
    Wrench,
    ShoppingBag,
    User,
    GraduationCap,
    BookOpen,
} from "lucide-react";
import { type NavItem } from "../Types/sidebar.type";

export const sidebarNavItems: NavItem[] = [
    { id: "home", label: "Home", href: "/dashboard", icon: Home, match: "/dashboard" },
    { id: "training", label: "Training", href: "/training", icon: GraduationCap },
    { id: "projects", label: "Projects", href: "/projects", icon: FolderOpen },
    { id: "publications", label: "Publications", href: "/publications", icon: BookOpen },
    { id: "services", label: "Services", href: "/services", icon: Wrench },
    { id: "products", label: "Products", href: "/products", icon: ShoppingBag },
    { id: "profile", label: "My Account", href: "/profile", icon: User, authRequired: true },
];
