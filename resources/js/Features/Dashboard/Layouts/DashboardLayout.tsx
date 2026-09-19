import { type ReactNode, useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { useUiStore } from "@/Core/Store/ui.store";
import { useMediaQuery } from "@/Core/Hooks/useMediaQuery";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import Sidebar from "@/Features/Dashboard/Components/Sidebar/Sidebar";
import Topbar from "@/Features/Dashboard/Components/Topbar/Topbar";
import Sheet from "@/Core/Components/Shared/Sheet/Sheet";
import { ChatbotWidget } from "@/Features/Chatbot";
import { startUserTour } from "@/Features/Tour/startUserTour";
import { cn } from "@/Core/Utils/utils";

interface DashboardLayoutProps {
    children: ReactNode;
}

export default function DashboardLayout({ children }: DashboardLayoutProps) {
    const { sidebarCollapsed, mobileSidebarOpen, setMobileSidebar } = useUiStore();
    const { lang } = useTranslation();
    const { url, props } = usePage();
    const isMobile = !useMediaQuery("(min-width: 768px)");
    const isTablet = !useMediaQuery("(min-width: 1024px)");

    // Auto-start the role tour once, on the user's first visit to their dashboard.
    const auth = props.auth as { user?: { active_role?: string } | null } | undefined;
    const activeRole = auth?.user?.active_role ?? "";
    useEffect(() => {
        if (url !== "/dashboard" || !activeRole) return;
        const timer = setTimeout(() => startUserTour(activeRole, lang), 700);
        return () => clearTimeout(timer);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [url, activeRole]);

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
            <div className={cn("flex flex-col flex-1 min-w-0 min-h-screen sidebar-transition", sidebarWidth)}>
                <Topbar />
                <main className="flex-1 min-w-0 px-4 sm:px-6 py-4 sm:py-6 space-y-8">
                    {children}
                </main>
            </div>

            {/* This layout also wraps the public catalogue (/services, /products, /events,
                /research, …), so a guest here gets the compact floating variant and only a
                real dashboard session gets the wider panel. */}
            <ChatbotWidget variant={auth?.user ? "panel" : "floating"} />
        </div>
    );
}
