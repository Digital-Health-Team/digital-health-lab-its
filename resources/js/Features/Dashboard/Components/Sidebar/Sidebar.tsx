import { useState } from "react";
import { cn } from "@/Core/Utils/utils";
import { usePage } from "@inertiajs/react";
import { ArrowLeftRight } from "lucide-react";
import { useUiStore } from "@/Core/Store/ui.store";
import { sidebarNavItems } from "@/Features/Dashboard/Data/sidebarNav.data";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import SidebarBrand from "./fragments/SidebarBrand";
import SidebarNavItem from "./fragments/SidebarNavItem";
import SidebarCollapseToggle from "./fragments/SidebarCollapseToggle";
import SwitchModeModal from "@/Features/Dashboard/Components/SwitchModeModal";

interface SidebarProps {
    collapsed?: boolean;
    showToggle?: boolean;
}

function isActiveItem(href: string, currentUrl: string, match?: string): boolean {
    const check = match ?? href;
    return currentUrl === check || currentUrl.startsWith(check + "/");
}

export default function Sidebar({ collapsed, showToggle = true }: SidebarProps) {
    const { url, props } = usePage();
    const { sidebarCollapsed, toggleSidebar } = useUiStore();
    const { t } = useTranslation();
    const [switchModalOpen, setSwitchModalOpen] = useState(false);

    const isCollapsed = collapsed !== undefined ? collapsed : sidebarCollapsed;

    const auth = props.auth as { user?: { roles?: string[]; active_role?: string } } | undefined;
    const roles      = auth?.user?.roles ?? [];
    const activeRole = auth?.user?.active_role ?? "";
    const canSwitch  = roles.length > 1;
    const activeLabel = activeRole.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());

    return (
        <aside
            className={cn(
                "flex flex-col h-full sidebar-transition overflow-hidden",
                isCollapsed ? "w-18" : "w-60",
            )}
            style={{ backgroundColor: "#082A55" }}
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

            {/* Role switcher — only visible for users with 2+ roles */}
            {canSwitch && (
                <div className="px-3 pb-2">
                    {isCollapsed ? (
                        <button
                            type="button"
                            onClick={() => setSwitchModalOpen(true)}
                            title={t("Switch Mode")}
                            className="w-full flex justify-center py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition-colors"
                        >
                            <ArrowLeftRight className="h-4 w-4" />
                        </button>
                    ) : (
                        <button
                            type="button"
                            onClick={() => setSwitchModalOpen(true)}
                            className="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors text-xs font-medium"
                        >
                            <ArrowLeftRight className="h-3.5 w-3.5 shrink-0 text-indigo-400" />
                            <span className="flex-1 text-left">{t("Switch Mode")}</span>
                            <span className="text-[10px] font-bold uppercase tracking-wider text-slate-500 truncate max-w-[80px]">
                                {activeLabel}
                            </span>
                        </button>
                    )}
                </div>
            )}

            {showToggle && (
                <SidebarCollapseToggle
                    collapsed={isCollapsed}
                    onToggle={toggleSidebar}
                />
            )}

            {!showToggle && <div className="pb-[env(safe-area-inset-bottom)]" aria-hidden="true" />}

            <SwitchModeModal
                open={switchModalOpen}
                onClose={() => setSwitchModalOpen(false)}
                roles={roles}
                activeRole={activeRole}
            />
        </aside>
    );
}
