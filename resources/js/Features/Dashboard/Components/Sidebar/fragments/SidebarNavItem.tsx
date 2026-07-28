import { cn } from "@/Core/Utils/utils";
import Tooltip from "@/Core/Components/Shared/Tooltip/Tooltip";
import { type NavItem } from "@/Features/Dashboard/Types/sidebar.type";
import { Link, usePage } from "@inertiajs/react";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface SidebarNavItemProps {
    item: NavItem;
    active: boolean;
    collapsed: boolean;
}

const itemClass = (collapsed: boolean, active: boolean) =>
    cn(
        "flex items-center gap-3 py-3 transition-all duration-150 group/nav",
        collapsed ? "justify-center px-3" : "px-4",
        active
            ? "sidebar-nav-active text-white"
            : "rounded-xl text-slate-300 hover:bg-white/8 hover:text-white",
    );

export default function SidebarNavItem({ item, active, collapsed }: SidebarNavItemProps) {
    const { t } = useTranslation();
    const { auth } = usePage().props as any;
    const Icon = item.icon;
    const label = t(item.label);

    const iconClass = cn(
        "shrink-0 h-5 w-5 transition-colors duration-150",
        active ? "text-secondary-400" : "text-slate-400 group-hover/nav:text-white",
    );

    const labelClass = cn(
        "text-sm font-medium whitespace-nowrap overflow-hidden transition-all duration-220",
        collapsed ? "w-0 opacity-0 pointer-events-none" : "w-auto opacity-100",
    );

    const needsLoginRedirect = item.authRequired && !auth?.user;

    const inner = needsLoginRedirect ? (
        <a
            href="/login"
            data-tour={`nav-${item.id}`}
            className={itemClass(collapsed, active)}
            aria-current={active ? "page" : undefined}
        >
            <Icon className={iconClass} />
            <span className={labelClass}>{label}</span>
        </a>
    ) : (
        <Link
            href={item.href}
            data-tour={`nav-${item.id}`}
            className={itemClass(collapsed, active)}
            aria-current={active ? "page" : undefined}
        >
            <Icon className={iconClass} />
            <span className={labelClass}>{label}</span>
        </Link>
    );

    if (collapsed) {
        return (
            <Tooltip label={label} side="right">
                {inner}
            </Tooltip>
        );
    }

    return inner;
}
