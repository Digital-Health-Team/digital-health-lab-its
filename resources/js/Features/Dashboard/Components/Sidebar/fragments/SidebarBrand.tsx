import { cn } from "@/Core/Utils/utils";

interface SidebarBrandProps {
    collapsed: boolean;
}

export default function SidebarBrand({ collapsed }: SidebarBrandProps) {
    return (
        <div
            className={cn(
                "flex items-center gap-3 px-4 py-5 border-b border-primary-800/50 shrink-0",
                collapsed ? "justify-center px-0" : "",
            )}
        >
            {/* Logo mark */}
            <div className="shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-secondary-400 to-secondary-500 flex items-center justify-center shadow-[0_0_12px_rgba(34,211,238,0.4)]">
                <span className="text-white font-bold text-sm font-display">ITS</span>
            </div>
            {/* Brand text — hidden when collapsed */}
            <div
                className={cn(
                    "overflow-hidden transition-all duration-220",
                    collapsed ? "w-0 opacity-0" : "w-auto opacity-100",
                )}
            >
                <p className="text-white font-display font-bold text-sm leading-tight whitespace-nowrap">
                    IDIG Lab
                </p>
                <p className="text-slate-400 text-[11px] leading-tight whitespace-nowrap">
                    Medical Technology
                </p>
            </div>
        </div>
    );
}
