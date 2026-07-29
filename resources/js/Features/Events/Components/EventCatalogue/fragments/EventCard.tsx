import { Link } from "@inertiajs/react";
import { MapPin, Users, CalendarClock } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import EventStatusBadge from "./EventStatusBadge";
import EventDateBlock from "./EventDateBlock";
import { durationInDays } from "@/Features/Events/Utils/eventDate";
import { type EventSummary } from "@/Features/Events/Types/event.type";

interface EventCardProps {
    event: EventSummary;
}

export default function EventCard({ event }: EventCardProps) {
    const { t } = useTranslation();
    const days = durationInDays(event.startsAt, event.endsAt);

    return (
        <Link href={event.href} className="group block h-full">
            <Card className="card-hover-lift flex h-full flex-col overflow-hidden">
                {/* Poster */}
                <Box className="relative h-48 shrink-0 bg-primary-900">
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
                    {/* Grounds both overlays so they stay legible on any poster */}
                    <Box className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-linear-to-b from-black/55 to-transparent" />

                    <Box className="absolute top-3 left-3 z-10">
                        <EventStatusBadge status={event.status} onImage />
                    </Box>
                    <Box className="absolute top-3 right-3 z-10">
                        <EventDateBlock startsAt={event.startsAt} onImage />
                    </Box>
                </Box>

                {/* Body */}
                <Box className="flex flex-1 flex-col p-4">
                    {event.category && (
                        <Box className="mb-2.5 flex items-center gap-1.5">
                            <Badge variant="tag" className="text-[11px] tracking-normal normal-case">
                                {event.category}
                            </Badge>
                            <Text as="span" className="text-xs font-medium text-slate-500 tabular-nums">
                                {event.year}
                            </Text>
                        </Box>
                    )}

                    <Heading
                        level={3}
                        className="font-display mb-2 line-clamp-2 text-base leading-snug font-bold text-slate-800 transition-colors group-hover:text-primary-700"
                    >
                        {event.name}
                    </Heading>

                    <Text className="mb-3 line-clamp-2 flex-1 text-sm leading-relaxed text-slate-500">
                        {event.themeTitle}
                    </Text>

                    {/* Meta — location gets its own line so the short facts always
                        sit on one row; wrapping them made card footers ragged. */}
                    <Box className="flex flex-col gap-1.5 border-t border-slate-100 pt-3">
                        {event.location && (
                            <Box className="flex min-w-0 items-center gap-1 text-slate-500">
                                <MapPin className="h-3 w-3 shrink-0" aria-hidden="true" />
                                <Text as="span" className="truncate text-[11px] text-slate-500">
                                    {event.location}
                                </Text>
                            </Box>
                        )}
                        <Box className="flex items-center gap-3">
                            <Box className="flex items-center gap-1 text-slate-500">
                                <Users className="h-3 w-3 shrink-0" aria-hidden="true" />
                                <Text as="span" className="text-[11px] text-slate-500">
                                    {event.teamsCount} {t("teams")}
                                </Text>
                            </Box>
                            {days !== null && (
                                <Box className="flex items-center gap-1 text-slate-500">
                                    <CalendarClock className="h-3 w-3 shrink-0" aria-hidden="true" />
                                    <Text as="span" className="text-[11px] text-slate-500">
                                        {days} {t("days")}
                                    </Text>
                                </Box>
                            )}
                        </Box>
                    </Box>
                </Box>
            </Card>
        </Link>
    );
}
