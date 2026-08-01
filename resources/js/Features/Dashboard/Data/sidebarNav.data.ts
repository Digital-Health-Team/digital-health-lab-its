import {
    Home,
    Wrench,
    ShoppingBag,
    Upload,
    User,
    BookOpen,
    CalendarDays,
} from "lucide-react";
import { type NavItem } from "../Types/sidebar.type";

export const sidebarNavItems: NavItem[] = [
    { id: "home", label: "Home", href: "/dashboard", icon: Home, match: "/dashboard" },
    // One entry for the merged Events page; /training keeps only its detail URLs.
    {
        id: "events",
        label: "Events",
        href: "/events",
        icon: CalendarDays,
        match: ["/events", "/training"],
    },
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
    // No `match` needed: the default href also covers /publish/create and the edit URLs.
    { id: "publish", label: "Publish", href: "/publish", icon: Upload, authRequired: true },
    { id: "profile", label: "My Account", href: "/profile", icon: User, authRequired: true },
];
