import { CalendarDays, MapPin, Users, FolderGit2, Tag } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import EventStatusBadge from "@/Features/Events/Components/EventCatalogue/fragments/EventStatusBadge";
import { formatDateRange } from "@/Features/Events/Utils/eventDate";
import { type EventDetail } from "@/Features/Events/Types/event.type";

interface EventPreviewProps {
    event: EventDetail;
}

export default function EventPreview({ event }: EventPreviewProps) {
    const { t, lang: locale } = useTranslation();

    const dateRange = formatDateRange(event.startsAt, event.endsAt, locale);
    const projectCount = event.teams.reduce((sum, team) => sum + team.projects.length, 0);

    const meta = [
        dateRange ? { icon: CalendarDays, label: dateRange } : null,
        event.location ? { icon: MapPin, label: event.location } : null,
        { icon: Users, label: `${event.teams.length} ${t("teams")}` },
        projectCount > 0
            ? { icon: FolderGit2, label: `${projectCount} ${t("projects")}` }
            : null,
        event.category ? { icon: Tag, label: t(event.category) } : null,
    ].filter(Boolean) as { icon: typeof CalendarDays; label: string }[];

    return (
        <Box
            as="article"
            className="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"
        >
            <Box className="relative bg-primary-900">
                {event.thumbnailUrl ? (
                    <Image
                        src={event.thumbnailUrl}
                        alt={`${event.name} — ${event.themeTitle}`}
                        objectFit="cover"
                        priority="eager"
                        className="h-48 w-full sm:h-64 md:h-100"
                    />
                ) : (
                    <Box className="h-48 w-full sm:h-64 md:h-100" />
                )}
                <Box className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-linear-to-b from-black/55 to-transparent" />
                <Box className="absolute top-4 left-4 z-10">
                    <EventStatusBadge status={event.status} onImage />
                </Box>
            </Box>

            <Box className="p-5 sm:p-6">
                <Box className="mb-3 flex flex-wrap items-center gap-2">
                    {event.category && (
                        <Badge variant="tag" className="tracking-normal normal-case">
                            {t(event.category)}
                        </Badge>
                    )}
                    <Text as="span" className="text-xs font-medium text-slate-500 tabular-nums">
                        {event.year}
                    </Text>
                </Box>

                <Heading
                    level={1}
                    className="font-display mb-2 text-2xl leading-tight font-bold text-balance text-slate-800 md:text-3xl"
                >
                    {event.name}
                </Heading>

                <Text className="mb-5 max-w-prose text-base leading-relaxed text-slate-600">
                    {event.themeTitle}
                </Text>

                <Box className="flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-slate-100 pt-4">
                    {meta.map(({ icon: Icon, label }) => (
                        <Box key={label} className="flex min-w-0 items-center gap-1.5 text-slate-500">
                            <Icon className="h-4 w-4 shrink-0" aria-hidden="true" />
                            <Text as="span" className="truncate text-sm text-slate-500">
                                {label}
                            </Text>
                        </Box>
                    ))}
                </Box>
            </Box>
        </Box>
    );
}
