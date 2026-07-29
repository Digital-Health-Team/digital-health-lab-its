import { Link } from "@inertiajs/react";
import { ArrowRight, CalendarDays, MapPin, Users } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import EventStatusBadge from "@/Features/Events/Components/EventCatalogue/fragments/EventStatusBadge";
import EventCountdown from "./fragments/EventCountdown";
import { currentDay, durationInDays, formatDateRange } from "@/Features/Events/Utils/eventDate";
import { type EventSpotlight } from "@/Features/Events/Types/event.type";

interface EventSpotlightProps {
    event: EventSpotlight;
}

export default function EventSpotlightCard({ event }: EventSpotlightProps) {
    const { t, lang: locale } = useTranslation();

    const dateRange = formatDateRange(event.startsAt, event.endsAt, locale);
    const totalDays = durationInDays(event.startsAt, event.endsAt);
    const day = currentDay(event.startsAt);

    return (
        <Box as="section" aria-labelledby="event-spotlight-heading">
            <Card className="group flex flex-col overflow-hidden md:flex-row">
                {/* Poster */}
                <Box className="relative min-h-55 shrink-0 bg-primary-900 md:min-h-72 md:w-5/12">
                    {event.thumbnailUrl && (
                        <Box
                            className="absolute inset-0"
                            style={{
                                backgroundImage: `url(${event.thumbnailUrl})`,
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                            }}
                        />
                    )}
                    <Box className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-linear-to-b from-black/55 to-transparent" />
                    <Box className="absolute top-3 left-3 z-10">
                        <EventStatusBadge status={event.status} onImage />
                    </Box>
                </Box>

                {/* Content */}
                <Box className="flex flex-1 flex-col justify-between gap-6 p-5 sm:p-6">
                    <Box>
                        <Box className="mb-3 flex flex-wrap items-center gap-2">
                            {event.category && (
                                <Badge variant="tag" className="tracking-normal normal-case">
                                    {event.category}
                                </Badge>
                            )}
                            <Text as="span" className="text-xs font-medium text-slate-500 tabular-nums">
                                {event.year}
                            </Text>
                        </Box>

                        <Link href={event.href} className="focus-visible:outline-none">
                            <Heading
                                level={2}
                                id="event-spotlight-heading"
                                className="font-display mb-2 text-xl leading-snug font-bold text-balance text-slate-800 transition-colors group-hover:text-primary-700 md:text-2xl"
                            >
                                {event.name}
                            </Heading>
                        </Link>

                        <Text className="max-w-prose text-sm leading-relaxed text-slate-600">
                            {event.subtitle || event.themeTitle}
                        </Text>
                    </Box>

                    {/* The temporal state — a countdown before, a day counter during */}
                    {event.status === "upcoming" && event.startsAt && (
                        <Box>
                            <Text
                                as="span"
                                className="mb-2 block text-xs font-semibold tracking-wide text-slate-500"
                            >
                                {t("Starts in")}
                            </Text>
                            <EventCountdown targetIso={event.startsAt} />
                        </Box>
                    )}

                    {event.status === "ongoing" && day !== null && totalDays !== null && (
                        <Box className="flex items-center gap-2">
                            <Box
                                className="relative flex h-3 w-3 shrink-0 items-center justify-center"
                                aria-hidden="true"
                            >
                                <Box className="live-ping absolute h-2.5 w-2.5 rounded-full bg-secondary-500/40" />
                                <Box className="h-1.5 w-1.5 rounded-full bg-secondary-500" />
                            </Box>
                            <Text as="span" className="text-sm font-semibold text-slate-700">
                                {t("Day")} {day} {t("of")} {totalDays}
                            </Text>
                        </Box>
                    )}

                    {/* Meta + CTA */}
                    <Box className="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-4">
                        <Box className="flex flex-wrap items-center gap-x-4 gap-y-2">
                            {dateRange && (
                                <Box className="flex items-center gap-1.5 text-slate-500">
                                    <CalendarDays className="h-4 w-4 shrink-0" aria-hidden="true" />
                                    <Text as="span" className="text-sm text-slate-500">
                                        {dateRange}
                                    </Text>
                                </Box>
                            )}
                            {event.location && (
                                <Box className="flex min-w-0 items-center gap-1.5 text-slate-500">
                                    <MapPin className="h-4 w-4 shrink-0" aria-hidden="true" />
                                    <Text as="span" className="truncate text-sm text-slate-500">
                                        {event.location}
                                    </Text>
                                </Box>
                            )}
                            <Box className="flex items-center gap-1.5 text-slate-500">
                                <Users className="h-4 w-4 shrink-0" aria-hidden="true" />
                                <Text as="span" className="text-sm text-slate-500">
                                    {event.teamsCount} {t("teams")}
                                </Text>
                            </Box>
                        </Box>

                        <Link
                            href={event.href}
                            className="inline-flex items-center gap-1.5 rounded-lg text-sm font-semibold text-primary-700 transition-colors hover:text-primary-600 focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:ring-offset-2 focus-visible:outline-none"
                        >
                            {t("View event")}
                            <ArrowRight className="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </Box>
                </Box>
            </Card>
        </Box>
    );
}
