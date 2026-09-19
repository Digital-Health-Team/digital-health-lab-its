import { useId, useState } from "react";
import { usePage } from "@inertiajs/react";
import { MessageCircle } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Button } from "@/Core/Components/Shared";
import { useMediaQuery } from "@/Core/Hooks/useMediaQuery";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import { chatbotCopy } from "../copy";
import { type ChatbotVariant } from "../Types/chatbot.type";
import ChatbotPanel from "./ChatbotPanel";

/**
 * Roles that never see the widget.
 *
 * They already have GlobalSearch, which is the better tool for operational lookups, and
 * letting the chatbot near operational data widens the risk surface for no gain. Gated on
 * active_role rather than the full role list: that is the role the person is currently
 * operating as, and it is what the rest of the dashboard keys off.
 */
const HIDDEN_FOR_ROLES = ["super_admin", "admin_lab", "admin_gudang"];

interface ChatbotWidgetProps {
    variant?: ChatbotVariant;
}

interface AuthProps {
    auth?: { user?: { active_role?: string } | null };
    [key: string]: unknown;
}

export default function ChatbotWidget({ variant = "floating" }: ChatbotWidgetProps) {
    const [open, setOpen] = useState(false);
    const baseId = useId();
    const panelId = `${baseId}-panel`;
    const titleId = `${baseId}-title`;
    const { lang } = useTranslation();
    const reducedMotion = useMediaQuery("(prefers-reduced-motion: reduce)");
    const { props } = usePage<AuthProps>();

    const activeRole = props.auth?.user?.active_role ?? "";

    if (HIDDEN_FOR_ROLES.includes(activeRole)) {
        return null;
    }

    const copy = chatbotCopy(lang);

    return (
        <Box className="fixed bottom-5 right-5 z-30 flex flex-col items-end gap-3 sm:bottom-6 sm:right-6">
            {open && (
                <ChatbotPanel
                    variant={variant}
                    lang={lang}
                    panelId={panelId}
                    titleId={titleId}
                    onClose={() => setOpen(false)}
                />
            )}

            <Button
                variant="primary"
                type="button"
                onClick={() => setOpen((current) => !current)}
                aria-label={open ? copy.close : copy.launch}
                aria-expanded={open}
                aria-controls={panelId}
                className={cn(
                    "h-14 w-14 rounded-full p-0 shadow-card-elevated",
                    !reducedMotion && "transition-transform duration-200 hover:scale-105",
                )}
            >
                <MessageCircle aria-hidden="true" className="h-6 w-6" />
            </Button>
        </Box>
    );
}
