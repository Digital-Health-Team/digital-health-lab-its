import { type ReactNode } from "react";
import { useUiStore } from "@/Core/Store/ui.store";
import { useMediaQuery } from "@/Core/Hooks/useMediaQuery";
import Sidebar from "@/Features/Dashboard/Components/Sidebar/Sidebar";
import Topbar from "@/Features/Dashboard/Components/Topbar/Topbar";
import Sheet from "@/Core/Components/Shared/Sheet/Sheet";
import { cn } from "@/Core/Utils/utils";

interface DashboardLayoutProps {
    children: ReactNode;
}

export default function DashboardLayout({ children }: DashboardLayoutProps) {
    const { sidebarCollapsed, mobileSidebarOpen, setMobileSidebar } = useUiStore();
    const isMobile = !useMediaQuery("(min-width: 768px)");
    const isTablet = !useMediaQuery("(min-width: 1024px)");

    // At tablet, force sidebar into icon-only mode; honour user preference at desktop
    const effectiveCollapsed = isTablet ? true : sidebarCollapsed;

    // Sidebar offset for the main area (zero on mobile since sidebar is a Sheet)
    const sidebarWidth = isMobile ? "ml-0" : effectiveCollapsed ? "ml-18" : "ml-60";

    return (
        <div className="min-h-screen flex bg-slate-50 font-body">
            {/* Desktop / tablet fixed sidebar */}
            {!isMobile && (
                <div
                    className={cn(
                        "fixed inset-y-0 left-0 z-40 sidebar-transition",
                        effectiveCollapsed ? "w-18" : "w-60",
                    )}
                >
                    <Sidebar collapsed={effectiveCollapsed} />
                </div>
            )}

            {/* Mobile sidebar — Sheet drawer */}
            {isMobile && (
                <Sheet open={mobileSidebarOpen} onClose={() => setMobileSidebar(false)}>
                    <Sidebar collapsed={false} showToggle={false} />
                </Sheet>
            )}

            {/* Main content area */}
            <div className={cn("flex flex-col flex-1 min-h-screen sidebar-transition", sidebarWidth)}>
                <Topbar />
                <main className="flex-1 px-6 py-6 space-y-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
