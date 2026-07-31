import { ChevronLeft, ChevronRight } from "lucide-react";
import { cn } from "@/Core/Utils/utils";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface SidebarCollapseToggleProps {
    collapsed: boolean;
    onToggle: () => void;
}

export default function SidebarCollapseToggle({ collapsed, onToggle }: SidebarCollapseToggleProps) {
    const { t } = useTranslation();

    return (
        <div className="shrink-0 px-3 py-4 border-t border-primary-800/50">
            <button
                type="button"
                onClick={onToggle}
                aria-label={collapsed ? t("Expand sidebar") : t("Collapse sidebar")}
                className={cn(
                    "w-full flex items-center gap-2 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-primary-900/50 transition-colors duration-150",
                    collapsed ? "justify-center" : "",
                )}
            >
                {collapsed ? (
                    <ChevronRight className="h-4 w-4 shrink-0" />
                ) : (
                    <>
                        <ChevronLeft className="h-4 w-4 shrink-0" />
                        <span className="text-xs font-medium overflow-hidden whitespace-nowrap">
                            {t("Collapse")}
                        </span>
                    </>
                )}
            </button>
        </div>
    );
}
