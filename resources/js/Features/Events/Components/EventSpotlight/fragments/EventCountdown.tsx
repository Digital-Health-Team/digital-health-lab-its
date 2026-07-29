import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { useCountdown } from "@/Features/Pameran/Hooks/useCountdown";
import { cn } from "@/Core/Utils/utils";

interface EventCountdownProps {
    /** ISO-8601 start instant. */
    targetIso: string;
    className?: string;
}

/**
 * Countdown to an upcoming event. Text only — no motion to suppress, so it needs
 * no reduced-motion branch. Reuses the Pameran countdown rather than adding a
 * second timer implementation.
 */
export default function EventCountdown({ targetIso, className }: EventCountdownProps) {
    const { t } = useTranslation();
    const { days, hours, minutes, seconds, isComplete } = useCountdown(targetIso);

    if (isComplete) return null;

    const units = [
        { value: days, label: "days" },
        { value: hours, label: "hours" },
        { value: minutes, label: "min" },
        { value: seconds, label: "sec" },
    ];

    return (
        <Box className={cn("flex items-center gap-2", className)}>
            {units.map((unit) => (
                <Box
                    key={unit.label}
                    className="flex min-w-12 flex-col items-center rounded-xl bg-slate-100 px-2.5 py-1.5"
                >
                    <Text
                        as="span"
                        className="font-display text-lg leading-none font-extrabold text-primary-950 tabular-nums"
                    >
                        {String(unit.value).padStart(2, "0")}
                    </Text>
                    <Text
                        as="span"
                        className="mt-1 text-[10px] leading-none font-semibold tracking-wider text-slate-500 uppercase"
                    >
                        {t(unit.label)}
                    </Text>
                </Box>
            ))}
        </Box>
    );
}
