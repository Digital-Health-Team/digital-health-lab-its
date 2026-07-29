import { CalendarDays, MapPin, Users, FolderGit2, ExternalLink } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import EventStatusBadge from "@/Features/Events/Components/EventCatalogue/fragments/EventStatusBadge";
import EventCountdown from "@/Features/Events/Components/EventSpotlight/fragments/EventCountdown";
import {
    currentDay,
    durationInDays,
    formatDateRange,
} from "@/Features/Events/Utils/eventDate";
import { type EventDetail } from "@/Features/Events/Types/event.type";

interface EventInfoCardProps {
    event: EventDetail;
}

export default function EventInfoCard({ event }: EventInfoCardProps) {
    const { t, lang: locale } = useTranslation();

    const dateRange = formatDateRange(event.startsAt, event.endsAt, locale);
    const totalDays = durationInDays(event.startsAt, event.endsAt);
    const day = currentDay(event.startsAt);
    const projectCount = event.teams.reduce((sum, team) => sum + team.projects.length, 0);

    const facts = [
        dateRange ? { icon: CalendarDays, label: t("Date"), value: dateRange } : null,
        event.location ? { icon: MapPin, label: t("Location"), value: event.location } : null,
        { icon: Users, label: t("Teams"), value: String(event.teams.length) },
        projectCount > 0
            ? { icon: FolderGit2, label: t("Projects"), value: String(projectCount) }
            : null,
    ].filter(Boolean) as { icon: typeof CalendarDays; label: string; value: string }[];

    // Registration only makes sense while the event is still ahead of you.
    const canRegister = event.status === "upcoming" && Boolean(event.registrationUrl);

    return (
        <Card className="lg:sticky lg:top-20">
            <Box className="flex flex-col gap-5 p-5 sm:p-6">
                <Box className="flex items-center justify-between gap-3">
                    <EventStatusBadge status={event.status} />
                    <Text as="span" className="text-xs font-medium text-slate-500 tabular-nums">
                        {event.year}
                    </Text>
                </Box>

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
                    <Box className="flex items-center gap-2 rounded-xl bg-secondary-500/10 px-3 py-2.5">
                        <Box
                            className="relative flex h-3 w-3 shrink-0 items-center justify-center"
                            aria-hidden="true"
                        >
                            <Box className="live-ping absolute h-2.5 w-2.5 rounded-full bg-secondary-500/40" />
                            <Box className="h-1.5 w-1.5 rounded-full bg-secondary-500" />
                        </Box>
                        <Text as="span" className="text-sm font-semibold text-secondary-700">
                            {t("Day")} {day} {t("of")} {totalDays}
                        </Text>
                    </Box>
                )}

                {/* Facts */}
                <Box as="dl" className="flex flex-col gap-3.5">
                    {facts.map(({ icon: Icon, label, value }) => (
                        <Box key={label} className="flex items-start gap-3">
                            <Icon
                                className="mt-0.5 h-4 w-4 shrink-0 text-secondary-500"
                                aria-hidden="true"
                            />
                            <Box className="min-w-0">
                                <Box
                                    as="dt"
                                    className="text-[11px] leading-relaxed font-semibold tracking-wide text-slate-500 uppercase"
                                >
                                    {label}
                                </Box>
                                <Box
                                    as="dd"
                                    className="text-sm leading-snug font-medium text-slate-700"
                                >
                                    {value}
                                </Box>
                            </Box>
                        </Box>
                    ))}
                </Box>

                {/* CTA */}
                <Box className="border-t border-slate-100 pt-4">
                    {canRegister ? (
                        <a
                            href={event.registrationUrl as string}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="block"
                        >
                            <Button variant="primary" size="md" className="w-full cursor-pointer">
                                {t("Register now")}
                                <ExternalLink className="h-4 w-4" aria-hidden="true" />
                            </Button>
                        </a>
                    ) : (
                        <>
                            <Button variant="outline" size="md" className="w-full" disabled>
                                {event.status === "past"
                                    ? t("Registration closed")
                                    : t("Registration not open yet")}
                            </Button>
                            <Text className="mt-2 text-center text-xs leading-relaxed text-slate-500">
                                {event.status === "past"
                                    ? t("Browse the teams below to see what came out of this edition.")
                                    : t("Registration details are announced closer to the date.")}
                            </Text>
                        </>
                    )}
                </Box>
            </Box>
        </Card>
    );
}
