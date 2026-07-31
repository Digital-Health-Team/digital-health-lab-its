import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type EventsHeroData } from "@/Features/Events/Types/event.type";

const glowCta = {
    boxShadow:
        "0 0 24px rgba(34,211,238,0.4), 0 0 40px rgba(34,211,238,0.3), inset 0 1px 0 rgba(255,255,255,0.3)",
};

interface EventsHeroProps {
    data: EventsHeroData;
    ongoingCount: number;
    upcomingCount: number;
}

export default function EventsHero({ data, ongoingCount, upcomingCount }: EventsHeroProps) {
    const { t } = useTranslation();

    // A live count is the honest lede for an events page; a marketing line isn't.
    const signals = [
        ongoingCount > 0 ? `${ongoingCount} ${t("running now")}` : null,
        upcomingCount > 0 ? `${upcomingCount} ${t("scheduled")}` : null,
    ].filter(Boolean);

    return (
        <Box
            as="section"
            className="relative flex min-h-60 items-center overflow-hidden rounded-3xl bg-primary-900 px-6 py-8 sm:min-h-70 sm:px-10 sm:py-12"
            style={{
                backgroundImage: `url(${data.backgroundUrl})`,
                backgroundSize: "cover",
                backgroundPosition: "center",
            }}
        >
            <Box className="pointer-events-none absolute inset-0 z-0 bg-linear-to-r from-black/75 via-black/55 to-black/25" />

            <Box className="relative z-10 max-w-lg">
                {signals.length > 0 && (
                    <Box className="mb-4 flex items-center gap-2">
                        {ongoingCount > 0 && (
                            <Box
                                className="relative flex h-3 w-3 shrink-0 items-center justify-center"
                                aria-hidden="true"
                            >
                                <Box className="live-ping absolute h-2.5 w-2.5 rounded-full bg-secondary-500/50" />
                                <Box className="h-1.5 w-1.5 rounded-full bg-secondary-400" />
                            </Box>
                        )}
                        <Text
                            as="span"
                            className="text-xs font-semibold tracking-wide text-secondary-200"
                        >
                            {signals.join(" · ")}
                        </Text>
                    </Box>
                )}

                <Heading
                    level={1}
                    className="font-display mb-4 text-4xl leading-tight font-bold text-balance text-white md:text-5xl"
                >
                    {t(data.title)}
                </Heading>
                <Text className="mb-8 max-w-md text-base leading-relaxed text-slate-200">
                    {t(data.subtitle)}
                </Text>
                <a href={data.ctaHref}>
                    <Button variant="glow" size="md" className="cursor-pointer" style={glowCta}>
                        {t(data.ctaLabel)}
                    </Button>
                </a>
            </Box>
        </Box>
    );
}
