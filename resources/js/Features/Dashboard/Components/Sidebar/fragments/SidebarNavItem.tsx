import { cn } from "@/Core/Utils/utils";
import Tooltip from "@/Core/Components/Shared/Tooltip/Tooltip";
import { type NavItem } from "@/Features/Dashboard/Types/sidebar.type";
import { Link } from "@inertiajs/react";

interface SidebarNavItemProps {
    item: NavItem;
    active: boolean;
    collapsed: boolean;
}

export default function SidebarNavItem({ item, active, collapsed }: SidebarNavItemProps) {
    const Icon = item.icon;

    const inner = (
        <Link
            href={item.href}
            className={cn(
                "flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-150 group/nav",
                collapsed ? "justify-center px-3" : "",
                active
                    ? "sidebar-nav-active text-white"
                    : "text-slate-300 hover:bg-primary-900/50 hover:text-white",
            )}
            aria-current={active ? "page" : undefined}
        >
            <Icon
                className={cn(
                    "shrink-0 h-5 w-5 transition-colors duration-150",
                    active ? "text-secondary-400" : "text-slate-400 group-hover/nav:text-white",
                )}
            />
            <span
                className={cn(
                    "text-sm font-medium whitespace-nowrap overflow-hidden transition-all duration-220",
                    collapsed ? "w-0 opacity-0 pointer-events-none" : "w-auto opacity-100",
                )}
            >
                {item.label}
            </span>
        </Link>
    );

    if (collapsed) {
        return (
            <Tooltip label={item.label} side="right">
                {inner}
            </Tooltip>
        );
    }

    return inner;
}
