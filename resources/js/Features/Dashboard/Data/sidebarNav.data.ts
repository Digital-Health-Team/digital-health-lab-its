import {
    Home,
    BookOpen,
    FolderOpen,
    Wrench,
    Settings2,
    ShoppingBag,
    ClipboardList,
    User,
    GraduationCap,
} from "lucide-react";
import { type NavItem } from "../Types/sidebar.type";

export const sidebarNavItems: NavItem[] = [
    { id: "home", label: "Home", href: "/dashboard", icon: Home, match: "/dashboard" },
    { id: "publications", label: "Publications", href: "/publications", icon: BookOpen },
    { id: "training", label: "Training", href: "/training", icon: GraduationCap },
    { id: "projects", label: "Projects", href: "/projects", icon: FolderOpen },
    { id: "services", label: "Services", href: "/services", icon: Wrench },
    { id: "orders", label: "My Orders", href: "/orders", icon: ClipboardList, match: "/orders" },
    { id: "management", label: "Management", href: "/management", icon: Settings2 },
    { id: "shop", label: "Shop", href: "/shop", icon: ShoppingBag },
    { id: "profile", label: "Profile", href: "/profile", icon: User },
];
