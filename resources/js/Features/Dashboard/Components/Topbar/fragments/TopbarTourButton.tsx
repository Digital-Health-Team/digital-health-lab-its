import { HelpCircle } from "lucide-react";
import { usePage } from "@inertiajs/react";
import { useUiStore } from "@/Core/Store/ui.store";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { startUserTour } from "@/Features/Tour/startUserTour";

export default function TopbarTourButton() {
    const { auth } = usePage().props;
    const language = useUiStore((s) => s.language);
    const { t } = useTranslation();

    if (!auth?.user) return null;

    const activeRole = (auth.user.active_role as string | undefined) ?? "";

    return (
        <button
            type="button"
            data-tour="tour-button"
            onClick={() => startUserTour(activeRole, language, { force: true })}
            aria-label={t("Start tutorial")}
            title={t("Tutorial")}
            className="w-10 h-10 flex items-center justify-center rounded-full text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors duration-150"
        >
            <HelpCircle className="h-5 w-5" />
        </button>
    );
}
