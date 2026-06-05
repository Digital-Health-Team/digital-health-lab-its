import { Menu } from "lucide-react";
import { useUiStore } from "@/Core/Store/ui.store";

export default function TopbarMobileToggle() {
    const { setMobileSidebar } = useUiStore();
    return (
        <button
            type="button"
            aria-label="Open sidebar menu"
            onClick={() => setMobileSidebar(true)}
            className="flex items-center justify-center h-9 w-9 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors duration-150 lg:hidden"
        >
            <Menu className="h-5 w-5" />
        </button>
    );
}
