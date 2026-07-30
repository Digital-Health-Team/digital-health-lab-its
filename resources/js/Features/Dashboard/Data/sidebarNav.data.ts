import {
    Home,
    Wrench,
    ShoppingBag,
    User,
    GraduationCap,
    BookOpen,
    CalendarDays,
} from "lucide-react";
import { type NavItem } from "../Types/sidebar.type";

export const sidebarNavItems: NavItem[] = [
    { id: "home", label: "Home", href: "/dashboard", icon: Home, match: "/dashboard" },
    { id: "training", label: "Training", href: "/training", icon: GraduationCap },
    { id: "events", label: "Events", href: "/events", icon: CalendarDays },
    // One entry for the merged Research page; the two detail-URL prefixes keep it highlighted.
    {
        id: "research",
        label: "Research",
        href: "/research",
        icon: BookOpen,
        match: ["/research", "/projects", "/publications"],
    },
    { id: "services", label: "Services", href: "/services", icon: Wrench },
    { id: "products", label: "Products", href: "/products", icon: ShoppingBag },
    { id: "profile", label: "My Account", href: "/profile", icon: User, authRequired: true },
];
