import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import { dateStamp } from "@/Features/Events/Utils/eventDate";

interface EventDateBlockProps {
    startsAt?: string | null;
    /** Over a photo the block gets its own dark ground; on a light card it inverts. */
    onImage?: boolean;
    className?: string;
}

/**
 * The event equivalent of training's instructor row: the first thing you need to
 * know about an event is when it is. Day numeral over short month, tabular so a
 * grid of cards keeps its numerals aligned.
 */
export default function EventDateBlock({ startsAt, onImage = false, className }: EventDateBlockProps) {
    const { lang: locale } = useTranslation();
    const stamp = dateStamp(startsAt, locale);

    if (!stamp) return null;

    return (
        <Box
            className={cn(
                "flex w-13 flex-col items-center rounded-xl px-2 py-1.5 text-center",
                onImage
                    ? "bg-primary-950/80 text-white backdrop-blur-sm"
                    : "bg-primary-950 text-white",
                className,
            )}
        >
            <Text
                as="span"
                className="font-display text-lg leading-none font-extrabold tabular-nums"
            >
                {stamp.day}
            </Text>
            <Text
                as="span"
                className="mt-0.5 text-[10px] leading-none font-semibold tracking-wider text-secondary-300 uppercase"
            >
                {stamp.month}
            </Text>
        </Box>
    );
}
