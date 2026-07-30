import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import { type EventStatus } from "@/Features/Events/Types/event.type";

interface EventStatusBadgeProps {
    status: EventStatus;
    /** Over a photo the badge needs its own opaque ground; on a card it doesn't. */
    onImage?: boolean;
    className?: string;
}

/**
 * Every status carries a text label — colour alone never communicates state
 * (DESIGN.md a11y rule). Teal is reserved for the live one; the other two are
 * neutral so the signal keeps its scarcity.
 *
 * English source strings, which are also the t() keys. Exported so the catalogue's
 * filter tabs read from the same words the badges do — a tab labelled "Ongoing"
 * has to match the badge it filters for.
 */
export const statusLabels: Record<EventStatus, string> = {
    ongoing: "Ongoing",
    upcoming: "Upcoming",
    past: "Completed",
};

const statusStyles: Record<EventStatus, string> = {
    ongoing: "bg-secondary-500/15 text-secondary-700 border-secondary-500/30",
    upcoming: "bg-primary-700/10 text-primary-700 border-primary-700/20",
    past: "bg-slate-100 text-slate-600 border-slate-200",
};

const onImageStyles: Record<EventStatus, string> = {
    ongoing: "bg-primary-950/80 text-secondary-300 border-secondary-400/40",
    upcoming: "bg-primary-950/80 text-white border-white/25",
    past: "bg-primary-950/70 text-slate-200 border-white/20",
};

export default function EventStatusBadge({ status, onImage = false, className }: EventStatusBadgeProps) {
    const { t } = useTranslation();

    return (
        <Box
            as="span"
            className={cn(
                "inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold tracking-wide",
                onImage ? cn(onImageStyles[status], "backdrop-blur-sm") : statusStyles[status],
                className,
            )}
        >
            {status === "ongoing" && (
                <Box as="span" className="relative flex h-2 w-2 shrink-0 items-center justify-center" aria-hidden="true">
                    <Box as="span" className="live-ping absolute h-2 w-2 rounded-full bg-secondary-400/60" />
                    <Box as="span" className="h-1.5 w-1.5 rounded-full bg-secondary-400" />
                </Box>
            )}
            <Text as="span" className="text-[11px] leading-none font-semibold">
                {t(statusLabels[status])}
            </Text>
        </Box>
    );
}
