import { cn } from "@/Core/Utils/utils";
import { usePage } from "@inertiajs/react";
import { useUiStore } from "@/Core/Store/ui.store";
import { sidebarNavItems } from "@/Features/Dashboard/Data/sidebarNav.data";
import SidebarBrand from "./fragments/SidebarBrand";
import SidebarNavItem from "./fragments/SidebarNavItem";
import SidebarCollapseToggle from "./fragments/SidebarCollapseToggle";

interface SidebarProps {
    collapsed?: boolean;
    showToggle?: boolean;
}

function isActiveItem(href: string, currentUrl: string, match?: string): boolean {
    const check = match ?? href;
    return currentUrl === check || currentUrl.startsWith(check + "/");
}

export default function Sidebar({ collapsed, showToggle = true }: SidebarProps) {
    const { url } = usePage();
    const { sidebarCollapsed, toggleSidebar } = useUiStore();
    const isCollapsed = collapsed !== undefined ? collapsed : sidebarCollapsed;

    return (
        <aside
            className={cn(
                "flex flex-col h-full bg-primary-950 sidebar-transition overflow-hidden",
                isCollapsed ? "w-18" : "w-60",
            )}
        >
            <SidebarBrand collapsed={isCollapsed} />

            <nav aria-label="Primary" className="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                {sidebarNavItems.map((item) => (
                    <SidebarNavItem
                        key={item.id}
                        item={item}
                        active={isActiveItem(item.href, url, item.match)}
                        collapsed={isCollapsed}
                    />
                ))}
            </nav>

            {showToggle && (
                <SidebarCollapseToggle
                    collapsed={isCollapsed}
                    onToggle={toggleSidebar}
                />
            )}
        </aside>
    );
}
